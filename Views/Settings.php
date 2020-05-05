<?php
// Begin session
session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>

      <main class="container">
          <section class="row">
              <div class="col-md-6">
                <h2>Changer mot de passe</h2>
                <div class="container">
                    <form id="form-change-pass" action="" method="post">
                        <table class="w-100">
                          <tr>
                              <td>Ancien mot de passe</td>
                              <td><input type="password" name="previous" class="w-100" placeholder="****" required/></td>
                          </tr>
                          <tr>
                              <td>Nouveau mot de passe</td>
                              <td><input type="password" name="new" class="w-100" placeholder="******" required/></td>
                          </tr>
                          <tr>
                              <td>Confirmer mot de passe</td>
                              <td><input type="password" name="confirm" class="w-100" placeholder="******" required/></td>
                          </tr>
                          <tr>
                              <td><input type="submit" value="Modifier"/></td>
                              <td></td>
                          </tr>
                        </table>
                    </form>
                </div>
              </div>
          </section>

      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>

  <script type="text/javascript">
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
  </script>
</html>
