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
  $cursorSize = filter_var($_POST['cursorSize'],FILTER_SANITIZE_STRING);
}

$ini->set('osu','cursor_size',$cursorSize);

$ini->write($ini_dir);
header('Location:../../editProfile.php?block=game&success=9');
?>