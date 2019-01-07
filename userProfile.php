<?php
session_start();
include 'php/analytics.php';
error_reporting(0);

require 'secure/osu_api_key.php';
require 'php/osuApiFunctions.php';
require 'php/navbar.php';

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

//Get the user JSONS
$userJSON = getUserJSON($userId,$osuApiKey);

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
$username=$userJSON[0]['username'];
if(empty($username)){
  header("Location:index.php");
}

$osuProfileLink = "https://osu.ppy.sh/users/".$userId;
$searchPageLink = "php/search/queryReplays.php?playerId=".$userId;
$profileImg = "https://a.ppy.sh/".$userId;
$userReplayList = getReplayInfo($conn,$userId);
 ?>

<html>
  <head>
    <title>osu!replayViewer - <?php echo $username; ?> profile</title>
    <link rel="stylesheet" type="text/css" href="css/userProfile.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body>
    <!-- Top navigation bar -->
    <?php showNavbar(); ?>

    <block id="header" class="block">
      <img src=<?php echo $profileImg; ?> id="userImage">
      <div id="headerContent">
        <h1> <?php echo $username; ?> </h1>
        <h3> <?php echo "osu!ID : ".$userId; ?> </h3>

      </div>
      <br>
        <a href=<?php echo $osuProfileLink ?> id="osuImage"><img src="images/osu_logo.png"></a>
        <?php
        if($isLogged){
          echo '<a href="editProfile.php?block=skin" id="profileImage"><img src="images/editProfile.png"></a>';
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
        echo  "<a href=$searchPageLink><img src=\"images/add.png\" class=\"showMore\"></a>";
      }
      echo '</block>';
    }
    ?>

    <br>
    <br>
    <br>
    <br>

    <footer>
      <h3 class="align_center">osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert</h3>
      <div class="footer_img">
        <a href="https://discord.gg/pqvhvxx" title="join us on discord!" target="_blank">
          <img src="images/index/discord_logo.png"/>
        </a>
        <a href="https://osu.ppy.sh/community/forums/topics/697883" target="_blank">
          <img src="images/index/osu forums.png"/>
        </a>
        <a href="https://github.com/codevirtuel/osu-replayViewer-web" target="_blank">
          <img src="images/index/github_logo.png"/>
        </a>
        <a href="https://paypal.me/codevirtuel" target="_blank">
          <img src="images/index/paypal_me.png"/>
        </a>
      </div>

      <div id="created">
        <span> website created by codevirtuel <a href="https://osu.ppy.sh/u/3481725" target="_blank"><img src="images/codevirtuel.jpg"/></a></span>
      </div>
    </footer>
  </body>


</html>
