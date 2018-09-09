<?php
session_start();

if(empty($_SESSION)){
  header("Location:index.php");
}

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

require 'replaySettings.php';

if(!file_exists($target_dir)){
  mkdir($target_dir);
}

$target_dir = "../../accounts/".$_SESSION["userId"]."/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

function error($error_code){
  global $target_dir;
  global $target_file;
  if(file_exists($target_dir."export")){
    removeFolder($target_dir."export");
  }

  if(file_exists($target_file) && strcmp($error_code,'0') != 0){
    unlink($target_file);
  }

  $var = "Location:../../editProfile.php?skinError=".$error_code;
  header($var);
  exit();
}

if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', basename($_FILES["fileToUpload"]["name"])))
{
  $uploadOk = 0;
  error('4');
}

// Check if file already exists
$account_folder = "../../accounts/".$_SESSION["userId"]."/".basename($_FILES["fileToUpload"]["name"]);
if (file_exists($account_folder)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    error('1');
}

// Allow certain file formats
if($imageFileType != "osk") {
    echo "Sorry, only OSK files are allowed.";
    $uploadOk = 0;
    error('2');
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50*1048576) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    error('5');
}

// -- Upload file --
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    error('3');
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //Check if the file is a zip archive
        $zip = new ZipArchive();
        $zip->open($target_file);
        if($zip->extractTo($target_dir."export")){
          echo 'extract Ok';
          if(!isSkinValid($target_dir."export")){
            $uploadOk = 0;
            error('2');
          }
        }else{
          echo 'extract Fail';
          $uploadOk = 0;
          error('2');
        }
        $zip->close();

        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        //Move the uploaded file
        $new_target = "../../accounts/".$_SESSION["userId"]."/".basename($_FILES["fileToUpload"]["name"]);
        rename($target_file,$new_target);
        removeFolder($target_dir."export");
        error('0');
    } else {
        echo "Sorry, there was an error uploading your file.";
        error('3');
    }
}
 ?>
