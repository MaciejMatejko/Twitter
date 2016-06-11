<?php

class Tweet {
    
    public static function LoadAllUserTweets(mysqli $conn, $userId){
        $sql="SELECT * FROM Tweet WHERE user_id = {$userId}";
        $userTweets=[];
        $result=$conn->query($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $tweet= new Tweet();
                $tweet->id = $row['id'];
                $tweet->setUserId($row['user_id']);
                $tweet->setText($row['text']);
                $userTweets[]=$tweet;
            }
            return $userTweets;
        }
        return false;
    }


    private $id;
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
    
    public function loadTweetFromDB(mysqli $conn, $id){
        $sql= "SELECT * FROM Tweet WHERE id = $id";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $rowUser=$result->fetch_assoc();
            $this->id = $rowUser['id'];
            $this->setUserId($rowUser['user_id']);
            $this->setText($rowUser['text']);
        }
        else{
            return false;
        }
    }
    
    public function countComments(mysqli $conn){
        $sql="SELECT COUNT(*) FROM Comment WHERE tweet_id={$this->id}";
        $result= $conn->query($sql);
        return intval($result->fetch_row()[0]);
    }
    
    public function showTweet(mysqli $conn){
        echo "Tweet #<a href='tweet_page.php?tweetId={$this->id}'>".$this->id. "</a> written by <a href='user_page.php?userId={$this->user_id}'>" . $this->getAuthor($conn) . "</a>: ".$this->text;
    }
    
}

