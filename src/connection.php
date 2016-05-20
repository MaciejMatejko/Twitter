<?php
$servername="localhost";
$username="root";
$password="coderslab";
$baseName="Twitter";

//Inicjujemy połączenie:
$conn = new mysqli($servername, $username, $password, $baseName);


//Sprawdzamy, czy połącznie się powioło:
if($conn->connect_error){
    
    die("Polaczenie nieudane. Blad: ". $conn->connect_error);
}
else{
    $conn->set_charset("utf-8");
    echo"Polaczenie udane<br>";
}