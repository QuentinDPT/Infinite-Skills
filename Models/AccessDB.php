<?php
  // Déclaration d'une nouvelle classe
class AccessDB {
    private $_host  = 'hi5bu.myd.infomaniak.com';  // nom de l'host
    private $_name  = 'hi5bu_infinite_skills';    // nom de la base de donnée
    private $_user  = 'hi5bu_infsk';       // utilisateur
    private $_pass  = 'tacosdumardi';
    private $_db = null;

    function __construct($host = null, $name = null, $user = null, $pass = null){
      if($host != null){
        $this->_host = $host;
        $this->_name = $name;
        $this->_user = $user;
        $this->_pass = $pass;
      }
    }

    function connect(){
      try{
        $this->_db = new PDO('mysql:host=' . $this->_host . ';dbname=' . $this->_name,
          $this->_user, $this->_pass);
      }catch (PDOException $e){
        throw new  Exception('Erreur : Impossible de se connecter  à la BDD !');
        die();
      }
    }

    function insert($request, $data){
      if(empty($request) || !is_array($data)){
          throw new UnexpectedValueException("argument invalid");
          die();
        }
        $query = $_db->prepare($request);
        foreach($data as $key => $value){
          $query->bindValue(":$key", $value);
        }
        $query->execute();
        return $query->fetchAll();
    }


    function update($request, $data){
      if(empty($request) || !is_array($data)){
        throw new UnexpectedValueException("argument invalid");
        die();
      }
      $query = $_db->prepare($request);
      foreach($data as $key => $value){
        $query->bindValue(":$key", $value);
      }
      $query->execute();
      return $query->fetchAll();
    }

    function delete($request, $data){
      if(empty($request) || !is_array($data)){
        throw new UnexpectedValueException("argument invalid");
        die();
      }
      $query = $_db->prepare($request);
      foreach($data as $key => $value){
        $query->bindValue(":$key", $value);
      }
      $query->execute();
      return $query->fetchAll();
    }

    function select($request, $data){
      if(empty($request) || !is_array($data)){
        throw new UnexpectedValueException("argument invalid");
        die();
      }

      $query = $this->_db->prepare($request);
      if($data){
        foreach($data as $key => $value){
          $query->bindValue(":$key", $value);
        }
      }
      if($query){
        $query->execute(array());
        return $query->fetchAll();
      }else{
        return "CLAMERDE";
      }

    }
}


  $db = new AccessDB();
  $db->connect();
  $db_res = $db->select("SELECT * FROM Subscription", array());
  print_r($db_res);
?>
