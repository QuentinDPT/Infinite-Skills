<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Infinite skills</title>
    <link rel="stylesheet" href="./src/lib/bootstrap-4.4.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./src/styles/main.css">

    <script src="./src/lib/JQuery-3.4.1/JQuery.min.js"></script>
    <script src="./src/lib/bootstrap-4.4.1-dist/js/bootstrap.bundle.min.js"></script>
    <script src="./src/lib/bootstrap-4.4.1-dist/js/bootstrap.min.js"></script>
  </head>
  <body>
    <header class="sticky-top">
      <nav class="navbar navbar-expand-sm navbar-toggleable-sm navbar-dark bg-dark border-bottom box-shadow mb-3">

        <div class="pos-f-t">
          <div class="collapse" id="navbarToggleExternalContent">
            <div class="bg-dark p-4">
              <h4 class="text-white">Collapsed content</h4>
              <span class="text-muted">Toggleable via the navbar brand.</span>
            </div>
          </div>
          <nav class="navbar navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          </nav>
        </div>

        <div class="container">
          <div class="navbar-brand" href=".">Infinite skills</div>
          <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
              <ul class="navbar-nav flex-grow-1">
                  <li class="nav-item">
                    <input type="button" class="nav-link bg-transparent" value="Acceuil" onclick="location.href = 'index.html';"/>
                  </li>
                  <li class="nav-item dropdown">
                    <input type="button" class="nav-link bg-transparent dropdown-toggle" value="Themes" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"/>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                      <a class="dropdown-item" href="#">High tech</a>
                      <a class="dropdown-item" href="#">Do it yourself</a>
                      <a class="dropdown-item" href="#">Art</a>
                      <a class="dropdown-item" href="#">Cuisine</a>
                      <div class="dropdown-divider"></div>
                    </div>
                  </li>
                  <li class="nav-item w-100">
                    <input type="text" class="nav-link w-100" placeholder="Rechercher"/>
                  </li>
                  <li>
                    <input type="button" class="dropdown-toggle nav-link bg-transparent active" value="Connexion"/>
                  </li>
              </ul>
          </div>
        </div>
      </nav>
      <div>
      </div>
    </header>
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
    <footer class="border-top footer">
      <div class="container">
        <p>Développé par moi même<p>
      </div>
    </footer>
  </body>
</html>
