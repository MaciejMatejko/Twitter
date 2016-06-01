<?php
require_once 'src/User.php';
require_once 'src/connection.php';

$userId = isset($_GET['userId']) ? $_GET['userId'] : null;
if($userId) {
	$user = new User();
	$user->loadFromDB($conn, $userId);
	var_dump($user);
}