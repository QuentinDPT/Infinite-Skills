<?php

class Theme{
    // Fields =================================================================
    private $_id;
    private $_name;
    private $_description;
    private $_thumbnail;

    // Constructor ============================================================
    /* constructor: Create a Theme object
     *      Input:
     *          - $id       : Theme id
     *          - $name     : Theme name
     *          - $desc     : Theme description
     *          - $thumbnail: Theme thumbnail
     */
    public function __construct($id, $name, $desc, $thumbnail){
        $this->_id          = $id;
        $this->_name        = $name;
        $this->_description = $desc;
        $this->_thumbnail   = $thumbnail;
    }

    // Methods ================================================================
    /* getId: Get a Theme id
     *      Output:
     *          - Integer: Theme id
     */
    public function getId(){ return $this->_id ; }
    /* getName: Get a Theme Name
     *      Output:
     *          - String: Theme Name
     */
    public function getName(){ return $this->_name ; }
    /* getDescription: Get a Theme description
     *      Output:
     *          - String: Theme description
     */
    public function getDescription(){ return $this->_description ; }
    /* getThumbnail: Get a Theme thumbnail
     *      Output:
     *          - String: Theme thumbnail
     */
    public function getThumbnail(){ return $this->_thumbnail ; }
}
