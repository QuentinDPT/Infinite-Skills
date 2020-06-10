<?php

class Comment{
    // Fields =================================================================
    private $_id;
    private $_videoId;
    private $_userId;
    private $_content;
    private $_date;

    // Constructor ============================================================
    /* constructor: Create a Comment object
     *      Input:
     *          - $id     : Comment id
     *          - $videoId: Video id
     *          - $userId : User id
     *          - $content: Content of the comment
     *          - $date   : Date of creation of the comment
     */
    public function __construct($id, $videoId, $userId, $content, $date){
        $this->_id      = $id;
        $this->_videoId = $videoId;
        $this->_userId  = $userId;
        $this->_content = $content;
        $this->_date    = $date;
    }

    // Methods ================================================================
    /* getId: Get the id of a comment
     *      Output:
     *          - Integer: Id of the comment
     */
    public function getId(){ return $this->_id ; }
    /* getVideoId: Get the id of the video attached to a comment
     *      Output:
     *          - Integer: Video id
     */
    public function getVideoId(){ return $this->_videoId ; }
    /* getUserId: Get the id of the user who created a comment
     *      Output:
     *          - Integer: User id
     */
    public function getUserId(){ return $this->_userId ; }
    /* getContent: Get the content of a comment
     *      Output:
     *          - String: Comment content
     */
    public function getContent(){ return $this->_content ; }
    /* getDate: Get the creation date of a comment
     *      Output:
     *          - Date: Date of creation of the comment
     */
    public function getDate(){ return $this->_date ; }
    /* getNumberLines: Get the number of line of a comment
     *      Output:
     *          - Integer: Number of lines
     */
    public function getNumberLines() {
        return count(explode("</br>", $this->getContent()));
    }
}
