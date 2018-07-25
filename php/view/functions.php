<?php
//mySQL
require 'secure/mysql_pass.php';
//connect to mysql database
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


function getReplayArray($replayId){
  global $conn;
  $return = array();

  $query = $conn->prepare("SELECT * FROM replaylist WHERE replayId=?");
  $query->bind_param("s",$replayId);
  $query->execute();
  $result = $query->get_result();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $return = $row;
    }
  }
  $query->close();
  return $return;
}

function generateYoutubeLink($youtubeId){
  $URL = "https://www.youtube.com/embed/$youtubeId?rel=0&amp;showinfo=0";
  return $URL;
}

function generateUserImageLink($userId){
  $userImgURL = "https://a.ppy.sh/".$userId;
  return $userImgURL;
}

function userHasAaccount($userId){
  global $conn;

  $query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
  $query->bind_param("i",$userId);
  $query->execute();
  $result = $query->get_result();
  if($result->num_rows > 0){
    return true;
  }else{
    return false;
  }
  $query->close();
}

function generateAccountBlock($userId,$username){
  $profileImg = generateUserImageLink($userId);
  $profileURL = "https://osu.ppy.sh/u/".$userId;
  $replayProfileURL = "userProfile.php?id=".$userId;

  echo '<div id="account_block">';
  echo "  <img id=\"profile_image\" src=$profileImg />";
  echo "<a href=$profileURL class=\"account_image\"><img src=\"images/osu_logo.png\"></a>";

  if(userHasAaccount($userId)){
    echo "<a href=$replayProfileURL class=\"account_image\"><img src=\"images/icon.png\"></a>";
  }
  echo '</div>';
}

function drawMod($bin){
  $modsArray = array(1,2,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,16777216,33554432,67108864,134217728,268435456);
  $modsImage = array("NoFail","Easy","Hidden","HardRock","SuddenDeath","DoubleTime","Relax","HalfTime","Nightcore","Flashlight","Autoplay","SpunOut","Autopilot","Perfect","Key4","Key5","Key6","Key7","Key8","FadeIn","Random","Cinema","Key9","Coop","Key1","Key3","Key2");

  echo '<div id=modsBlock>';
  for($i=0;$i<count($modsArray)-1;$i++){
    $result = $modsArray[$i] & $bin;
    if($result != 0){
      $link = "images/mods/".$modsImage[$i].".png";
      echo "<img src=$link>";
    }
  }

  if($bin == 0){
    echo '<img src="images/mods/None.png">';
  }
  echo '</div>';
}



 ?>
