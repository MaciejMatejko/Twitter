<?php

session_start();
require_once 'src/User.php';
require_once 'src/connection.php';
require_once 'src/Tweet.php';
require_once 'src/Comment.php';
date_default_timezone_set("Europe/Warsaw");

if(!isset($_SESSION['loggedUserId'])){
    header("Location: index.php");
}

$tweetId = isset($_GET['tweetId']) ? $_GET['tweetId'] : null;
if($tweetId) {
	$tweet = new Tweet();
	$tweet->loadTweetFromDB($conn, $tweetId);
	echo("<strong>Tweet #".$tweet->getId()."</strong><br>");
        echo("Written by: <strong><a href='User_page.php?userId={$tweet->getUserId()}'>".$tweet->getAuthor($conn)."</strong></a><br>");
        echo("<br>".$tweet->getText()."<br>");
        echo("<br>Comments:<br>");
        $comments=$tweet->loadAllComments($conn);
                if($comments===false){
                    echo ("No comments yet");
                }
                else{
                    echo("<ul>");
                    foreach($comments as $value){
                        echo"<li>";
                        echo($value->showComment($conn));
                        echo"</li>";
                    }
                    echo("</ul>");
                }
}else{
    header('Location: index.php');
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['newComment']) && strlen(trim($_POST['newComment']))>0 && strlen(trim($_POST['newComment']))<=60){
    $date=date('Y-m-d H:i:s');
    
    $commentToAdd= new Comment();
    $commentToAdd->setTweetId($tweetId);
    $commentToAdd->setUserId($_SESSION['loggedUserId']);
    $commentToAdd->setText($_POST['newComment']);
    $commentToAdd->setCreationDate($date);
    if($commentToAdd->create($conn)){
        echo "<meta http-equiv='refresh' content='0'>";
    }
    else{
        echo("Error during adding comment");
    }
}


if(isset($tweetId)){
    ?><div>
            Write new comment:
            <form action="#" method="POST">
                <textarea name="newComment" maxlength="60" placeholder="Share your thoughts!"></textarea>
                <input type="submit" value="Comment!">
            </form>
        </div>
<?php
}
$conn->close();
$conn=null;
?>

<br>
<a href="index.php">Main page</a>