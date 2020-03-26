<?php

class User{
  private $_id;
  private $_name;
  private $_email;
  private $_login;
  private $_password;
  private $_inscriptionDate;
  private $_expirationDate;
  private $_subscriptionId ;

  function __contructor($id, $name, $mail){
    $this->_id    = $id;
    $this->_name  = $name;
    $this->_email = $mail;
  }

  function getId(){ return $this->_id ; }

  function getName(){ return $this->_name ; }

  function getMail(){ return $this->_email ; }

  function getLogin(){ return $this->_login ; }

  function getInscriptionDate(){ return $this->_inscriptionDate ; }

  function getExpirationDate(){ return $this->_expirationDate ; }

  function getSubscriptionId(){ return $this->_subscriptionId ; }

  //function isSubscribed(){ return $this->_expirationDate > 9 ; }
}
