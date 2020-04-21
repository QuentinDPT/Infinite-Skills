<?php
session_start();
require_once("./Controllers/C_User.php");
require_once("./Controllers/C_Video.php");

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);

$owner = C_User::GetUserById($_GET["u"]);
$followers = formatNumber(C_User::GetCountFollowers($owner->getId()));
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
                <link rel="stylesheet" href="/src/styles/user.css">

                <div class="col-lg-10 col-md-11 col-sm-11 col-11 border">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-2 border user-navbar">
                                <button type="button" name="button" class="btn user-navbar-btn user-navbar-btn-active">Profile</button>
                                <form class="" action="/user" method="post">
                                    <button type="button" name="button" class="btn user-navbar-btn">Videos</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php require("./Views/Common/footer.php"); ?>
    </body>
</html>
