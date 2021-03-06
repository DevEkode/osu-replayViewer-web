<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require 'functions.php';
require '../ftp_agent.class.php';

function cleanFolder($dir)
{
	//delete all folder files
	$files = glob($dir . "/*"); // get all file names
	foreach ($files as $file) { // iterate files
		if (is_file($file)) {
			unlink($file); // delete file
		}
	}
}

function removeFolder($dir)
{
	cleanFolder($dir);
	//delete folder
	rmdir($dir);
}

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

if (!isset($_GET['replayId'])) {
  header('Location:../../index.php');
}

//Construct sanitized replay array
$replays = array();
$json_get = json_decode($_GET['replayId']);
if ($json_get == null) {
	//Not an array
	$replayId = filter_var($_GET['replayId'], FILTER_SANITIZE_STRING);
	array_push($replays, $replayId);
} else {
	//Is an array
	foreach ($json_get as $elem) {
		$replayId = filter_var($elem, FILTER_SANITIZE_STRING);
		array_push($replays, $replayId);
	}
}

//Initialize FTP
$ftp = new ftp_agent();
$ftp->connect();

foreach ($replays as $replayId) {
	//Check if the correct user delete the replay
	$replayJSON = getReplayArray($replayId, $conn);
	if (strcmp($_SESSION['userId'], $replayJSON['userId']) != 0) {
		header('Location:../../index.php');
	}

//Delete replay folder in replayList
	if ($ftp->dirExists($replayId)) {
		$ftp->removeFolder($replayId);
	}

//Delete replay from database
	$query = $conn->prepare("DELETE FROM replaylist WHERE replayId=?");
	$query->bind_param("s", $replayId);
	$query->execute();
	$query->close();
}


if (isset($_GET['redirect'])) {
	$redirect = filter_var($_GET['redirect'], FILTER_SANITIZE_STRING);
	header("Location:../../editProfile.php?block=" . $redirect);
} else {
	header("Location:../../index.php");
}

?>
