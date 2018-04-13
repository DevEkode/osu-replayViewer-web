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
  return $username;
}

function getReplayInfo($conn,$userId){
  $query = $conn->prepare("SELECT * FROM replaylist WHERE userId=?");
  $query->bind_param("i",$userId);
  $query->execute();
  $result=$query->get_result();

  while($row=$result->fetch_assoc()){
    $username = $row['username']; //TODO create an array of replays
  }
  return $username;
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
$profileImg = "images/defaultProfilePicture.png";
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
      <img src="http://via.placeholder.com/240x180">
      <img src="http://via.placeholder.com/240x180">
      <img src="http://via.placeholder.com/240x180">
      <img src="http://via.placeholder.com/240x180">
      <img src="http://via.placeholder.com/240x180">
      <img src="http://via.placeholder.com/240x180">

      <h3>Show more</h3>
    </block>
  </body>


</html>
