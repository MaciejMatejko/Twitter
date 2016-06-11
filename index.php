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
    if(!$loggedUser->loadFromDB($conn, $_SESSION['loggedUserId'])){
        echo("Error during loading user from database");
    }
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['newTweet']) && strlen(trim($_POST['newTweet'])) >0 && strlen(trim($_POST['newTweet'])) <=140){
    $tweetToAdd = new Tweet();
    $tweetToAdd->setUserId($loggedUser->getId());
    $tweetToAdd->setText($_POST['newTweet']);
    if($tweetToAdd->create($conn)){
        echo ("<meta http-equiv='refresh' content='0'>");
    }
    else{
        echo("Error during adding tweet");
    }
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
            <br>
            <a href="user_page.php?userId=<?php echo($loggedUser->getId()); ?>">Show your profile</a>
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
                $userTweets=Tweet::LoadAllUserTweets($conn, $loggedUser->getId());
                if($userTweets===false){
                    echo "You haven't tweeted yet.";
                }
                else{
                    foreach($userTweets as $value){
                        echo"<li>";
                        $value->showTweet($conn);
                        if($value->countComments($conn)===1){
                            echo(" - 1 <a href='tweet_page.php?tweetId={$value->getId()}'>comment</a>");
                        }
                        elseif($value->countComments($conn)>1){
                            echo(" - ".$value->countComments($conn)." <a href='tweet_page.php?tweetId={$value->getId()}'>comments</a>");
                        }
                        else{
                            echo(" - 0 comments");
                        }
                        echo"</li>";
                    }
                }
                
                ?>
            </ul>
        </div>
        
        
        <a href="logout.php">Logout</a>
    </body>
</html>

<?php
$conn->close();
$conn=null;

