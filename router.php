<?php

$PageTitle = "Infinte skills" ;
$NavActive = "" ;
$Connected = !($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_SESSION['user'])) ;
$Url = $_SERVER['REQUEST_URI'] ;
$UrlHashed = explode("/",$_SERVER['REQUEST_URI']) ;

switch($UrlHashed[1]){
  case "" :
    header("Location: ./home");
    break ;
  case "home" :
    require("./Views/Home.php") ;
    break ;
  case "connection" :
  case "connexion" :
    require("./Views/Connection.php") ;
    break ;
  case "testPDO" :
    require("./Models/AccessDB.php") ;
    $dbo = new AccessDB() ;
    $dbo->connect() ;
    var_dump($dbo->select("select * from user",array())) ;
    break ;
  case "testmail" :
    require("./Controllers/C_Mail.php") ;
    $mail = new Mail("quentin@depotter.fr","test","ceci est un test") ;
    $mail->send() ;
    break ;
  case "addUser" :
    header("Location: ./home");
    break ;
  case "resetpwd" :
    require("./Models/User.php") ;
    require("./Controllers/C_User.php") ;
    $usr = new User(0,"Quentin", "quentin@depotter.fr") ;
    $mail = C_User::UserResetPassword($usr);
    $mail->send() ;
    break ;
  case "api" :
    switch($UrlHashed[2]){
      case "adduser" :
        if(isset($_POST['login']) && $_POST['login'] != ""){
          mkdir("./user/".$_POST['login']) ;
        } else {
          header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
          $PageTitle .= " - Il est où ?" ;
          $ErrorMsg = "<h1>MHhh?</h1><p>qu'en pense bobby ?</p><img src='/src/img/bobby.png' alt='Grapefruit slice atop a pile of other slices'>" ;
          require("./Views/Error.php") ;
        }

        //header("Location: ./home");
        break ;
      default:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        $PageTitle .= " - Il est où ?" ;
        $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
        require("./Views/Error.php") ;
        break ;
    }
    break ;
  case (preg_match("/\/watch\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false) :
    require("./Views/Watch.php");
    //$video = C_Video::GetVideoById($_GET['video_id']);
    //C_Video::LoadVideo($video);
    //echo '<iframe width="1200" height="500" src="' . $video->getEmbedUrl() . '"></iframe>';
    break;
  case "error" :
  default :
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
    require("./Views/Error.php") ;
    break ;
}
