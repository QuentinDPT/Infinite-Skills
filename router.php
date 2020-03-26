<?php

session_start();

$PageTitle = "Infinte skills" ;
$NavActive = "" ;
$Connected = !($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_SESSION['user'])) ;

switch($_SERVER['REQUEST_URI']){
  case "/" :
    header("Location: ./home");
    break ;
  case "/home" :
    $PageTitle .= " - Home" ;
    $NavActive = "Acceuil" ;
    require("./Views/Home.php") ;
    break ;
  case "/connection" :
  case "/connexion" :
    $PageTitle .= " - Connection" ;
    $NavActive = "Connection" ;
    require("./Views/Connection.php") ;
    break ;
  case "/testPDO" :
    break ;
  case "/testmail" :
<<<<<<< HEAD
    //require("./Controllers/C_Mail.php") ;
    mail("quentin@depotter.fr","test","ceci est un test","From: infinite.skills@quentin.depotter.fr");
    //$mail = new Mail("quentin@depotter.fr","test","ceci est un test") ;
    //$mail->send() ;
=======
    require("./Controllers/C_Mail.php") ;
    $mail = new Mail("quentin@depotter.fr","test","ceci est un test") ;
    $mail->send() ;
>>>>>>> dev-quentin
    break ;
  case "/error" :
  default :
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
    require("./Views/Error.php") ;
    break ;
}
