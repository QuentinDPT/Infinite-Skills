<?php

$PageTitle = "Infinte skills" ;
$NavActive = "" ;

// Print the link
/*
echo "<script type=\"text/javascript\">console.log(\"" . $_SERVER['HTTP_HOST'] . "\")</script>" ;
echo "<script type=\"text/javascript\">console.log(\"" . $_SERVER['REQUEST_URI'] . "\")</script>" ;
//*/

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
  case "/testmail" :
    $PageTitle .= " - Test mail" ;
    require("./Controllers/C_Mail.php") ;
    break ;
  case "/error" :
  default :
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
    require("./Views/Error.php") ;
    break ;
}
