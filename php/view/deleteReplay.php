<?php
session_start();
require '../../secure/mysql_pass.php';
require 'functions.php';
require '../ftp_agent.class.php';

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

//Check if the correct user delete the replay
$replayJSON = getReplayArray($_POST['replayId'],$conn);
if(strcmp($_SESSION['userId'],$replayJSON['userId']) != 0){
  header('Location:../../index.php');
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

//Initialize FTP
$ftp = new ftp_agent();
$ftp->connect();

//Delete replay folder in replayList
if($ftp->dirExists($_POST['replayId'])){
  $ftp->removeFolder($_POST['replayId']);
}

//Delete replay from database
$query = $conn->prepare("DELETE FROM replaylist WHERE replayId=?");
$query->bind_param("s",$_POST['replayId']);
$query->execute();
$query->close();

header("Location:../../index.php");
?>
