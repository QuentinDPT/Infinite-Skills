<?php
require_once("./Controllers/C_User.php");
$followed = C_User::GetFollow((isset($_SESSION["User"]) ? $_SESSION["User"] : -1));
?>
<!-- Followed ============================================== -->
<div class="col-lg-2 col-md-1 col-sm-1 col-1 followed-container" id="followedContainer">
    <form class="" action="/users" method="get" id="formFollow">
        <button type="button" id="btnFollow" class="btn btn-sm followed-btn" onclick="openFollowed(this)">+</button>
        <div id="divFollowed" class="followed-div">
            <h3 class="followed-title basic">Followed:</h3>
            <div class="container text-left">
            <?php if (!isset($_SESSION["User"]) || $_SESSION["User"] == -1) { ?> <span class="basic">Not connected. <a href="./connection">Login?</a></span> <?php }
            else {
                if (count($followed) == 0) { ?> <span class="basic" id="followed-no-one">You followed no one :(</span> <?php }
                for ($i=0; $i < count($followed); $i++) { ?>
                <div class="row pb-2" onclick="submitForm(this, 'formFollow')" id="followed-<?php echo $followed[$i]->getId(); ?>">
                    <div class="col-3 followed-img-container">
                        <img class="rounded-circle followed-img" src="<?php echo $followed[$i]->getAvatar() ?>" alt="avatar" id="<?php echo $followed[$i]->getId() ?>">
                    </div>
                    <div class="col-9 followed-name-container">
                        <span class="basic"><?php echo $followed[$i]->getName() ?></span>
                    </div>
                </div>
            <?php } } ?>
        </div>
        <input type="hidden" id="follow_id" name="u" value="">
    </form>
    </div>
</div>

<script src="/src/scripts/Followed.js" charset="utf-8"></script>
