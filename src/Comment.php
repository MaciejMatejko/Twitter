<?php

class Comment {
    
    public static function LoadAllTweetComments(mysqli $conn, $tweetId){
        $sql="SELECT * FROM Comment WHERE tweet_id={$tweetId} ORDER BY creation_date DESC";
        $tweetComments = [];
        $result = $conn->query($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $comment=new Comment();
                $comment->id =$row['id'];
                $comment->setTweetId($row['tweet_id']);
                $comment->setUserId($row['user_id']);
                $comment->setText($row['text']);
                $comment->setCreationDate($row['creation_date']);
                $tweetComments[]=$comment;
            }
            return $tweetComments;
        }
        return false;
    }


    private $id;
    private $tweet_id;
    private $user_id;
    private $text;
    private $creation_date;
    
    public function __construct(){
        $this->id=-1;
        $this->tweet_id=null;
        $this->user_id=null;
        $this->text="";
        $this->creation_date=null;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setTweetId($newTweetId){
        $this->tweet_id=$newTweetId;
    }
    
    public function getTweetId(){
        return $this->tweet_id;
    }
    
    public function setUserId($newUserId){
        $this->user_id=$newUserId;
    }
    
    public function getUserId(){
        return $this->user_id;
    }
    
    public function setText($newText){
        if(strlen($newText)<=40){
            $this->text=$newText;
        }
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function setCreationDate($newCreationDate){
        $this->creation_date=$newCreationDate;
    }
    
    public function getCreationDate(){
        return $this->creation_date;
    }
    
    public function loadCommentFromDB(mysqli $conn, $id){
        $sql= "SELECT * FROM Comment WHERE id = $id";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $row=$result->fetch_assoc();
            $this->id = $row['id'];
            $this->setTweetId($row['tweet_id']);
            $this->setUserId($row['user_id']);
            $this->setText($row['text']);
            $this->setCreationDate($row['creation_date']);
        }
        else{
            return false;
        }
    }
    
    public function create(mysqli $conn){
        if($this->id===-1){
            $sql="INSERT INTO Comment (tweet_id, user_id, text, creation_date) VALUES ('{$this->tweet_id}', '{$this->user_id}', '{$this->text}', '{$this->creation_date}')";
            if($conn->query($sql)){
                $this->id=$conn->insert_id;
                return true;
            }
        }
        return false; 
    }
    
    public function update(mysqli $conn){
        if($this->id!==-1){
            $sql="UPDATE Tweet SET 
                  user_id='{$this->user_id}'
                  text = '{$this->text}'
                  WHERE id = '{$this->id}'";
            if($conn->query($sql)){
                return true;
            }
        }
        return false;
    }
    
    public function getAuthor(mysqli $conn){
        $sql="SELECT User.email FROM User WHERE id='{$this->user_id}'";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            return $result->fetch_row()[0];
        }
    }
    
    public function showComment(mysqli $conn){
        echo ($this->creation_date. " written by <a href=User_page.php?userId={$this->user_id}>" . $this->getAuthor($conn) . '</a>:<br> '.$this->text);
    }
}