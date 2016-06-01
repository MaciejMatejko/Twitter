<?php

class Tweet {
    
    public $id;
    public $user_id;
    public $text;
    
    public function __construct(){
        $this->id=-1;
        $this->user_id=null;
        $this->text="";
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setUserId($userId){
        $this->user_id=$userId;
    }
    
    public function getUserId() {
        return $this->user_id;
    }
    
    public function setText($text){
        if(strlen($text)<=140){
            $this->text=$text;
        }
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function create(mysqli $conn){
        if($this->id===-1){
            $sql="INSERT INTO Tweet (user_id, text) VALUES ('{$this->user_id}', '{$this->text}')";
            if($conn->query($sql)){
                $this->id=$conn->insert_id;
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    public function Update(mysqli $conn){
        if($this->id!==-1){
            $sql="UPDATE Tweet SET 
                  user_id='{$this->user_id}'
                  text = '{$this->text}'
                  WHERE id = '{$this->id}'";
            if($conn->query($sql)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }
    
    public function loadTweetFromDB(mysqli $conn, $id){
        $sql= "SELECT * FROM Tweet WHERE id = $id";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $rowUser=$result->fetch_assoc();
            $this->id = $rowUser['id'];
            $this->user_id=$rowUser['user_id'];
            $this->text=$rowUser['text'];
        }
        else{
            return null;
        }
    }
    
    public function showTweet(){
        echo '#'.$this->id. ' written by ' . $this->user_id . ': '.$this->text;
    }
    
    public function getAllComments(){
        
    }
}

