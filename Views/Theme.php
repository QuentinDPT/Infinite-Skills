<?php
session_start();

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
          <img src="' . $t->getThumbnail() .'" alt="Loading..." id="' . $t->getId() . '">
        </div>
        <div class="usrAvatar">
          <div class="userAvatar">
            <img src="' . $t->getThumbnail() .'" alt="Loading..." id="' . $t->getId() . '">
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

                            <!-- Create Themes ================================
                            <div class="theme-create-container theme-hidden-tab" id="divCreateTheme">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label class="basic" for="txtName">Name: </label>
                                        <input class="form-control mb-4 theme-search" type="text" name="txtName" value="" onkeyup="checkSimilar(this.value)" placeholder="Type the name here">
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <div class="theme-similar-container mb-4">
                                            <div class="theme-similar-title">
                                                <span class="accent">Similar themes name</span>
                                            </div>
                                            <hr class="mt-0">
                                            <div class="theme-similar-content" id="themeSimilar"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label class="basic" for="txtDesc">Description: </label>
                                        <textarea class="form-control mb-4 theme-search" name="txtDesc" rows="8" cols="80"></textarea>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                        <label class="basic" for="icon">Icon: </label>
                                        <img class="theme-icon m-2" src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png" alt="no image" id="themeIcon">
                                        <input class="form-control mb-4 theme-search" type="file" name="icon" value="" accept="image/*" onchange="addedImage(this)">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 theme-similar-title">
                                        <button class="btn btn-success" type="button" name="button">CREATE</button>
                                    </div>
                                </div>

                            </div>
                            -->
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

        function changeTab(col) {
            var allThemes = document.getElementById("allThemes");
            var createTheme = document.getElementById("createTheme");
            var divAllThemes = document.getElementById("divAllThemes");
            var divCreateTheme = document.getElementById("divCreateTheme");
            var activ = "theme-nav-elem-active";
            var hidden = "theme-hidden-tab";

            if ( (col.id === allThemes.id && !allThemes.classList.contains(activ))
                || (col.id === createTheme.id && !createTheme.classList.contains(activ)) ) {
                    allThemes.classList.toggle(activ);
                    createTheme.classList.toggle(activ);

                    divAllThemes.classList.toggle(hidden);
                    divCreateTheme.classList.toggle(hidden);
                    divAllThemes.classList.toggle("col-12");
                    divCreateTheme.classList.toggle("col-12");
            }
        }
        function lookForTheme(val) {
            val = val.toLowerCase();

            // Hide separators if we do a reseach
            var sep = document.getElementsByName("theme-separator");
            if (val != "") {
                for (var i = 0; i < sep.length; i++) {
                    sep[i].style.display = "none";
                }
            }
            else {
                for (var i = 0; i < sep.length; i++) {
                    sep[i].style.display = "";
                }
            }

            // Hide unrelated themes for research
            var related = 0;
            var list = document.getElementsByClassName("video");
            for (var i = 0; i < list.length; i++) {
                if (list[i].getAttribute('name').toLowerCase().indexOf(val) === -1) list[i].classList.add("theme-hidden");
                else {
                    list[i].classList.remove("theme-hidden");
                    related++;
                }
            }

            // If no theme is related, suggest to create it
            var btn = document.getElementById('btnCreate');
            if (related == 0) btn.classList.remove("hidden");
            else btn.classList.add("hidden");
        }
        function addTheme(div) {
            var divCheck = div.firstElementChild.childNodes[7];
            divCheck.classList.toggle("theme-hidden");

            divSave = document.getElementById("divSave");
            if (divSave.classList.contains("theme-hidden")) {
                divSave.classList.toggle("theme-hidden");
            }
        }
        function cancelChanges() {
            document.getElementById("divSave").classList.toggle("theme-hidden");

            var listThemes = Array.from(document.getElementsByClassName("video"));
            var previousList = <?php echo json_encode($listIdThemes); ?>;

            for (var i = 0; i < listThemes.length; i++) {
                if (previousList.indexOf(listThemes[i].id) > -1) {
                    listThemes[i].firstElementChild.childNodes[7].classList.remove("theme-hidden");
                }
                else {
                    listThemes[i].firstElementChild.childNodes[7].classList.add("theme-hidden");
                }
            }
        }
        function saveChanges() {
            document.getElementById("divSave").classList.toggle("theme-hidden");

            var input = document.getElementById("listThemes");
            var listAddedThemes = Array.from(document.getElementsByClassName("video")).filter(item => {
                return !item.firstElementChild.childNodes[7].classList.contains("theme-hidden");
            });

            var list = [];
            for (var i = 0; i < listAddedThemes.length; i++) {
                list.push(listAddedThemes[i].id);
            }

            input.value = JSON.stringify(list);
            document.getElementById("formSave").submit();
        }
        function checkSimilar(val) {
            var list = document.getElementsByClassName("video");
            var divSimilar = document.getElementById('themeSimilar');
            val = val.toLowerCase();

            divSimilar.innerHTML = null;

            var str = "";
            for (var i = 0; i < list.length; i++) {
                if (list[i].getAttribute('name').toLowerCase().indexOf(val) > -1) {
                    str += list[i].getAttribute('name') + ", ";
                }
            }

            var span = document.createElement("span");
            span.className = "theme-similar-element basic";
            span.innerText = str.substr(0, str.length -2);
            divSimilar.appendChild(span);
        }
        function addedImage(input) {
            var file = input.files[0];
            var preview = document.getElementById("themeIcon");
            var reader = new FileReader();
            reader.onloadend = function () {
                preview.src = reader.result;
            }
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/1024px-No_image_available.svg.png";
            }
        }
        function submitForm(div, formId) {
            var form = document.getElementById(formId);
            var img = div.getElementsByTagName("img")[0];
            switch (formId) {
                case "formFollow":
                    document.getElementById("follow_id").value = img.id;
                    form.submit();
                    break;
            }
            document.getElementById(formId).submit();
        }

        // Theme Creation ----------------------------------------------------
        function createNewTheme() {
            // Get current input
            var name = document.getElementById('inputSearchTheme').value;

            // Clear research
            lookForTheme("");

            // Create div
            var div = document.createElement("div");
                div.className = "video col-5 col-sm-4 col-md-2 theme-new-container";
                div.setAttribute("id", "newThemeDiv");
            var img = document.createElement("img");
                img.src = "https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-image-512.png";
                img.id = "newThemeImg";
                img.className = "theme-new-img";
                img.setAttribute("onclick", "openUrl()");
            var divSearch = document.createElement("div");
                divSearch.className = "theme-search-container container hidden";
                divSearch.setAttribute("id", "divSearch");
            var divSearchRow1 = document.createElement("div");
                divSearchRow1.className = "row";
                divSearchRow1.setAttribute("style", "height: 30%");
            var url = document.createElement("input");
                url.className = "form-control theme-search-url";
                url.placeholder = "url for image";
                url.setAttribute("id", "newThemeUrl");
            var divSearchRow2 = document.createElement("div");
                divSearchRow2.className = "row";
            var divCol1 = document.createElement("div");
                divCol1.className = "col-lg-6 col-md-6 col-sm-6 col-6";
            var btnUse = document.createElement("btn");
                btnUse.className = "btn btn-sm btn-success";
                btnUse.innerText = "USE!";
                btnUse.setAttribute("onclick", "loadImage()");
            var divCol2 = document.createElement("div");
                divCol2.className = "col-lg-6 col-md-6 col-sm-6 col-6";
            var btnCancel = document.createElement("btn");
                btnCancel.className = "btn btn-sm btn-danger";
                btnCancel.innerText = "CANCEL";
                btnCancel.setAttribute("onclick", "closeUrl()");
            var input = document.createElement('input');
                input.value = name;
                input.className = "form-control theme-new-name";
                input.setAttribute("onkeyup", "nameNewThemeChanged(this)");
                input.setAttribute("id", "inputNameNewTheme");
            var btn = document.createElement("btn");
                btn.className = "btn btn-sm background-primary basic";
                btn.innerText = "Create";
                btn.setAttribute("onclick", "addCreatedTheme()");

            divCol2.appendChild(btnCancel);
            divCol1.appendChild(btnUse);
            divSearchRow2.appendChild(divCol1);
            divSearchRow2.appendChild(divCol2);
            divSearchRow1.appendChild(url);
            divSearch.appendChild(divSearchRow1);
            divSearch.appendChild(divSearchRow2);
            div.appendChild(img);
            div.appendChild(divSearch);
            div.appendChild(input);
            div.appendChild(btn);


            // Add div
            var letter = name[0].toUpperCase();
            var row = document.getElementById("row-" + letter);

            if (row == undefined) {
                var section = createSection(letter);
                addSection(section, letter);

                row = createRow(letter);
                addRow(section, row);
            }

            row.appendChild(div);

            // put focus on input
            input.focus();

            const y = div.getBoundingClientRect().top + window.scrollY;
            window.scroll({
              top: y + (y*0.3),
              behavior: 'smooth'
            });

            // reinit value so we can continue to type at the end and not the beginning due to focus
            var val = input.value;
            input.value = "";
            input.value = val;

            previousLetter = letter;
        }
        function createSection(letter) {
            var separator = document.createElement("div");
                separator.setAttribute("name", "theme-separator");
                separator.setAttribute("id", "section-" + letter);
            var sepDivLetter = document.createElement("div");
                sepDivLetter.className = "theme-first-letter primary";
            var spanFirstLetter = document.createElement("span");
                spanFirstLetter.innerText = letter;
            var hr = document.createElement("hr");

            sepDivLetter.appendChild(spanFirstLetter);
            separator.appendChild(sepDivLetter);
            separator.appendChild(hr);

            return separator;
        }
        function addSection(section, letter) {
            var list = document.getElementsByName("theme-separator");
            var index = -1;
            var parent = document.getElementById("divAllThemes");
            for (var i = 0; i < list.length; i++) {
                var secLetter = list[i].children[0].children[0].innerText;
                if (secLetter.localeCompare(letter) > 0) {
                    index = Array.from(parent.children).indexOf(list[i]);
                    break;
                }
            }
            if (index > -1) parent.insertBefore(section, parent.children[index]);
            else parent.appendChild(section);
        }
        function createRow(letter) {
            var row =  document.createElement("div");
                row.setAttribute("id", "row-" + letter);
                row.className = "theme-display";
            return row;
        }
        function addRow(section, row) {
            section.insertAdjacentElement("afterend", row);
        }
        function nameNewThemeChanged(input) {
            if (!input.value.replace(/\s/g, '').length) return;
            var letter = input.value[0].toUpperCase();

            if (previousLetter == letter) return;
            previousLetter = letter;

            var div = document.getElementById("newThemeDiv");
            var previousRow = div.parentNode;
            var clone = div.cloneNode(true);
            var row = document.getElementById("row-" + letter);

            // Create row if not exists
            if (row == undefined) {
                var section = createSection(letter);
                addSection(section, letter);

                row = createRow(letter);
                addRow(section, row);
            }
            row.appendChild(clone);

            // Delete previous row and section if nothing else in it
            var parent = previousRow.parentNode;
            if (previousRow.childElementCount < 2) {
                console.log("section-" + previousRow.getAttribute("id").split("-")[1]);
                document.getElementById("section-" + previousRow.getAttribute("id").split("-")[1]).remove();
                previousRow.remove();
            }
            else div.remove();
            var inputClone = clone.getElementsByTagName("input")[0];

            // put focus on input
            inputClone.focus();

            // reinit value so we can continue to type at the end and not the beginning due to focus
            var val = inputClone.value;
            inputClone.value = "";
            inputClone.value = val;
        }
        function addCreatedTheme() {
            var name = document.getElementById("nameNewTheme");
            var img = document.getElementById("imgPath");
            var div = document.getElementById("newThemeDiv");
            name.value = document.getElementById("inputNameNewTheme").value;
            img.value = document.getElementById("newThemeImg").src;
            document.getElementById("formNewTheme").submit();
        }
        function loadImage() {
            var path = document.getElementById("newThemeUrl").value;
            if (!path.replace(/\s/g, '').length) return;
            document.getElementById("newThemeImg").src = path;
            closeUrl();
        }
        function closeUrl() {
            document.getElementById("divSearch").classList.add("hidden");
        }
        function openUrl() {
            document.getElementById("divSearch").classList.remove("hidden");
        }
    </script>
</html>
