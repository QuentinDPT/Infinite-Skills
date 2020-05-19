<?php

class Theme{
    private $_id;
    private $_name;
    private $_description;
    private $_thumbnail;

    public function __construct($id, $name, $desc, $thumbnail){
        $this->_id          = $id;
        $this->_name        = $name;
        $this->_description = $desc;
        $this->_thumbnail   = $thumbnail;
    }

    public function getId(){ return $this->_id ; }
    public function getName(){ return $this->_name ; }
    public function getDescription(){ return $this->_description ; }
    public function getThumbnail(){ return $this->_thumbnail ; }
}
