<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require 'verificationFunctions.php';

//-- Connect to mysql request database --

// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

function exitPage($conn)
{
  $conn->close();
  exit;
}

// CORE

$query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
$query->bind_param("i",$_GET['userId']);
$query->execute();
$result = $query->get_result();
if($result ->num_rows >0){
  while($row = $result->fetch_assoc()){
    if(empty($row['verfId']) && empty($row['verfIdEmail'])){
      header("Location:../index.php");
      exitPage();
    }else{
      sendEmail($row['email'], $row['username'], $row['verfIdEmail']);
    }
  }
}

header("Location:../userVerification.php?id=".$_GET['userId']);
exitPage($conn);
 ?>
