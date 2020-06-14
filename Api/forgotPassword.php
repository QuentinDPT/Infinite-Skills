<?php

require("./Models/User.php") ;
require("./Controllers/C_User.php") ;
$usr = C_User::GetUserByLogin($_POST["login"]) ;
if($usr == null)
   $usr = C_User::GetUserByMail($_POST["login"]) ;
if($usr == null)
    return "1";

$pwd = sprintf("%06d", rand() % 1000000); ;

$mail = C_User::UserResetPassword($usr, $pwd);
if ($mail->send()) return "0" ;
return "2";
