<?php

require_once 'src/User.php';
require_once 'src/connection.php';
session_start();

if(isset($_SESSION['loggedUserId'])){
    header ('Location: index.php');
}



if($_SERVER['REQUEST_METHOD']=== 'POST'){
    $email= strlen(trim($_POST['email'])) > 0 ? $_POST['email'] : null;
    $password= strlen(trim($_POST['password'])) > 0 ? $_POST['password'] : null;
    $retypedPassword= strlen(trim($_POST['retypedPassword'])) > 0 ? $_POST['retypedPassword'] : null;
    $fullName= strlen(trim($_POST['fullName'])) > 0 ? $_POST['fullName'] : null;
    
    $user=User::getUserByEmail($conn, $email);
    
    if($email && $password && $retypedPassword && $fullName && $password==$retypedPassword && !$user){
        $newUser= new User();
        $newUser->setEmail($email);
        $newUser->setPassword($password, $retypedPassword);
        $newUser->setFullName($fullName);
        $newUser->activate();
        if($newUser->saveToDB($conn)) {
            echo 'Registration succssfull<br>';
            if($loggedUserId=User::login($conn, $email, $password)){
                $_SESSION['loggedUserId'] = $loggedUserId;
                header("Location: index.php");
            }
        }
        else{
            echo 'Error during the registration<br>';
        }
    }
    else{
        if(!$email){
            echo 'Incorrect email<br>';
        }
        if(!$password){
            echo 'Incorrect password<br>';
        }
        if(!$retypedPassword || $password != $retypedPassword){
            echo 'Incorrect retyped password<br>';
        }
        if(!$fullName){
            echo 'Incorrect full name<br>';
        }
        if($user){
            echo'User exists<br>';
        }
    }
}


?>

<html>
    <head>
        <meta charset='utf-8'>
    </head>
    <body>
        <form method="POST">
            <fieldset>
                <label>
                    Email:
                    <input type="text" name='email' />
                </labe>
                <br>
                 <label>
                    Password:
                    <input type="password" name='password' />
                </labe>
                <br>
                <label>
                    Retype password:
                    <input type="password" name='retypedPassword' />
                </labe>
                <br>
                <label>
                    Full name:
                    <input type="text" name='fullName' />
                </labe>
                <br>
                <input type='submit' value='Register' />
            </fieldset>
        </form>
        <a href="login.php">Login page</a>
    </body>
</html>

<?php 

$conn->close();
$conn=null;