<?php
//Filter $_GET
$deleteVerfId = filter_var($_POST['id'],FILTER_SANITIZE_STRING);
$userId = filter_var($_POST['userId'],FILTER_SANITIZE_NUMBER_INT);

//Delete account
require $_SERVER['DOCUMENT_ROOT'].'/secure/mysql_pass.php';
$conn = new PDO("mysql:host=$mySQLservername;dbname=$mySQLdatabase", $mySQLusername, $mySQLpassword);

//Check if the verf id is correct
$query = $conn->prepare('SELECT * FROM verfIds WHERE userId=:user');
$query->bindParam('user',$userId,PDO::PARAM_STR);
$query->execute();

$result = $query->fetch();
if(strcmp($deleteVerfId,$result['deleteVerfId']) != 0){
  //TODO error
  header('Location:index.php');
}

unset($query);
//Delete verf id
$query = $conn->prepare('DELETE FROM verfIds WHERE userId=:user');
$query->bindParam('user',$userId,PDO::PARAM_STR);
$query->execute();

//Delete account files
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

$accountDir = $_SERVER['DOCUMENT_ROOT'].'/accounts/'.$userId;
if(is_dir($accountDir)){
  removeFolder($accountDir);
  echo 'deleting account folder...<br>';
}

//Delete account for database
echo 'deleting from database';
$query2 = $conn->prepare('DELETE FROM accounts WHERE userId=:user');
$query2->bindParam('user',$userId,PDO::PARAM_STR);
$query2->execute();

header('Location:logout.php');

 ?>
