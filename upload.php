<?php
ini_set('display_errors', 1);
// ******************** Variables **********************************
//--Connect to osu API --
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
$servername = "mysql.hostinger.fr";
$username = "u611457272_code";
require_once 'secure/mysql_pass.php';
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

//create folder upload if does not exist
if (!file_exists('./uploads')) {
    mkdir('./uploads', 0777, true);
}
// ******************** Fonctions **********************************
function getPlayerName($fileName){ //Return player name of the replay from the name of the file
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));
	$pName = explode(" ",$replay_content);
	return substr($pName[1], 34, -1);
}

function getPlayerId($username,$api){
	$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$api&u=$username");
	$json = json_decode($apiRequest, true);
	return $json[0]['user_id'];
}

function getBeatmapMD5($fileName){
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));
	fclose($myfile);
	$md5 = substr($replay_content,7,32);
	return $md5;
}

function getFileMD5($fileName){
	$myfile = "./uploads/".$fileName;
	return md5_file($myfile);
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

function replayExist($file_name, $table, $conn){
	$md5 = getFileMD5($file_name);
	$result = $conn->query("SELECT * FROM $table WHERE md5='$md5'");
	
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

function getReplayId($file_name,$conn){
	$md5 = getFileMD5($file_name);
	$result = $conn->query("SELECT * FROM replaylist WHERE md5='$md5'");
	$id = "";
	
	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["replayId"];
		}
	}
	return $id;
}

function getRank($replayId,$conn){
	$result = $conn->query("SET @rank=0; SELECT @rank:=@rank+1 AS rank,replayId FROM `replaylist` GROUP BY replayId ORDER BY `date` DESC");
	if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row["replayId"] == $replayId){
			return $row["rank"];
		}
	}
}
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

//*********************** UPLOAD FILE **********************************
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
	header("Location:index.php?error=4");
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		
		//----- Check if the replay already exist -----
		if(replayExist($file_name,"requestlist",$conn)){
			//replay is in wait list
			header("Location:index.php?error=2");
			closeUpload($conn);
		}
		if(replayExist($file_name,"replaylist",$conn)){
			//replay has been already recorded
			$replayId = getReplayId($file_name,$conn);
			header("Location:index.php?error=5&id=$replayId");
			closeUpload($conn);
		}
		
		//----- Check if player already exist in player database and osu database -----
		if(getPlayerId(getPlayerName($file_name),$apiKey) == 0){
			header("Location:index.php?error=7");
			closeUpload($conn);
		}
		
		//Sql request
		$playerName = getPlayerName($file_name);
		$result = $conn->query("SELECT userName FROM playerlist WHERE userName='$playerName'");
		if($result->num_rows == 0){
			//Player is not into database
			//Create entry for player
			$playerId = getPlayerId($playerName,$apiKey);
			
			//Send info to database
			$sql = "INSERT INTO playerlist (userId,userName) VALUES ('$playerId','$playerName')";
			if ($conn->query($sql) === TRUE) {
				//row created
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
		//----- Create a request ticket -----
		date_default_timezone_set('Europe/Paris');
		$replayId = uniqid();
		$fileMD5 = getFileMD5($file_name);
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
		
		
		//----- Send record -----
		$sql = "INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5) VALUES ('$replayId','$beatmapId','$beatmapSetId','$replayName','$beatmapName','$replayDuration','$playerId','$fileMD5')";
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
		header("Location:index.php?error=6");
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

