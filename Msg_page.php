<?php

session_start();
require_once 'src/User.php';
require_once 'src/connection.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';
require_once 'src/Message.php';

if(!isset($_SESSION['loggedUserId'])){
    header("Location: index.php");
}else{
    if(!$loggedUserId=$_SESSION['loggedUserId']){
        echo("Error during loading user from database");
    }
}

if($_SERVER['REQUEST_METHOD']==="GET" && isset($_GET['messageId'])){
    $messageId = $_GET['messageId'];
    if(Message::UserValidation($loggedUserId, $messageId, $conn)){
        $message = new Message();
        $message->loadMessageFromDB($conn, $messageId);
        $message->showMessage($conn);
        if($loggedUserId == $message->getRecipientId()){
            $message->setRead(0);
        }
        $message->update($conn);
    }
    else{
        echo("Error during loading message");
    }
}
else{
    echo("Error during loading message");
}


echo("<br><a href='Messages.php'>Go back to messages</a>");

$conn->close();
$conn=null;
