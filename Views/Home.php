<?php
// Begin session
session_start();

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = $_SESSION["User"];

require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_Theme.php");
require_once("./Controllers/C_User.php");

// Require controllers
// require_once('...');

// Check if user is connected or not
// if ( isset($_SESSION['...']) ) OU fonction controller

// Get videos / themes / etc
$global_data = array();
$global_data['Videos'] = C_Video::GetVideos();
$global_data['Themes'] = C_Theme::GetThemes();
$global_data['Followed'] = C_User::GetFollow($userConnected);


$nb_themes_displayed = count($global_data['Themes']);
if ($nb_themes_displayed > 3) $nb_themes_displayed = 3;

function getVideosByThemeId($list, $id) {
    $listRes = array();
    for ($i=0; $i < count($list['Videos']); $i++) {
        if ($list['Videos'][$i]->getThemeId() == $id) $listRes[] = $list['Videos'][$i];
    }
    return $listRes;
}
function createVideoRec($vid) {
    $styleDiv = "border: 1px solid black; border-radius: 10px; height: 300px; min-width: 250px; max-width: 250px; margin: 10px;";
    $styleDiv2 = "min-width: 100%; min-height: 90%; max-height: 90%; max-width: 100%; height: 90%; background-color: #252525; border-radius: 10px 10px 0 0; position: relative; overflow: hidden";
    $styleImg = "width: 100%; height: auto; border-radius: 10px; margin: auto; position: absolute; top: 0; bottom: 0; object-fit: cover;";
    $div = "<div class='' style='$styleDiv' onclick='submitForm(this, `formVideo`)'>";
    $div2 = "<div style='$styleDiv2'>";
    $img = "<img src='" . $vid->getThumbnail() . "' alt='Thumbnail' style='$styleImg' id='" . $vid->getId() . "'>";
    $h4 = "<h4 class='text-center'>" . $vid->getName() . "</h4>";
    return "$div $div2 $img </div> $h4 </div>";
}

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>

      <main class="container-fluid mb-4">
          <!-- Content =================================================== -->
          <section class="row">
              <!-- Followed ============================================== -->
              <div class="col-2">
                  <form class="" action="#" method="post" id="formFollow">
                      <div class="border border-dark rounded-lg text-center p-2" style="width: 15%; height: 50em; overflow-y: auto; position: fixed">
                          <h3>Followed:</h3>
                          <div class="container text-left">
                          <?php if ($userConnected < 0) { ?> <span class="text-black-50">Not connected. <a href="./connection">Login?</a></span> <?php }
                          else {
                              if (count($global_data['Followed']) == 0) { ?> <span class="text-black-50">You followed no one :(</span> <?php }
                              for ($i=0; $i < count($global_data['Followed']); $i++) { ?>
                              <div class="row" onclick="submitForm(this, 'formFollow')">
                                  <div class="col-3">
                                      <img class="rounded-circle" src="<?php echo $global_data['Followed'][$i]->getAvatar() ?>" alt="avatar" width="50px" height="50px" id="<?php echo $global_data['Followed'][$i]->getId() ?>">
                                  </div>
                                  <div class="col-9 p-2">
                                      <p><?php echo $global_data['Followed'][$i]->getName() ?></p>
                                  </div>
                              </div>
                          <?php } } ?>
                      </div>
                      <input type="hidden" id="follow_id" name="follow_id" value="">
                  </form>
                  </div>
              </div>

              <!-- Videos ================================================ -->
              <div class="col-10">
                  <form class="" action="#" method="post" id="formVideo">
                      <?php for ($i=0; $i < $nb_themes_displayed; $i++) { ?>
                          <div class="col-md-12">
                              <h2><?php echo $global_data['Themes'][$i]->getName() ?></h2>
                              <div style="display: flex; overflow-x: scroll;">
                                  <?php
                                  $filtered_list = getVideosByThemeId($global_data, $global_data['Themes'][$i]->getId());
                                  for ($j=0; $j<count($filtered_list); $j++) {
                                      echo createVideoRec($filtered_list[$j]);
                                  } ?>
                              </div>
                              <hr>
                          </div>
                      <?php } ?>
                      <input type="hidden" id="video_id" name="video_id" value="">
                  </form>
              </div>
          </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>

  <script type="text/javascript">
      function submitForm(div, formId) {
          var img = div.getElementsByTagName("img")[0];
          switch (formId) {
              case "formVideo":
                  alert("Video: " + img.id);
                  document.getElementById("video_id").value = img.id;
                  break;
              case "formFollow":
                  alert("Follow: " + img.id);
                  document.getElementById("follow_id").value = img.id;
                  break;

          }

          document.getElementById(formId).submit();
      }
  </script>
</html>
