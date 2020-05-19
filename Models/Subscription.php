<?php

class Subscription{
    private $_id;
    private $_name;
    private $_price;
    private $_offer;

    public function __construct($id, $name, $price, $offer){
        $this->_id      = $id;
        $this->_name    = $name;
        $this->_price   = $price;
        $this->_offer   = $offer;
    }

    public function getId(){ return $this->_id ; }
    public function getName(){ return $this->_name ; }
    public function getPrice(){ return $this->_price ; }
    public function getOffer(){ return $this->_offer ; }
}
