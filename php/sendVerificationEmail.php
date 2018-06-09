<?php
require 'verificationFunctions.php';

//-- Connect to mysql request database --
require '../secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

function exitPage(){
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
      $return = sendEmail($row['email'],$row['username'],$row['verfIdEmail']);
	  var_dump($return);
    }
  }
}

header("Location:../userVerification.php?id=".$_GET['userId']);
exitPage();
 ?>
