<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//check if the user is logged
if(!isset($_SESSION['userId'])){
  echo 'nobody is connected, please login to continue';
  exit;
}

$secretToken = bin2hex(openssl_random_pseudo_bytes(16));

//Insert into database

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:../../index.php?error=1");
	exit;
}

$query = $conn->prepare('INSERT INTO accounts_client VALUES(?,?)');
$query->bind_param("is",$_SESSION['userId'],$secretToken);
$query->execute();

//Redirect



?>
