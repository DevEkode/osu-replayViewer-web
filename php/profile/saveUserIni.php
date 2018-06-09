<?php
session_start();
if(empty($_SESSION)){
  header("Location:index.php");
}

require 'replaySettings.php';
var_dump($_POST);

if(isset($_POST["customSkin"])) $customSkin = "true";
else $customSkin = "false";

if(isset($_POST["skin"]) && $customSkin) $skin = $_POST["skin"];
else $skin = "default";

if(isset($_POST["dim"])) $dim = $_POST["dim"];
else $dim = 50;

updateIniFile('../../accounts/',$_SESSION["userId"],$customSkin,$skin,$dim,"true");

header("Location:../../editProfile.php");
exit();
 ?>
