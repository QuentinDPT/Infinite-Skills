<?php

$PageTitle = "Infinte skills" ;
$NavActive = "" ;
$Connected = !($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_SESSION['user'])) ;
$HeaderIncludes = "" ;
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
        case "signup" :
            require("./Api/signup.php");
            break ;
        case "authenticate" :
            require("./Api/authenticate.php");
            break;
        case "changePass" :
            require("./Api/changePass.php");
            break;
        case "delete":
            require("./Controllers/C_User.php");
            session_start();
            if(isset($_SESSION['User'])){
                C_User::DeleteAccount($_SESSION['User']);
            }
            unset($_SESSION['User']);
            session_destroy();
            header("Location: /home");
            break;
        default:
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        $PageTitle .= " - Il est où ?" ;
        $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
        require("./Views/Error.php") ;
        break ;
    }
    break ;
  case "follow":
    require_once("./Controllers/C_User.php");
    $userId = $_GET['userId'];
    $ownerId = $_GET['ownerId'];
    $doReq = $_GET['doReq'];
    if ($doReq === '1') C_User::AddFollower($ownerId, $userId);
    $count = C_User::GetCountFollowers($ownerId);
    echo '<span style="color: #666; font-size: smaller">' . formatNumber($count) . ($count > 1 ? " followers" : " follower") . '</span>';
    break;
  case (preg_match("/\/rgpd\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false):
    require("./Views/RGPD.php");
    break;
  case "like":
    require_once("./Controllers/C_User.php");
    require_once("./Controllers/C_Video.php");
    $userId = $_GET['userId'];
    $videoId = $_GET['videoId'];
    $doReq = $_GET['doReq'];
    if ($doReq === '1') C_User::AddLike($videoId, $userId);
    echo '<html style="overflow: hidden">
            <body>
                <div style="text-align: right"><span style="color: #666; text-align: right;word-wrap: break-word;">' . formatNumber(C_Video::GetLikes($videoId)) . '</span></div>
            </body>
          </html>';
  break;
  case "logout":
    session_start();
    unset($_SESSION['User']);
    session_destroy();
    header("Location: ./home");
    break;
  case (preg_match("/\/watch\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false) :
    require("./Views/Watch.php");
    //$video = C_Video::GetVideoById($_GET['video_id']);
    //C_Video::LoadVideo($video);
    //echo '<iframe width="1200" height="500" src="' . $video->getEmbedUrl() . '"></iframe>';
    break;
  case (preg_match("/\/new-comment\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false):
    $content = $_GET['content'];
    $videoId = $_GET['videoId'];
    $userId = $_GET['userId'];
    require_once("./Controllers/C_User.php");
    try {
        C_User::AddComment($userId, $videoId, $content);
        $domComment = C_User::CreateNewCommentDom($userId, $videoId);
        echo $domComment;
    } catch (Exception $e) {
        echo 0;
    }
    break;
  case (preg_match("/\/users\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false):
    require("./Views/User.php");
    break;
  case (preg_match("/\/search\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false):
    require("./Views/Home.php");
    break;
  case "settings":
    require("./Views/Settings.php");
    break;
  case "error" :
  default :
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1>404</h1>Allo chef ? Je suis perdu.." ;
    require("./Views/Error.php") ;
    break ;
}

function formatNumber($num) {
    if ($num >= 1000000000) return round($num / 1000000000, 3) . "Mi";
    else if ($num >= 1000000) return round($num / 1000000, 3) . "M";
    else if ($num >= 1000) return round($num / 1000, 3) . "k";
    return $num;
}
