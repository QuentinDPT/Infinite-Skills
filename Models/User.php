<?php

class User{
  private $_id;
  private $_name;
  private $_email;
  private $_login;
  private $_password;
  private $_inscriptionDate;
  private $_expirationDate;
  private $_subscriptionId;
  private $_avatar;

  public function __construct($id, $name, $mail, $login, $inscr, $expir, $sub, $avatar){
    $this->_id              = $id;
    $this->_name            = $name;
    $this->_email           = $mail;
    $this->_login           = $login;
    $this->_inscriptionDate = $inscr;
    $this->_expirationDate  = $expir;
    $this->_subscriptionId  = $sub;
    $this->_avatar          = $avatar;
  }

  public function getId(){ return $this->_id ; }
  public function getName(){ return $this->_name ; }
  public function getMail(){ return $this->_email ; }
  public function getLogin(){ return $this->_login ; }
  public function getInscriptionDate(){ return $this->_inscriptionDate ; }
  public function getExpirationDate(){ return $this->_expirationDate ; }
  public function getSubscriptionId(){ return $this->_subscriptionId ; }
  public function getAvatar(){ return $this->_avatar ; }

  //function isSubscribed(){ return $this->_expirationDate > 9 ; }
}
