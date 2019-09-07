<?php
ini_set('display_errors', 1);
include 'php/osuApiFunctions.php';
require 'secure/uploadKey.php';
// ******************** Variables **********************************
//--Connect to osu API --
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;

//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$disableUpload = false;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

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

    $array = unpack("x/iversion/x/clength/A32md5/x/clength2/Auser", $replay_content);
    $userLength = $array['length2'];
    $array = unpack("x/iversion/x/clength/A32md5/x/clength2/A".$userLength."user", $replay_content);
	return $array['user'];
}

function isDT($fileName){ //Return player name of the replay from the name of the file
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));

    $array = unpack("x/iversion/x/clength/A32md5/x/clength2/Auser", $replay_content);
    $userLength = $array['length2'];
    $array = unpack("x/iversion/x/clength/A32md5/x/clength2/A".$userLength."user/x/clength3/A32md5Replay/sx300/sx100/sx50/sGekis/sKatus/sMiss/iScore/sMaxCombo/xperfectCombo/iMods", $replay_content);

	$binary = $array['Mods'];
	$filter = 0b0000000000000000000001000000;
	$result = $binary & $filter;

	if($result != 0){
		return true;
	}else{
		return false;
	}
}

function getModsBinary($fileName){
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));

	$array = unpack("x/iversion/x/clength/A32md5/x/clength2/Auser", $replay_content);
    $userLength = $array['length2'];
    $array = unpack("x/iversion/x/clength/A32md5/x/clength2/A".$userLength."user/x/clength3/A32md5Replay/sx300/sx100/sx50/sGekis/sKatus/sMiss/iScore/sMaxCombo/xperfectCombo/iMods", $replay_content);

	return $array['Mods'];
}

function getOsuMod($fileName){
	$myfile = fopen("./uploads/".$fileName, "r") or die("Unable to open file!");
	$replay_content = fread($myfile,filesize("./uploads/".$fileName));

	$array = unpack("C1mod", $replay_content);

	return $array['mod'];
}

function getPlayerId($username,$api){
	$json = getUserJSON($username,$api);
	return $json[0]['user_id'];
}

function playerBanned($username,$api){
  $json = getUserJSON($username,$api);
	if(empty($json)){
		return true;
	}else{
		return false;
	}
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
  $json = getBeatmapJSON($beatmapId,$api);
	$beatmapSetId = $json[0]["beatmapset_id"];
	$artist = $json[0]["artist"];
	$title = $json[0]["title"];
	$BFN = $beatmapSetId." ".$artist." - ".$title.".osz";

	return $BFN;
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

function getReplayId($file_name,$bdd,$conn){
	$md5 = getFileMD5($file_name);
	$result = $conn->query("SELECT * FROM $bdd WHERE md5='$md5'");
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
function fakeReplay($beatmapId,$userId,$api){
	$json = getBeatmapJSON($beatmapId,$api);
	if(empty($json)){
		$bool = false;
	}
	return $bool;
}


// ******************** CORE **********************************
//**** security key check ****
if(!password_verify($upload_replay_key,$_POST['keyHash'])){
  header("Location:index.php?error=13");
  closeUpload($conn);
}

if($disableUpload){
    header("Location:index.php?error=11");
    closeUpload($conn);
}

//-- Upload check --
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//get persitance
if($_POST["checkbox"] != NULL){
	$persistance = 1;
}else{
	$persistance = 0;
}

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

		//----- Check if the replay is a fake replay -----
		$md5 = getBeatmapMD5($file_name);
		$json = getBeatmapJSONwMD5($md5,$apiKey);
    if(empty($json)){
  		header("Location:index.php?error=10");
  		closeUpload($conn);
  	}
		$beatId = $json[0]["beatmap_id"];
    $playerId = $_SESSION['replay_playerId'];
		//$playerId = getPlayerId(getPlayerName($file_name),$apiKey);
		if(fakeReplay($beatId,$playerId,$apiKey)){
			header("Location:index.php?error=1");
			closeUpload($conn);
		}
		//----- Check if the replay already exist -----
		if(replayExist($file_name,"requestlist",$conn)){
			//replay is in wait list
			$replayId = getReplayId($file_name,"requestlist",$conn);
			header("Location:index.php?error=2&pid=$replayId");
			closeUpload($conn);
		}
		if(replayExist($file_name,"replaylist",$conn)){
			//replay has been already recorded
			$replayId = getReplayId($file_name,"replaylist",$conn);
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

    $userJSON = getUserJSON($playerName,$osuApiKey);
    if(empty($userJSON)){
      header("Location:index.php?error=9");
			closeUpload($conn);
    }

		//Check if the player is banned
		if(playerBanned($playerName,$apiKey)){
			header("Location:index.php?error=9");
			closeUpload($conn);
		}

		//----- Create a request ticket -----
		date_default_timezone_set('Europe/Paris');
		$replayId = uniqid();
		$fileMD5 = getFileMD5($file_name);
		$beatmapMD5 = getBeatmapMD5($file_name);
		$beatmapJson = getBeatmapJSONwMD5($beatmapMD5,$apiKey);
    if(empty($beatmapJson)){
  		header("Location:index.php?error=10");
  		closeUpload($conn);
  	}
		$replayMod = getOsuMod($file_name); //Osu, Mania, CTB, Taiko
		$binaryMods = getModsBinary($file_name);

		//Check if the beatmap exist
		if(empty($beatmapJson)){
			header("Location:index.php?error=10");
			closeUpload($conn);
		}

		$beatmapId = $beatmapJson[0]["beatmap_id"];
		$beatmapSetId = $beatmapJson[0]["beatmapset_id"];
    if(!isBeatmapAvailable($beatmapSetId)){
      header("Location:index.php?error=12");
  		closeUpload($conn);
    }

		$replayDuration = $beatmapJson[0]["total_length"];

		//Divide the time by 33% when DT mods is activated
		if(isDT($file_name)){
			$replayDuration = $replayDuration - ($replayDuration * (33/100));
		}

		//Check if the replay is 10min max
		if($replayDuration > 600){
			header("Location:index.php?error=8");
			closeUpload($conn);
		}

		//Encode to Base64 to avoid sql syntax error
		$beatmapName = base64_encode(generateBtFileName($beatmapId,$apiKey));
		$replayName = base64_encode($file_name);

		//----- Send record -----
		$query = $conn->prepare("INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
		$query->bind_param("siissiisiii",$replayId,$beatmapId,$beatmapSetId,$replayName,$beatmapName,$replayDuration,$playerId,$fileMD5,$replayMod,$binaryMods,$persistance);

		if ($query->execute()) {
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
		header("Location:index.php?error=6&pid=$replayId");
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
