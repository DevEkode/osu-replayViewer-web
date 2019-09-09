<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require '../osuApiFunctions.php';
require 'functions.php';
require '../ftp_agent.class.php';

$osuApiKey = getenv('OSU_KEY');

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));;

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

if(!isset($_POST['replayId'])){
  header('Location:../../index.php');
}


//Check if the correct user delete the replay
$replayJSON = getReplayArray($_POST['replayId'],$conn);
if(strcmp($_SESSION['userId'],$replayJSON['userId']) != 0){
  header('Location:../../index.php');
}

//--Functions
function cleanFolder($dir){
	//delete all folder files
	$files = glob($dir."/*"); // get all file names
	foreach($files as $file){ // iterate files
		if(is_file($file)){
			unlink($file); // delete file
		}
	}
}

function removeFolder($dir){
	cleanFolder($dir);
	//delete folder
	rmdir($dir);
}

//--Recreate the folder in requestList
$folder_dir = "../../requestList/".$_POST['replayId'];
if(!file_exists($folder_dir)){
  mkdir($folder_dir);
}

//Initialize ftp
$ftp = new ftp_agent();
$ftp->connect();


//Download replay
$replayDATA = getReplayArray($_POST['replayId'],$conn);
$replay_dir = $_POST['replayId']."/".base64_decode($replayDATA['OFN']);
$ftp->downloadFile($replay_dir,$_SERVER['DOCUMENT_ROOT'].'/uploads/'.base64_decode($replayDATA['OFN']));

$replay_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/".base64_decode($replayDATA['OFN']);
$replayJSON = getReplayContent($replay_dir);
$beatmapJSON = getBeatmapJSONwMD5($replayJSON['md5'],$osuApiKey);

//Check replay variables
$replayDuration = $beatmapJSON[0]['total_length'];
if(isDT($replayJSON['Mods'])){
  $replayDuration = $replayDuration - ($replayDuration * (33/100));
}

//Move the .osr in the folder
$new_dir = '../../requestList/'.$_POST['replayId'].'/'.base64_decode($replayDATA['OFN']);
rename($replay_dir,$new_dir);

//--Create the row in requestlist table
$query = $conn->prepare("INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
$query->bind_param("siissiisiii",$replayDATA['replayId'],$replayDATA['beatmapId'],$replayDATA['beatmapSetId'],$replayDATA['OFN'],$replayDATA['BFN'],$replayDuration,$replayDATA['userId'],$replayDATA['md5'],$replayDATA['playMod'],$replayDATA['binaryMods'],$replayDATA['permanent']);
$query->execute();
var_dump($query);
$query->close();

//--Delete the row in replaylist table
$query = $conn->prepare("DELETE FROM replaylist WHERE replayId=?");
$query->bind_param("s",$replayDATA['replayId']);
$query->execute();
$query->close();

//--Delete the folder in replayList
if($ftp->dirExists($_POST['replayId'])){
  $ftp->removeFolder($_POST['replayId']);
}

//--Redirect on the waiting page
header("Location:../../progress.php?id=".$_POST['replayId']);

?>
