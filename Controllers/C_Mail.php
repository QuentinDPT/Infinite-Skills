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
    var_dump($this->_dest) ;
    var_dump($this->_sub) ;
    var_dump($this->_content) ;
    mail($this->_dest, $this->_sub, $this->_content, "From: infinite.skills@quentin.depotter.fr");
  }
}

?>
