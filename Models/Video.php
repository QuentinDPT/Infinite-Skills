<?php

class Video{
    // Fields =================================================================
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

    // Constructor ============================================================
    /* constructor: Create a Video object
     *      Input:
     *          - $id       : Video id
     *          - $ownerId  : Video owner id
     *          - $themeId  : Video theme id
     *          - $name     : Video name
     *          - $desc     : Video description
     *          - $pub      : Video publication date
     *          - $price    : Video price
     *          - $views    : Video views
     *          - $url      : Video path
     *          - $thumbnail: Video thumbnail url
     */
    public function __construct($id, $ownerId, $themeId, $name, $desc, $pub, $price, $views, $url, $thumbnail){
        $this->_id          = $id;
        $this->_ownerId     = $ownerId;
        $this->_themeId     = $themeId;
        $this->_name        = $name;
        $this->_description = $desc;
        $this->_publication = $pub;
        $this->_price       = $price;
        $this->_views       = $views;
        $this->_url         = $url;
        $this->_thumbnail   = $thumbnail;
    }

    // Methods ================================================================
    /* getId: Get a Video id
     *      Output:
     *          - Integer: Video id
     */
    public function getId(){ return $this->_id ; }
    /* getOwnerId: Get a Video ower id
     *      Output:
     *          - Integer: Video ower id
     */
    public function getOwnerId(){ return $this->_ownerId ; }
    /* getThemeId: Get a Video Theme id
     *      Output:
     *          - Integer: Video Theme id
     */
    public function getThemeId(){ return $this->_themeId ; }
    /* getName: Get a Video name
     *      Output:
     *          - String: Video name
     */
    public function getName(){ return $this->_name ; }
    /* getDescription: Get a Video description
     *      Output:
     *          - String: Video description
     */
    public function getDescription(){ return $this->_description ; }
    /* getPublication: Get a Video publication date
     *      Output:
     *          - Date: Video publication date
     */
    public function getPublication(){ return $this->_publication ; }
    /* getPrice: Get a Video price
     *      Output:
     *          - Integer: Video price
     */
    public function getPrice(){ return $this->_price ; }
    /* getViews: Get a Video views number
     *      Output:
     *          - Integer: Video views number
     */
    public function getViews(){ return $this->_views ; }
    /* getUrl: Get a Video path
     *      Output:
     *          - string: Video path
     */
    public function getUrl(){ return $this->_url ; }
    /* getThumbnail: Get a Video thumbnail url
     *      Output:
     *          - String: Video thumbnail url
     */
    public function getThumbnail(){ return $this->_thumbnail ; }
    /* getEmbedUrl: Get a youtube video id
     *      Output:
     *          - Integer: yourube video id
     */
    public function getEmbedUrl() {
        if (strpos($this->_url, "\/embed/") === false) {
            $ex = explode("watch?v=", $this->_url);
            return $ex[0] . "embed/" . $ex[1];
        }
        return $this->_url;

    }
}
