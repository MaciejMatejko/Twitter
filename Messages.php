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
    $loggedUser->loadFromDB($conn, $_SESSION['loggedUserId']);
}

echo("<h2>Recived messages:</h2>");
$recivedMessages=$loggedUser->loadAllReceivedMessages($conn);
if($recivedMessages===false){
    echo("<br>No messages");
}else{
    foreach($recivedMessages as $value){
        $value->showMessagePreviev($conn);
    }
}

echo("<br><h2>Send messages:</h2>");
$sentMessages=$loggedUser->loadAllSentMessages($conn);
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