<?php
// Begin session

if(!isset($_SESSION['User'])){
    header("Location: /home");
}

require_once("./Controllers/C_Subscription.php");
require_once("./Controllers/C_User.php");
$firstSub = C_Subscription::HadTrial($_SESSION["User"]);
$user = C_User::GetUserById($_SESSION["User"]);
$allsub = C_Subscription::GetAllSubscription() ;
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>
      <link rel="stylesheet" href="./src/styles/settings.css">


      <!-- Overlay delete ================================================= -->
      <div class="overlay-delete hidden" id="overlayDelete">
          <div class="container-delete">
              <img src="https://meme-generator.com/wp-content/uploads/mememe/2019/11/mememe_eda432dd0a0c39c09f0d141c7e3b8f72-1.jpg" class="img-delete">
              <div class="row">
                  <div class="col-6 centered-h mt-4 mb-4">
                      <button type="button" class="btn btn-danger" onclick="openOverlayDelete()">DELETE</button>
                  </div>
                  <div class="col-6 centered-h mt-4 mb-4">
                      <button type="button" class="btn stroked-primary primary" onclick="openOverlayDelete(true)">CANCEL</button>
                  </div>
              </div>
          </div>
      </div>
      <form id="form-del" action="/api/delete" method="post"></form>


      <main class="container basic">
          <div class="col-md-12">
              <h2 class="accent">Account Management</h2>
          </div>

          <!-- Account ==================================================== -->
          <form id="form-edit-account" action="/api/editaccount" method="post">
              <div class="row account-container mb-4" id="divAccount">
                  <!-- Button edit - - - - - - - - - - - - - - - - - - - - --->
                  <div class="container-btn-edit" id="divBtnEdit">
                        <button type="button" class="btn bg-primary-color basic mr-2 mt-2" onclick="openEditMode()">Edit</button>
                  </div>

                  <!-- Pfp - - - - - - - - - - - - - - - - - - - - - - - - --->
                  <div class="col-lg-3 col-md-3 col-sm-5 col-5">
                      <div class="pfp-container">
                          <div class="overlay-new-pfp hidden" id="overlayPfp">
                              <div class="row mt-4 mb-4">
                                  <div class="col-12 centered-h">
                                      <input type="text" class="form-control new-pfp-input" name="urlNewPfp" value="<?php echo $user->GetAvatar(); ?>" id="urlNewPfp">
                                  </div>
                              </div>
                              <div class="row mt-4">
                                  <div class="col-6 centered-h">
                                      <button type="button" class="btn btn-success btn-sm" onclick="changePfp()">Use</button>
                                  </div>
                                  <div class="col-6 centered-h">
                                      <button type="button" class="btn btn-warning btn-sm" onclick="changePfp(true)">Cancel</button>
                                  </div>
                              </div>
                          </div>
                          <img src="<?php echo $user->getAvatar(); ?>" alt="Avatar" class="rounded-circle pfp" onclick="changePfp(true)" id="pfp">

                      </div>
                  </div>

                  <!-- Data - - - - - - - - - - - - - - - - - - - - - - - - -->
                  <div class="col-lg-9 col-md-9 col-sm-7 col-7 flex-c basic">
                      <!-- Username - - - - - - - - - - - - - - - - - - - - -->
                      <span>Username</span>
                      <input type="text" id="txtUsername" name="txtUsername" value="<?php echo $user->getName() ?>" class="form-control settings-input hidden mb-2 basic" required>
                      <span class="disabled mb-2" id="spanUsername"><?php echo $user->getName() ?></span>

                      <!-- Mail - - - - - - - - - - - - - - - - - - - - - - -->
                      <span>Email</span>
                      <input type="mail" id="txtMail" name="txtMail" value="<?php echo $user->getMail() ?>" class="form-control settings-input hidden mb-2 basic" required>
                      <span class="disabled mb-2" id="spanMail"><?php echo $user->getMail() ?></span>

                      <!-- Pass - - - - - - - - - - - - - - - - - - - - - - -->
                      <div class="hidden" id="divPass">
                          <span>Password</span>
                          <input type="password" id="txtPass" name="txtPass" placeholder="*****" class="form-control settings-input mb-2 basic" required>

                          <span class="link-pass" id="spanChangePass" onclick="openDivPass()">Change Password?</span>
                      </div>

                      <!-- New pass - - - - - - - - - - - - - - - - - - - - -->
                      <div class="flex-c hidden" id="divNewPass">
                          <span>New Password</span>
                          <input type="password" name="txtNewPass" id="txtNewPass" placeholder="*****" class="form-control settings-input mb-2 basic" required disabled>

                          <span>Confirm new password</span>
                          <input type="password" name="txtNewPassConfirm" id="txtNewPassConfirm" placeholder="*****" class="form-control settings-input mb-2 basic" required disabled>
                      </div>

                      <!-- Buttons - - - - - - - - - - - - - - - - - - - - --->
                      <div class="hidden" id="divBtn">
                          <hr>
                          <div class="row mb-2">
                              <div class="col-2 centered-h">
                                  <button type="button" id="btnCancelEdit" class="btn btn-danger" onclick="openOverlayDelete(true)">Delete Account</button>
                              </div>
                              <div class="col-6"></div>
                              <div class="col-2 centered-h">
                                  <button type="button" id="btnCancelEdit" class="btn stroked-basic basic" onclick="openEditMode(true)">Cancel</button>
                              </div>
                              <div class="col-2 centered-h">
                                  <button type="submit" id="btnSaveEdit" class="btn btn-success basic">Save</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </form>
          <hr/>

          <!-- Subsciptions =============================================== -->
          <div class="col-md-12">
              <h2 class="accent">Subscriptions</h2>
          </div>
          <?php for ($i=1; $i < count($allsub); $i++) { ?>
              <form class="row mt-4 mb-4" action="/sub" method="post">
                  <input type="hidden" name="idSub" value="<?php echo $allsub[$i]->GetId(); ?>">
                  <div class="col-6">
                      <span class="h4 basic"><?= $allsub[$i]->GetName() . ": $" . $allsub[$i]->GetPrice() . (!$firstSub && $i == count($allsub) -1 ? " (Free 7 trial days)" : ""); ?></span>
                  </div>
                  <div class="col-6">
                      <?php if (!$firstSub && count($allsub) - 1 == $i) { ?>
                          <button type="submit" class="btn btn-lg bg-primary-color basic" onclick="document.getElementById('free').value = true;">Start 7 days trial!</button>
                          <input type="hidden" name="free" id="free" value="true">
                      <?php } else { ?>
                          <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="pk_test_joErBT5GSf5MZ2jgPK7p0KaS00du3bmANx"
                            data-amount="<?= $allsub[$i]->GetPrice() * 100; ?>"
                            data-name="Infinite Subscription"
                            data-description="<?= $allsub[$i]->GetName(); ?>"
                            data-image="/src/img/infinite-logo.jpg"
                            data-locale="auto">
                          </script>
                      <?php } ?>
                  </div>
              </form>
          <?php } ?>
          <hr/>

          <!-- Themes ===================================================== -->
          <section class="row mt-4 mb-4">
              <div class="col-md-12">
                  <h2 class="accent">Theme</h2>
              </div>
              <div class="theme-switch-wrapper">
                <span class="text-white">Dark Theme</span>
                <label class="theme-switch" for="checkbox">
                    <input type="checkbox" id="checkbox" />
                    <div class="slider round"></div>
                </label>
                <select class="navbar-select" id="select_bg_color">
                    <option value="blue">Blue</option>
                    <option value="orange">Orange</option>
                    <option value="green">Green</option>
                </select>
              </div>
          </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>

  <script src="./src/scripts/settings.js" charset="utf-8"></script>
  <script type="text/javascript">

      const toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
      const changeColor = document.getElementById('select_bg_color');

      toggleSwitch.addEventListener('change', switchTheme, false);
      changeColor.addEventListener('change', switchTheme, false);

      if (currentTheme) {
          document.documentElement.setAttribute('data-theme', currentTheme);

          if (currentTheme.startsWith('dark')) {
              toggleSwitch.checked = true;
          }
          changeColor.selectedIndex = getIndex(changeColor, getCurrentTheme());
      }

      function switchTheme(e) {
          if (e.target.checked || toggleSwitch.checked) {
              document.documentElement.setAttribute('data-theme', 'dark-' + getTheme());
              localStorage.setItem('theme', 'dark-' + getTheme()); //add this
          }
          else {
              document.documentElement.setAttribute('data-theme', 'light-' + getTheme());
              localStorage.setItem('theme', 'light-' + getTheme()); //add this
          }
      }
      function getTheme() {
          var val =  changeColor.selectedOptions[0].value;
          var res = "blue";
          switch (val) {
              case "blue": res = "blue"; break;
              case "green": res = "green"; break;
              default: res = "orange"; break;
          }
          return res;
      }
      function getCurrentTheme() {
          if (currentTheme.endsWith('blue')) return "blue";
          if (currentTheme.endsWith('orange')) return "orange";
          if (currentTheme.endsWith('green')) return "green";
      }


      $("#form-change-pass").on("submit", function(e){
          e.preventDefault();

          let newPass = $("input[name='new']").val();
          let confirmPass = $("input[name='confirm']").val();
          if(newPass == confirmPass){
              let data = $(this).serialize();
              $.ajax({
                 type: "POST",
                 url: "/api/changePass",
                 data: data,
                 success: function(res){
                    $("#change-pass").remove();
                    switch (res) {
                        case '0':
                            $("<span id='change-pass' class='badge badge-danger'>An error occurred</span>").insertBefore("#form-change-pass");
                            break;
                        case '1':
                            $("<span id='change-pass' class='badge badge-success'>New password successfully set!</span>").insertBefore("#form-change-pass");
                            break;
                        case '2':
                            $("<span id='change-pass' class='badge badge-danger'>Incorrect former password</span>").insertBefore("#form-change-pass");
                            break;
                        default:
                        break;
                    }
                 }
              });
          }else{
              $("#change-pass").remove();
              $("<span id='change-pass' class='badge badge-danger'>Confirmation mot de passe est incorrect</span>").insertBefore("#form-change-pass");
          }
      });

      $("#form-change-mail").on("submit", function(e){
          e.preventDefault();

          let newMail = $("input[name='mail']").val();
          let data = $(this).serialize();
          $.ajax({
             type: "POST",
             url: "/api/changeMail",
             data: data,
             success: function(res) {
                 $("#change-mail").remove();
                 switch (res) {
                     case '0':
                         $("<span id='change-mail' class='badge badge-danger'>An error occurred</span>").insertBefore("#form-change-mail");
                         break;
                     case '1':
                         $("<span id='change-mail' class='badge badge-success'>New mail successfully set!</span>").insertBefore("#form-change-mail");
                         break;
                     case '2':
                         $("<span id='change-pass' class='badge badge-danger'>Incorrect password</span>").insertBefore("#form-change-mail");
                         break;
                     default:
                     break;
                 }
             }
          });
      });

      $("#form-edit-account").on("submit", function(e){
          e.preventDefault();

          let data = $(this).serialize();
          $.ajax({
             type: "POST",
             url: "/api/editaccount",
             data: data,
             success: function(res) {
                 console.log(res);
                 $("#edited").remove();
                 switch (res) {
                     case '0':
                         $("<span id='edited' class='badge badge-danger'>An error occurred</span>").insertBefore("#form-edit-account");
                         document.getElementById("txtUsername").value = document.getElementById("spanUsername").innerText;
                         document.getElementById("txtMail").value = document.getElementById("spanMail").innerText;
                         break;
                     case '1':
                         $("<span id='edited' class='badge badge-success'>Saved!</span>").insertBefore("#form-edit-account");
                         document.getElementById("spanUsername").innerText = document.getElementById("txtUsername").value;
                         document.getElementById("spanMail").innerText = document.getElementById("txtMail").value;
                         break;
                     case '2':
                         $("<span id='edited' class='badge badge-danger'>Incorrect password</span>").insertBefore("#form-edit-account");
                         document.getElementById("txtUsername").value = document.getElementById("spanUsername").innerText;
                         document.getElementById("txtMail").value = document.getElementById("spanMail").innerText;
                         break;
                     case '3':
                         $("<span id='edited' class='badge badge-danger'>Passwords are not the same</span>").insertBefore("#form-edit-account");
                         document.getElementById("txtUsername").value = document.getElementById("spanUsername").innerText;
                         document.getElementById("txtMail").value = document.getElementById("spanMail").innerText;
                         break;
                     default:
                     break;
                 }
                 openEditMode(true);
             }
          });
      });

      $('#btn-delete').click(function(){
          let conf = confirm("Etes-vous sûr ? Cette action est irrévocable");
          if(conf){
              document.location.href = "/api/delete";
          }
      });
  </script>
</html>
