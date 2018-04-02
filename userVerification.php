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
function getUserInterests($userId){
	$page = file_get_contents('https://osu.ppy.sh/users/'.$userId);
	preg_match("/\"interests\":\".*\",\"occupation\"/", $page, $output_array);
	if(!empty($output_array)){
		$web = explode("\"", $output_array[0]);
		return $web[3];
	}else{
		return "";
	}
}

function close($conn){
  header("Location:index.php");
}

// ******************** Core **********************************
$queryInfos = $conn->prepare("SELECT * FROM accounts WHERE verificationId=?");
$queryInfos->bind_param("s",$verfId);
$queryInfos->execute();
$result = $queryInfos->get_result();
$queryInfos->close();

if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $username = $row['username'];
    $userId = $row['userId'];
    $verfIdEmail = $row['verfIdEmail'];
    $email = ['email'];
  }
}else{
  close($conn);
}

//prepare Variables
$profileUrl = "https://osu.ppy.sh/users/".$userId;

if(empty($verfIdEmail)){
  echo 'email verified';
}else{
  echo 'email verification needed';
}

 ?>
