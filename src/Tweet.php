<?php

class Tweet {
    
    public $id;
    private $user_id;
    private $text;
    
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
    
    public function getAuthor(mysqli $conn){
        $sql="SELECT User.email FROM User WHERE id='{$this->user_id}'";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            return $result->fetch_row()[0];
        }
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
    
    public function countComments(mysqli $conn){
        $sql="SELECT COUNT(*) FROM Comment WHERE tweet_id={$this->id}";
        $result= $conn->query($sql);
        return intval($result->fetch_row()[0]);
    }
    
    public function showTweet(mysqli $conn){
        echo "Tweet #<a href='Tweet_page.php?tweetId={$this->id}'>".$this->id. "</a> written by <a href='User_page.php?userId={$this->user_id}'>" . $this->getAuthor($conn) . "</a>: ".$this->text;
    }
    
    public function loadAllComments(mysqli $conn){
        $sql="SELECT * FROM Comment WHERE tweet_id={$this->id} ORDER BY creation_date DESC";
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
}

