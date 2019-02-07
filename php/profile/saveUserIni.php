<?php
//require_once 'ini.class.php';
require_once 'replaySettings.php';

session_start();

if(empty($_SESSION)){
  header("Location:index.php");
}
var_dump($_POST);
//Sanitize input
$skinPost = filter_var($_POST['dim'],FILTER_SANITIZE_NUMBER_INT);

if(isset($_POST["dim"])) $dim = $_POST["dim"];
else $dim = 50;

//Check ini file
$ini_dir = '../../accounts/'.$_SESSION["userId"].'/'.$_SESSION["userId"].'.ini';
$ini2 = new Ini();
$ini2->read($ini_dir);

$ini2->set('osu','dim',$dim);
$ini2->write($ini_dir);

header("Location:../../editProfile.php?block=game&success=7");
exit();
 ?>
