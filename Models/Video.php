<?php

class Video{
    private $_id;
    private $_ownerId;
    private $_themeId;
    private $_name;
    private $_description;
    private $_publication;
    private $_price;
    private $_views;
    private $_url;
    private $_thumbnail;

    public function __construct($id, $ownerId, $themeId, $name, $desc, $pub, $price, $views, $url, $thumbnail){
        $this->_id          = $id;
        $this->_ownerId     = $ownerId;
        $this->_themeId     = $themeId;
        $this->_name        = $name;
        $this->_description = $desc;
        $this->_publication = $pub;
        $this->_price       = $price;
        $this->_views       = $views;
        $this->_thumbnail   = $thumbnail;
    }

    public function getId(){ return $this->_id ; }
    public function getOwnerId(){ return $this->_ownerId ; }
    public function getThemeId(){ return $this->_themeId ; }
    public function getName(){ return $this->_name ; }
    public function getDescription(){ return $this->_description ; }
    public function getPublication(){ return $this->_publication ; }
    public function getPrice(){ return $this->_price ; }
    public function getViews(){ return $this->_views ; }
    public function getUrl(){ return $this->_url ; }
    public function getThumbnail(){ return $this->_thumbnail ; }
}
