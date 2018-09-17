<?php
//--Check POST info
if(!isset($_POST)){
  header("Location:../../index.php");
}

//--Connect to MYSQL
require '../../secure/mysql_pass.php';
//connect to mysql database
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);
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
    echo $row['currentStatut'];
  }
}

 ?>
