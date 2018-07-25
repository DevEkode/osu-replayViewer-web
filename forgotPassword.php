<?php
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
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
    <link rel="stylesheet" type="text/css" href="css/forgotPassword.css">
  </head>

  <body>
  <div class="loader"></div>
  <!-- Top navigation bar -->
  <div class="top-nav">
    <div class="floatleft">
      <a href="search.php" class="nav-link">
        <i class="material-icons">search</i> Search</a>
      <a href="faq.php" class="nav-link">
        <i class="material-icons">question_answer</i> FAQ</a>
    </div>

    <a href="index.php" id="logo">
      <img src="images/icon.png" />
    </a>

    <?php
      if(isset($_SESSION['userId']) && isset($_SESSION['username'])){
        $userUrl = "userProfile.php?id=".$_SESSION['userId'];
        echo '<div class="floatright">';
        echo  "<a href=$userUrl class=\"nav-link\">";
        echo    '<i class="material-icons">how_to_reg</i> Profile</a>';
        echo  '<a href="logout.php" class="nav-link">';
        echo    '<i class="material-icons">vpn_key</i> Logout</a>';
        echo '</div>';
      }else{
        echo '<div class="floatright">';
        echo  '<a href="register.php" class="nav-link">';
        echo    '<i class="material-icons">how_to_reg</i> Register</a>';
        echo  '<a href="login.php" class="nav-link">';
        echo    '<i class="material-icons">vpn_key</i> Login</a>';
        echo '</div>';
      }
    ?>
  </div>

  <h1 id="title"> Forgot password </h1>

  <div class="block" id="block">
  <h2>Enter your email account to recover your password</h2>
  <form method="post">
    <input type="email" name="email">
    <input type="submit"><br>
    <?php
      if(isset($_GET['error'])){
        echo'<br>';
        echo "<span>".$errors[$_GET['error']]."</span>";
      }

      if(isset($_GET['id']) && isset($_GET['verf'])){
        echo '<br>';
        echo "<span> Your temporary password is ".$tempPasswordClear."</span>";
        echo "<span> You I'll soon recieve an email with this temporary password</span>";
        echo "<span> Please update your password into your user account options </span>";
      }
     ?>
  </form>
  </div>

  <footer>
    <h3 class="align_center">osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert</h3>
    <div class="footer_img">
      <a href="https://discord.gg/pqvhvxx" title="join us on discord!" target="_blank">
        <img src="images/index/discord_logo.png"/>
      </a>
      <a href="https://osu.ppy.sh/community/forums/topics/697883" target="_blank">
        <img src="images/index/osu forums.png"/>
      </a>
      <a href="https://github.com/codevirtuel/osu-replayViewer-web" target="_blank">
        <img src="images/index/github_logo.png"/>
      </a>
      <a href="https://paypal.me/codevirtuel" target="_blank">
        <img src="images/index/paypal_me.png"/>
      </a>
    </div>

    <div id="created">
      <span> website created by codevirtuel <a href="https://osu.ppy.sh/u/3481725" target="_blank"><img src="images/codevirtuel.jpg"/></a></span>
    </div>
  </footer>
</body>
</html>
