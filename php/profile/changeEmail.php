<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//-------- Connect to mysql request database ---------

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    header("Location:../../index.php?error=3");
    exit;
  }

  function closeConn(){
    global $conn;
    $conn->close();
    exit;
  }

//-------- core ---------
  if(empty($_SESSION)){
    header("Location:../../index.php");
  }

  $query = $conn->prepare("UPDATE accounts SET email=? WHERE userId=?");
  $query->bind_param("si",$_POST['newEmail'],$_SESSION['userId']);
  if($query->execute()){

    $verfIdEmail = uniqid('eVerf_');
    $query2=$conn->prepare("UPDATE accounts SET verfIdEmail=? WHERE userId=?");
    $query2->bind_param("si",$verfIdEmail,$_SESSION['userId']);
    $query2->execute();
    $query2->close();

    //Send new verfication email
    require_once '../verificationFunctions.php';
    sendEmail($_POST['newEmail'],$_SESSION["username"],$verfIdEmail);

    header("Location:../../editProfile.php?success=3&block=security#email");
    closeConn();
  }else{
    header("Location:../../editProfile.php?error=6&block=security#email");
    closeConn();
  }
  $query->close();
 ?>
