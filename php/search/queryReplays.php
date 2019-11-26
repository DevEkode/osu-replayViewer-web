<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
include '../osuApiFunctions.php';


$osuApiKey = getenv('OSU_KEY');

$blockPerPages = 5;
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

//-- Get all the Informations --

//check if the text is a number
if(is_numeric($_GET['playerId'])){
  $playerId = $_GET['playerId'];
}else{
  //convert to a numeric
  $userJSON = getUserJSON($_GET['playerId'],$osuApiKey);
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
