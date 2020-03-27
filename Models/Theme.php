<?php

class Theme{
    private $_id;
    private $_name;
    private $_description;

    public function __construct($id, $name, $desc){
        $this->_id          = $id;
        $this->_name        = $name;
        $this->_description = $desc;
    }

    public function getId(){ return $this->_id ; }
    public function getName(){ return $this->_name ; }
    public function getDescription(){ return $this->_description ; }
}
