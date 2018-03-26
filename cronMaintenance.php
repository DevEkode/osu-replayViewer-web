<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ******************** Variables **********************************
//--Connect to osu API --
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

//Time limit in days
$timeLimit = 30;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "u611457272_osu");
//$conn = new PDO('mysql:host=mysql.hostinger.fr;dbname=u611457272_osu','u611457272_code','123');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	exit;
}

// ******************** Functions **********************************
function getRemovableReplays($conn,$timeLimit){
	$array = array();

	$result = $conn->query("SELECT * FROM replaylist WHERE date < DATE_SUB(now(), INTERVAL '$timeLimit' DAY) ");
		while ($row = $result->fetch_assoc()) {
			$permanent = $row['permanent'];
			if($permanent == 0){
				$replayId = $row['replayId'];
				array_push($array,$replayId);
			}
		}
	return $array;
}

function removeRow($conn,$replayId){
	$sql = "DELETE FROM replaylist WHERE replayId='$replayId'";
	if ($conn->query($sql) === TRUE) {
		//echo "Record deleted successfully";
	} else {
		echo "Error deleting record: " . $conn->error;
	}
}

function cleanRequestFolder($conn){
	$array = array();

	$result = $conn->query("SELECT * FROM requestlist");
		while ($row = $result->fetch_assoc()) {
			$replayId = $row['replayId'];
			array_push($array,$replayId);
		}

	$folders = scandir("./requestList/");
	foreach($folders as $folder){
		if(!in_array($folder,$array) && $folder != "." && $folder != ".."){
			removeFolder("./requestList/".$folder);
		}
	}
}

function cleanReplayFolder($conn){
	$array = array();

	$result = $conn->query("SELECT * FROM replaylist");
		while ($row = $result->fetch_assoc()) {
			$replayId = $row['replayId'];
			array_push($array,$replayId);
		}

	$folders = scandir("./replayList/");
	foreach($folders as $folder){
		if(!in_array($folder,$array) && $folder != "." && $folder != ".."){
			removeFolder("./replayList/".$folder);
		}
	}
}

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

//**************** Maintenance *************************

//Show some infos
$ds = disk_total_space("/")*0.0000001;
$df = disk_free_space("/")*0.0000001 ;
$du = $ds-$df;
//$ds = $ds*0.000001;
echo "Disk total space : ".ceil($du)." Mb remaining on ".ceil($ds)." Mb total <br>";

//clean upload folder
echo "Cleaning upload folder... <br>";
cleanFolder("./uploads");
echo "Cleaning request folder...<br>";
cleanRequestFolder($conn);

cleanReplayFolder($conn);

$array = getRemovableReplays($conn,$timeLimit);
echo count($array)." replays can be removed";

foreach($array as $replay){
	removeFolder("./replayList/".$replay);
	removeRow($conn,$replay);
}

?>
