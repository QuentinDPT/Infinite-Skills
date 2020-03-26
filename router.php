<?php

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
    require("./Controllers/C_Mail.php") ;
    $mail = new Mail("quentin@depotter.fr","test","ceci est un test") ;
    $mail->send() ;
    break ;
  case "/resetpwd" :
    require("./Models/User.php") ;
    require("./Controllers/C_User.php") ;

    $usr = new User(0,"Quentin", "quentin@depotter.fr") ;

    var_dump($usr) ;

    $mail = UserResetPassword($usr);
    var_dump($mail) ;
    $mail->send() ;
    break ;
  case "/error" :
  default :
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
    require("./Views/Error.php") ;
    break ;
}
