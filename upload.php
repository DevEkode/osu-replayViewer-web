<?php
// ******************** Variables **********************************
//--Connect to osu API --
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
$servername = "localhost";
$username = "root";
require_once 'secure/mysql_pass.php';
$password = $mySQLpassword;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "osureplay");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//create folder upload if does not exist
if (!file_exists('./uploads')) {
    mkdir('./uploads', 0777, true);
}
// ******************** Fonctions **********************************
function getPlayerName($fileName){ //Return player name of the replay
	$pName = explode(" ",$fileName);
	return $pName[0];
}

function getBeatmapMD5($fileName){
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));
	fclose($myfile);
	$md5 = substr($replay_content,7,32);
	return $md5;
}

function generateBtFileName($beatmapId,$api){
	//Setid Artist - Title
	$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&b=$beatmapId");
	$json = json_decode($apiRequest, true);
	$beatmapSetId = $json[0]["beatmapset_id"];
	$artist = $json[0]["artist"];
	$title = $json[0]["title"];
	$BFN = $beatmapSetId." ".$artist." - ".$title.".osz";
	
	return $BFN;
	return $json;
}

function getBeatmapJSON($md5,$api){
	$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&h=$md5");
	$json = json_decode($apiRequest, true);
	return $json;
}

function replayExist($replayName, $table, $conn){
	$result = $conn->query("SELECT * FROM $table WHERE OFN='$replayName'");
	echo "SELECT * FROM '$table' WHERE OFN='$replayName'";
	
	if($result->num_rows > 0){
		return true;
	}
	else{
		return false;
	}
}

function closeUpload($conn){
	$conn->close();
	exit;
}

function getReplayId($OFN,$conn){
	$result = $conn->query("SELECT * FROM replaylist WHERE OFN='$OFN'");
	$id = "";
	
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["replayId"];
		}
	}
	return $id;
}
// ******************** CORE **********************************

//-- Upload check --
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual replay osu file or fake
/*if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //osr
        $uploadOk = 1;
    } else {
        //not a osr
        $uploadOk = 0;
    }
}*/

// Allow certain file formats
if($imageFileType != "osr") {
    echo "Sorry, only osu replay files are allowed.";
    $uploadOk = 0;
	header("Location:index.php?error=1");
	closeUpload($conn);
}

//Check if request already exists
$result = $conn->query('SELECT OFN FROM requestlist');

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row["OFN"] == $file_name){
			echo 'file already requested';
			$uploadOk = 0;
			header("Location:index.php?error=2");
			closeUpload($conn);
		}
	}
}

//Check if replay has already been process
$result = $conn->query('SELECT OFN FROM replaylist');
if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row["OFN"] == $file_name){
			echo 'file has already been requested';
			$uploadOk = 0;
			header("Location:index.php?error=5");
			closeUpload($conn);
		}
	}
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
	header("Location:index.php?error=4");
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		//-- Check if player already exist in player database --
		//Sql request
		$playerName = getPlayerName($file_name);
		$result = $conn->query("SELECT userName FROM playerlist WHERE userName='$playerName'");
		if($result->num_rows == 0){
			//Player is not into database
			//Create entry for player
			$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$apiKey&u=$playerName");
			$json = json_decode($apiRequest, true);
			$playerId = $json[0]['user_id'];
			
			//Send info to database
			$sql = "INSERT INTO playerlist (userId,userName) VALUES ('$playerId','$playerName')";
			if ($conn->query($sql) === TRUE) {
				//row created
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
		//-- Create a request ticket --
		date_default_timezone_set('Europe/Paris');
		$replayId = uniqid();
		$beatmapMD5 = getBeatmapMD5($file_name);
		$beatmapJson = getBeatmapJSON($beatmapMD5,$apiKey);
		$beatmapId = $beatmapJson[0]["beatmap_id"];
		$beatmapSetId = $beatmapJson[0]["beatmapset_id"];
		$replayDuration = $beatmapJson[0]["total_length"];
		//Encode to Base64 to avoid sql syntax error
		$beatmapName = base64_encode(generateBtFileName($beatmapId,$apiKey));
		$replayName = base64_encode($file_name);
		$result = $conn->query("SELECT userId FROM playerlist WHERE userName='$playerName'");
		while ($row = $result->fetch_assoc()) {
			$playerId = $row['userId'];
		}
		
		//Check if the replay already exist
		if(replayExist($replayName,"requestlist",$conn)){
			//replay is in wait list
			header("Location:index.php?error=2");
			closeUpload($conn);
		}
		if(replayExist($replayName,"replaylist",$conn)){
			//replay has been already recorded
			$replayId = getReplayId($replayName,$conn);
			header("Location:index.php?error=5&id=$replayId");
			closeUpload($conn);
		}
		
		
		//Send record
		$sql = "INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId) VALUES ('$replayId','$beatmapId','$beatmapSetId','$replayName','$beatmapName','$replayDuration','$playerId')";
		if ($conn->query($sql) === TRUE) {
			//row created
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
			header("Location:index.php?error=3&sqlErr=".$conn->error);
			closeUpload($conn);
		}
		
		//Deplacement du fichier en liste d'attente
		mkdir('requestList/'.$replayId, 0777, true);
		rename('uploads/'.$file_name,'requestList/'.$replayId.'/'.$file_name);
		
		//upload finised
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		header("Location:index.php?error=0");
		closeUpload($conn);
    } else {
        echo "Sorry, there was an error uploading your file.";
		header("Location:index.php?error=4");
		closeUpload($conn);
    }
}
//Errors :
/*
	0="Upload successful"
	1="File is not a osu replay"
	2="File already been requested"
	3="Database connection error"
	4="Upload error"
	5="File has been already processed"
*/
?>

