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

function generateAccountBlock($userId){
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



 ?>
