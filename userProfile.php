<?php
session_start();

//get the session info
if(empty($_SESSION)) header("Location:index.php");
$userId = $_SESSION["userId"];

$osuProfileLink = "https://osu.ppy.sh/users/"+$userId;
$profileImg = "";
 ?>
