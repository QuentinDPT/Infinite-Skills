<?php
require_once("./Controllers/C_User.php");
require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_Theme.php");

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);
$owner = C_User::GetUserById($_GET["u"]);
$isFollower = ($userConnected !== -1 ? C_User::GetFollowByOwnerAndUser($owner->getId(), $userConnected->getId()) : false);

$followers = C_User::GetCountFollowers($owner->getId());
$followersFormatted = formatNumber(C_User::GetCountFollowers($owner->getId()));
$latestVideos = C_Video::GetLatestVideosByUserId($owner->getId());
$mostViewedVideos = C_Video::GetMostViewedVideosByUserId($owner->getId());
$allVideos = C_Video::GetVideosByUserId($owner->getId());
$allThemes = C_Theme::GetThemes();

$HeaderSocial = '
  <meta property="og:title" content="' . $owner->getName() . '">
  <meta property="og:description" content="' . $owner->getDescription() . '">
  <meta property="og:image" content="' . $owner->getAvatar() . '">
  <meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">

  <meta property="og:site_name" content="Infinite Skills">' ;

function createVideoRec($vid) {
    return
    '<div class="video col-5 col-sm-4 col-md-2" onclick="' . ((!isset($_SESSION['User']) && $vid->getPrice() > 0) ? "createModal('login', '/watch?v=" . $vid->getId() . "');" : "submitForm(this, `formVideo`)") . '" data-likes="'
      . C_Video::GetLikes($vid->GetId()) . '" data-views="' . C_Video::GetViews($vid->GetId()) . '" data-recent="' . date_timestamp_get(new DateTime($vid->GetPublication())) . '" data-price="'. $vid->getPrice() . '" data-id="' . $vid->GetId() . '" data-theme="'
      . $vid->getThemeId() . '">
      <div>
        <div class="thumbnail">
          <img data-src="' . $vid->getThumbnail() .'" alt="Loading..." id="' . $vid->getId() . '">
        </div>
        <div class="usrAvatar">
          <div class="userAvatar">
            <img data-src="' . $vid->getThumbnail() .'" alt="Loading..." id="' . $vid->getId() . '">
          </div>
        </div>
        <div class="description basic">' . str_replace("\\n", "</br>", $vid->getDescription()) . '</div>' .
        ($vid->getPrice() > 0 ?
        '<div class="video-price-container">
            <span class="basic video-price">$' . $vid->getPrice() . '</span>
        </div>' : "") .
      '</div>
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
                                <?php if ($userConnected !== -1 && $owner->getId() == $userConnected->getId()) { ?>
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
                                        <img data-src="<?php echo $owner->getAvatar(); ?>" alt="Avatar" class="rounded-circle user-img" id="ownerImage">
                                        <?php echo $owner->getName(); ?>
                                    </span>
                                </div>
                            </div>

                        </div>

                        <!-- Description =================================== -->
                        <div class="row mb-4">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 user-desc" id="desc">
                                <div class="basic"><?php echo $owner->getDescription(); ?></div>
                                <input id="share-btn" type="button" class="btn btn-sm btn-primary position-absolute share-btn" value="Share">
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

                        <!-- New / Edit Video ============================== -->
                        <?php if ($userConnected !== -1 && $owner->getId() == $userConnected->getId()) { ?>
                        <div class="row mb-4">
                            <div class="col-12 user-create-container">
                                <button type="button" id="btnEditVid" class="btn btn-lg bg-primary-color basic" onclick="changeUserPage(this)">Create or edit videos</button>
                            </div>
                        </div>

                        <div class="container-fluid mt-4 user-hidden basic" id="divCreate">
                            <form action="/api/upload_file" method="post" enctype="multipart/form-data" onsubmit="subForm()">
                                <div class="row mb-4">
                                    <!-- Thumbnail ========================= -->
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4 flex-c">
                                        <span>Thumbnail:</span>
                                        <div class="user-new-thumbnail">
                                            <div class="user-new-url-container user-hidden" id="divUrlImg">
                                                <div class="row mt-4 mb-4">
                                                    <input type="text" class="form-control user-new-url" name="txtUrlImg" id="txtUrlImg" value="" placeholder="URL">
                                                </div>
                                                <div class="row mt-4 mb-4">
                                                    <div class="col-6 centered-h">
                                                        <button type="button" class="btn btn-success" onclick="useUrlImg()">OK</button>
                                                    </div>
                                                    <div class="col-6 centered-h">
                                                        <button type="button" class="btn btn-danger" onclick="useUrlImg(true)">cancel</button>
                                                    </div>
                                                </div>

                                            </div>

                                            <img src="https://static.thenounproject.com/png/340719-200.png" class="user-new-img" name="imgNewVideo" id="imgNewVideo" onclick="useUrlImg(true)">

                                        </div>
                                        <input type="text" name="txtTitle" value="" placeholder="Title" class="form-control mt-2 user-new-title" id="txtTitle">
                                    </div>

                                    <!-- File ============================== -->
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4 flex-c">
                                        <span>Upload file or Youtube video:</span>
                                        <div class="user-new-file-container">
                                            <div class="user-new-url-container user-hidden" id="divUrl">
                                                <div class="row mt-4 mb-4">
                                                    <input type="text" class="form-control user-new-url" name="txtUrl" id="txtUrl" value="" placeholder="URL">
                                                </div>
                                                <div class="row mt-4 mb-4">
                                                    <div class="col-6 centered-h">
                                                        <button type="button" class="btn btn-success" onclick="useUrl()">OK</button>
                                                    </div>
                                                    <div class="col-6 centered-h">
                                                        <button type="button" class="btn btn-danger" onclick="useUrl(true)">cancel</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-6 centered-h mt-4">
                                                    <button type="button" name="btnFile" class="btn bg-primary-color basic" onclick="openFile()">Choose a file</button>
                                                </div>
                                                <div class="col-6 centered-h mt-4">
                                                    <button type="button" name="btnFile" class="btn bg-primary-color basic" onclick="useUrl(true)">Youtube link</button>
                                                </div>
                                            </div>

                                            <div class="row mb-4 ml-4">
                                                <span id="spanFileName">File name:</span>
                                            </div>

                                            <div class="row mb-4 ml-4">
                                                <span id="spanFileType">Type:</span>
                                            </div>
                                        </div>
                                        <!-- File input ==================== -->
                                        <input type="file" name="file" id="file" accept=".mp4,.ogg,.webm" onchange="loadFile(this)" class="user-hidden"/>
                                        <input type="hidden" name="typeVideo" id="typeVideo" value="file">
                                    </div>


                                    <!-- Description ======================= -->
                                    <div class="col-lg-4 col-md-6 col-sm-12 col-12 mb-4">
                                        <div class="row">
                                            <span>Description:</span>
                                        </div>
                                        <div class="row">
                                            <textarea name="txtNewDesc" id="txtNewDesc" class="user-new-desc"></textarea>
                                        </div>
                                    </div>

                                    <!-- Price ============================= -->
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12 mb-4">
                                        <div class="container user-new-price-container">
                                            <div class="row">
                                                <span>Price: </span>
                                                <input type="number" name="txtPrice" value="0" placeholder="Price" class="form-control" id="txtPrice">
                                            </div>
                                            <div class="row text-center">
                                                <span>Youtube videos will automaticaly be free ($0)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Theme ============================= -->
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-12 mb-4 flex-c">
                                        <span>Theme:</span>
                                        <select class="form-control" name="selectTheme" id="selectTheme">
                                            <?php for ($i=0; $i < count($allThemes); $i++) { ?>
                                                <option value="<?php echo $allThemes[$i]->getId(); ?>"><?php echo $allThemes[$i]->getName(); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-lg btn-success form-control" id="btnSave">SAVE</button>
                                <input type="hidden" id="inputEdit" name="edit" value="-1">
                                <input type="hidden" id="inputDelete" name="delete" value="-1">
                            </form>
                        </div>
                        <?php } ?>

                        <!-- Latest Videos ================================= -->
                        <div class="mt-4" id="divSearch">
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
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Contextual Menu ============================================== -->
        <nav class="context-menu" id="context-menu">
          <ul class="context-menu__items m-0 p-2">
            <li class="context-menu__item mb-2">
              <button class="btn btn-lg bg-primary-color basic context-menu__link" data-action="Edit" onclick="editVideo()">
                <i class="fa fa-edit"></i> Edit Video
              </button>
            </li>
            <li class="context-menu__item">
              <button class="btn btn-lg bg-warning basic context-menu__link" data-action="Delete" onclick="deleteVideo()">
                <i class="fa fa-times"></i> Delete Video
            </button>
            </li>
          </ul>
        </nav>
        <?php require("./Views/Common/footer.php"); ?>
    </body>
    <script type="text/javascript">
        var followers = <?php echo ($isFollower ? $followers - 1 : $followers); ?>
    </script>
    <script>
      async function phoneShare(){
        try {
          const url = "https://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>" ;
          const title = "Checkout <?= $userConnected != -1 && $owner->getId() == $userConnected->getId() ? "my" : "this"?> page" ;
          const text  = "Checkout <?= $userConnected != -1 && $owner->getId() == $userConnected->getId() ? "my" : "this"?> page at " ;
          await navigator.share({undefined, title, text, url});
        } catch (error) {
          console.log('Error sharing: ' + error);
        }
      }

      if (navigator.share != undefined) {
        // advanced share on phone
        if (window.location.protocol === 'http:') {
          // navigator.share() is only available in secure contexts.
          window.location.replace(window.location.href.replace(/^http:/, 'https:'));
        }
        document.getElementById("share-btn").addEventListener("click",phoneShare) ;

      }else{
        document.getElementById("share-btn").outerHTML = `` ;
      }
    </script>
    <script src="/src/scripts/User.js" charset="utf-8"></script>
</html>
