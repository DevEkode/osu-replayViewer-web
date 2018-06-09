<?php
session_start();
if(empty($_SESSION)){
  header("Location:index.php");
}

function error($error_code){
  header("Location:../../editProfile.php?removeError=".$error_code);
  exit();
}

  $userURL = "../../accounts/".$_SESSION["userId"]."/";
  $skinToRemove = $_POST["skin"];

  //Check if this skin exists
  if(!file_exists($userURL.$skinToRemove)){
    error(1);
  }

  //Delete the file
  if(unlink($userURL.$skinToRemove)){
    error(0);
  }else{
    error(2);
  }

 ?>
