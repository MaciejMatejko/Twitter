<?php

session_start();
require_once 'src/User.php';
require_once 'src/connection.php';
require_once 'src/Tweet.php';
require_once 'src/Message.php';

if(!isset($_SESSION['loggedUserId'])){
    header("Location: index.php");
}else{
    $loggedUser = new User();
    if(!$loggedUser->loadFromDB($conn, $_SESSION['loggedUserId'])){
        echo("Error during loading user from database");
    }
}

echo("<h2>Recived messages:</h2>");
$recivedMessages=Message::LoadAllRecivedMessagesOfUser($conn, $loggedUser->getId());
if($recivedMessages===false){
    echo("<br>No messages");
}else{
    foreach($recivedMessages as $value){
        $value->showMessagePreviev($conn);
    }
}

echo("<br><h2>Send messages:</h2>");
$sentMessages=Message::LoadAllSendMessagesOfUser($conn, $loggedUser->getId());
if($sentMessages===false){
    echo("<br>No messages");
}else{
    foreach($sentMessages as $value){
        $value->showMessagePreviev($conn);
    }
}

echo("<br><a href='User_page.php?userId={$_SESSION['loggedUserId']}'>Back to your profile</a>");

$conn->close();
$conn=null;