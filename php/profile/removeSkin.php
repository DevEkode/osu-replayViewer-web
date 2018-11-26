<?php
session_start();
if(empty($_SESSION)){
  header("Location:index.php");
}
require 'replaySettings.php';

function error($error_code){
  header("Location:../../editProfile.php?error=".$error_code);
  exit();
}

function getIniKey2($userId,$key){
  $ini = parse_ini_file('../../accounts/'.$userId.'/'.$userId.'.ini');
  return $ini[$key];
}

function listAllSkins2($userId){
  $skins = array();
  $path = __DIR__.'/../../accounts/'.$userId;
  foreach (glob($path.'/*.osk') as $filename) {
    var_dump($filename);
    $tab = explode("/",$filename);
    array_push($skins,$tab[11]);
  }
  return $skins;
}

  $userURL = "../../accounts/".$_SESSION["userId"]."/";
  $skinToRemove = $_POST["skin"];

  //Check if this skin exists
  if(!file_exists($userURL.$skinToRemove)){
    error(12);
  }

  //update ini file
  $skins = listAllSkins2($_SESSION["userId"]);

  //Remove skin from array
  if (($key = array_search($skinToRemove, $skins)) !== false) {
    unset($skins[$key]);
  }

  if(count($skins) <= 0){
    $customSkin = 0;
    $skin = "default";
  }else{
    $customSkin = getIniKey2($_SESSION["userId"],"enable");
    $array = array_rand($skins,1);
    $skin = $skins[$array];
  }


  $dim = getIniKey2($_SESSION["userId"],"dim");
  updateIniFile('../../accounts/',$_SESSION["userId"],$customSkin,$skin,$dim,"true");

  //Delete the file
  if(unlink($userURL.$skinToRemove)){
    header('Location:../../editProfile.php?success=1');
  }else{
    error(13);
  }
 ?>
