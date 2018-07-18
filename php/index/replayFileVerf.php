<?php
session_start();
require '../osuApiFunctions.php';
require_once '../../secure/osu_api_key.php';
require '../../secure/mysql_pass.php';

// Create connection
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//booleans
$replayStructure = false;
$beatmapAvailable = false;
$playerOsuAccount = false;
$replayBelow10 = false;
$replayNotDuplicate = false;
$replayNotWaiting = false;

//---- Functions -----

function replayExist($filedir, $table, $conn){
	$md5 = md5_file($filedir);
	$result = $conn->query("SELECT * FROM $table WHERE md5='$md5'");

	if($result->num_rows > 0){
		return true;
	}
	else{
		return false;
	}
}

//----- CORE ------

$target_dir = "../../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if($imageFileType == "osr") {
    $replayStructure = true;
}

if($replayStructure){
  //upload file
  var_dump(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file));

  //check replay structure
  $replay_content = getReplayContent("../../uploads/".$file_name);
  var_dump($replay_content);

  if(in_array($replay_content['gamemode'], array(0,1,2,3), true )) {$replayStructure = true;}

  //check beatmap existance
  $beatmapJSON = getBeatmapJSONwMD5($replay_content['md5'],$osuApiKey);

  if(isBeatmapAvailable($beatmapJSON[0]['beatmapset_id'])) {$beatmapAvailable = true;}

  //Check osu account
  $userJSON = getUserJSON($replay_content['user'],$osuApiKey);
  if(!empty($userJSON)) {$playerOsuAccount = true;}

  //Check replay duration
  $replayDuration = $beatmapJSON[0]['total_length'];
  if(isDT($replay_content['Mods'])){
    $replayDuration = $replayDuration - ($replayDuration * (33/100));
  }
  if($replayDuration <= 600) {$replayBelow10 = true;}

  //Check if the replay already exists in database
  if(!replayExist("../../uploads/".$file_name,"replaylist",$conn)) {$replayNotDuplicate = true;}
  //Check if the replay is not already in queue
  if(!replayExist("../../uploads/".$file_name,"requestlist",$conn)) {$replayNotWaiting = true;}
}
//Send all the Informations


$_SESSION['filename'] = $file_name;
$_SESSION['replayStructure'] = $replayStructure;
$_SESSION['beatmapAvailable'] = $beatmapAvailable;
$_SESSION['playerOsuAccount'] = $playerOsuAccount;
$_SESSION['replayBelow10'] = $replayBelow10;
$_SESSION['replayNotDuplicate'] = $replayNotDuplicate;
$_SESSION['replayNotWaiting'] = $replayNotWaiting;
$_SESSION['beatmapName'] = $beatmapJSON[0]['title'];
$_SESSION['beatmapSetId'] = $beatmapJSON[0]['beatmapset_id'];
$_SESSION['difficulty'] = $beatmapJSON[0]['version'];
if(isset($userJSON)){
  $_SESSION['playername'] = $userJSON[0]['username'];
  $_SESSION['replay_playerId'] = $userJSON[0]['user_id'];
}else{
  $_SESSION['playername'] = 'unknown';
  $_SESSION['replay_playerId'] = null;
}

if(isset($replayDuration)){
  $_SESSION['duration'] = $replayDuration;
}else{
  $_SESSION['duration'] = 0;
}

if(isset($replay_content)){
  $_SESSION['mods'] = drawMods($replay_content['Mods']);
}else{
  $_SESSION['mods'] = 'none';
}


header("Location:../../index_v2.html");

?>
