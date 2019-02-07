<?php
require 'ini.class.php';
session_start();

var_dump($_POST);

//Check ini file
$ini_dir = '../../accounts/'.$_SESSION["userId"].'/'.$_SESSION["userId"].'.ini';
$ini = new Ini();
$ini->read($ini_dir);

//Sanitize data
if(!empty($_POST)){
  $music_volume = filter_var($_POST['musicVolume'],FILTER_SANITIZE_STRING);
  $effects_volume = filter_var($_POST['effectsVolume'],FILTER_SANITIZE_STRING);
}

$ini->set('osu','music_volume',$music_volume);
$ini->set('osu','effects_volume',$effects_volume);

$ini->write($ini_dir);
header('Location:../../editProfile.php?block=game&success=9');
?>