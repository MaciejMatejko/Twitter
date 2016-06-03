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
    $loggedUserId=$_SESSION['loggedUserId'];
}

if($_SERVER['REQUEST_METHOD']==="GET" && isset($_GET['messageId'])){
    $messageId = $_GET['messageId'];
    if(Message::userValidation($loggedUserId, $messageId, $conn)){
        $message = new Message();
        $message->loadMessageFromDB($conn, $messageId);
        $message->showMessage($conn);
        if($loggedUserId == $message->getRecipientId()){
            $message->setRead(0);
        }
        $message->Update($conn);
    }
    else{
        echo("Error during loading message1");
    }
}
else{
    echo("Error during loading message2");
}


echo("<br><a href='Messages.php'>Go back to messages</a>");

$conn->close();
$conn=null;
