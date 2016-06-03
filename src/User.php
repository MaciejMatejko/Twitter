<?php

class User {
    
    public static function getUserByEmail(mysqli $conn, $email){
        $sql="SELECT * FROM User WHERE  email='$email'";
        $result=$conn->query($sql);
        if($result->num_rows ==1){
            return $result->fetch_assoc();
            
        }
        else{
            return false;
        }
    }
    
    
    public static function login(mysqli $conn, $email, $password){
        $sql="SELECT * FROM User WHERE email='{$email}'";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $rowUser= $result->fetch_assoc();
            if(password_verify($password, $rowUser['password']) && $rowUser['active']==1){
                return $rowUser['id'];
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
        
    }
    
    
    private $id;
    private $email;
    private $password;
    private $fullName;
    private $active;
    
    public function __construct(){
        $this->id=-1;
        $this->email="";
        $this->password="";
        $this->fullName="";
        $this->active=0;
    }
    
    public function setEmail($newEmail){
        $this->email = is_string($newEmail) ? trim($newEmail) : $this->email ;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function setPassword($password, $retypedPassword){
        if($password != $retypedPassword){
            return false;
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return true;
    }
    
    public function setFullName($FullName) {
        $this->fullName = is_string($FullName) ? trim($FullName) : $this->fullName ;
    }
    
    public function getFullName(){
        return $this->fullName;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function activate(){
        $this->active = 1;
    }
    
    public function deactivate(){
        $this->active = 0;
    }
    
    public function getActive(){
        return $this->active;
    }
    
    public function saveToDB(mysqli $conn) {
        if($this->id == -1){
            $sql="INSERT INTO User (email, password, fullName, active) VALUES ('{$this->email}', '{$this->password}', '{$this->fullName}', {$this->active})";
            if($conn->query($sql)){
                $this->id = $conn->insert_id;
                return true;
            }
            else{
                return false;
            }
        }
        else{
            $sql="UPDATE User SET 
                  email='{$this->email}',
                  fullName = '{$this->fullName}',
                  password = '{$this->password}',    
                  active = '{$this->active}'
                  WHERE id={$this->id}";
            if($conn->query($sql)) {
                return true;
            }
            else{
                return false;
            }
        }
    }
    
    
    public function loadFromDB(mysqli $conn, $id){
        $sql= "SELECT * FROM User WHERE id = $id";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $rowUser=$result->fetch_assoc();
            $this->id = $rowUser['id'];
            $this->email=$rowUser['email'];
            $this->password=$rowUser['password'];
            $this->fullName=$rowUser['fullName'];
            $this->active=$rowUser['active'];
        }
        else{
            return null;
        }
    }
    
    public function loadAllTweets(mysqli $conn) {
        $sql="SELECT * FROM Tweet WHERE user_id = {$this->id}";
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
    
    public function showUser() {
        echo $this->email. ' ' . $this->fullName;
    }
    
    public function deleteUser(mysqli $conn){
        $sql="DELETE FROM User WHERE id={$this->id}";
        if($conn->query($sql)){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function countUnreadMessages(mysqli $conn){
        $sql="SELECT COUNT(*) FROM Message WHERE recipient_id={$this->id} AND `read`=1";
        $result= $conn->query($sql);
        return intval($result->fetch_row()[0]);
    }
    
    public function loadAllSentMessages(mysqli $conn){
        $sql="SELECT * FROM Message WHERE sender_id={$this->id}";
        $sendMessages = [];
        $result = $conn->query($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $message=new Message();
                $message->id =$row['id'];
                $message->setSenderId($row['sender_id']);
                $message->setRecipientId($row['recipient_id']);
                $message->setText($row['text']);
                $message->setRead($row['read']);
                $message->setCreationDate($row['creation_date']);
                $sentMessages[]=$message;
            }
            return $sentMessages;
        }
        return false;
    }
    
    public function loadAllReceivedMessages(mysqli $conn){
        $sql="SELECT * FROM Message WHERE recipient_id={$this->id}";
        $receivedMessages = [];
        $result = $conn->query($sql);
        if($result->num_rows>0){
            while($row=$result->fetch_assoc()){
                $message=new Message();
                $message->id =$row['id'];
                $message->setSenderId($row['sender_id']);
                $message->setRecipientId($row['recipient_id']);
                $message->setText($row['text']);
                $message->setRead($row['read']);
                $message->setCreationDate($row['creation_date']);
                $receivedMessages[]=$message;
            }
            return $receivedMessages;
        }
        return false;
    }
    
}

