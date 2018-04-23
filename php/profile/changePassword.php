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
  	header("Location:index.php?error=3");
  	exit;
  }

  function closeConn(){
    $conn->close();
    exit;
  }
  //-------- core ---------
  //Check if the new password match
  //var_dump($_POST['newPassword'], $_POST['newPasswordVerf']);
  if(strcmp($_POST['newPassword'], $_POST['newPasswordVerf']) !== 0){
    header("Location:../../editProfile.php?pswError=3");
    closeConn();
  }

  //Check if the actual password match
  $query = $conn->prepare("SELECT password FROM accounts WHERE userId=?");
  $query->bind_param("i",$_SESSION["userId"]);
  $query->execute();
  $result = $query->get_result();

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      $passwordHash = $row['password'];
    }
  }
  $query->close();

  if(password_verify($_POST['oldPassword'],$passwordHash)){
    //Change password
    $newPasswordHash = password_hash($_POST['newPassword'],PASSWORD_BCRYPT);

    $query = $conn->prepare("UPDATE accounts SET password=? WHERE userId=?");
    $query->bind_param("si",$newPasswordHash,$_SESSION["userId"]);
    if($query->execute()){
      $query->close();
      header("Location:../../editProfile.php?pswError=0");
      closeConn();
    }else{
      header("Location:../../editProfile.php?pswError=2");
      closeConn();
    }

  }else{
    header("Location:../../editProfile.php?pswError=1");
    closeConn();
  }
 ?>
