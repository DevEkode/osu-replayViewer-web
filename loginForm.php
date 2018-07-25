<?php
// ******************** Variables **********************************
//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$userId = $_POST['userId'];
$userPassword = $_POST['psw'];
// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

// ******************** Functions **********************************
function close($conn){
  $conn->close();
  exit;
}

function verifyCaptcha($secretCaptcha,$cResponse){
  //POST request
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretCaptcha&response=$cResponse";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  $result=curl_exec($ch);
  curl_close($ch);

  $json = json_decode($result, true);
  return $json['success'];
}
// ******************** Core **********************************
$query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
$query->bind_param("i",$userId);
$query->execute();
$result = $query->get_result();
$query->close();

if(strcmp(intval($userId),$userId) != 0){
  header("Location:login.php?error=5");
  close($conn);
}

//check reCaptcha (avoid bot)
require_once 'secure/recaptcha.php';
if(!verifyCaptcha($secretCaptcha,$_POST['g-recaptcha-response'])){
  header("Location:login.php?error=3");
  close($conn);
}

if($result->num_rows < 1){ //Check if the account exist
  header("Location:login.php?error=2");
  close($conn);
}

//Check if the account need verification
while($row = $result->fetch_assoc()){
  $verfUserId = $row['verificationId'];
  $verfIdEmail = $row['verfIdEmail'];
  $passwordHash = $row['password'];
  $userUsername = $row['username'];
}

//Check password
if(!password_verify($userPassword,$passwordHash)){
  header("Location:login.php?error=2");
  close($conn);
}

//var_dump(empty($verfUserId) && empty($verfIdEmail));

if((empty($verfUserId) && empty($verfIdEmail)) == false){
  header("Location:login.php?error=1&userId=".$userId);
  close($conn);
}

//Everything is valid create a new session
session_start();
$_SESSION["userId"] = $userId;
$_SESSION["username"] = $userUsername;
header("Location:index.html");
 ?>
