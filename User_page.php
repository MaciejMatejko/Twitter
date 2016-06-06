<?php
session_start();
require_once 'src/User.php';
require_once 'src/connection.php';
require_once 'src/Tweet.php';
require_once 'src/Message.php';
date_default_timezone_set("Europe/Warsaw");

if(!isset($_SESSION['loggedUserId'])){
    header("Location: index.php");
}

$userId = isset($_GET['userId']) ? $_GET['userId'] : null;
if($userId) {
	$user = new User();
	$user->loadFromDB($conn, $userId);
	echo("User fullname: <strong>".$user->getFullName()."</strong><br>");
        echo("User email: <strong>".$user->getEmail()."</strong><br>");
        if(isset($_SESSION['loggedUserId']) && $userId===$_SESSION['loggedUserId']){
            echo("You have ".$user->countUnreadMessages($conn)." unread messages<br>");
            echo("Go to <a href='Messages.php'>messages</a><br>");
        }
        echo("<br>{$user->getFullName()}'s tweets:<br>");
        $userTweets=Tweet::LoadAllUserTweets($conn, $user->getId());
                if($userTweets===false){
                    echo "Not tweeted yet.";
                }
                else{
                    foreach($userTweets as $value){
                        echo"<li>";
                        $value->showTweet($conn);
                        if($value->countComments($conn)===1){
                            echo(" - 1 <a href='Tweet_page.php?tweetId={$value->getId()}'>comment</a>");
                        }
                        elseif($value->countComments($conn)>1){
                            echo(" - ".$value->countComments($conn)." <a href='Tweet_page.php?tweetId={$value->getId()}'>comments</a>");
                        }
                        else{
                            echo(" - 0 comments");
                        }
                        echo"</li>";
                    }
                }
}


if(isset($_SESSION['loggedUserId']) && $_SESSION['loggedUserId']===$_GET['userId']){
    ?>
        <br>
        <form method='POST'>
            <fieldset>
                <label>
                    Change email:
                    <input type='text' name='newEmail'>
                </label>
                <input type='submit' value='Change'>
            </fieldset>
        </form>
        <br>
        <form method='POST'>
            <fieldset>
                <label>
                    New password:
                    <input type='password' name='newPassword'>
                </label>
                <br>
                <label>
                    Retype new password:
                    <input type='password' name='newPasswordRetyped'>
                </label>
                <input type='submit' value='Change'>
            </fieldset>
        </form>
        <br>
        <form method='POST'>
            <fieldset>
                <label>
                    Change full name:
                    <input type='text' name='newFullName'>
                </label>
                <input type='submit' value='Change'>
            </fieldset>
        </form>
        <form method='POST'>
            <button type="submit" name ="delete" value="delete">Delete account</button>
        </form>
    <?php
}
if(isset($_POST['newEmail'])){
    $newEmail=$_POST['newEmail'];
    if(strlen(trim($newEmail))>0){
        $user->setEmail($newEmail);
        $user->saveToDB($conn);
        echo("Email changed successfully");
    }
    else{
        echo("Error during changing email");
    }
}
if (isset($_POST['newPassword']) && isset($_POST['newPasswordRetyped']) && strlen(trim($_POST['newPassword']))>0){
    $newPassword=$_POST['newPassword'];
    $newPasswordRetyped = $_POST['newPasswordRetyped'];
    if($user->setPassword($newPassword, $newPasswordRetyped)){
        echo("Password changed successfully");
        $user->saveToDB($conn);
    }
    else{
        echo("Error during changing password");
    }
}

if(isset($_POST['newFullName'])){
    $newFullName=$_POST['newFullName'];
    if(strlen(trim($newFullName))>0){
        $user->setFullName($newFullName);
        $user->saveToDB($conn);
        echo("Full name changed successfully");
    }
    else{
        echo("Error during changing full name");
    }
}

if(isset($_POST['delete'])){
    if(!$user->deleteUser($conn)){
        echo("Error during deleting account");
    }
    else{
        unset($_SESSION['loggedUserId']);
        header("Location: index.php");
    }
}

if(isset($_SESSION['loggedUserId']) && $_SESSION['loggedUserId']!==$_GET['userId']){
    ?>
        <br>
        <div>
            Send message:
            <form action="#" method="POST">
                <textarea name="newMessage"  placeholder="Write your message"></textarea>
                <input type="submit" value="Send!">
            </form>
        </div>
<?php
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['newMessage']) && strlen(trim($_POST['newMessage']))>0){
    $date=date('Y-m-d H:i:s');
    
    $messageToSend = new Message();
    $messageToSend->setSenderId($_SESSION['loggedUserId']);
    $messageToSend->setRecipientId($_GET['userId']);
    $messageToSend->setText($_POST['newMessage']);
    $messageToSend->setRead(1);
    $messageToSend->setCreationDate($date);
    if($messageToSend->create($conn)){
        echo ("<meta http-equiv='refresh' content='0'>");
    }
    else{
        echo("Error during sending message");
    }
}
$conn->close();
$conn=null;

?>
<br>
<a href="index.php">Main page</a>
