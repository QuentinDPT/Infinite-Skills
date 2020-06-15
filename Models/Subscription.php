<?php

class Subscription{
    // Fields =================================================================
    private $_id;
    private $_name;
    private $_price;
    private $_offer;

    // Constructor ============================================================
    /* constructor: Create a Subscription object
     *      Input:
     *          - $id   : Subscription id
     *          - $name : Subscription name
     *          - $price: Subscription price
     *          - $offer: Subscription offer
     */
    public function __construct($id, $name, $price, $offer){
        $this->_id      = $id;
        $this->_name    = $name;
        $this->_price   = $price;
        $this->_offer   = $offer;
    }

    // Methods ================================================================
    /* getId: Get a subscription id
     *      Output:
     *          - Integer: Subscription id
     */
    public function getId(){ return $this->_id ; }
    /* getName: Get a subscription name
     *      Output:
     *          - String: Subscription name
     */
    public function getName(){ return $this->_name ; }
    /* getPrice: Get a subscription price
     *      Output:
     *          - Integer: Subscription price
     */
    public function getPrice(){ return $this->_price ; }
    /* getOffer: Get a subscription offer
     *      Output:
     *          - Integer: Subscription offer
     */
    public function getOffer(){ return $this->_offer ; }
}
