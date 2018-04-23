<?php
session_start();
//Password edit error
  $pswErrorArray = array(
    0 => "Password successfully updated",
    1 => "Actual password doesn't match",
    2 => "Database error",
    3 => "New password doesn't match with the verification"
  );

  $verfLink = "userVerification.php?id=".$_SESSION["userId"];
  $emailErrorArray = array(
    0 => "",
    1 => "Email successfully updated, ". "<a href=$verfLink>click here to validate this new email</a>",
    2 => "Database error"
  );

 ?>

<!DOCTYPE html>
<html>
  <head>
    <script src="js/request.js"></script>
    <title>osu!replayViewer - edit profile</title>
    <link rel="stylesheet" type="text/css" href="css/editProfile.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body>
    <a href="userProfile.php"><img src="images/back.png" class="back"></a>

    <h1> Edit profile </h1>

    <div class="block" id="password">
      <h2> Change password </h2>

      <form action="php/profile/changePassword.php" method="post">
        Actual password : <br>
        <input type="password" name="oldPassword" required /><br>
        New password : <br>
        <input type="password" name="newPassword" id="pass" required /><br>
        Retype new password : <br>
        <input type="password" name="newPasswordVerf" id="confPass" onkeyup="showCheckPass()" required /><br>
        <span style="text-shadow:1px 1px 0 #444;color:red" id="checkPass"></span><br>
        <?php
        if(isset($_GET['pswError'])){
          echo "<span id=\"pswError\">".$pswErrorArray[$_GET['pswError']]."</span><br>";
        }
        ?>
        <input type="submit" value="Submit" />
      </form>
    </div>

    <div class="block" id="email">
      <h2> Change email </h2>

      <form action="php/profile/changeEmail.php" method="post">
        New email address : <br>
        <input type="email" name="newEmail" required /><br>
        <?php
        if(isset($_GET['emailError'])){
          echo "<span id=\"emailError\">".$emailErrorArray[$_GET['emailError']]."</span><br>";
        }
        ?>
        <input type="submit" value="Submit" />
      </form>
    </div>

  </body>
</html>
