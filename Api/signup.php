<?php
require($_SERVER['DOCUMENT_ROOT']."/Models/AccessDB.php");
require($_SERVER['DOCUMENT_ROOT']."/Controllers/C_User.php");

$post = $_POST; // TODO: post()
if(!isset($post['login'],$post['mail'], $post['mail_confirm'], $post['password'], $post['password_confirm'])){
  echo 0;
  exit;
}

if(empty($post['login']) || empty($post['mail']) || empty($post['mail_confirm']) || empty($post['password']) || empty($post['password_confirm'])){
  echo 0;
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

if($resLogin || !filter_var($mail, FILTER_VALIDATE_EMAIL) || $mail != $mailConfirm){
  echo 0;
  exit;
}else{
  $resMail = $db->select("SELECT 1 FROM User WHERE Mail = :mail", ['mail' => $mail]);
  if(!empty($resMail)){ // vérifie si le mail existe déjà
    echo 0;
    exit;
  }
}

if($pass != $passConfirm){
  echo 0;
  exit;
}

$pass = sha1(md5($pass)."WALLAH");

$res = C_User::CreateUser($login,$mail,$pass);
$id = ($db->select("SELECT Id FROM User WHERE mail = :mail",['mail' => $mail]))[0]['Id'];
$_SESSION['User'] = $id;
echo 1;
exit;
