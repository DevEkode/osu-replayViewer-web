<?php
session_start();
require_once '../disableUploads.php';

require '../osuApiFunctions.php';
require_once '../../secure/osu_api_key.php';
require '../../secure/mysql_pass.php';
require '../websiteFunctions.php';

// Create connection
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:../../index.php?error=1");
	exit;
}

//booleans
$replayStructure = false;
$beatmapAvailable = false;
$playerOsuAccount = false;
$replayBelow10 = false;
$replayNotDuplicate = false;
$replayNotWaiting = false;

$skinName = 'null';
$beatmapJSON = null;

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

function userFileExists($userId){
  $user_URL = "../../accounts/".$userId;
  return is_dir($user_URL);
}

function checkIfIniExists($userId){
  $ini_URL = "../../accounts/".$userId.'/'.$userId.'.ini';
  return file_exists($ini_URL);
}

function getIniKey($userId,$key){
  $ini = parse_ini_file('../../accounts/'.$userId.'/'.$userId.'.ini');
  return $ini[$key];
}

function hasImpossibleMods($mods_bin)
{
    //List of the impossible configurations
    $configs = array(
        0 => array("PF", "NF"),
        1 => array("EZ", "HR"),
        2 => array("NF", "SD"),
        3 => array("HT", "DT"),
        4 => array("HT", "NC"),
        5 => array("SD", "AP"),
        6 => array("RL", "AP"),
        7 => array("SO", "AP"),
        8 => array("NF", "AP"),
    );

    //Get the converted array of mods
    $mods = getModsArray($mods_bin);

    //Check if at least one of this configs are in the array
    foreach ($configs as $config) {
        if (in_array($configs[0], $mods) && in_array($configs[1], $mods)) return true;
    }

    //Second check for mania mods
    //(You cannot have multiple 4K,5K... mods)
    $counter = 0;
    foreach ($mods as $mod) {
        if (preg_match('/.K/', $mod)) $counter++;
        if ($counter >= 2) return false;
    }

    return false;
}

//----- CORE ------
require_once '../../secure/admins.php';
if(isset($_SESSION['userId']) && in_array($_SESSION['userId'],$admins)){
  $disableUploads = false;
}

if($disableUploads || !isset($_FILES['fileToUpload'])){
  header("Location:../../index.php?error=9");
  exit;
}


$target_dir = "../../uploads/";
if(!file_exists($target_dir)){
  mkdir($target_dir);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(validateReplayStructure($_FILES["fileToUpload"]["tmp_name"],$osuApiKey) && $imageFileType == "osr"){
  $replayStructure = true;
}

if($replayStructure){
  //upload file
  move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

  //check replay structure
  $replay_content = getReplayContent("../../uploads/".$file_name);

  if(empty($replay_content)) {
    header("Location:../../index.php?error=2");
  }

  if(in_array($replay_content['gamemode'], array(0,1,2,3), true )) {$replayStructure = true;}

  //check beatmap existance
  $beatmapJSON = getBeatmapJSONwMD5($replay_content['md5'],$osuApiKey);

  if(empty($beatmapJSON)){
    header("Location:../../index.php?error=3");
  }

  if (isBeatmapAvailable($beatmapJSON[0]['beatmap_id'], $osuApiKey)) {
    $beatmapAvailable = true;
  }

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

  //Check the skin used
  if(userHasAaccount($userJSON[0]['user_id'],$conn) && userFileExists($userJSON[0]['user_id']) && checkIfIniExists($userJSON[0]['user_id'])){
    $skinName = getIniKey($userJSON[0]['user_id'],"fileName");
  }else{
    $skinName = "osu!replayViewer skin";
  }
}
//Send all the Informations


$_SESSION['filename'] = $file_name;
$_SESSION['skinName'] = $skinName;
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

header("Location:../../index.php");

?>
