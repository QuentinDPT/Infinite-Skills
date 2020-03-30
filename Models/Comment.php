<?php

class Comment{
    private $_id;
    private $_videoId;
    private $_userId;
    private $_content;
    private $_date;

    public function __construct($id, $videoId, $userId, $content, $date){
        $this->_id      = $id;
        $this->_videoId = $videoId;
        $this->_userId  = $userId;
        $this->_content = $content;
        $this->_date    = $date;
    }

    public function getId(){ return $this->_id ; }
    public function getVideoId(){ return $this->_videoId ; }
    public function getUserId(){ return $this->_userId ; }
    public function getContent(){ return $this->_content ; }
    public function getDate(){ return $this->_date ; }

    public function getNumberLines() {
        return count(explode("\\n", $this->getContent()));
    }
}
