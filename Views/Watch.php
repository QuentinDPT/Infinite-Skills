<?php
require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_User.php");

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);

$video = C_Video::GetVideoById($_GET['v']);
$owner = C_User::GetUserById($video->getOwnerId());
$followers = C_User::GetCountFollowers($owner->getId());
$formattedFollowers = formatNumber($followers);

$isFollower = false;
$hasLiked = false;
if ($userConnected !== -1) {
    $isFollower = C_User::GetFollowByOwnerAndUser($owner->getId(), $userConnected->getId());
    $hasLiked = C_User::GetLikeByVideoAndUser($video->getId(), $userConnected->getId());
}
$comments = C_Video::GetComments($video->getId());
$likes = C_Video::GetLikes($video->getId());
$formattedLikes = formatNumber($likes);
$views = formatNumber($video->getViews());
$related = C_Video::GetRelatedVideos($video);

// Add a view
C_User::AddSee($video->getId(), ($userConnected === -1 ? -1 : $userConnected->getId()));

function createVideoRec($vid) {
    return
    '<div class="video col-11" onclick="' . ((!isset($_SESSION['User']) && $vid->getPrice() > 0) ? "createModal('login', '/watch?v=" . $vid->getId() . "');" : "submitForm(this, `formVideo`)") . '">
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

function createVideoFrame($video){
    $url = $video->getUrl();
    $type = C_Video::GetTypeVideo($video);
    if ($type == "file") {
        $dom = "<video width='100%' height='100%' preload='auto' controls>
                <source src='$url' type='video/mp4' autoplay>
                Impossible de charger la vidéo
                </video>";
        $js ="";
    }else{
        $dom = '<div id="player" class="video-player"></div>';
        $js = " // 2. This code loads the IFrame Player API code asynchronously.
         var tag = document.createElement('script');

         tag.src = 'https://www.youtube.com/iframe_api';
         var firstScriptTag = document.getElementsByTagName('script')[0];
         firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

         // 3. This function creates an <iframe> (and YouTube player)
         //    after the API code downloads.
         var player;
         function onYouTubeIframeAPIReady() {
           player = new YT.Player('player', {
             height: '360',
             width: '640',
             videoId: '$url',
             events: {
               'onReady': onPlayerReady,
               'onStateChange': onPlayerStateChange
             }
           });
         }

         // 4. The API will call this function when the video player is ready.
         function onPlayerReady(event) {
           event.target.playVideo();
         }

         // 5. The API calls this function when the player's state changes.
         //    The function indicates that when playing a video (state=1),
         //    the player should play for six seconds and then stop.
         var done = false;
         function onPlayerStateChange(event) {
           if (event.data == YT.PlayerState.PLAYING && !done) {
             setTimeout(stopVideo, 6000);
             done = true;
           }
         }
         function stopVideo() {
           player.stopVideo();
       }";
    }

   return array("dom" => $dom, "js" => $js);
}

$HeaderSocial = '
  <meta property="og:title" content="' . $video->getName() . '">
  <meta property="og:description" content="' . $video->getDescription() . '">
  <meta property="og:image" content="' . $video->getThumbnail() . '">
  <meta property="og:url" content="https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">

  <meta property="og:site_name" content="Infinite Skills">' ;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <?php require("./Views/Common/head.php") ?>
    <body>
        <?php require("./Views/Common/navbar.php") ?>
        <link rel="stylesheet" href="/src/styles/comments.css">
        <link rel="stylesheet" href="/src/styles/thumbnail.css">
        <link rel="stylesheet" href="/src/styles/user.css">

        <main class="container-fluid mb-4">
            <!-- Content =================================================== -->
            <section class="row">
                <?php require("./Views/Common/followed.php"); ?>

                <!-- Video / Desc / Comments =============================== -->
                <div class="col-lg-8 col-md-11 col-sm-11 col-11 mb-4">
                    <!-- Video ============================================= -->
                    <div class="video-container">
                        <?= createVideoFrame($video)['dom']?>
                        <div class="video-info">
                            <div class="col-lg-9 col-md-9 col-sm-8 col-7">
                                <span class="h3 basic"> <?php echo $video->getName(); ?></span></br>
                                <span class="link"> <?php echo $views . ($video->getViews() > 1 ? " Views" : " View") . " • " . $video->getPublication(); ?>
                                      <div id="share-section" class="fb-share-button"
                                        data-href="https://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>"
                                        data-layout="button">
                                      </div>
                                </span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-3 col-4 video-iframe-container p-0">
                                <span id="spanLikes" class="link video-iframe"><?php echo $formattedLikes ?></span>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-1 text-left video-views p-0">
                                <button type="button" id="btnLike" class="btn <?php echo ($hasLiked ? "video-liked" : "btn-success") . ($userConnected === -1 ? " video-hidden" : "") ?>" onclick="submitForm(this, 'formLike'); changeLike()"><?php echo ($hasLiked ? "LIKED" : "LIKE") ?></button>
                            </div>
                        </div>
                    </div>
                    <form class="" action="/connection" method="get" id="formConnect"></form>
                    <form class="" action="/like/" method="get" target="iframe-likes" id="formLike">
                        <input type="hidden" name="userId" value="<?php echo ($userConnected === -1 ? -1 : $userConnected->getId()); ?>">
                        <input type="hidden" name="videoId" value="<?php echo $video->getId(); ?>">
                    </form>
                    <iframe name="iframe-likes" class="hidden" width="500" height="100"></iframe>
                    <hr>

                    <!-- Desc and User ===================================== -->
                    <form class="" action="/users" method="get" id="userForm">
                        <div class="container text-left ml-0 p-0" >
                            <div class="row" style="display: flex">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                    <img class="rounded-circle" src="<?php echo $owner->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $owner->getId() ?>" onclick="submitForm(this, 'userForm')">
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-6">
                                    <div class="video-owner">
                                        <span class="h5 link" id="ownerName" onclick="submitForm(this, 'userForm')"><?php echo $owner->getName() ?></span></br>
                                        <div class="video-iframe-container">
                                            <span id="spanFollowers" class="link"><?php echo $formattedFollowers ?> follower(s)</span>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="u" name="u" value="<?php echo $owner->getId() ?>">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                    <?php if ($owner->getId() != ($userConnected === -1 ? -1 : $userConnected->getId())) { ?>
                                        <button type="button" id="btnFollowOwner" class="btn <?php echo ($isFollower ? "user-followed" : "btn-primary") . ($userConnected === -1 ? " video-hidden" : "")?> btn-lg video-follow-btn" onclick="submitForm(this, 'formFollowOwner'); changeFollowedList(); changeFollowers()"><?php echo ($isFollower ? "FOLLOWED" : "FOLLOW") ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-2"></div>
                                <div class="col-lg-11 col-md-11 col-sm-10 col-10">
                                    <div class="video-desc" id="desc">
                                        <p class="basic"> <?php echo str_replace("\\n", "</br>", $video->getDescription()) ?> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex">
                            <div class="col-5"> <hr> </div>
                            <div class="col-2 text-center">
                                <?php if (count(explode("\\n", $video->getDescription())) > 3) { ?>
                                    <div class="comment-next">
                                        <span class="comment-button" onclick="readMore(this, 'desc')">Read more</span>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-5"> <hr> </div>
                        </div>
                    </form>
                    <form class="" action="/follow/" id="formFollowOwner" method="get" target="iframe-followers">
                        <input type="hidden" name="ownerId" value="<?php echo $owner->getId(); ?>">
                        <input type="hidden" name="userId" value="<?php echo ($userConnected === -1 ? -1 : $userConnected->getId()) ?>">
                    </form>
                    <iframe name="iframe-followers" class="hidden"></iframe>
                    <iframe class="video-hidden" name="iframe-video"></iframe>

                    <!-- Comments ========================================== -->
                    <div class="comments" id="comments">
                        <div class="col-2 text-left mt-4 mb-4 comments-title">
                            <span class="h4 primary">Comments</span>
                            <span class="comment-button comment-display pl-4" onclick="showComments(this);">Display</span>
                        </div>

                        <!-- Add one =========================================== -->
                        <?php if ($userConnected !== -1) { ?>
                            <div class="comment-container mt-4">
                                <!-- User ========================================== -->
                                <div class="col-lg-1 col-md-2 col-sm-2 col-3 pr-0 pl-0 comment-user">
                                    <img class="comment-user-icon" src="<?php echo $userConnected->getAvatar() ?>" alt="avatar" id="<?php echo $userConnected->getId() ?>" onclick="submitForm(this, 'userForm2')">
                                </div>

                                <!-- Text ========================================== -->
                                <div class="col-lg-11 col-md-10 col-sm-10 col-9 pr-0 pl-0 mb-4">
                                    <form id="form-comment" class="" action="" method="get">
                                        <div class="comment-text-container">
                                            <p class="comment-user-name"><?php echo $userConnected->getName() ?></p>
                                            <textarea class="comment-create" id="newComment" name="content" placeholder="Type your comment!" onkeyup="commentChanged(this)"></textarea>
                                            <button type="submit" id="subComment" class="btn btn-success" disabled>Validate</button>
                                        </div>
                                        <input type="hidden" name="videoId" value="<?php echo $video->getId(); ?>">
                                        <input type="hidden" name="userId" value="<?php echo $userConnected->getId(); ?>">
                                    </form>
                                </div>
                            </div>
                        <?php } ?>

                        <div id="list-comments" class="mt-4">
                            <?php
                            if (count($comments) < 1) { ?>
                                <div id="no-comment" class="text-center">
                                    <p class="link">No comments. Be the first!</p>
                                </div>
                            <?php }
                            else {
                                for ($i=0; $i < count($comments); $i++) {
                                    $c = $comments[$i];
                                    $c_user = C_User::GetUserById($c->getUserId()); ?>

                                    <div class="comment-container">
                                        <!-- User ========================================== -->
                                        <div class="col-lg-1 col-md-2 col-sm-2 col-3 pr-0 pl-0 comment-user">
                                            <img class="comment-user-icon" src="<?php echo $c_user->getAvatar() ?>" alt="avatar" id="<?php echo $c_user->getId() ?>" onclick="document.getElementById('u').value = <?php echo $c_user->getId(); ?>; submitForm(this, 'userForm')">
                                        </div>

                                        <!-- Text ========================================== -->
                                        <div class="col-lg-11 col-md-10 col-sm-10 col-9 pr-0 pl-0">
                                            <div class="comment-text-container">
                                                <p class="comment-user-name"><?php echo $c_user->getName() ?> • <?php echo $c->getDate() ?></p>
                                                <p class="comment-text basic" id="<?php echo $c->getId(); ?>"> <?php echo str_replace("\\n", "</br>", $c->getContent()) ?></p>
                                            </div>
                                            <?php if ($c->getNumberLines() > 3) { ?>
                                                <div class="comment-next">
                                                    <span class="comment-button" onclick="readMore(this, '<?php echo $c->getId(); ?>')">Read more</span>
                                                </div>
                                            <?php } ?>
                                        </div>

                                    </div>

                                <?php }
                            }
                            ?>
                        </div>
                    </div>

                </div>

                <!-- Related Content ======================================= -->
                <div class="col-lg-0 col-md-1 col-sm-1 col-1 video-related-space"></div>
                <div class="col-lg-2 col-md-11 col-sm-11 col-11 mb-4 video-related-section">
                    <h4 class="primary">Related content:</h4>
                    <form class="video-related-content" action="/watch" method="get" id="formVideo">
                        <div class="video-related">
                            <input type="hidden" name="v" id="video_id" value="">
                            <?php for ($i=0; $i < count($related); $i++) { ?>
                                <div class="video-related-container">
                                    <?php echo createVideoRec($related[$i]); ?>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            </section>
        </main>

        <?php require("./Views/Common/footer.php"); ?>
        <div id="fb-root"></div>
    </body>

    <!-- Load Facebook SDK for JavaScript -->
    <script src="/src/scripts/Watch.js" charset="utf-8"></script>
    <script>
      async function phoneShare(){
        try {
          const url = "https://<?=$_SERVER['HTTP_HOST']?><?=$_SERVER['REQUEST_URI']?>" ;
          const title = "Look what I've found" ;
          const text  = "Hey !\nI just want you to take a look at this site. It's lovely\nBye :3" ;
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
        document.getElementById("share-section").outerHTML =
          `<input id="share-btn" type="button" class="btn btn-sm btn-primary" value="Share""/>` ;
        document.getElementById("share-btn").addEventListener("click",phoneShare) ;

      }else{
        // default laptop fb share
            (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
            fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));
      }
    </script>
    <script type="text/javascript">
        <?= createVideoFrame($video)['js'] ?>

        $("#form-comment").on("submit", function(e){
            e.preventDefault();
            let data = $(this).serialize();
            console.log(data);
            $.ajax({
               type: "GET",
               url: "/new-comment",
               data: data,
               success: function(res){
                   if(res == 0){
                       console.log("error");
                   }else{
                       let isVisible = $("#no-comment").is(":visible");
                       if(isVisible){
                           $("#no-comment").hide();
                       }
                       $("#list-comments").prepend(res);
                   }
               }
            });
        });

        var likes = <?php echo ($hasLiked ? $likes - 1 : $likes); ?>;
        var followers = <?php echo ($isFollower ? $followers - 1 : $followers); ?>;
    </script>
</html>
