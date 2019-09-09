<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//connect to mysql database
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


function getRequestArray($replayId){
  global $conn;
  $return = array();

  $query = $conn->prepare("SELECT * FROM requestlist WHERE replayId=?");
  $query->bind_param("s",$replayId);
  $query->execute();
  $result = $query->get_result();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $return = $row;
    }
  }
  $query->close();

  //Add classement
  if(!empty($return)){
    $return['rank'] = getClassement($replayId); 
  }
  return $return;
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
}

function drawStates($currentState,$classement){
  //g = green, o = orange, r = red
  $state0 = array('orange.png','red.png','red.png','red.png','red.png');
  $state1 = array('green.png','orange.png','red.png','red.png','red.png');
  $state2 = array('green.png','green.png','orange.png','red.png','red.png');
  $state3 = array('green.png','green.png','green.png','orange.png','red.png');
  $state4 = array('green.png','green.png','green.png','green.png','orange.png');

  switch($currentState){
    case 0 : $state = $state0; break;
    case 1 : $state = $state1; break;
    case 2 : $state = $state2; break;
    case 3 : $state = $state3; break;
    case 4 : $state = $state4; break;
  }

  $url = "images/progress/";

  echo "<div><img src=$url$state[0]> <span>In position : $classement#<span><br></div>";
  echo "<div><img src=$url$state[1]> <span>Setting up recorder<span><br></div>";
  echo "<div><img src=$url$state[2]> <span>Recording<span><br></div>";
  echo "<div><img src=$url$state[3]> <span>Encoding<span><br></div>";
  echo "<div><img src=$url$state[4]> <span>Uploading to the website<span><br></div>";
}

function getClassement($replayId){
  global $conn;

  $query = $conn->prepare("SET @rank=0");
  $query2 = $conn->prepare("SELECT @rank:=@rank+1 AS rank,replayId FROM requestlist ORDER BY date ASC");
  $query->execute();
  $query2->execute();
  $result = $query2->get_result();
  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      if(strcmp($row['replayId'],$replayId) == 0){
        $classement = $row['rank'];
      }
    }
  }
  $query->close();
  $query2->close();

  return $classement;
}



?>
