

<?php



// Print the link
echo "<script type=\"text/javascript\">console.log(\"" . $_SERVER['HTTP_HOST'] . "\")</script>" ;
echo "<script type=\"text/javascript\">console.log(\"" . $_SERVER['REQUEST_URI'] . "\")</script>" ;

switch($_SERVER['REQUEST_URI']){
  case "/" :
  case "/home" :
    require("./Views/Home.php") ;
    break ;
  case "/connection" :
  case "/connexion" :
    require("./Views/Connection.php") ;
    break ;
  case "/error" :
  default :
    require("./Views/Error.php") ;
    break ;
}
