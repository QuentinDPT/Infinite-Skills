<?php
require($_SERVER['DOCUMENT_ROOT']."/Models/AccessDB.php");
require($_SERVER['DOCUMENT_ROOT']."/Controllers/C_User.php");

$post = $_POST; // TODO: post()
if(!isset($post['login'], $post['password'])){
  echo 0;
  exit;
}

if(empty($post['login']) || empty($post['password'])){
  echo 0;
  exit;
}
$login = trim($post['login']);
$pass = trim($post['password']);

$pass = sha1(md5($pass)."WALLAH");

$db = new AccessDB();
$db->connect();
$res = $db->select("SELECT Id, Password FROM User WHERE Login = :login", ['login' => $login]);
if($res){
    if($res[0]["Password"] != $pass){
        echo 0;
        exit;
    }
    $_SESSION['User'] = $res[0]['Id'];
    echo 1;
    exit;
}else{
    echo 0;
    exit;
}
