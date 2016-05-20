<?php

session_start();
require_once 'src/connection.php';
require_once 'src/User.php';

if(isset($_SESSION['loggedUserId'])){
    header ('Location: index.php');
}


if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=strlen(trim($_POST['email'])) ? trim($_POST['email']) : null;
    $password=strlen(trim($_POST['password']))? trim($_POST['password']) : null;

    if($email && $password){
        if($loggedUserId=User::login($conn, $email, $password)){
            $_SESSION['loggedUserId'] = $loggedUserId;
            header('Location: index.php');
        }
        else{
            echo"Incorrect email or password<br>";
        }
    }
    
    
    
}




?>







<html>
    <head>
        <meta charset='utf-8'>
    </head>
    <body>
        <form method='POST'>
            <fieldset>
                <label>
                    Email:
                    <input type='text' name='email'>
                </label>
                <br>
                <label>
                    Password:
                    <input type='password' name='password'>
                </label>
                <br>
                <input type='submit' value='Login'>
            </fieldset>
        </form>
        <a href="register.php">Registration</a>
    </body>
</html>
