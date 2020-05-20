<?php
// Begin session
session_start();

if(!isset($_SESSION['User'])){
    header("Location: /home");
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>

      <main class="container basic">
          <section class="row">
              <div class="col-md-12">
                <h2>Change password</h2>
                <div class="container">
                    <form id="form-change-pass" action="" method="post">
                        <table class="w-100">
                          <tr>
                              <td>Former password</td>
                              <td><input type="password" name="previous" class="w-100" placeholder="****" required/></td>
                          </tr>
                          <tr>
                              <td>New password</td>
                              <td><input type="password" name="new" class="w-100" placeholder="******" required/></td>
                          </tr>
                          <tr>
                              <td>Confirm password</td>
                              <td><input type="password" name="confirm" class="w-100" placeholder="******" required/></td>
                          </tr>
                          <tr>
                              <td><input type="submit" class="btn btn-outline-secondary basic" value="Change"/></td>
                              <td></td>
                          </tr>
                        </table>
                    </form>
                </div>
              </div>
          </section>
          <hr/>
          <section class="row">
              <div class="col-md-12">
                <h2>Subscription</h2>
                <script src="https://js.stripe.com/v3/"></script>
                <div class="container">
                  <?php
                  require("./Controllers/C_Subscription.php") ;
                  $allsub = C_Subscription::GetAllSubscription() ;
                  for ($i=0; $i < count($allsub); $i++){
                  ?>
                    <form id="form-" action="" method="post">
                        <table class="w-100">
                          <tr>
                            <td><p class="w-100"/><?= $allsub[$i]->GetName() ; ?></p></td>
                            <td>
                              <form class="" action="" method="post">
                                <script
                                  src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                  data-key="pk_test_joErBT5GSf5MZ2jgPK7p0KaS00du3bmANx"
                                  data-amount="<?= $allsub[$i]->GetPrice() ; ?>"
                                  data-name="Infinite Subscription"
                                  data-description="<?= $allsub[$i]->GetName() ; ?>"
                                  data-image="/src/img/infinite-logo.jpg"
                                  data-locale="auto">
                                </script>
                              </form>
                            </td>
                          </tr>
                        </table>
                    </form>
                  <?php } ?>
                </div>
              </div>
          </section>
          <hr/>
          <section class="row">
              <div class="col-md-12">
                    <button id="btn-delete" class="btn btn-danger" type="button">Supprimer mon compte</button>
              </div>
          </section>

          <section class="row">
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
                            $("<span id='change-pass' class='badge badge-danger'>Erreur lors du changement de mot de passe</span>").insertBefore("#form-change-pass");
                            break;
                        case '1':
                            $("<span id='change-pass' class='badge badge-success'>Changement de mot de passe réussi</span>").insertBefore("#form-change-pass");
                            break;
                        case '2':
                            $("<span id='change-pass' class='badge badge-danger'>Ancien mot de passe incorrect</span>").insertBefore("#form-change-pass");
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

      $('#btn-delete').click(function(){
          let conf = confirm("Etes-vous sûr ? Cette action est irrévocable");
          if(conf){
              document.location.href = "/api/delete";
          }
      });
  </script>
</html>
