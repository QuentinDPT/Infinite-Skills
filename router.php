<?php
session_start();
$PageTitle = "Infinite skills" ;
$NavActive = "" ;
$Connected = !($_SERVER['REQUEST_METHOD'] != 'GET' || !isset($_SESSION['User'])) ;
$HeaderIncludes = "" ;
$Url = $_SERVER['REQUEST_URI'] ;
$UrlHashed = explode("/",$_SERVER['REQUEST_URI']) ;

// Get the Id of a youtube url
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

// Do different actions for each route
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
  case "endtrial":
    require_once("./Controllers/C_Subscription.php");
    C_Subscription::TrialReminded($_SESSION['User']);
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $extra = "";
    if ($_POST["redirection"] == "true") $extra = 'settings';
    else $extra = "home";
    header("Location: http://$host$uri/$extra");
    break;
  case "api" :
    switch($UrlHashed[2]){
        case "signup" :
            require("./Api/signup.php");
            break ;
        case "editaccount":
            $url = $_POST["urlNewPfp"];
            $txtUsername = $_POST["txtUsername"];
            $txtMail = $_POST["txtMail"];
            $txtPass = sha1(md5(trim($_POST["txtPass"]))."WALLAH");
            $txtNewPass = (isset($_POST['txtNewPass']) ? sha1(md5(trim($_POST["txtNewPass"]))."WALLAH") : null);
            $txtNewPassConfirm = (isset($_POST['txtNewPassConfirm']) ? sha1(md5(trim($_POST["txtNewPassConfirm"]))."WALLAH") : null);
            if ($txtNewPass != $txtNewPassConfirm) {
                echo 3;
                break;
            }
            require_once("./Controllers/C_User.php");
            echo C_User::EditAccount($_SESSION["User"], $txtUsername, $txtMail, $txtPass, $txtNewPass, $url);
            break;
        case "authenticate" :
            require("./Api/authenticate.php");
            break;
        case "checkout" :
            require("./Api/checkout.php");
            break;
        case "changePass" :
            require("./Api/changePass.php");
            break;
        case "changeMail" :
            require_once("./Controllers/C_User.php");
            $pass = sha1(md5(trim($_POST["pass"]))."WALLAH");

            // Check if previous password is correct
            $res = C_User::CheckPass($_SESSION["User"], $pass);
            if ($res == 2) echo 2;
            else {
                echo C_User::UpdateMail($_SESSION["User"], $_POST["mail"]);
            }
            break;
        case "forgotPassword" :
            echo require("./Api/forgotPassword.php");
            break;
        case "upload_file" :
            require_once("./Controllers/C_Video.php");
            $type = $_POST["typeVideo"];
            $delete = $_POST["delete"];
            $edit = $_POST["edit"];
            $url = "";
            $urlImg = ($_POST["txtUrlImg"] == "" ? "https://static.thenounproject.com/png/340719-200.png" : $_POST["txtUrlImg"]);
            if ($delete != "-1") {
                C_Video::DeleteVideo($delete);
                $url = "0";
            }
            else {
                if ($type == "file" && $edit == "-1") {
                    require_once("./Api/upload_file.php");
                    $url = UploadFile::exec();
                }
                if ($edit == "-1") {
                    $url = ($type == "file" ? $url : getIdFromUrl($_POST["txtUrl"]));
                    C_Video::InsertVideo($_SESSION['User'], $_POST['selectTheme'], $_POST['txtTitle'], $_POST["txtNewDesc"], $_POST['txtPrice'], $url, $urlImg);
                }
                else {
                    $url = C_Video::UpdateVideo($edit, $_POST['selectTheme'], $_POST['txtTitle'], $_POST["txtNewDesc"], $_POST['txtPrice'], $urlImg);
                }
            }
            if (strlen($url) > 1) echo "0";
            else echo $url;
            break;
        case "delete":
            require("./Controllers/C_User.php");
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
    if (isset($_POST["free"])) {
        C_Subscription::AddUserTrial($_SESSION["User"], $_POST["idSub"]);
    }
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
    if ($video->getPrice() > 0 && $user->getSubscriptionId() <= 1 && !C_User::UserOwnVideo($user->getId(), $video->getId()) && $video->getOwnerId() != $user->getId()) require("./Views/Pay.php");
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
    $PageTitle .= " - Where is he?" ;
    $ErrorMsg = "<div class='row mb-4'><div class='col-12 flex-c centered-h' style='text-align: center'><h1 class='basic'>404</h1><p class='basic'>Uuh boss? I'm lost..</p></div></div>" ;
    require("./Views/Error.php") ;
    break ;
}

// To add k, M or Mi to numbers like views / followers / likes
function formatNumber($num) {
    if ($num >= 1000000000) return round($num / 1000000000, 3) . "Mi";
    else if ($num >= 1000000) return round($num / 1000000, 3) . "M";
    else if ($num >= 1000) return round($num / 1000, 3) . "k";
    return $num;
}
