<?php
  $errors = array (
    0 => "",
    1 => "Your account need verification",
    2 => "The osu!id or the username is invalid, ",
    3 => "The reCaptcha is invalid, please try again"
  );
 ?>

<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<form action="loginForm.php">
  <div class="imgcontainer">
   <img src="images/icon.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
   <label for="uname"><b>osu!ID</b></label>
   <input type="text" placeholder="Enter your osu user id" name="userId"required>

   <label for="psw"><b>Password</b></label>
   <input type="password" placeholder="Enter Password" name="psw" required>
   <div class="g-recaptcha" data-sitekey="6LcYyk8UAAAAAHmsgHYvmnCIr3I6hIlKv7VWANSo"></div>

   <?php
    $baseLink = "http://osu-replayviewer-web/";

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
   <button type="button" class="cancelbtn">Cancel</button>
   <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>
</html>
