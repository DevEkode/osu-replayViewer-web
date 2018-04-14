<?php
session_start();

//-------- Connect to mysql request database ---------
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$conn = new mysqli($servername, $username, $password, "u611457272_osu");
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//***************** Functions **********************
function getDBInfo($conn,$userId){
  $query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
  $query->bind_param("i",$userId);
  $query->execute();
  $result=$query->get_result();

  while($row=$result->fetch_assoc()){
    $username = $row['username'];
  }
  $query->close();
  return $username;
}

function getReplayInfo($conn,$userId){
  $array = array();

  $query = $conn->prepare("SELECT * FROM replaylist WHERE userId=?");
  $query->bind_param("i",$userId);
  $query->execute();
  $result=$query->get_result();

  while($row=$result->fetch_assoc()){
    $replayId = $row['replayId'];
    if(count($array) < 8){
      array_push($array,$replayId);
    }
  }
  $query->close();
  return $array;
}

function getReplayBTid($conn,$replayId){
  $beatmapId = "";

  $query = $conn->prepare("SELECT * FROM replaylist WHERE replayId=?");
  $query->bind_param("s",$replayId);
  $query->execute();
  $result=$query->get_result();

  while($row=$result->fetch_assoc()){
    $beatmapId = $row['beatmapSetId'];
  }
  return $beatmapId;
}

//get the session info
$isLogged = false;
$username = "unknown";

if(!empty($_SESSION)){
  $isLogged=true;
  $userId = $_SESSION["userId"];
} else{
  if(!isset($_GET['id'])) header("Location:index.php");
  $userId=$_GET['id'];
}

//check if the player exist un DB
$username=getDBInfo($conn,$userId);
if(empty($username)){
  header("Location:index.php");
}

$osuProfileLink = "https://osu.ppy.sh/users/".$userId;
$searchPageLink = "search.php?u=".$userId;
$profileImg = "images/defaultProfilePicture.png";
$userReplayList = getReplayInfo($conn,$userId);
 ?>

<html>
  <head>
    <title>osu!replayViewer - unknown profile</title>
    <link rel="stylesheet" type="text/css" href="css/userProfile.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body>
    <block id="header" class="block">
      <img src="images/defaultProfilePicture.png" id="userImage">
      <div id="headerContent">
        <h1> <?php echo $username; ?> </h1>
        <h3> <?php echo $userId; ?> </h3>

      </div>
        <a href=<?php echo $osuProfileLink ?> id="osuImage"><img src="images/osu_logo.png"></a>
    </block>

    <block id="replayList" class="block">
      <h2> Replay library</h2>
      <?php
        foreach ($userReplayList as $replayId) {
          $imageUrl = "https://b.ppy.sh/thumb/".getReplayBTid($conn,$replayId)."l.jpg";
          $replayUrl = "view.php?id=".$replayId;
          echo "<a href=$replayUrl><img src=$imageUrl></a>";
        }
      ?>

      <h3><a href=<?php echo $searchPageLink; ?>> Show more</a></h3>
    </block>
  </body>


</html>
