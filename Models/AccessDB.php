<?php
  // Déclaration d'une nouvelle classe
class AccessDB {
    private $_host;     // nom de l'host
    private $_name;     // nom de la base de donnée
    private $_user;     // utilisateur
    private $_pass;
    private $_db = null;

    function __construct($host = null, $name = null, $user = null, $pass = null){
      if($host != null){
        $this->_host = $host;
        $this->_name = $name;
        $this->_user = $user;
        $this->_pass = $pass;
      }else{
        require("./DBConfig.php");
        $this->_host = $DBConfig->DBHost;
        $this->_name = $DBConfig->DBName;
        $this->_user = $DBConfig->DBPassword;
        $this->_pass = $DBConfig->DBUser;
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

      $query = $this->_db->prepare($request);
      if($data){
        foreach($data as $key => $value){
          $query->bindValue(":$key", $value);
        }
      }
      if($query){
        $query->execute();
        return $query->fetchAll();
      }else{
        return false;
      }
    }


    function update($request, $data){
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
        $query->execute();
        return $query->fetchAll();
      }else{
        return false;
      }
    }

    function delete($request, $data){
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
        $query->execute();
        return $query->fetchAll();
      }else{
        return false;
      }
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
        $query->execute();
        return $query->fetchAll();
      }else{
        return false;
      }
    }
}
  // Pour se connecter a la bdd
  // $db = new AccessDB();
  // $db->connect();

?>
