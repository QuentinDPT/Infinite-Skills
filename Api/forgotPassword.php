<?php

require("./Models/User.php") ;
require("./Controllers/C_User.php") ;
$usr = C_User::GetUserByLogin($_POST["login"]) ;
if($usr == null)
  $usr = C_User::GetUserByMail($_POST["login"]) ;

$mail = C_User::UserResetPassword($usr);
$mail->send() ;
