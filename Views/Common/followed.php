<?php
require_once("./Controllers/C_User.php");
$_SESSION["User"] = -1;
$followed = C_User::GetFollow((isset($_SESSION["User"]) ? $_SESSION["User"] : -1));
?>
<!-- Followed ============================================== -->
<div class="col-lg-2 col-md-1 col-sm-1 col-1 followed-container" id="followedContainer">
    <form class="" action="#" method="post" id="formFollow">
        <button type="button" id="btnFollow" class="btn btn-sm followed-btn" onclick="openFollowed(this)">+</button>
        <div id="divFollowed" class="followed-div">
            <h3>Followed:</h3>
            <div class="container text-left">
            <?php if (!isset($_SESSION["User"]) || $_SESSION["User"] == -1) { ?> <span class="followed-info">Not connected. <a href="./connection">Login?</a></span> <?php }
            else {
                if (count($followed) == 0) { ?> <span class="followed-info">You followed no one :(</span> <?php }
                for ($i=0; $i < count($followed); $i++) { ?>
                <div class="row pb-2" onclick="submitForm(this, 'formFollow')">
                    <div class="col-3 followed-img-container">
                        <img class="rounded-circle followed-img" src="<?php echo $followed[$i]->getAvatar() ?>" alt="avatar" id="<?php echo $followed[$i]->getId() ?>">
                    </div>
                    <div class="col-9 followed-name-container">
                        <span><?php echo $followed[$i]->getName() ?></span>
                    </div>
                </div>
            <?php } } ?>
        </div>
        <input type="hidden" id="follow_id" name="follow_id" value="">
    </form>
    </div>
</div>

<script type="text/javascript">
    function openFollowed(btn) {
        btn.classList.toggle("followed-btn-shown");
        btn.innerText = (btn.innerText == "+" ? "-" : "+");
        document.getElementById("divFollowed").classList.toggle("followed-div-shown");
        document.getElementById("followedContainer").classList.toggle("col-1");
        document.getElementById("followedContainer").classList.toggle("col-sm-1");
        document.getElementById("followedContainer").classList.toggle("col-md-1");
        document.getElementById("followedContainer").classList.toggle("col-12");
        document.getElementById("followedContainer").classList.toggle("col-sm-12");
        document.getElementById("followedContainer").classList.toggle("col-md-12");document.getElementById("followedContainer").classList.toggle("followed-container-shown");
        document.body.classList.toggle("no-scroll");
    }
</script>
