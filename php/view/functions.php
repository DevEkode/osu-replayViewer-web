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



 ?>
