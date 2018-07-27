<?php
require '../../secure/mysql_pass.php';
require '../osuApiFunctions.php';
require '../../secure/osu_api_key.php';

$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

if(!isset($_POST['replayId'])){
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

function getReplayArray($replayId){
  global $conn;
  $return = array();

  $query = $conn->prepare("SELECT * FROM replaylist WHERE replayId=?");
  $query->bind_param("s",$replayId);
  $query->execute();
  $result = $query->get_result();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $return = $row;
    }
  }
  $query->close();
  return $return;
}

//--Recreate the folder in requestList
$folder_dir = "../../requestList/".$_POST['replayId'];
if(!file_exists($folder_dir)){
  mkdir($folder_dir);
}

$replayDATA = getReplayArray($_POST['replayId']);
$replay_dir = "../../replayList/".$_POST['replayId']."/".base64_decode($replayDATA['OFN']);

$replayJSON = getReplayContent($replay_dir);
$beatmapJSON = getBeatmapJSONwMD5($replayJSON['md5'],$osuApiKey);


$replayDuration = $beatmapJSON[0]['total_length'];
if(isDT($replayJSON['Mods'])){
  $replayDuration = $replayDuration - ($replayDuration * (33/100));
}

//Move the .osr in the folder
$old_dir = '../../replayList/'.$_POST['replayId'].'/'.base64_decode($replayDATA['OFN']);
$new_dir = '../../requestList/'.$_POST['replayId'].'/'.base64_decode($replayDATA['OFN']);
rename($old_dir,$new_dir);

//--Create the row in requestlist table
$query = $conn->prepare("INSERT INTO requestlist (replayId,beatmapId,beatmapSetId,OFN,BFN,duration,playerId,md5,playMod,binaryMods,persistance) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
$query->bind_param("siissiisiiis",$replayDATA['replayId'],$replayDATA['beatmapId'],$replayDATA['beatmapSetId'],$replayDATA['OFN'],$replayDATA['BFN'],$replayDuration,$replayDATA['userId'],$replayDATA['md5'],$replayDATA['playMod'],$replayDATA['binaryMods'],$replayDATA['permanent']);
$query->execute();
$query->close();

//--Delete the row in replaylist table
$query = $conn->prepare("DELETE FROM replaylist WHERE replayId=?");
$query->bind_param("s",$replayDATA['replayId']);
$query->execute();
$query->close();

//--Delete the folder in replayList
$folder_dir = "../../replayList/".$_POST['replayId'];
if(file_exists($folder_dir)){
  removeFolder($folder_dir);
}

//--Redirect on the waiting page
header("Location:../../progress.php?id=".$_POST['replayId']);

?>
