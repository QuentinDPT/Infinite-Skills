<?php
// Begin session
session_start();

require_once("./Controllers/C_Video.php");
require_once("./Controllers/C_Theme.php");

// Require controllers
// require_once('...');

// Check if user is connected or not
// if ( isset($_SESSION['...']) ) OU fonction controller

// Get videos / themes / etc
$global_data = array(); // fonction controller à definir

// Just for tests
// Simulate Themes, Videos, and Owner Objects obtained with a POST
/*$global_data['Themes'] = [
    ["Id" => 0, "Name" => "Animals", "Description" => "Everything about animals"],
    ["Id" => 1, "Name" => "Food", "Description" => "Everything about food"],
    ["Id" => 2, "Name" => "Sport", "Description" => "Everything about sport"]
];*/
/*$global_data['Videos'] = [
    ["Id" => 0, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #1", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/691200266363469854/0322.jpg" ],
    ["Id" => 1, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #2", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/686125751678140446/0308.jpg" ],
    ["Id" => 2, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #3", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/684290184799453187/0303.jpg?width=541&height=677" ],
    ["Id" => 8, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #4", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/692273421433962566/0325.jpg?width=677&height=677" ],
    ["Id" => 9, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #5", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/691911901268934686/0324.jpg?width=483&height=677" ],
    ["Id" => 10, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #6", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/691911774588502077/0323.jpg?width=508&height=677" ],
    ["Id" => 11, "OwnerId" => 0, "ThemeId" => 0, "Name" => "Animal #7", "Description" => "Wow", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://media.discordapp.net/attachments/641401938235097110/690850832870146068/0321.jpg?width=761&height=677" ],
    ["Id" => 3, "OwnerId" => 1, "ThemeId" => 1, "Name" => "Food #1", "Description" => "Yum", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://d1doqjmisr497k.cloudfront.net/-/media/ducrosfr-2016/recipes/2000/steak_au_vin_rouge_et_aux_echalotes_2000.jpg?vd=20180616T221321Z&ir=1&width=885&height=498&crop=auto&quality=75&speed=0&hash=53CBB1CD5F1F493DBFE5923FA8F6D7A79AA4CD32" ],
    ["Id" => 4, "OwnerId" => 1, "ThemeId" => 1, "Name" => "Food #2", "Description" => "Yum", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKR_pTc4kCDEF92VGbhk3KDcPzgo8tXhQngMibEye88Ox8FYrR&s" ],
    ["Id" => 5, "OwnerId" => 1, "ThemeId" => 1, "Name" => "Food #3", "Description" => "Yum", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHkC1tgaHwI7VcKVaPR2sZGyljUhxTrvUxZMpy9CRQAnb-Lebi&s" ],
    ["Id" => 6, "OwnerId" => 2, "ThemeId" => 2, "Name" => "Sport #1", "Description" => "Damn", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://www.arcueil.fr/wp-content/uploads/2018/04/sports-arcueil.jpg" ],
    ["Id" => 7, "OwnerId" => 2, "ThemeId" => 2, "Name" => "Sport #2", "Description" => "Damn", "Publication" => "25/03/2020", "Price" => 0, "Views" => 0, "Likes" => 0, "Url" => "url", "Thumbnail" => "https://image.shutterstock.com/image-photo/huge-multi-sports-collage-soccer-260nw-650017768.jpg" ]
];*/
$global_data['Videos'] = C_Video::GetVideos();
$global_data['Themes'] = C_Theme::GetThemes();
$global_data['Followed'] = [
    [ "Id" => 0, "Name" => "Foxy", "Password" => "bidon", "InscriptionDate" => "23/03/2020", "ExpirationDate" => "23/03/2020", "SubscriptionId" => -1, "Avatar" => "https://media.discordapp.net/attachments/641401938235097110/691200266363469854/0322.jpg" ],
    [ "Id" => 1, "Name" => "Steakatcheur", "Password" => "bidon", "InscriptionDate" => "23/03/2020", "ExpirationDate" => "23/03/2020", "SubscriptionId" => -1, "Avatar" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQHkC1tgaHwI7VcKVaPR2sZGyljUhxTrvUxZMpy9CRQAnb-Lebi&s" ],
    [ "Id" => 2, "Name" => "Tybo la Chaype", "Password" => "bidon", "InscriptionDate" => "23/03/2020", "ExpirationDate" => "23/03/2020", "SubscriptionId" => 0, "Avatar" => "https://www.arcueil.fr/wp-content/uploads/2018/04/sports-arcueil.jpg" ],
];


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
                          <?php for ($i=0; $i < count($global_data['Followed']); $i++) { ?>
                              <div class="row" onclick="submitForm(this, 'formFollow')">
                                  <div class="col-3">
                                      <img class="rounded-circle" src="<?php echo $global_data['Followed'][$i]['Avatar'] ?>" alt="avatar" width="50px" height="50px" id="<?php echo $global_data['Followed'][$i]['Id'] ?>">
                                  </div>
                                  <div class="col-9 p-2">
                                      <p><?php echo $global_data['Followed'][$i]['Name'] ?></p>
                                  </div>
                              </div>
                          <?php } ?>
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
