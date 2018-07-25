<?php
var_dump($_POST);
include '../osuApiFunctions.php';
include 'clearSession.php';
require_once '../../secure/osu_api_key.php';
require '../../secure/mysql_pass.php';

$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//---- get all the Informations ----
//get replay file Informations
$replayJSON = getReplayContent("../../uploads/".$_POST['filename']);

//Create replayId
date_default_timezone_set('Europe/Paris');
$replayId = uniqid();

//persistance
if(isset($_POST['checkbox']) && $_POST["checkbox"] != NULL){
	$persistance = 1;
}else{
	$persistance = 0;
}

//beatmap Informations
$beatmapJSON = getBeatmapJSONwMD5($replayJSON['md5'],$osuApiKey);
$beatmapId = $beatmapJSON[0]['beatmap_id'];
$beatmapSetId = $beatmapJSON[0]['beatmapset_id'];
$beatmapName = base64_encode(generateBtFileNamewJSON($beatmapJSON));

//replay Informations
$replayName = base64_encode($_POST['filename']);
$replayDuration = $_POST['duration'];
$fileMD5 = md5_file("../../uploads/".$_POST['filename']);
$replayMod = $replayJSON['gamemode'];
$binaryMods = $replayJSON['Mods'];

//player Informations
$playerJSON = getUserJSON($replayJSON['user'],$osuApiKey);
$playerId = $playerJSON[0]['user_id'];

//---- Send the Informations into the database ----
$sql = "INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES ('$replayId','$beatmapId','$beatmapSetId','$replayName','$beatmapName','$replayDuration','$playerId','$fileMD5','$replayMod','$binaryMods','$persistance')";

if ($conn->query($sql) === TRUE) {
  //row created
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  $conn->close();
  exit;
}

//Deplacement du fichier en liste d'attente
mkdir('../../requestList/'.$replayId, 0777, true);
rename('../../uploads/'.$_POST['filename'],'../../requestList/'.$replayId.'/'.$_POST['filename']);

$conn->close();
clear();
header("Location:../../progress.php?id=".$replayId);
exit;




 ?>
