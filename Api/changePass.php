<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/Models/AccessDB.php");
require_once($_SERVER['DOCUMENT_ROOT']."/Controllers/C_User.php");

$post = $_POST;

if(!isset($_SESSION['User'],$post['previous'], $post['new'], $post['confirm'])){
  echo 0;
  exit;
}

if(empty($post['previous']) || empty($post['new']) ||empty($post['confirm'])){
  echo 0;
  exit;
}

$previous = trim($post['previous']);
$new = trim($post['new']);
$confirm = trim($post['confirm']);

if($new != $confirm){
    echo 0;
    exit;
}

$previous = sha1(md5($previous)."WALLAH");
$new = sha1(md5($new)."WALLAH");
$confirm = sha1(md5($confirm)."WALLAH");

$db = new AccessDB();
$db->connect();

// Check if previous password is correct
$res = $db->select("SELECT 1 FROM User WHERE Id = :userId and Password = :pass", ['userId' => $_SESSION['User'], 'pass' => $previous]);
if(empty($res)){
    echo 2;
    exit;
}

$db->insert("UPDATE User SET Password = :pass WHERE Id = :userId AND Password = :previous", ['userId' => $_SESSION['User'], 'pass' => $new, 'previous' =>$previous]);
echo 1;
exit;
