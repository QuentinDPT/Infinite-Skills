<?php
class Mail{
  private $_dest ;
  private $_sub ;
  private $_content ;

  public function __construct($destination, $subject, $content){
    $_dest = $destination ;
    $_sub = $subject ;
    $_content = $content ;
  }

  public function send(){
    mail($_dest,$_sub,$_content,"From: infinite.skills@quentin.depotter.fr");
  }
}
?>
