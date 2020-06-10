<?php

class AccessDB {
    // Fields =================================================================
    private $_host;
    private $_name;
    private $_user;
    private $_pass;
    private $_db = null;

    // Constructor ============================================================
    /* constructor: Create an object that will be the interface between application and database
     *      Input:
     *          - $host: Host server
     *          - $name: Database name
     *          - $user: User name
     *          - $pass: User password
     */
    function __construct($host = null, $name = null, $user = null, $pass = null){
      if ($host != null) {
        $this->_host = $host;
        $this->_name = $name;
        $this->_user = $user;
        $this->_pass = $pass;
      }
      else {
        require($_SERVER['DOCUMENT_ROOT']."/DBConfig.php");
        $this->_host = $DBConfig->DBHost;
        $this->_name = $DBConfig->DBName;
        $this->_user = $DBConfig->DBPassword;
        $this->_pass = $DBConfig->DBUser;
      }
    }

    // Methods ================================================================
    /* connect: Create a link between application and database */
    function connect(){
        try{
            $this->_db = new PDO('mysql:host=' . $this->_host . ';dbname=' . $this->_name,
            $this->_user, $this->_pass);
        }
        catch (PDOException $e) {
            throw new  Exception('Erreur : Impossible de se connecter  à la BDD !');
            die();
        }
    }
    /* execReq: Execute an SQL request
     *      Input:
     *          - $request: SQL request
     *          - $data   : Array of values to bind to the request
     *      Output:
     *          - Array: Array of values returned by the request
     */
    function execReq($request, $data) {
        if (empty($request) || !is_array($data)) {
            throw new UnexpectedValueException("argument invalid");
            die();
        }

        $query = $this->_db->prepare($request);
        if ($data) {
            foreach ($data as $key => $value){
                $query->bindValue(":$key", $value);
            }
        }
        if ($query) {
            $query->execute();
            return $query->fetchAll();
        }
        else return false;
    }
    /* insert: Alias for execReq */
    function insert($request, $data){ return $this->execReq($request, $data); }
    /* update: Alias for execReq */
    function update($request, $data){ return $this->execReq($request, $data); }
    /* delete: Alias for execReq */
    function delete($request, $data){ return $this->execReq($request, $data); }
    /* select: Alias for execReq */
    function select($request, $data){ return $this->execReq($request, $data); }
}
?>
