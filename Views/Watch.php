<?php
session_start();
require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_User.php");

$_SESSION['User'] = 3;

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = C_User::GetUserById($_SESSION["User"]);

$video = C_Video::GetVideoById($_GET['v']);
$owner = C_User::GetUserById($video->getOwnerId());
$followers = C_User::GetCountFollowers($owner->getId());

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
C_User::AddSee($video->getId(), $userConnected->getId());

function formatNumber($num) {
    if ($num >= 1000000000) return round($num / 1000000000, 3) . "Mi";
    else if ($num >= 1000000) return round($num / 1000000, 3) . "M";
    else if ($num >= 1000) return round($num / 1000, 3) . "k";
    return $num;
}
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
                <div class="col-8 mb-4">
                    <!-- Video ============================================= -->
                    <div class="video-container">
                        <iframe src="<?php echo $video->getEmbedUrl(); ?>" frameborder="0" class="video-player"></iframe>
                        <div class="video-info">
                            <div class="col-10">
                                <h3> <?php echo $video->getName(); ?></h3>
                            </div>
                            <div class="col-2 text-left video-views">
                                <span class="text-black-50 mr-2"><?php echo $likes; ?></span>
                                <button type="button" name="button" class="btn <?php echo ($hasLiked ? "video-liked" : "btn-success") ?>" onclick="submitForm(this, 'formLike')"><?php echo ($hasLiked ? "LIKED" : "LIKE") ?></button>
                            </div>
                        </div>
                        <div class="col">
                            <p class="text-black-50"> <?php echo $views . ($video->getViews() > 1 ? " Views" : " View") . " • " . $video->getPublication(); ?> </p>
                        </div>
                    </div>
                    <form class="" action="/like/" method="get" target="iframe-video" id="formLike">
                        <input type="hidden" name="userId" value="<?php echo $userConnected->getId(); ?>">
                        <input type="hidden" name="videoId" value="<?php echo $video->getId(); ?>">
                    </form>

                    <hr>

                    <!-- Desc and User ===================================== -->
                    <form class="" action="#" method="get" id="userForm">
                        <div class="container text-left ml-0" style="display: flex">
                            <div class="col-1">
                                <img class="rounded-circle" src="<?php echo $owner->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $owner->getId() ?>" onclick="submitForm(this, 'userForm')">
                            </div>
                            <div class="col-9">
                                <div class="video-owner">
                                    <span class="h5" onclick="submitForm(this, 'userForm')"><?php echo $owner->getName() ?></span></br>
                                    <span class="text-black-50 mt-0" onclick="submitForm(this, 'userForm')"><?php echo $followers . ($followers > 1 ? ' followers' : " follower"); ?></span>
                                </div>
                                <div class="video-desc" id="desc">
                                    <p> <?php echo str_replace("\\n", "</br>", $video->getDescription()) ?> </p>
                                </div>
                            </div>
                            <input type="hidden" id="u" name="u" value="<?php echo $owner->getId() ?>">
                            <div class="col-2">
                                <?php if ($owner->getId() != $userConnected->getId()) { ?>
                                <button type="button" name="button" class="btn <?php echo ($isFollower ? "video-followed" : "btn-primary") ?> btn-lg video-follow-btn" onclick="submitForm(this, 'formFollowOwner')"><?php echo ($isFollower ? "FOLLOWED" : "FOLLOW") ?></button>
                            <?php } ?>
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
                    <form class="" action="/follow/" id="formFollowOwner" method="get" target="iframe-video">
                        <input type="hidden" name="ownerId" value="<?php echo $owner->getId(); ?>">
                        <input type="hidden" name="userId" value="<?php echo $userConnected->getId() ?>">
                    </form>
                    <iframe class="video-hidden" name="iframe-video"></iframe>

                    <!-- Comments ========================================== -->
                    <div class="col-2 text-left mt-4 mb-4">
                        <h4>Comments</h4>
                    </div>

                    <!-- Add one =========================================== -->
                    <?php if ($userConnected !== -1) { ?>
                        <div class="comment-container">
                            <!-- User ========================================== -->
                            <div class="col-1 pr-0 pl-0 comment-user">
                                <img class="comment-user-icon" src="<?php echo $userConnected->getAvatar() ?>" alt="avatar" id="<?php echo $userConnected->getId() ?>" onclick="submitForm(this, 'userForm')">
                            </div>

                            <!-- Text ========================================== -->
                            <div class="col-11 pr-0 pl-0">
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
                                <div class="col-1 pr-0 pl-0 comment-user">
                                    <img class="comment-user-icon" src="<?php echo $c_user->getAvatar() ?>" alt="avatar" id="<?php echo $c_user->getId() ?>" onclick="submitForm(this, 'userForm')">
                                </div>

                                <!-- Text ========================================== -->
                                <div class="col-11 pr-0 pl-0">
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

                <!-- Related Content ======================================= -->
                <div class="col-2 mb-4">
                    <h4>Related content:</h4>
                    <div class="video-related">
                        <form class="" action="/watch" method="get" id="formVideo">
                            <input type="hidden" name="v" id="v" value="">
                            <?php for ($i=0; $i < count($related); $i++) { ?>
                                <div class="video-related-container">
                                    <?php echo createVideoRec($related[$i]); ?>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
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
                case "formVideo":
                    document.getElementById('v').value = div.getElementsByTagName('img')[0].id;
                    form.submit();
                    break;
                case "formFollowOwner":
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
                    form.submit();
                    break;
                case "formLike":
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
                    form.submit();
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
    </script>
</html>
