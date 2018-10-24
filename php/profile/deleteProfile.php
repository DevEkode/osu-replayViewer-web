<?php
session_start();

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
require '../../secure/mysql_pass.php';
$conn = new PDO("mysql:host=$mySQLservername;dbname=$mySQLdatabase", $mySQLusername, $mySQLpassword);

$deleteVerfId = uniqid('dVerf_');
//Check if the line already exists
 mysqli_report(MYSQLI_REPORT_ALL);
$query = $conn->prepare('SELECT * FROM verfIds WHERE userId = :user');
var_dump($query);
$query->bindParam('user',$userId,PDO::PARAM_INT);
$query->execute();
$result = $query->fetchAll();

if(empty($result)){
  //Insert new line
  $query2 = $conn->prepare('INSERT INTO verfIds(userId,deleteVerfId) VALUES (:user,:verfId)');
  $query2->bindParam('user',$userId,PDO::PARAM_INT);
  $query2->bindParam('verfId',$deleteVerfId,PDO::PARAM_INT);
  $query2->execute();
}else{
  //Update existing line
  $query2 = $conn->prepare('UPDATE verfIds SET deleteVerfId = :verfId WHERE userId=:user');
  $query2->bindParam('user',$userId,PDO::PARAM_INT);
  $query2->bindParam('verfId',$deleteVerfId,PDO::PARAM_INT);
  $query2->execute();
}
unset($query);
//Get user email
$query = $conn->prepare('SELECT email FROM accounts WHERE userId=:user');
$query->bindParam('user',$userId,PDO::PARAM_INT);
$query->execute();
$line = $query->fetch();

$email = $line['email'];

//Send verification email
require_once '../verificationFunctions.php';
sendDeleteVerification($email,$userId,$deleteVerfId);


 ?>
