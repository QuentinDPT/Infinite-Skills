<?php session_start() ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <?php require("./Views/Common/head.php") ?>
  <body>
      <?php require("./Views/Common/navbar.php") ?>
      <main class="container">
        <section class="row">
          <div class="col-md-6">
            <h2>Se connecter</h2>
            <p>Vous souhaitez avoir accès à tous vos achats ? Connectez vous !</p>
            <div class="container">
                <form action="/api/authenticate" method="post">
                    <table class="w-100">
                      <tr>
                          <td>identifiant</td>
                          <td><input type="text" name="login" class="w-100" placeholder="Xx_d4rkKill3r_xX"/></td>
                      </tr>
                      <tr>
                          <td>Mot de passe</td>
                          <td><input type="password" name="password" class="w-100" placeholder="******"/></td>
                      </tr>
                      <tr>
                          <td><input type="submit" value="Se connecter"/></td>
                          <td></td>
                      </tr>
                    </table>
                </form>
            </div>
          </div>
          <div class="col-md-6">
            <h2>S'enregistrer</h2>
            <p>Ayez accès au contenu infinite skills de partout.</p>
            <p>Une semaine premium offerte pour l'ouverture d'un compte</p>
            <div class="container">
              <form action="/api/signup" method="post">
                <table class="w-100">
                  <tr>
                      <td><label for="register_pseudo">Identifiant</label></td>
                      <td><input id="register_pseudo" name="login" type="text" class="w-100" placeholder="XxDarkKiller_67xX"/></td>
                  </tr>
                  <tr>
                      <td><label for="register_mail">E-mail</label></td>
                      <td><input id="register_mail" name="mail" type="text" class="w-100" placeholder="alain-chabbat@gmail.com"/></td>
                  </tr>
                  <tr>
                      <td><label for="register_mail_confirm">Confirmation E-mail</label></td>
                      <td><input id="register_mail_confirm" name="mail_confirm" type="text" class="w-100" placeholder="alain-chabbat@gmail.com"/></td>
                  </tr>
                  <tr>
                      <td><label for="register_password">Mot de passe</label></td>
                      <td><input id="register_password" name="password" type="password" class="w-100" placeholder="password"/></td>
                  </tr>
                  <tr>
                      <td><label for="register_password_confirm">Confirmation Mot de passe</label></td>
                      <td><input id="register_password_confirm" name="password_confirm" type="password" class="w-100" placeholder="password"/></td>
                  </tr>
                  <tr>
                      <td><input type="submit" value="S'inscrire"></td>
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
</html>
