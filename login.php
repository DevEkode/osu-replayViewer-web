<?php
  session_start();
  include 'php/analytics.php';
  require 'php/navbar.php';
  if(!empty($_SESSION['userId'])){
    header("Location:index.php");
  }

  $errors = array (
    0 => "",
    1 => "Your account need verification",
    2 => "The osu!id, the username or the password is invalid, ",
    3 => "The reCaptcha is invalid, please try again",
    4 => "Verification completed, you can now login",
    5 => "Please enter a number into the osu!ID field"
  );


 ?>

<html>
<head>
  <title>osu!replayViewer - Login</title>
  <link rel="icon" type="image/png" href="images/icon.png" />
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/navbar.css">
  <link rel="stylesheet" type="text/css" href="css/footer.css">
  <link rel="stylesheet" type="text/css" href="css/loader.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
  <script src="js/loader.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <!-- Cookie bar -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body>
  <div class="loaderCustom"></div>
  <!-- Top navigation bar -->
  <?php showNavbar(); ?>

<h1 id="title">Login</h1>
<form action="loginForm.php" method="post">

  <div class="container">
   <label for="uname"><b>osu!Username / osu!ID</b></label>
   <div class="tooltip">Find my osu!ID
     <img class="tooltiptext" src="images/tooltips/findOsuId.png">
   </div>
   <input type="text" placeholder="Enter your osu username or your id" name="userId"required>

   <label for="psw"><b>Password</b></label>
   <input type="password" placeholder="Enter Password" name="psw" required>
   <div class="g-recaptcha" data-sitekey="6LcYyk8UAAAAAHmsgHYvmnCIr3I6hIlKv7VWANSo"></div>

   <?php
    $baseLink = "http://osureplayviewer.xyz/";

     $error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
     if ($error_id != -1 && $error_id != 0) {
       echo '<br>';
       echo '<span style="text-align:center" class=errorText>'.$errors[$error_id];
       switch($error_id){
         case 1 :
          $link = $baseLink."userVerification.php?id=".$_GET['userId'];
          echo ' <a href='.$link.'>click here! </a></span>';
          break;
        case 2 :
          $link = $baseLink."register.php";
          echo ' <a href='.$link.'>create an account here! </a></span>';
          break;
        default :
          break;
       }
     }
   ?>
   <button type="submit">Login</button>
   <label>
     <!-- <input type="checkbox" name="remember"> Remember me -->
   </label>
  </div>

  <div class="container" style="background-color:#f1f1f1">
   <!-- <button type="button" class="cancelbtn">Cancel</button> -->
   <span class="psw">Forgot <a href="forgotPassword.php">password?</a></span>
  </div>
</form>

<div class="spacer">
  <br>
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
