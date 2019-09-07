<?php
session_start();
var_dump($_SESSION);
include '../osuApiFunctions.php';
require_once '../../secure/osu_api_key.php';
require '../../secure/mysql_pass.php';

$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:../../index.php?error=1");
	exit;
}

//---- get all the Informations ----
//Cancel if the TU is not accepted
if(!isset($_POST['checkboxTU']) && $_POST['checkboxTU'] != "true"){
  header("Location:../../index.php?error=8");
  exit;
}

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
$playerId = $_POST['userId'];

//---- Send the Informations into the database ----
$sql = "INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES ('$replayId','$beatmapId','$beatmapSetId','$replayName','$beatmapName','$replayDuration','$playerId','$fileMD5','$replayMod','$binaryMods','$persistance')";

if ($conn->query($sql) === TRUE) {
  //row created
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  $conn->close();
  header("Location:../../index.php?error=5");
  exit;
}

$btContent = getBeatmapJSONwMods($replayJSON['md5'], $replayJSON['Mods'], $osuApiKey);

$stmt = $conn->prepare("INSERT INTO replaystats (replayId, gamemode, modsBinary, stars, pp, acc, ar, BPM, x300, x100, x50, gekis, katus, miss, t_score, max_combo, perfect) VALUES (:replayId, :gamemode, :modsBinary, :stars, 0, :acc, :ar, :BPM, :x300, :x100, :x50, :gekis, :katus, :miss, :t_score, :max_combo, :perfect)");

//$query->bind_param(':pp',);
$stmt->bind_param(':replayId', $replayId);
$stmt->bind_param(':gamemode', $replayJSON['gamemode']);
$stmt->bind_param(':modsBinary', $replayJSON['Mods']);
$stmt->bind_param(':stars', $btContent[0]['difficultyrating']);
$stmt->bind_param(':acc', getReplayAccuracy($replayJSON));
$stmt->bind_param(':ar', $btContent[0]['diff_approach']);
$stmt->bind_param(':BPM', $btContent[0]['bpm']);
$stmt->bind_param(':x300', $replayJSON['x300']);
$stmt->bind_param(':x100', $replayJSON['x100']);
$stmt->bind_param(':x50', $replayJSON['x50']);
$stmt->bind_param(':gekis', $replayJSON['Gekis']);
$stmt->bind_param(':katus', $replayJSON['Katus']);
$stmt->bind_param(':miss', $replayJSON['Miss']);
$stmt->bind_param(':t_score', $replayJSON['Score']);
$stmt->bind_param(':max_combo', $replayJSON['MaxCombo']);
$stmt->bind_param(':perfect', $replayJSON['perfectCombo']);

if ($stmt->execute()) {
    //row created
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    header("Location:index.php?error=3&sqlErr=" . $conn->error);
    closeUpload($conn);
}

//Deplacement du fichier en liste d'attente
if(!mkdir('../../requestList/'.$replayId, 0777, true)){
  header("Location:../../index.php?error=6");
}
if(!rename('../../uploads/'.$_POST['filename'],'../../requestList/'.$replayId.'/'.$_POST['filename'])){
  header("Location:../../index.php?error=7");
}

$conn->close();
include 'clearSession.php';
//clear();
header("Location:../../progress.php?id=".$replayId);
exit;




 ?>
