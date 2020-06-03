<?php
session_start();
$PageTitle = "Infinite skills" ;
$NavActive = "" ;
$Connected = !($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_SESSION['User'])) ;
$HeaderIncludes = "" ;
$Url = $_SERVER['REQUEST_URI'] ;
$UrlHashed = explode("/",$_SERVER['REQUEST_URI']) ;

function getIdFromUrl($url) {
    $ex = explode("/", $url);
    $ex = $ex[count($ex) - 1];
    if (strpos($ex, "watch?") === false) {
        return $ex;
    }
    else {
        // Remove watch?v=
        $u = preg_replace("/watch\?v=/ig", "", $ex);
        // remove anything else after it (ex: &feature=youtu.be)
        $u = preg_replace("/&.*/ig", "", $ex);
        return $u;
    }
}

switch($UrlHashed[1]){
  case "" :
    header("Location: ./home");
    break ;
  case "home" :
    require("./Views/Home.php") ;
    break ;
  case "test" :
    require("./Views/Test.php");
    break;
  case "saveThemes" :
    $list = json_decode($_POST["listThemes"]);
    $userId = $_POST["userId"];
    require_once("./Controllers/C_Theme.php");
    C_Theme::SaveUserThemes($userId, $list);
    break;
  case "connection" :
  case "connexion" :
    require("./Views/Connection.php") ;
    break ;
  case "addUser" :
    header("Location: ./home");
    break ;
  case "api" :
    switch($UrlHashed[2]){
        case "signup" :
            require("./Api/signup.php");
            break ;
        case "authenticate" :
            require("./Api/authenticate.php");
            break;
        case "checkout" :
            require("./Api/checkout.php");
            break;
        case "changePass" :
            require("./Api/changePass.php");
            break;
        case "forgotPassword" :
            require("./Api/forgotPassword.php");
            break ;
        case "upload_file" :
            require_once("./Controllers/C_Video.php");
            $type = $_POST["typeVideo"];
            $delete = $_POST["delete"];
            $edit = $_POST["edit"];
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'users?u=' . $_SESSION["User"];

            if ($delete != "-1") {
                C_Video::DeleteVideo($delete);
                header("Location: http://$host$uri/$extra");
                break;
            }
            $url = "";
            if ($type == "file" && $edit == "-1") {
                require("./Api/upload_file.php");
                $url = $videoPath;
                // Il faudrait enregistrer sous l'Id de la video
            }
            if ($edit == "-1") {
                $url = getIdFromUrl($_POST["txtUrl"]);
                C_Video::InsertVideo($_SESSION['User'], $_POST['selectTheme'], $_POST['txtTitle'], $_POST["txtNewDesc"], $_POST['txtPrice'], $url, $_POST["txtUrlImg"]);
            }
            else {
                C_Video::UpdateVideo($edit, $_POST['selectTheme'], $_POST['txtTitle'], $_POST["txtNewDesc"], $_POST['txtPrice'], $_POST["txtUrlImg"]);
            }

            header("Location: http://$host$uri/$extra");
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
    C_User::AddFollower($ownerId, $userId);
    break;
  case "editDesc":
    require_once("./Controllers/C_User.php");
    C_User::EditDesc($_GET["ownerId"], $_GET["desc"]);
    break;
  case (preg_match("/\/rgpd\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false):
    require("./Views/RGPD.php");
    break;
  case "like":
    require_once("./Controllers/C_User.php");
    require_once("./Controllers/C_Video.php");
    $userId = $_GET['userId'];
    $videoId = $_GET['videoId'];
    C_User::AddLike($videoId, $userId);
    break;
  case "logout":
    session_start();
    unset($_SESSION['User']);
    session_destroy();
    header("Location: ./home");
    break;
  case "sub":
    require_once("./Controllers/C_Subscription.php");
    C_Subscription::UpdateSubscription($_POST["idSub"], $_SESSION["User"]);
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'home';
    header("Location: http://$host$uri/$extra");
    break;
  case "paid":
    $vid = $_SESSION["IdVideo"];
    require_once("./Controllers/C_User.php");
    C_User::AddPaidVideo($vid, $_SESSION["User"]);
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = 'watch?v=' . $vid;
    header("Location: http://$host$uri/$extra");
    break;
  case (preg_match("/\/watch\?[a-zA-Z]*/i", $_SERVER['REQUEST_URI']) ? true : false) :
    require_once("./Controllers/C_Video.php");
    require_once("./Controllers/C_User.php");
    $_GET['v'] = (isset($_GET['v']) ? $_GET['v'] : $vid);
    $video = C_Video::GetVideoById($_GET['v']);
    $user = (isset($_SESSION['User']) ? C_User::GetUserById($_SESSION['User']) : null);
    if ($video->getPrice() > 0 && $user == null) {
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'connection';
        header("Location: http://$host$uri/$extra");
        break;
    }
    if ($video->getPrice() > 0 && $user->getSubscriptionId() <= 1 && !C_User::UserOwnVideo($user->getId(), $video->getId()) && !$video->getOwnerId() == $user) require("./Views/Pay.php");
    else require("./Views/Watch.php");
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
  case (strpos($UrlHashed[1], "themes") !== false) :
    if (isset($_POST["nameNewTheme"])) {
        $name = $_POST["nameNewTheme"];
        $img = $_POST["imgPath"];
        require_once("./Controllers/C_Theme.php");
        C_Theme::SaveTheme($name, $img);
    }
    require_once("./Views/Theme.php");
    break;
  case "forgotPassword" :
    require("./Views/ForgotPassword.php");
    break ;
  case "error" :
  default :
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
    $PageTitle .= " - Il est où ?" ;
    $ErrorMsg = "<h1 class='basic'>404</h1><p class='basic'>Allo chef ? Je suis perdu..</p>" ;
    require("./Views/Error.php") ;
    break ;
}

function formatNumber($num) {
    if ($num >= 1000000000) return round($num / 1000000000, 3) . "Mi";
    else if ($num >= 1000000) return round($num / 1000000, 3) . "M";
    else if ($num >= 1000) return round($num / 1000, 3) . "k";
    return $num;
}
