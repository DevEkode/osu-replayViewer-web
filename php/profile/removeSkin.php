<?php
session_start();
if(empty($_SESSION)){
  header("Location:index.php");
}
require 'replaySettings.php';

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

  //update ini file
  $customSkin = getIniKey($_SESSION["userId"],'enable');
  $skin = "default";
  $dim = getIniKey($_SESSION["userId"],'dim');
  updateIniFile('../../accounts/',$_SESSION["userId"],$customSkin,$skin,$dim,"true");

  //Delete the file
  if(unlink($userURL.$skinToRemove)){
    error(0);
  }else{
    error(2);
  }
 ?>
