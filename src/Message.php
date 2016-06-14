<?php

class Message{
    
    public static function UserValidation($userId, $messageId, mysqli $conn){
        $message= new Message();
        $message->loadMessageFromDB($conn, $messageId);
        if($message->getRecipientId() == $userId || $message->getSenderId() == $userId){
            return true;
        }
        else{
            return false;
        }
    }
    
    public static function LoadAllSendMessagesOfUser(mysqli $conn, $userId){
        $sql="SELECT * FROM Message WHERE sender_id={$userId}";
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
    
    public static function LoadAllRecivedMessagesOfUser(mysqli $conn, $userId){
        $sql="SELECT * FROM Message WHERE recipient_id={$userId}";
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
    
    private $id;
    private $sender_id;
    private $recipient_id;
    private $text;
    private $read;
    private $creation_date;
    
    public function __construct() {
        $this->id=-1;
        $this->sender_id=null;
        $this->recipient_id=null;
        $this->text="";
        $this->read=null;
        $this->creation_date=null;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setSenderId($newSenderId){
        $this->sender_id=$newSenderId;
    }
    
    public function getSenderId(){
        return $this->sender_id;
    }
    
    public function setRecipientId($newRecipientId){
        $this->recipient_id=$newRecipientId;
    }
    
    public function getRecipientId(){
        return $this->recipient_id;
    }
    
    public function setText($newText){
        if(strlen(trim($newText))>0){
            $this->text=$newText;
        }
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function isRead(){
        if($this->read==0){
            return true;
        }
        else{
            return false;
        }
    }
    
    public function setRead($newRead){
        $this->read=$newRead;
    }
    
    public function setCreationDate($newDate){
        $this->creation_date=$newDate;
    }
    
    public function getCreationDate(){
        return $this->creation_date;
    }
    
    public function getAuthor(mysqli $conn){
        $sql="SELECT User.email FROM User WHERE id='{$this->sender_id}'";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            return $result->fetch_row()[0];
        }
    }
    
    public function getRecipient(mysqli $conn){
        $sql="SELECT User.email FROM User WHERE id='{$this->recipient_id}'";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            return $result->fetch_row()[0];
        }
    }
    
    public function create(mysqli $conn){
        if($this->id===-1){
            $sql="INSERT INTO Message (sender_id, recipient_id, text, `read`, creation_date) VALUES ('{$this->sender_id}', '{$this->recipient_id}', '{$this->text}', '{$this->read}', '{$this->creation_date}')";
            if($conn->query($sql)){
                $this->id=$conn->insert_id;
                return true;
            }
        }
        return false;
    }
    
    public function update(mysqli $conn){
        if($this->id!==-1){
            $sql="UPDATE Message SET sender_id={$this->sender_id}, recipient_id={$this->recipient_id}, text = '{$this->text}', `read` = {$this->read}, creation_date = '{$this->creation_date}' WHERE id = {$this->id}";
            if($conn->query($sql)){
                return true;
            }
        }
        return false;
    }
    
    public function loadMessageFromDB(mysqli $conn, $id){
        $sql= "SELECT * FROM Message WHERE id = $id";
        $result=$conn->query($sql);
        if($result->num_rows==1){
            $row=$result->fetch_assoc();
            $this->id = $row['id'];
            $this->sender_id=$row['sender_id'];
            $this->recipient_id=$row['recipient_id'];
            $this->text=$row['text'];
            $this->read=$row['read'];
            $this->creation_date=$row['creation_date'];
            return true;
        }
        else{
            return false;
        }
    }
    
    
    public function showMessagePreviev(mysqli $conn){
        if($this->isRead()){
            echo("<br>{$this->creation_date} - Send by <a href='user_page.php?userId={$this->sender_id}'>{$this->getAuthor($conn)}</a> to <a href='user_page.php?userId={$this->recipient_id}'>{$this->getRecipient($conn)}</a><br>");
            echo(substr($this->getText(), 0, 30)."(...)<br><a href='msg_page.php?messageId={$this->getId()}'>See more</a><br>");
        }
        else{
            echo("<br><strong>UNREAD</strong> {$this->creation_date} - Send by <a href='user_page.php?userId={$this->sender_id}'>{$this->getAuthor($conn)}</a> to <a href='user_page.php?userId={$this->recipient_id}'>{$this->getRecipient($conn)}</a><br>");
            echo(substr($this->getText(), 0, 30)."(...)<br><a href='msg_page.php?messageId={$this->getId()}'>See more</a><br>");
        }
    }
    
    public function showMessage(mysqli $conn){
        echo("<br>{$this->creation_date} - Send by <a href='user_page.php?userId={$this->sender_id}'>{$this->getAuthor($conn)}</a> to <a href='user_page.php?userId={$this->recipient_id}'>{$this->getRecipient($conn)}</a><br>");
        echo($this->getText()."<br>");
    }
    
}