<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require_once './functions.php';

//--Check POST info
if(!isset($_POST)){
  header("Location:../../index.php");
}

//--Connect to MYSQL
//connect to mysql database
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//--Check current statut
$query = $conn->prepare("SELECT * FROM requestlist WHERE replayId=?");
$query->bind_param("s",$_POST['replayId']);
$query->execute();
$result = $query->get_result();
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    echo $row['currentStatut'].' '.getClassement($_POST['replayId']);
  }
}

 ?>
