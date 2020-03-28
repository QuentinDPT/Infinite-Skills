<?php
session_start();
require_once("./Controllers/C_User.php");

$_SESSION['USER_ID'] = 4; // Temporaire
$post = $_POST; // TODO: fonction pour sécuriser données

if(!isset($post['CASE'])){
  echo false;
  exit;
}

switch ($post['CASE']) {
  case "follow":
    $res = false;
    if(isset($post['ID']) || !empty($post['ID'])){
      C_User::AddFollow($_SESSION['USER_ID'], $post['ID']);
      $res = true;
    }
    break;
  case "unfollow":
    $res = false;
    if(isset($post['ID']) || !empty($post['ID'])){
      C_User::RemoveFollow($_SESSION['USER_ID'], $post['ID']);
      $res = true;
    }
    break;
  case "like":
  case "unlike":
  default:
    $res = false;
    break;
}

echo $res;
