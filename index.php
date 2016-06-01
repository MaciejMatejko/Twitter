<?php

session_start();
require_once 'src/User.php';
require_once 'src/Tweet.php';
require_once 'src/connection.php';

if(!isset($_SESSION['loggedUserId'])){
    header('Location: login.php');
}
else{
    $loggedUser = new User();
    $loggedUser->loadFromDB($conn, $_SESSION['loggedUserId']);
}


if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['newTweet']) && strlen(trim($_POST['newTweet'])) >0 && strlen(trim($_POST['newTweet'])) <=140){
    $tweetToAdd = new Tweet();
    $tweetToAdd->setUserId($loggedUser->getId());
    $tweetToAdd->setText($_POST['newTweet']);
    $tweetToAdd->create($conn);
    $tweetToAdd=null;
    unset($_POST);
}
?>

<html>
    <head>
        <meta chareset='utf-8'>
    </head>
    <body>
        <div>Logged user:
        <?php
            $loggedUser->showUser();
            ?>
        </div>
        <br>
        <div>
            Write new tweet:
            <form action="index.php" method="POST">
                <textarea name="newTweet" maxlength="140" placeholder="Tweet about it!"></textarea>
                <input type="submit" value="Tweet!">
            </form>
        </div>
        <br>
        <div>
            User Tweets:
            <ul>
                <?php
                $userTweets=$loggedUser->loadAllTweets($conn);
                if($userTweets===false){
                    echo "You haven't tweeted yet.";
                }
                else{
                    foreach($userTweets as $value){
                        echo"<li>{$value->showTweet()}</li>";
                    }
                }
                ?>
            </ul>
        </div>
        
        <a href="logout.php">Logout</a>
    </body>
</html>


