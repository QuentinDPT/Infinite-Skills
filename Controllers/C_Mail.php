<?php
class Mail{
  private $_dest ;
  private $_sub ;
  private $_content ;

  public function __construct($destination, $subject, $content){
    $this->_dest = $destination ;
    $this->_sub = $subject ;
    $this->_content = $content ;
  }

  public function send(){
    mail($this->_dest, $this->_sub, $this->_content, "From: infinite.skills@quentin.depotter.fr");
    //echo "envoie de : " . $this->_content . " à " . $this->_dest . " ( " . $this->_sub . " )" ;
  }
}

?>
