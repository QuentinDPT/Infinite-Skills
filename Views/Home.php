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
//if ($nb_themes_displayed > 3) $nb_themes_displayed = 3;

function getVideosByThemeId($list, $id) {
    $listRes = array();
    for ($i=0; $i < count($list['Videos']); $i++) {
        if ($list['Videos'][$i]->getThemeId() == $id) $listRes[] = $list['Videos'][$i];
    }
    return $listRes;
}
function createVideoRec($vid) {
    return
    '<div class="video col-5 col-sm-4 col-md-2" onclick="submitForm(this, `formVideo`)">
      <div>
        <div class="thumbnail">
          <img src="' . $vid->getThumbnail() .'" alt="Thumbnail" id="' . $vid->getId() . '">
        </div>
        <div class="description">' . str_replace("\\n", "</br>", $vid->getDescription()) . '</div>
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

      <link rel="stylesheet" href="/src/styles/thumbnail.css">

      <main class="container-fluid mb-4">
          <!-- Content =================================================== -->
          <section class="row">
              <?php require("./Views/Common/followed.php"); ?>

              <!-- Videos ================================================ -->
              <div class="col-lg-10 col-md-11 col-sm-11 col-11">
                  <form class="" action="/watch" method="get" id="formVideo">
                      <?php for ($i=0; $i < $nb_themes_displayed; $i++) { ?>
                          <div class="theme">
                              <h2><?php echo $global_data['Themes'][$i]->getName() ?></h2>
                              <div style="display: flex; overflow-x: auto;">
                                  <?php
                                  $filtered_list = getVideosByThemeId($global_data, $global_data['Themes'][$i]->getId());
                                  for ($j=0; $j<count($filtered_list); $j++) {
                                      echo createVideoRec($filtered_list[$j]);
                                  } ?>
                              </div>
                              <hr>
                          </div>
                      <?php } ?>
                      <input type="hidden" id="video_id" name="v" value="">
                  </form>
              </div>
          </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>

  <script type="text/javascript">
      function submitForm(div, formId) {
          var form = document.getElementById(formId);
          var img = div.getElementsByTagName("img")[0];
          switch (formId) {
              case "formVideo":
                  document.getElementById("video_id").value = img.id;
                  break;
              case "formFollow":
                  document.getElementById("follow_id").value = img.id;
                  form.submit();
                  break;

          }

          document.getElementById(formId).submit();
      }
  </script>
  <script type="text/javascript">
    var sqt = document.getElementsByTagName("square") ;
    for (var i of sqt) {
      i.style.height = i.stle.width ;
    }
  </script>
</html>
