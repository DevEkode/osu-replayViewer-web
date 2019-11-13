<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require 'php/osuApiFunctions.php';

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
  header("Location:index.php?error=3");
  exit;
}

//Get replays
$query = $conn->prepare("SELECT * FROM replaylist");
$query->execute();
$result = $query->get_result();
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){

    //Gatter info
    $playMod = $row['playMod'];
    $binaryMods = $row['binaryMods'];


  }
}
$query->close();

 ?>
