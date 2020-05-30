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
$allVideos = C_Video::GetVideosByUserId($owner->getId());

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
        <div class="description basic">' . str_replace("\\n", "</br>", $vid->getDescription()) . '</div>
      </div>
      <h4 class="title basic">' . $vid->getName() .
      (strlen($vid->getName()) > 18 ? '<span class="tooltiptext">' . $vid->getName() . '</span>' : '') . '</h4>
    </div>' ;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr" data-theme="light-blue">
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
                        <div class="row background-primary">
                            <!-- Desc et bouton - - - - - - - - - - - -  - - -->
                            <div class="col-lg-5 col-md-5 col-sm-5 col-12 user-navbar">
                                <span class="user-centered text-white">Description</span>

                                <!-- Bouton Follow - - - - - - - - - - - - - -->
                                <?php if ($owner->getId() != ($userConnected === -1 ? -1 : $userConnected->getId())) { ?>
                                    <button type="button" id="btnFollowOwner" class="btn <?php echo ($isFollower ? "user-followed" : "btn-primary") . ($userConnected === -1 ? " user-hidden" : "")?> btn-lg user-follow-btn" onclick="submitForm(this, 'formFollowOwner'); changeFollowedList(); changeFollowers()"><?php echo ($isFollower ? "FOLLOWED" : "FOLLOW") ?></button>
                                <?php } ?>
                                <form class="" action="/follow/" id="formFollowOwner" method="get" target="iframe-user">
                                    <input type="hidden" name="ownerId" id="ownerId" value="<?php echo $owner->getId(); ?>">
                                    <input type="hidden" name="userId" value="<?php echo ($userConnected === -1 ? -1 : $userConnected->getId()) ?>">
                                </form>
                                <iframe class="user-hidden" name="iframe-user"></iframe>

                                <!-- Bouton Edit - - - - - - - - - - - - - - -->
                                <?php if ($owner->getId() == $userConnected->getId()) { ?>
                                    <button type="button" id="btnEditDesc" class="btn btn-lg stroked-basic p-2 m-1 basic" onclick="editDesc();">Edit</button>
                                    <button type="button" id="btnCancelDesc" class="btn btn-lg stroked-warning p-2 m-1 warning user-hidden" onclick="editDesc(true);">Cancel</button>
                                <?php } ?>
                                <form class="" action="/editDesc/" id="formEditDesc" method="get" target="iframe-desc">
                                    <input type="hidden" name="ownerId" id="ownerId" value="<?php echo $owner->getId(); ?>">
                                    <input type="hidden" name="desc" id="descHiddenInput" value="<?php echo $owner->getDescription(); ?>">
                                </form>
                                <iframe class="user-hidden" name="iframe-desc"></iframe>
                            </div>

                            <!-- Stats - - - - - - - - - - - -  - - - - - - - -->
                            <div class="col-lg-7 col-md-7 col-sm-7 col-12 container user-navbar">
                                <!-- followers -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 user-stats-container">
                                    <span class="user-centered text-white" id="spanFollowers"><?php echo $followersFormatted . " follower(s)"?></span>
                                </div>

                                <!-- Avatar -->
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6 user-stats-container">
                                    <span class="user-centered text-white" id="ownerName">
                                        <img src="<?php echo $owner->getAvatar(); ?>" alt="Avatar" class="rounded-circle user-img" id="ownerImage">
                                        <?php echo $owner->getName(); ?>
                                    </span>
                                </div>
                            </div>

                        </div>

                        <!-- Description =================================== -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-desc" id="desc">
                                <span class="basic"><?php echo $owner->getDescription(); ?></span>
                            </div>
                            <textarea id="descTxt" class="user-desc user-desc-txt basic user-hidden"><?php echo $owner->getDescription(); ?></textarea>
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
                                <h2 class="primary">Latest</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-video-line">
                                    <?php for ($i=0; $i < count($latestVideos); $i++) {
                                        echo createVideoRec($latestVideos[$i]);
                                    } ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <h2 class="primary">Most viewed</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-video-line">
                                    <?php for ($i=0; $i < count($mostViewedVideos); $i++) {
                                        echo createVideoRec($mostViewedVideos[$i]);
                                    } ?>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <h2 class="primary">All videos</h2>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-latest">
                                    <?php for ($i=0; $i < count($allVideos); $i++) {
                                        echo createVideoRec($allVideos[$i]);
                                    } ?>
                                </div>
                            </div>
                            <input type="hidden" id="video_id" name="v" value="">
                        </form>
                        <hr>
                        <div class="row">
                            <h2>Ajouter une vidéo</h2>
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <form action="/api/upload_file" method="post" enctype="multipart/form-data">
                                    <label for="file"><span>Choisir un fichier : </span></label>
                                    <input type="file" name="file" id="file" />
                                    <br />
                                    <input type="submit" name="submit" value="Submit" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php require("./Views/Common/footer.php"); ?>
    </body>
    <script type="text/javascript">
        var followers = <?php echo ($isFollower ? $followers - 1 : $followers); ?>
    </script>
    <script src="/src/scripts/User.js" charset="utf-8"></script>
</html>
