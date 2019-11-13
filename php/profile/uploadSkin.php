<?php
session_start();

require_once 'replaySettings.php';

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

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                else
                    unlink($dir."/".$object);
            }
        }
        rmdir($dir);
    }
}

function error($error_code,$reason = null){
    global $target_dir;
    global $target_file;
    if(file_exists($target_dir."export")){
        removeFolder($target_dir."export");
    }

    if(file_exists($target_file) && strcmp($error_code,'0') != 0){
        unlink($target_file);
    }

    if($reason == null) $var = "Location:../../editProfile.php?block=skin&error=".$error_code;
    else $var = "Location:../../editProfile.php?block=skin&error=".$error_code."&errorMsg=".$reason;
    header($var);
    exit();
}

if(empty($_FILES)){
    error('14');
}

$target_dir = "../../accounts/".$_SESSION["userId"]."/uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$uploadErrorMsg = "";
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

if(!file_exists($target_dir)){
  mkdir($target_dir);
}

if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', basename($_FILES["fileToUpload"]["name"])))
{
  $uploadOk = 0;
  error('10');
}

// Check if file already exists
$account_folder = "../../accounts/".$_SESSION["userId"]."/".basename($_FILES["fileToUpload"]["name"]);
if (file_exists($account_folder)) {
    //echo "Sorry, file already exists.";
    $uploadOk = 0;
    error('7');
}

// Allow certain file formats
if($imageFileType != "osk") {
    //echo "Sorry, only OSK files are allowed.";
    $uploadOk = 0;
    error('8');
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 50*1048576) {
    //echo "Sorry, your file is too large.";
    $uploadOk = 0;
    error('11');
}

// -- Upload file --
if ($uploadOk == 0) {
    //echo "Sorry, your file was not uploaded.";
    error('9');
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        //Check if the file is a zip archive
        $zip = new ZipArchive();
        $zip->open($target_file);
        if($zip->extractTo($target_dir."export")){
          //echo 'extract Ok';
            $skinValidityMsg = isSkinValid($target_dir."export");
          if($skinValidityMsg != null){
            $uploadOk = 0;
            error('9',$skinValidityMsg);
          }
        }else{
          //echo 'extract Fail';
          $uploadOk = 0;
          error('9','.osk extraction test failed');
        }
        $zip->close();

        //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        //Move the uploaded file
        $new_target = "../../accounts/".$_SESSION["userId"]."/".basename($_FILES["fileToUpload"]["name"]);
        rename($target_file,$new_target);
        //removeFolder($target_dir."export");
        rrmdir($target_dir."export");
        //TODO add success message
        header('Location:../../editProfile.php?block=skin&success=0');
    } else {
        //echo "Sorry, there was an error uploading your file.";
        error('9','Cannot move the uploaded file');
        exit;
    }
}
 ?>
