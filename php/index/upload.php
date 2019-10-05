<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
include '../osuApiFunctions.php';


$osuApiKey = getenv('OSU_KEY');

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

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
$sql = "INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("siissiisiii",
    $replayId,
    $beatmapId,
    $beatmapSetId,
    $replayName,
    $beatmapName,
    $replayDuration,
    $playerId,
    $fileMD5,
    $replayMod,
    $binaryMods,
    $persistance);

if ($stmt->execute()) {
  //row created
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
  $conn->close();
  header("Location:../../index.php?error=5");
  exit;
}

$btContent = getBeatmapJSONwMods($replayJSON['md5'], $replayJSON['Mods'], $osuApiKey);
$replayAcc = getReplayAccuracy($replayJSON);
$pp = 0;

$stmt = $conn->prepare("INSERT INTO replaystats (replayId, gamemode, modsBinary, stars, pp, acc, ar, BPM, x300, x100, x50, gekis, katus, miss, t_score, max_combo, perfect) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('siidddddiiiiiiiii',
    $replayId,
    $replayJSON['gamemode'],
    $replayJSON['Mods'],
    $btContent[0]['difficultyrating'],
    $pp,
    $replayAcc,
    $btContent[0]['diff_approach'],
    $btContent[0]['bpm'],
    $replayJSON['x300'],
    $replayJSON['x100'],
    $replayJSON['x50'],
    $replayJSON['Gekis'],
    $replayJSON['Katus'],
    $replayJSON['Miss'],
    $replayJSON['Score'],
    $replayJSON['MaxCombo'],
    $replayJSON['perfectCombo']);

if ($stmt->execute()) {
    //row created
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    header("Location:../../index.php?error=3&sqlErr=" . $conn->error);
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
header("Location:../../progress.php?id=".$replayId);
exit;




 ?>
