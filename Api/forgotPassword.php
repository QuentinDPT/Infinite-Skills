<?php

require("./Models/User.php") ;
require("./Controllers/C_User.php") ;
$usr = C_User::GetUserByLogin($_POST["login"]) ;
if($usr == null)
  $usr = C_User::GetUserByMail($_POST["login"]) ;

$pwd = sprintf("%06d", rand() % 1000000);

if($usr != null){
  $mail = C_User::UserResetPassword($usr, $pwd);
  $mail->send() ;
}else{
  header("Location: ../forgotPassword");
  die();
}

header("Location: ..");
die();
