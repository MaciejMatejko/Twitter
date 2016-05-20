<?php

session_start();

if(!isset($_SESSION['loggedUserId'])){
    header('Location: login.php');
}
?>

<html>
    <head>
        <meta chareset='utf-8'>
    </head>
    <body>
        Logged user id = <?php echo $_SESSION['loggedUserId']; ?><br>
        <a href='logout.php'>Logout</a>
    </body>
</html>


