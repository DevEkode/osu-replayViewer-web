<?php
include 'php/analytics.php';
require 'php/navbar.php';
//ini_set('display_errors', 1);
//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$imageOK = "images/ok.png";
$imageNOK = "images/cross.png";
$timeToVerif = 1; //day
// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}
//Variables
$errors = array(
  0 => "",
  1 => "If this email is associated with an account, you l'll receive a recovery email"
);

//Functions
function exitPage(){
  exit;
}

//Core

  //1st action
  if(isset($_POST['email']) && !isset($_GET['id']) && !isset($_GET['verf'])){

    $query = $conn->prepare("SELECT * FROM accounts WHERE email=?");
    $query->bind_param("s",$_POST['email']);
    $query->execute();
    $result = $query->get_result();
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        $userId = $row['userId'];
      }
      $verfId = uniqid('pVerf_');
      var_dump($verfId);
      $query2 = $conn->prepare("UPDATE accounts SET verfPassword=? WHERE email=?");
      $query2->bind_param("ss",$verfId,$_POST['email']);
      $query2->execute();
      $query2->close();

      require_once 'php/verificationFunctions.php';
      sendPasswordRecoveryEmail($_POST['email'],$userId,$verfId);
      header("Location:forgotPassword.php?error=1");

    }
    $query->close();
    header("Location:forgotPassword.php?error=1");
    exitPage();
  }

  //2nd action (user has cliked the link in the email)
  if(isset($_GET['id']) && isset($_GET['verf'])){

    $query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
    $query->bind_param("i",$_GET['id']);
    $query->execute();
    $result = $query->get_result();

    if($result->num_rows >0){
      while($row = $result->fetch_assoc()){
        if(strcmp($row['verfPassword'], $_GET['verf']) == 0){
          //Verfication id is OK
          $tempPasswordClear = uniqid();
          require_once 'php/verificationFunctions.php';
          sendTempPassword($row['email'],$tempPasswordClear);
          $tempPasswordHash = password_hash($tempPasswordClear,PASSWORD_BCRYPT);

          $updatePass = $conn->prepare("UPDATE accounts SET verfPassword=NULL, password=? WHERE userId=?");
          $updatePass->bind_param("si",$tempPasswordHash,$row['userId']);
          $updatePass->execute();
          $updatePass->close();

        }else{
          //verification id is NOK
          header("Location:index.php");
          exitPage();
        }
      }
    }
    $query->close();
  }

 ?>


<!DOCTYPE html>
<html>
  <head>
    <title>osu!replayViewer - Reset password</title>
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
    <script type="text/javascript" src="js/index/upload.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
    <link rel="stylesheet" type="text/css" href="css/forgotPassword.css">
  </head>

  <body>
  <div class="loaderCustom"></div>
  <!-- Top navigation bar -->
  <?php showNavbar(); ?>

  <h1 id="title"> Forgot password </h1>

  <div class="block" id="block">
  <h2>Enter your email to recover your password</h2>
  <form method="post">
    <input type="email" name="email" required><br>
    <input type="submit"><br>
    <?php
      if(isset($_GET['error'])){
        echo'<br>';
        echo "<span>".$errors[$_GET['error']]."</span>";
      }

      if(isset($_GET['id']) && isset($_GET['verf'])){
        echo '<br>';
        echo "<span> Your temporary password is ".$tempPasswordClear."</span>";
        echo "<span> You'll soon receive an email with this temporary password</span>";
        echo "<span> Please update your password in your user account options </span>";
      }
     ?>
  </form>
  </div>

  <div class="spacer">
    <br>
  </div>

  <?php showFooter() ?>
</body>
</html>
