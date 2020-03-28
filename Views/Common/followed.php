<?php
require_once("./Controllers/C_User.php");
$followed = C_User::GetFollow((isset($_SESSION["User"]) ? $_SESSION["User"] : -1));
?>
<!-- Followed ============================================== -->
<div class="col-2">
    <form class="" action="#" method="post" id="formFollow">
        <div class="border border-dark rounded-lg text-center p-2" style="width: 15%; height: 50em; overflow-y: auto; position: fixed">
            <h3>Followed:</h3>
            <div class="container text-left">
            <?php if (!isset($_SESSION["User"])) { ?> <span class="text-black-50">Not connected. <a href="./connection">Login?</a></span> <?php }
            else {
                if (count($followed) == 0) { ?> <span class="text-black-50">You followed no one :(</span> <?php }
                for ($i=0; $i < count($followed); $i++) { ?>
                <div class="row" onclick="submitForm(this, 'formFollow')">
                    <div class="col-3">
                        <img class="rounded-circle" src="<?php echo $followed[$i]->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $followed[$i]->getId() ?>">
                    </div>
                    <div class="col-9 p-2">
                        <p><?php echo $followed[$i]->getName() ?></p>
                    </div>
                </div>
            <?php } } ?>
        </div>
        <input type="hidden" id="follow_id" name="follow_id" value="">
    </form>
    </div>
</div>
