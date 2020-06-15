<?php

$userConnected = -1;
if (isset($_SESSION["User"])) $userConnected = $_SESSION["User"];
else header('Location: ./Views/Home.php');

require_once("./Controllers/C_Theme.php");
$allThemes = C_Theme::GetThemes();
usort($allThemes, function ($a, $b) {
    return strcmp($a->getName(), $b->getName());
});

$userThemes = C_Theme::GetThemesByUserId($userConnected);
$listIdThemes = [];
for ($i=0; $i < count($userThemes); $i++) {
    $listIdThemes[] = $userThemes[$i]->getId();
}

function userHasTheme($t, $userThemes) {
    for ($i=0; $i < count($userThemes); $i++) {
        if ($t->getId() === $userThemes[$i]->getId()) {
            return true;
        }
    }
    return false;
}
function addThemeRect($t, $userThemes) {
    return
    '<div class="video col-5 col-sm-4 col-md-2" onclick="addTheme(this)" id="' . $t->getId() . '" name="' . $t->getName() . '">
      <div>
        <div class="thumbnail">
          <img data-src="' . $t->getThumbnail() .'" alt="Loading..." id="' . $t->getId() . '">
        </div>
        <div class="usrAvatar">
          <div class="userAvatar">
            <img data-src="' . $t->getThumbnail() .'" alt="Loading..." id="' . $t->getId() . '">
          </div>
        </div>
        <div class="description basic">' . str_replace("\\n", "</br>", $t->getDescription()) . '</div>
        <div class="theme-checked ' . (userHasTheme($t, $userThemes) ? "" : "theme-hidden") . '">✔️</div>
      </div>
      <h4 class="title basic">' . $t->getName() .
      (strlen($t->getName()) > 18 ? '<span class="tooltiptext">' . $t->getName() . '</span>' : '') . '</h4>
    </div>' ;
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <?php require("./Views/Common/head.php") ?>
    <link rel="stylesheet" href="/src/styles/theme.css">
    <link rel="stylesheet" href="/src/styles/thumbnail.css">
    <body>
        <?php require("./Views/Common/navbar.php") ?>

        <!-- Themes ======================================================== -->
        <main class="container-fluid mb-4">
            <section class="row">
                <?php require("./Views/Common/followed.php"); ?>

                <div class="col-lg-10 col-md-11 col-sm-11 col-11">
                    <div class="container-fluid">
                        <!-- Nav ========================================== ->
                        <div class="row">
                            <div class="col-6 theme-nav-elem theme-nav-elem-active" onclick="changeTab(this)" id="allThemes">
                                <span>All themes</span>
                            </div>
                            <div class="col-6 theme-nav-elem" id="createTheme" onclick="changeTab(this)">
                                <span>Create one</span>
                            </div>
                        </div>
                        -->

                        <div class="row">
                            <!-- All Themes =================================== -->
                            <div class="col-12 theme-all-container" id="divAllThemes">
                                <div class="row">
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-11">
                                        <input class="form-control mb-4 theme-search basic" type="text" id="inputSearchTheme" value="" placeholder="Search" onkeyup="lookForTheme(this.value)">
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                                        <button type="button" class="btn background-primary basic hidden" id="btnCreate" onclick="createNewTheme()">Create</button>
                                    </div>
                                </div>


                                <!-- display all themes by alphabetical order -->
                                <?php
                                $firstLetter = null;
                                for ($i=0; $i < count($allThemes); $i++) {
                                    $t = $allThemes[$i];

                                    // si premier theme afficher lettre et ouvrir une div
                                    if ($firstLetter == null) {
                                        $firstLetter = $t->getName()[0];
                                        echo "<div name='theme-separator' id='section-" . $firstLetter . "'>
                                                <div class='theme-first-letter primary'>
                                                  <span>$firstLetter</span>
                                                </div>
                                                <hr>
                                              </div>
                                              <div class='theme-display' id='row-" . $firstLetter . "'>";
                                    }

                                    // si pas premier theme et meme lettre mettre dans la div
                                    if ($firstLetter == $t->getName()[0]) {
                                        echo addThemeRect($t, $userThemes);
                                    }

                                    // si nouvelle lettre fermer div et ouvrir une nouvelle
                                    if ($firstLetter != $t->getName()[0]) {
                                        $firstLetter = $t->getName()[0];
                                        echo "</div>
                                              <div name='theme-separator' id='section-" . $firstLetter . "'>
                                                <div class='theme-first-letter'>
                                                    <span class='primary'>$firstLetter</span>
                                                </div>
                                                <hr>
                                              </div>
                                              <div class='theme-display' id='row-" . $firstLetter . "'>";
                                        echo addThemeRect($t, $userThemes);
                                    }

                                    // si dernier theme de la liste fermer div
                                    if ($i == count($allThemes) - 1) {
                                        echo "</div>";
                                    }
                                }
                                 ?>

                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </main>

        <!-- Pop up unsaved changes ======================================== -->
        <div class="theme-save-container theme-hidden" id="divSave">
            <span class="mb-2 basic">You have unsaved changes</span>
            <div class="container-fluid">
                <button class="btn btn-success m-2" type="button" name="button" onclick="saveChanges()">Save</button>
                <button class="btn btn-danger m-2" type="button" name="button" onclick="cancelChanges()">cancel</button>
            </div>
        </div>
        <form id="formSave" action="/saveThemes" method="post" target="iframeSave">
            <input type="hidden" name="listThemes" id="listThemes" value="">
            <input type="hidden" name="userId" value="<?php echo $userConnected; ?>">
        </form>
        <iframe class="theme-hidden" id="iframeSave" name="iframeSave"></iframe>
        <form action="/themes" method="post" id="formNewTheme">
            <input type="hidden" id="nameNewTheme" name="nameNewTheme" value="">
            <input type="hidden" id="imgPath" name="imgPath" value="">
        </form>
    </body>
    <script type="text/javascript">
        var previousLetter = "";
        var previousList = <?php echo json_encode($listIdThemes); ?>;
    </script>
    <script src="src/scripts/Theme.js" charset="utf-8"></script>
</html>
