<?php

class User{
    // Fields =================================================================
    private $_id;
    private $_name;
    private $_email;
    private $_login;
    private $_password;
    private $_inscriptionDate;
    private $_expirationDate;
    private $_subscriptionId;
    private $_avatar;
    private $_description;

    // Constructor ============================================================
    /* constructor: Create a User object
     *      Input:
     *          - $id         : User id
     *          - $name       : User name
     *          - $mail       : User mail
     *          - $login      : User login
     *          - $inscr      : User inscription date
     *          - $expir      : User end of subscription date
     *          - $sub        : User subscription id
     *          - $avatar     : User avatar
     *          - $description: User description
     */
    public function __construct($id, $name, $mail, $login, $inscr, $expir, $sub, $avatar, $description){
        $this->_id              = $id;
        $this->_name            = $name;
        $this->_email           = $mail;
        $this->_login           = $login;
        $this->_inscriptionDate = $inscr;
        $this->_expirationDate  = $expir;
        $this->_subscriptionId  = $sub;
        $this->_avatar          = $avatar;
        $this->_description     = $description;
    }

    // Methods ================================================================
    /* getId: Get a User id
     *      Output:
     *          - Integer: User id
     */
    public function getId(){ return $this->_id ; }
    /* getName: Get a User name
     *      Output:
     *          - String: User name
     */
    public function getName(){ return $this->_name ; }
    /* getMail: Get a User mail
     *      Output:
     *          - String: User mail
     */
    public function getMail(){ return $this->_email ; }
    /* getLogin: Get a User login
     *      Output:
     *          - String: User login
     */
    public function getLogin(){ return $this->_login ; }
    /* getInscriptionDate: Get a User inscription date
     *      Output:
     *          - Date: User inscription date
     */
    public function getInscriptionDate(){ return $this->_inscriptionDate ; }
    /* getExpirationDate: Get a User subscription expiration date
     *      Output:
     *          - Date: User subscription expiration date
     */
    public function getExpirationDate(){ return $this->_expirationDate ; }
    /* getSubscriptionId: Get a User subscription id
     *      Output:
     *          - Integer: User subscription id
     */
    public function getSubscriptionId(){ return $this->_subscriptionId ; }
    /* getAvatar: Get a User avatar
     *      Output:
     *          - String: User avatar
     */
    public function getAvatar(){ return $this->_avatar ; }
    /* getDescription: Get a User description
     *      Output:
     *          - String: User description
     */
    public function getDescription() { return $this->_description; }
}
