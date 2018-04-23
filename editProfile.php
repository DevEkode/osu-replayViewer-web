<?php
//Password edit error
  $pswErrorArray = array(
    0 => "",
    1 => "Actual password doesn't match",
    2 => "Database error",
    3 => "New password doesn't match with the verification"
  );

 ?>

<!DOCTYPE html>
<html>
  <head>
    <script src="js/request.js"></script>
  </head>

  <h1> Edit profile </h1>
  <h3> Change password </h3>

  <form action="php/profile/changePassword.php" method="post">
    Actual password : <br>
    <input type="password" name="oldPassword" required /><br>
    New password : <br>
    <input type="password" name="newPassword" id="pass" required /><br>
    Retype new password : <br>
    <input type="password" name="newPasswordVerf" id="confPass" onkeyup="showCheckPass()" required /><span id="checkPass"></span><br>
    <?php
    if(isset($_GET['pswError'])){
      echo "<span id=\"pswError\">".$pswErrorArray[$_GET['pswError']]."</span><br>";
    }
    ?>
    <input type="submit" value="Submit" />
  </form>
</html>
