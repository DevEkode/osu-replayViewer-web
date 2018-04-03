<?php
// ******************** Variables **********************************
//--Connect to osu API --
require 'php/osuApiFunctions.php';
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;


// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "u611457272_osu");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

if(isset($_GET['id'])){
  $verfId = $_GET['id'];
}else{
  close($conn);
}

// ******************** Functions **********************************
function close($conn){
  header("Location:index.php");
}
// ******************** core **********************************
$queryInfos = $conn->prepare("SELECT * FROM accounts WHERE verfIdEmail=?");
$queryInfos->bind_param("s",$verfId);
$queryInfos->execute();
$result = $queryInfos->get_result();
$queryInfos->close();

if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $username = $row['username'];
    $userId = $row['userId'];
  }
}else{
  close($conn);
}

$queryInfos = $conn->prepare("UPDATE accounts SET verfIdEmail='' WHERE userId=?");
$queryInfos->bind_param("s",$userId);
$queryInfos->execute();
$queryInfos->close();

echo 'Thanks '.$username." ! Your email has been validated";
 ?>
