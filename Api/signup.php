<?php
require($_SERVER['DOCUMENT_ROOT']."/Models/AccessDB.php");
require($_SERVER['DOCUMENT_ROOT']."/Controllers/C_User.php");
// if(!isset($_POST['login']) || $_POST['login'] == ""){
//   mkdir("./user/".$_POST['login']) ;
// } else {
//   header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
//   $PageTitle .= " - Il est où ?" ;
//   $ErrorMsg = "<h1>MHhh?</h1><p>qu'en pense bobby ?</p><img src='/src/img/bobby.png' alt='Grapefruit slice atop a pile of other slices'>" ;
//   require("./Views/Error.php") ;
// }

$post = $_POST; // TODO: post()
if(!isset($post['login'],$post['mail'], $post['mail_confirm'], $post['password'], $post['password_confirm'])){
  header("Location: /connexion");
  exit;
}

if(empty($post['login']) || empty($post['mail']) || empty($post['mail_confirm']) || empty($post['password']) || empty($post['password_confirm'])){
  header("Location: /connexion");
  exit;
}
$login = trim($post['login']);
$mail = strtolower(trim($post['mail']));
$mailConfirm = strtolower(trim($post['mail_confirm']));
$pass = trim($post['password']);
$passConfirm = trim($post['password_confirm']);
$db = new AccessDB();
$db->connect();
$resLogin = $db->select("SELECT 1 FROM User WHERE Login = :login", ['login' => $login]);

if($resLogin || !preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail) || $mail != $mailConfirm){
  header("Location: /connexion");
  exit;
}else{
  $resMail = $db->select("SELECT 1 FROM User WHERE Mail = :mail", ['mail' => $mail]);
  if(!empty($resMail)){ // vérifie si le mail existe déjà
    header("Location: /connexion");
    exit;
  }
}

if($pass != $passConfirm){
  header("Location: /connexion");
  exit;
}

$pass = sha1(md5($pass)."WALLAH");

$res = C_User::CreateUser($login,$mail,$pass);
$id = ($db->select("SELECT Id FROM User WHERE mail = :mail",['mail' => $mail]))[0]['Id'];
session_start();
$_SESSION['User'] = $id;
header("Location: /home");
exit;
