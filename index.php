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
                <div class="container">
                    <div class="navbar-brand" href=".">Infinite skills</div>
                    <div class="navbar-collapse collapse d-sm-inline-flex flex-sm-row-reverse">
                        <ul class="navbar-nav flex-grow-1">
                            <li class="nav-item">
                                <input type="button" class="nav-link bg-transparent active" value="Acceuil"/>
                            </li>
                            <li class="nav-item dropdown">
                                <input type="button" class="nav-link bg-transparent dropdown-toggle" value="Themes" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"/>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                                <input type="button" class="dropdown-toggle nav-link bg-transparent" value="Connexion" onclick="location.href = 'connection.html';"/>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div>
            </div>
        </header>
        <main class="container-fluid mb-4">

        </main>
        <footer class="border-top footer">
            <div class="container">
                <p>Développé par chance<p>
                </div>
            </footer>
    </body>
</html>
