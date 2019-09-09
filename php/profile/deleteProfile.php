<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//Filter POST
$userId = filter_var($_POST['id'],FILTER_SANITIZE_NUMBER_INT);

//Check if the user is logged
if(!isset($_SESSION['userId'])){
  //TODO error
}

//Check if the user has an account
require_once '../websiteFunctions.php';
if(!userHasAaccount($userId)){
  //TODO error
}

//Create a new verificationId
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

$deleteVerfId = uniqid('dVerf_');
//Check if the line already exists
$query = $conn->prepare('SELECT * FROM verfIds WHERE userId = ?');
$query->bindParam('i', $userId);
$query->execute();
$result = $query->fetchAll();

if(empty($result)){
  //Insert new line
  $query2 = $conn->prepare('INSERT INTO verfIds(userId,deleteVerfId) VALUES (?,?)');
  $query2->bindParam('ii', $userId, $deleteVerfId);
  $query2->execute();
}else{
  //Update existing line
  $query2 = $conn->prepare('UPDATE verfIds SET deleteVerfId = ? WHERE userId=?');
  $query2->bindParam('ii', $userId, $deleteVerfId);
  $query2->execute();
}
unset($query);
//Get user email
$query = $conn->prepare('SELECT email FROM accounts WHERE userId=?');
$query->bindParam('i', $userId);
$query->execute();
$line = $query->fetch();

$email = $line['email'];

//Send verification email
require_once '../verificationFunctions.php';
sendDeleteVerification($email,$userId,$deleteVerfId);

header("Location:/editProfile.php");
 ?>
