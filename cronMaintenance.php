<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';


$osuApiKey = getenv('OSU_KEY');

// ******************** Variables **********************************
//--Connect to osu API --
$apiKey = $osuApiKey;

//-- Connect to mysql request database --

//Time limit in days
$timeLimit = 30;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	exit;
}

//FTP
$conn_id = ftp_connect(getenv('FTP_HOST'));
$login_result = ftp_login($conn_id, getenv('FTP_USER'), getenv('FTP_PASS'));


// check connection
if ((!$conn_id) || (!$login_result)) {
	die("FTP connection has failed !");
}

if (ftp_chdir($conn_id, $ftp_replay_dir)) {
	echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
} else {
	echo "Couldn't change directory\n";
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

function cleanReplayFolder($conn, $conn_id)
{
	$array = array();

	$result = $conn->query("SELECT * FROM replaylist");
		while ($row = $result->fetch_assoc()) {
			$replayId = $row['replayId'];
			array_push($array,$replayId);
		}

	$folders = ftp_nlist($conn_id, ".");
	foreach($folders as $folder){
		if(!in_array($folder,$array) && $folder != "." && $folder != ".."){
			removeRemoteFolder($folder, $conn_id);
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

function cleanRemoteFolder($dir, $conn_id)
{
	//delete all folder files
	$files = ftp_nlist($conn_id, "./" . $dir); // get all file names
	foreach ($files as $file) { // iterate files
		if (is_file($file)) {
			ftp_delete($conn_id, $file);
		}
	}
}

function removeRemoteFolder($dir, $conn_id)
{
	cleanRemoteFolder($dir, $conn_id);
	//delete folder
	ftp_rmdir($conn_id, $dir);
}

//**************** Maintenance *************************

//Show some infos
//$ds = disk_total_space("/")*0.0000001;
//$df = disk_free_space("/")*0.0000001 ;
//$du = $ds-$df;
//$ds = $ds*0.000001;
//echo "Disk total space : ".ceil($du)." Mb remaining on ".ceil($ds)." Mb total <br>";

//clean upload folder
echo "Cleaning upload folder... <br>";
cleanFolder("./uploads");
echo "Cleaning request folder...<br>";
cleanRequestFolder($conn);

$array = getRemovableReplays($conn,$timeLimit);
echo count($array)." replays can be removed";

foreach($array as $replay){
	echo $replay;
	removeRemoteFolder($replay, $conn_id);
	removeRow($conn,$replay);
}


?>
