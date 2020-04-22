<?php
session_start();
require_once("./Controllers/C_User.php");
require_once("./Controllers/C_Video.php");

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);
$owner = C_User::GetUserById($_GET["u"]);
$isFollower = ($userConnected !== -1 ? C_User::GetFollowByOwnerAndUser($owner->getId(), $userConnected->getId()) : false);

$followers = C_User::GetCountFollowers($owner->getId());
$followersFormatted = formatNumber(C_User::GetCountFollowers($owner->getId()));
$latestVideos = C_Video::GetLatestVideosByUserId($owner->getId());
$mostViewedVideos = C_Video::GetMostViewedVideosByUserId($owner->getId());
//$allVideos = C_Video::GetVideosByUserId($owner->getId());

$allVideos = $latestVideos;

function createVideoRec($vid) {
    return
    '<div class="video col-5 col-sm-4 col-md-2" onclick="submitForm(this, `formVideo`)">
      <div>
        <div class="thumbnail">
          <img src="' . $vid->getThumbnail() .'" alt="Loading..." id="' . $vid->getId() . '">
        </div>
        <div class="usrAvatar">
          <div class="userAvatar">
            <img src="' . $vid->getThumbnail() .'" alt="Loading..." id="' . $vid->getId() . '">
          </div>
        </div>
        <div class="description">' . str_replace("\\n", "</br>", $vid->getDescription()) . '</div>
      </div>
      <h4 class="title">' . $vid->getName() .
      (strlen($vid->getName()) > 18 ? '<span class="tooltiptext">' . $vid->getName() . '</span>' : '') . '</h4>
    </div>' ;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <?php require("./Views/Common/head.php") ?>
    <body>
        <?php require("./Views/Common/navbar.php") ?>

        <link rel="stylesheet" href="/src/styles/thumbnail.css">

        <main class="container-fluid mb-4">
            <!-- Content =================================================== -->
            <section class="row">
                <?php require("./Views/Common/followed.php"); ?>
                <link rel="stylesheet" href="/src/styles/user.css">

                <div class="col-lg-10 col-md-11 col-sm-11 col-11">
                    <div class="container-fluid">
                        <!-- NavBar ======================================== -->
                        <div class="row">
                            <!-- Desc et bouton - - - - - - - - - - - -  - - -->
                            <div class="col-lg-5 col-md-5 col-sm-5 col-12 user-navbar">
                                <span class="user-centered text-white">Description</span>
                                <?php if ($owner->getId() != ($userConnected === -1 ? -1 : $userConnected->getId())) { ?>
                                        <button type="button" id="btnFollowOwner" class="btn <?php echo ($isFollower ? "user-followed" : "btn-primary") . ($userConnected === -1 ? " user-hidden" : "")?> btn-lg user-follow-btn" onclick="submitForm(this, 'formFollowOwner');"><?php echo ($isFollower ? "FOLLOWED" : "FOLLOW") ?></button>
                                        <button type="button" id="btnFollowOwner2" class="btn btn-primary btn-lg user-follow-btn <?php echo ($userConnected !== -1 ? " user-hidden" : "") ?>" onclick="submitForm(this, 'formConnect');">FOLLOW</button>
                                    <?php } ?>
                                <form class="" action="/follow/" id="formFollowOwner" method="get" target="iframe-user">
                                    <input type="hidden" name="ownerId" value="<?php echo $owner->getId(); ?>">
                                    <input type="hidden" name="userId" value="<?php echo ($userConnected === -1 ? -1 : $userConnected->getId()) ?>">
                                    <input type="hidden" id="doReqFollow" name="doReq" value="1">
                                </form>
                                <iframe class="user-hidden" name="iframe-user"></iframe>
                            </div>

                            <!-- Stats - - - - - - - - - - - -  - - - - - - - -->
                            <div class="col-lg-7 col-md-7 col-sm-7 col-12 container user-navbar">
                                <!-- followers -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 user-stats-container">
                                    <span class="user-centered text-white"><?php echo ($followers > 1 ? "Followers : " : "Follower : ") . $followersFormatted ?></span>
                                </div>

                                <!-- Avatar -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 user-stats-container">
                                    <span class="user-centered text-white">
                                        <img src="<?php echo $owner->getAvatar(); ?>" alt="Avatar" class="rounded-circle user-img">
                                        <?php echo $owner->getName(); ?>
                                    </span>
                                </div>
                            </div>

                        </div>

                        <!-- Description =================================== -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-desc" id="desc">
                                <span><?php echo $owner->getDescription(); ?></span>
                            </div>
                            <?php if (count(explode("</br>", $owner->getDescription())) > 6) { ?>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-5"> <hr> </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-2 text-center">
                                    <div class="user-next-desc">
                                        <span class="user-next-button" onclick="readMore(this, 'desc')">Read more</span>
                                    </div>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-5"> <hr> </div>
                            <?php } ?>
                        </div>

                        <!-- Latest Videos ================================= -->
                        <form class="col-lg-12 col-md-12 col-sm-12 col-12" action="/watch" method="get" id="formVideo">
                            <div class="row">
                                <h2>Latest</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-video-line">
                                    <?php for ($i=0; $i < count($latestVideos); $i++) {
                                        echo createVideoRec($latestVideos[$i]);
                                    } ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <h2>Most viewed</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-video-line">
                                    <?php for ($i=0; $i < count($mostViewedVideos); $i++) {
                                        echo createVideoRec($mostViewedVideos[$i]);
                                    } ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <h2>All videos</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-latest">
                                    <?php for ($i=0; $i < count($allVideos); $i++) {
                                        echo createVideoRec($allVideos[$i]);
                                    } ?>
                                </div>
                            </div>
                            <input type="hidden" id="video_id" name="v" value="">
                        </form>
                    </div>
                </div>
            </section>
        </main>

        <?php require("./Views/Common/footer.php"); ?>
    </body>
    <script type="text/javascript">
        function submitForm(div, formId) {
            var form = document.getElementById(formId);
            var img = div.getElementsByTagName("img")[0];
            switch (formId) {
                case "formVideo":
                    document.getElementById("video_id").value = img.id;
                    break;
                case "formFollow":
                    document.getElementById("follow_id").value = img.id;
                    form.submit();
                    break;
                case "formFollowOwner":
                    if (Array.from(div.classList).indexOf("user-followed") != -1) {
                        div.innerText = "FOLLOW";
                        div.classList.remove("user-followed");
                        div.classList.add("btn-primary");
                    }
                    else {
                        div.innerText = "FOLLOWED"
                        div.classList.add("user-followed");
                        div.classList.remove("btn-primary");
                    }
                    form.submit();
                    break;
            }

            document.getElementById(formId).submit();
        }
        function readMore(span, divId) {
            var div = document.getElementById(divId);
            div.classList.add("user-text-more");
            span.setAttribute('onclick', "readLess(this, '" + divId + "')");
            span.innerText = "Less";
        }
        function readLess(span, divId) {
            var div = document.getElementById(divId);
            div.classList.remove("user-text-more");
            span.setAttribute('onclick', "readMore(this, '" + divId + "')");
            span.innerText = "Read more";
        }
    </script>
</html>
