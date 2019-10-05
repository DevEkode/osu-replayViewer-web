<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//Get post
$replayId = filter_var($_POST['replayId'],FILTER_SANITIZE_STRING);
if (isset($_POST['replayMd5'])) {
    $replayMd5 = filter_var($_POST['replayMd5'], FILTER_SANITIZE_STRING);
}

$redirectTo = "index";
if (isset($_POST['redirectTo'])) {
    $redirectTo = filter_var($_POST['redirectTo'], FILTER_SANITIZE_STRING);
}


//Check md5
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

//get md5 from file (if replayMd5 is not posted)
if (!isset($_POST['replayMd5'])) {
    $fileMD5 = md5_file($_FILES["file"]["tmp_name"]);
} else {
    $fileMD5 = $replayMd5;
}


//get md5 from database
$query = $conn->prepare('SELECT md5 FROM requestlist WHERE replayId=?');
$query->bind_param('s', $replayId);
$query->execute();
$result = $query->get_result();
while ($row = $result->fetch_assoc()) {
    $line = $row;
}

if(empty($line)){
  $databaseMD5 = uniqid();
}else{
  $databaseMD5 = $line['md5'];
}

//Compare md5
echo 'file md5 : '.$fileMD5.'<br>';
echo 'database md5 : '.$databaseMD5;

if(strcmp($fileMD5,$databaseMD5) == 0){
  //Files are identical, deleting for database and server
  echo 'Same file, deleting...<br>';

  //Deleting from server
  function cleanFolder($dir){
  	//delete all folder files
  	$files = glob($dir."/*"); // get all file names
  	foreach($files as $file){ // iterate files
  		if(is_file($file)){
  			unlink($file); // delete file
  		}
      if(is_dir($file)){
        removeFolder($file);
      }
  	}
  }

  function removeFolder($dir){
  	cleanFolder($dir);
  	//delete folder
  	rmdir($dir);
  }

  $dir = $_SERVER['DOCUMENT_ROOT'].'/requestList/'.$replayId;
  removeFolder($dir);

  //Remove from database
    $query2 = $conn->prepare('DELETE FROM requestlist WHERE replayId=?');
    $query2->bind_param('s', $replayId);
  $query2->execute();
}else{
  //Not the same, sending error
  header("Location:/progress.php?id=".$replayId."&error=1");
}

//Redirect
switch ($redirectTo) {
    case "profile" :
        header("Location:/editProfile.php?block=pending");
        break;
    default :
        header("Location:/index.php");
}

 ?>
