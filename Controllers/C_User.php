<?php
require("./Controllers/C_Mail.php") ;

function UserResetPassword($user){
  $dest = $user->getMail() ; ;
  $sub = "Réinitialisation de votre mot de passe" ;
  $content = "Bonjour " . $user->getName() . ",\n\nVous avez demander récemment à changer votre mot de passe.\nVoici le code qu'il va faloir rentrer pour acceder à votre compte" ;



  return new Mail($dest, $sub, $content) ;
}



 ?>
