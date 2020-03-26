
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
                <table class="w-100">
                  <tr>
                      <td>E-mail</td>
                      <td><input type="text" class="w-100" placeholder="alain-chabbat@gmail.com"/></td>
                  </tr>
                  <tr>
                      <td>Mot de passe</td>
                      <td><input type="password" class="w-100" placeholder="chuiM@rrant"/></td>
                  </tr>
                  <tr>
                      <td><input type="button" value="Se connecter"/></td>
                      <td></td>
                  </tr>
                </table>
            </div>
          </div>
          <div class="col-md-6">
            <h2>S'enregistrer</h2>
            <p>Ayez accès au contenu infinite skills de partout.</p>
            <p>Une semaine premium offerte pour l'ouverture d'un compte</p>
            <div class="container">
                <table class="w-100">
                  <tr>
                      <td>E-mail</td>
                      <td><input type="text" class="w-100" placeholder="alain-chabbat@gmail.com"/></td>
                  </tr>
                  <tr>
                      <td>Confirmation</td>
                      <td><input type="text" class="w-100" placeholder="alain-chabbat@gmail.com"/></td>
                  </tr>
                  <tr>
                      <td>Mot de passe</td>
                      <td><input type="password" class="w-100" placeholder="chuiM@rrant"/></td>
                  </tr>
                  <tr>
                      <td>Confirmation</td>
                      <td><input type="password" class="w-100" placeholder="chuiM@rrant"/></td>
                  </tr>
                  <tr>
                      <td><input type="button" value="S'inscrire"/></td>
                      <td></td>
                  </tr>
                </table>
            </div>
          </div>
        </section>
      </main>

      <?php require("./Views/Common/footer.php") ?>
  </body>
</html>
