<?php
session_start();
require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_User.php");

$video = C_Video::GetVideoById($_GET['v']);
$ownerId = $video->getOwnerId();
$owner = C_User::GetUserById($ownerId);
$followers = C_User::GetCountFollowers($owner->getId());

if(C_User::Follow($_SESSION['USER_ID'], $ownerId)){
  $showFollow = "none" ;
  $showUnFollow = "block";
}else{
  $showFollow = "block";
  $showUnFollow = "none";
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <?php require("./Views/Common/head.php") ?>
    <body>
        <?php require("./Views/Common/navbar.php") ?>

        <main class="container-fluid mb-4">
            <!-- Content =================================================== -->
            <section class="row">
                <?php require("./Views/Common/followed.php"); ?>

                <div class="col-10">
                    <!-- Video ============================================= -->
                    <div style="line-height: 5px;">
                        <iframe src="<?php echo $video->getEmbedUrl(); ?>" frameborder="0" style="width: 80%; height: 40em; margin-bottom: 1em"></iframe>
                        <div style="display: flex">
                            <h3> <?php echo $video->getName(); ?></h3>
                            <button type="button" name="button" class="btn btn-success" style="margin-left: 65%">LIKE</button>

                        </div>
                        <p class="text-black-50"> <?php echo $video->getViews() . ($video->getViews() > 1 ? " Views" : " View") . " • " . $video->getPublication(); ?> </p>
                    </div>

                    <hr style="width: 80%; margin-left: 0">

                    <!-- Desc and User ===================================== -->
                    <form class="" action="#" method="get" id="userForm">
                        <div class="container text-left ml-0" style="display: flex">
                            <div class="col-1">
                                <img class="rounded-circle" src="<?php echo $owner->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $owner->getId() ?>" onclick="submitForm(this, 'userForm')">
                            </div>
                            <div class="col-10 p-1" style="line-height: 5px;">
                                <h5 onclick="submitForm(this, 'userForm')"><?php echo $owner->getName() ?></h5>
                                <p class="text-black-50 mt-0" onclick="submitForm(this, 'userForm')"><?php echo $followers . ($followers > 1 ? ' followers' : " follower"); ?></p>
                                <p style="margin-top: 2em;"> <?php echo $video->getDescription() ?> </p>
                            </div>
                            <input type="hidden" id="u" name="u" value="<?php echo $owner->getId() ?>">
                            <div class="col-1">
                                <button id="btn-follow" type="button" name="follow" class="btn btn-primary btn-lg" onclick="callAjax(this,'follow',<?=$ownerId?>)" style="display:<?=$showFollow?>">FOLLOW</button>
                                <button id="btn-unfollow" type="button" name="unfollow" class="btn btn-danger btn-lg" onclick="callAjax(this,'unfollow',<?=$ownerId?>)" style="display:<?=$showUnFollow?>">UNFOLLOW</button>
                            </div>
                        </div>
                    </form>
                </div>

            </section>
        </main>

        <?php require("./Views/Common/footer.php") ?>
    </body>
    <script type="text/javascript">
        function submitForm(div, formId) {
            var form = document.getElementById(formId);
            switch (formId) {
                case "userForm": alert("Redirect to user profile"); break;
                default: break;
            }
        }

        function callAjax(div, type, id){ // TODO LIKE + remplir Tableau followers
            $.ajax({
      				  url : "/callAjax",
      				  type : "POST",
      				  data : "CASE="+type+"&ID="+id,
      				  dataType : 'html',
      				  success : function(res){
                  if(res == 1){
                    switch (type) {
                      case "follow":
                        $("#btn-follow").hide();
                        $("#btn-unfollow").show();
                        break;
                      case "unfollow":
                        $("#btn-follow").show();
                        $("#btn-unfollow").hide();
                        break;
                      case "like":break;
                      case "unlike":break;
                      default:
                        alert('erreur lors du traitement de votre requête');
                        break;
                    }
                  }
      				  },
    			  });
        }
    </script>
</html>
