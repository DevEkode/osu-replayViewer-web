<?php
  $errors = array (
    0 => "",
    1 => "Your account need verification"
  );
 ?>

<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/login.css">
</head>

<form action="action_page.php">
  <div class="imgcontainer">
   <img src="../images/icon.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
   <label for="uname"><b>osu!ID</b></label>
   <input type="text" placeholder="Enter your osu user id" name="userId" required>

   <label for="psw"><b>Password</b></label>
   <input type="password" placeholder="Enter Password" name="psw" required>

   <?php
     $error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
     if ($error_id != -1) {
       $link = "http://osu-replayviewer-web/accountsMana/userVerification.php?id="; //TODO
       echo '<br>';
       echo '<span style="text-align:center" class=errorText>'.$errors[$error_id].' <a href='.$link.'>click here! </a></span>';
     }
   ?>

   <button type="submit">Login</button>
   <label>
     <input type="checkbox" name="remember"> Remember me
   </label>
  </div>

  <div class="container" style="background-color:#f1f1f1">
   <button type="button" class="cancelbtn">Cancel</button>
   <span class="psw">Forgot <a href="#">password?</a></span>
  </div>
</form>
</html>
