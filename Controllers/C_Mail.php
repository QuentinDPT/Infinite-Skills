<?php
class Mail{
    // Fields =================================================================
    private $_dest ;
    private $_sub ;
    private $_content ;

    // Constructor ============================================================
    /* constructor: Create a new Mail object
     *      Input:
     *          - $destination: mail destination
     *          - $subject    : mail subject
     *          - $content    : mail content
     */
    public function __construct($destination, $subject, $content){
        $this->_dest = $destination ;
        $this->_sub = $subject ;
        $this->_content = $content ;
    }

    // Methods ================================================================
    /* send: Send a mail */
    public function send(){
        var_dump($this->_dest) ;
        var_dump($this->_sub) ;
        var_dump($this->_content) ;
        mail($this->_dest, $this->_sub, $this->_content, "From: infinite.skills@quentin.depotter.fr");
    }
}
