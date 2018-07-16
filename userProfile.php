<?php
session_start();
error_reporting(0);

//-------- Connect to mysql request database ---------
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$conn = new mysqli($servername, $username, $password, $mySQLdatabase);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//***************** Functions **********************
function getDBInfo($conn,$userId){
  $query = $conn->prepare("SELECT * FROM accounts WHERE userId=? AND verificationId=\"\" AND verfIdEmail=\"\" ");
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

if(isset($_GET['id'])){
  $userId = $_GET['id'];
}

//Detect the user page
if(isset($_SESSION) && !empty($_SESSION) && isset($_GET['id'])){
  if(strcmp($_SESSION["userId"],$_GET['id']) == 0){
    $isLogged=true;
    $userId = $_SESSION["userId"];
  }else{
    $isLogged=false;
    $userId = $_GET['id'];
  }
}

if(isset($_SESSION) && !empty($_SESSION) && !isset($_GET['id'])){
  $isLogged=true;
  $userId = $_SESSION["userId"];
}

//check if the player exist un DB
$username=getDBInfo($conn,$userId);
if(empty($username)){
  header("Location:index.php");
}

$osuProfileLink = "https://osu.ppy.sh/users/".$userId;
$searchPageLink = "search.php?u=".$userId;
$profileImg = "https://a.ppy.sh/".$userId;
$userReplayList = getReplayInfo($conn,$userId);
 ?>

<html>
  <head>
    <title>osu!replayViewer - <?php echo $username; ?> profile</title>
    <link rel="stylesheet" type="text/css" href="css/userProfile.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body>
    <a href="index.php"><img src="images/home.png" class="back"></a>
    <block id="header" class="block">
      <img src=<?php echo $profileImg; ?> id="userImage">
      <div id="headerContent">
        <h1> <?php echo $username; ?> </h1>
        <h3> <?php echo "osu!ID : ".$userId; ?> </h3>

      </div>
        <a href=<?php echo $osuProfileLink ?> id="osuImage"><img src="images/osu_logo.png"></a>
        <?php
        if($isLogged){
          echo '<a href="editProfile.php" id="profileImage"><img src="images/editProfile.png"></a>';
        }
        ?>
    </block>

    <?php
    if(!empty($userReplayList)){
      echo '<block id="replayList" class="block">';
      echo  '<h2> Replay library</h2>';
            foreach ($userReplayList as $replayId) {
              $imageUrl = "https://b.ppy.sh/thumb/".getReplayBTid($conn,$replayId)."l.jpg";
              $replayUrl = "view.php?id=".$replayId;
              echo "<a href=$replayUrl><img src=$imageUrl class=\"replayImg\"></a>";
            }
      echo '<br>';
      if(count($userReplayList) >= 8){
        echo  "<a href=$searchPageLink; ><img src=\"images/add.png\" class=\"showMore\"></a>";
      }
      echo '</block>';
    }
    ?>
  </body>


</html>
