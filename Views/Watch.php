<?php
session_start();
require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_User.php");

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);

$video = C_Video::GetVideoById($_GET['v']);
$owner = C_User::GetUserById($video->getOwnerId());
$followers = formatNumber(C_User::GetCountFollowers($owner->getId()));

$isFollower = false;
$hasLiked = false;
if ($userConnected !== -1) {
    $isFollower = C_User::GetFollowByOwnerAndUser($owner->getId(), $userConnected->getId());
    $hasLiked = C_User::GetLikeByVideoAndUser($video->getId(), $userConnected->getId());
}
$comments = C_Video::GetComments($video->getId());
$likes = formatNumber(C_Video::GetLikes($video->getId()));
$views = formatNumber($video->getViews());
$related = C_Video::GetRelatedVideos($video);

// Add a view
C_User::AddSee($video->getId(), ($userConnected === -1 ? -1 : $userConnected->getId()));

function createVideoRec($vid) {
    return
    '<div class="video" onclick="submitForm(this, `formVideo`)">
      <div>
        <div class="thumbnail">
          <img src="' . $vid->getThumbnail() .'" alt="Thumbnail" id="' . $vid->getId() . '">
        </div>
        <div class="description">' . $vid->getDescription() . '</div>
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
        <link rel="stylesheet" href="/src/styles/comments.css">
        <link rel="stylesheet" href="/src/styles/thumbnail.css">

        <main class="container-fluid mb-4">
            <!-- Content =================================================== -->
            <section class="row">
                <?php require("./Views/Common/followed.php"); ?>

                <!-- Video / Desc / Comments =============================== -->
                <div class="col-lg-8 col-md-11 col-sm-11 col-11 mb-4">
                    <!-- Video ============================================= -->
                    <div class="video-container">
                        <iframe src="<?php echo $video->getEmbedUrl(); ?>" frameborder="0" class="video-player"></iframe>
                        <div class="video-info">
                            <div class="col-md-9 col-8">
                                <span class="h3"> <?php echo $video->getName(); ?></span></br>
                                <span class="text-black-50"> <?php echo $views . ($video->getViews() > 1 ? " Views" : " View") . " • " . $video->getPublication(); ?> </span>
                            </div>
                            <div class="col-md-2 col-2 video-iframe-container p-0">
                                <iframe class="video-iframe" name="iframe-likes" frameborder="0" onload="resizeIframe(this)" on>
                                </iframe>
                            </div>
                            <div class="col-md-1 col-2 text-left video-views p-0">
                                <button type="button" id="btnLike" class="btn <?php echo ($hasLiked ? "video-liked" : "btn-success") ?>" onclick="submitForm(this, 'formLike');"><?php echo ($hasLiked ? "LIKED" : "LIKE") ?></button>
                            </div>
                        </div>
                    </div>
                    <form class="" action="<?php echo ($userConnected === -1 ? '/connection' : '/like/') ?>" method="get" target="<?php echo ($userConnected === -1 ? "" : "iframe-likes") ?>" id="formLike">
                        <?php if ($userConnected !== -1) { ?>
                            <input type="hidden" name="userId" value="<?php echo $userConnected->getId(); ?>">
                            <input type="hidden" name="videoId" value="<?php echo $video->getId(); ?>">
                            <input type="hidden" id="doReqLike" name="doReq" value="0">
                        <?php } ?>
                    </form>

                    <hr>

                    <!-- Desc and User ===================================== -->
                    <form class="" action="#" method="get" id="userForm">
                        <div class="container text-left ml-0 p-0" >
                            <div class="row" style="display: flex">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-2">
                                    <img class="rounded-circle" src="<?php echo $owner->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $owner->getId() ?>" onclick="submitForm(this, 'userForm')">
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-6">
                                    <div class="video-owner">
                                        <span class="h5" onclick="submitForm(this, 'userForm')"><?php echo $owner->getName() ?></span></br>
                                        <div class="video-iframe-container">
                                            <iframe class="" name="iframe-followers" width="500" height="50" frameborder="0">
                                            </iframe>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="u" name="u" value="<?php echo $owner->getId() ?>">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-4">
                                    <?php if ($owner->getId() != ($userConnected === -1 ? -1 : $userConnected->getId())) { ?>
                                        <button type="button" id="btnFollowOwner" class="btn <?php echo ($isFollower ? "video-followed" : "btn-primary") ?> btn-lg video-follow-btn" onclick="submitForm(this, 'formFollowOwner');"><?php echo ($isFollower ? "FOLLOWED" : "FOLLOW") ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-2 col-2"></div>
                                <div class="col-lg-11 col-md-11 col-sm-10 col-10">
                                    <div class="video-desc" id="desc">
                                        <p> <?php echo str_replace("\\n", "</br>", $video->getDescription()) ?> </p>
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
                    <form class="" action="<?php echo ($userConnected === -1 ? '/connection' : '/follow/') ?>" id="formFollowOwner" method="get" target="<?php echo ($userConnected === -1 ? "" : "iframe-followers") ?>">
                        <?php if ($userConnected !== -1) { ?>
                            <input type="hidden" name="ownerId" value="<?php echo $owner->getId(); ?>">
                            <input type="hidden" name="userId" value="<?php echo $userConnected->getId() ?>">
                            <input type="hidden" id="doReqFollow" name="doReq" value="0">
                        <?php } ?>
                    </form>
                    <iframe class="video-hidden" name="iframe-video"></iframe>

                    <!-- Comments ========================================== -->
                    <div class="comments" id="comments">
                        <div class="col-2 text-left mt-4 mb-4 comments-title">
                            <span class="h4">Comments</span>
                            <span class="comment-button comment-display pl-4" onclick="showComments(this);">Display</span>
                        </div>

                        <!-- Add one =========================================== -->
                        <?php if ($userConnected !== -1) { ?>
                            <div class="comment-container mt-4">
                                <!-- User ========================================== -->
                                <div class="col-lg-1 col-md-2 col-sm-2 col-3 pr-0 pl-0 comment-user">
                                    <img class="comment-user-icon" src="<?php echo $userConnected->getAvatar() ?>" alt="avatar" id="<?php echo $userConnected->getId() ?>" onclick="submitForm(this, 'userForm')">
                                </div>

                                <!-- Text ========================================== -->
                                <div class="col-lg-11 col-md-10 col-sm-10 col-9 pr-0 pl-0 mb-4">
                                    <form class="" action="/new-comment" method="get">
                                        <div class="comment-text-container">
                                            <p class="comment-user-name"><?php echo $userConnected->getName() ?></p>
                                            <textarea class="comment-create" id="newComment" name="newComment" placeholder="Type your comment!"></textarea>
                                            <button type="submit" class="btn btn-success">Validate</button>
                                        </div>
                                        <input type="hidden" name="videoId" value="<?php echo $video->getId(); ?>">
                                        <input type="hidden" name="userId" value="<?php echo $userConnected->getId(); ?>">
                                    </form>
                                </div>
                            </div>
                        <?php } ?>


                        <?php
                        if (count($comments) < 1) { ?>
                            <div class="text-center">
                                <p>No comments. Be the first!</p>
                            </div>
                        <?php }
                        else {
                            for ($i=0; $i < count($comments); $i++) {
                                $c = $comments[$i];
                                $c_user = C_User::GetUserById($c->getUserId()); ?>

                                <div class="comment-container">
                                    <!-- User ========================================== -->
                                    <div class="col-lg-1 col-md-2 col-sm-2 col-3 pr-0 pl-0 comment-user">
                                        <img class="comment-user-icon" src="<?php echo $c_user->getAvatar() ?>" alt="avatar" id="<?php echo $c_user->getId() ?>" onclick="submitForm(this, 'userForm')">
                                    </div>

                                    <!-- Text ========================================== -->
                                    <div class="col-lg-11 col-md-10 col-sm-10 col-9 pr-0 pl-0">
                                        <div class="comment-text-container">
                                            <p class="comment-user-name"><?php echo $c_user->getName() ?> • <?php echo $c->getDate() ?></p>
                                            <p class="comment-text" id="<?php echo $c->getId(); ?>"> <?php echo str_replace("\\n", "</br>", $c->getContent()) ?></p>
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

                <!-- Related Content ======================================= -->
                <div class="col-lg-0 col-md-1 col-sm-1 col-1 video-related-space"></div>
                <div class="col-lg-2 col-md-11 col-sm-11 col-11 mb-4">
                    <h4>Related content:</h4>
                    <form class="" action="/watch" method="get" id="formVideo">
                        <div class="video-related">
                            <input type="hidden" name="v" id="v" value="">
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

        <?php require("./Views/Common/footer.php") ?>
    </body>
    <script type="text/javascript">
        <?php if ($userConnected !== -1) { ?>
        document.getElementById("btnLike").click();
        document.getElementById("btnFollowOwner").click();
        <?php } ?>

        function submitForm(div, formId) {
            var form = document.getElementById(formId);
            switch (formId) {
                case "userForm": alert("Redirect to user profile"); break;
                case "formVideo":
                    document.getElementById('v').value = div.getElementsByTagName('img')[0].id;
                    form.submit();
                    break;
                case "formFollowOwner":
                    var doReq = document.getElementById("doReqFollow");
                    if (doReq != undefined && doReq.value == "1") {
                        if (Array.from(div.classList).indexOf("video-followed") != -1) {
                            div.innerText = "FOLLOW";
                            div.classList.remove("video-followed");
                            div.classList.add("btn-primary");
                        }
                        else {
                            div.innerText = "FOLLOWED"
                            div.classList.add("video-followed");
                            div.classList.remove("btn-primary");
                        }
                    }
                    form.submit();
                    doReq.value = "1";
                    break;
                case "formLike":
                    var doReq = document.getElementById("doReqLike");
                    if (doReq != undefined && doReq.value == "1") {
                        if (Array.from(div.classList).indexOf("video-liked") != -1) {
                            div.innerText = "LIKE";
                            div.classList.remove("video-liked");
                            div.classList.add("btn-success");
                        }
                        else {
                            div.innerText = "LIKED"
                            div.classList.add("video-liked");
                            div.classList.remove("btn-success");
                        }
                    }
                    form.submit();
                    doReq.value = "1";
                    break;
                default: break;
            }
        }

        function readMore(span, divId) {
            var div = document.getElementById(divId);
            div.classList.add("comment-text-more");
            span.setAttribute('onclick', "readLess(this, '" + divId + "')");
            span.innerText = "Less";
        }
        function readLess(span, divId) {
            var div = document.getElementById(divId);
            div.classList.remove("comment-text-more");
            span.setAttribute('onclick', "readMore(this, '" + divId + "')");
            span.innerText = "Read more";
        }

        function showComments(btn) {
            document.getElementById("comments").classList.toggle("comments-show");
            btn.innerText = (btn.innerText == "Display" ? "Hide" : "Display");
        }

        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
    </script>
</html>
