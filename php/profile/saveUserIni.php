<?php
session_start();
if(empty($_SESSION)){
  header("Location:index.php");
}
var_dump($_POST);
//Sanitize input
$skinPost = filter_var($_POST['dim'],FILTER_SANITIZE_NUMBER_INT);

require 'replaySettings.php';

if(isset($_POST["dim"])) $dim = $_POST["dim"];
else $dim = 50;

updateIniFile('../../accounts/',$_SESSION["userId"],$customSkin,$skin,$dim,"true");

header("Location:../../editProfile.php?block=game&success=7");
exit();
 ?>
