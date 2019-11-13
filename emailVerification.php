<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
// ******************** Variables **********************************
//--Connect to osu API --
require 'php/osuApiFunctions.php';


$osuApiKey = getenv('OSU_KEY');

$apiKey = $osuApiKey;


// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

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
    $usernameOsu = $row['username'];
    $userId = $row['userId'];
  }
}else{
  close($conn);
}

$queryInfos = $conn->prepare("UPDATE accounts SET verfIdEmail='' WHERE userId=?");
$queryInfos->bind_param("s",$userId);
$queryInfos->execute();
$queryInfos->close();

echo 'Thanks '.$usernameOsu." ! Your email has been validated";

header("Location:userVerification.php?id=$userId");
 ?>
