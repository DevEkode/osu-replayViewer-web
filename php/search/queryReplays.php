<?php
include '../osuApiFunctions.php';
require '../../secure/mysql_pass.php';
require '../../secure/osu_api_key.php';

$blockPerPages = 5;
// Create connection
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

//-- Get all the Informations --

//check if the text is a number
if(is_numeric($_POST['playerId'])){
  $playerId = $_POST['playerId'];
}else{
  //convert to a numeric
  $userJSON = getUserJSON($_POST['playerId'],$osuApiKey);
  if(empty($userJSON)){
    $conn->close();
    header("Location:../../search?error=1");
  }
  $playerId = $userJSON[0]['user_id'];
}

//Generate url
$queryUserId = $conn->prepare("SELECT COUNT(*) AS nbr FROM replaylist WHERE userId=?");
$queryUserId->bind_param("i",$playerId);

$queryUserId->execute();
$queryUserId->bind_result($recordsNbr);
$row = $queryUserId->fetch();
$queryUserId->close();

$pageNbr = ceil($recordsNbr / $blockPerPages); //nbr of pages in total

$url = "../../search.php?error=0&u=$playerId&pn=$pageNbr&p=0";

$conn->close();
header("Location:".$url);
exit;

 ?>
