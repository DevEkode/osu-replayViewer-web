<?php
//mySQL
require '../../secure/mysql_pass.php';
//connect to mysql database
$conn = new mysqli($mySQLservername, $mySQLusername, $mySQLpassword, $mySQLdatabase);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

//Check if the user has an account
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

 ?>
