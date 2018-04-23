<?php
session_start();

//-------- Connect to mysql request database ---------
  require '../../secure/mysql_pass.php';
  $servername = $mySQLservername;
  $username = $mySQLusername;
  $password = $mySQLpassword;

  $conn = new mysqli($servername, $username, $password, "u611457272_osu");
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    header("Location:../../index.php?error=3");
    exit;
  }

  function closeConn(){
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

    header("Location:../../editProfile?emailError=1");
    closeConn();
  }else{
    header("Location:../../editProfile?emailError=2");
    closeConn();
  }
  $query->close();
 ?>
