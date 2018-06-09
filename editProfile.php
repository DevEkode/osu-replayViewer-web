<?php
  session_start();
  if(empty($_SESSION)){
    header("Location:index.php");
  }

  require 'php/profile/replaySettings.php';

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

  //Query user information
  checkUserFile($_SESSION["userId"]);
  $skins = listAllSkins($_SESSION["userId"]);
  $customSkin = getIniKey($_SESSION["userId"],"enable");
  $actualSkin = getIniKey($_SESSION["userId"],"fileName");
  $actualDim = getIniKey($_SESSION["userId"],"dim");
 ?>

<!DOCTYPE html>
<html>
  <head>
    <script src="js/request.js"></script>
    <script src="js/editProfile.js"></script>
    <title>osu!replayViewer - edit profile</title>
    <link rel="stylesheet" type="text/css" href="css/editProfile.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body onload="showDim(); updateCustomSkin()">
    <a href="userProfile.php"><img src="images/back.png" class="back"></a>

    <h1> Edit profile </h1>

    <div class="block" id="replay">
      <h2> Edit replays config </h2>

      <h3>- Custom skin and dim chooser -</h3>
      <form>

        <div id="skinZone">
        <?php
        //Check box to enable custom skin
        if($customSkin == "true"){
          echo '<input type="checkbox" name="customSkin" id="checkBox" oninput="updateCustomSkin()" checked> Enable custom skin <br>';
        }else{
          echo '<input type="checkbox" name="customSkin" oninput="updateCustomSkin()" id="checkBox"> Enable custom skin <br>';
        }

        ?>
        <br>
        Choose your custom skin : <br>
            <?php
            //Combobox with all skins uploaded
            echo "<select id='skinsSelector' name='skin'>";
              foreach($skins as $skin)
              {
                echo "<option value='".$skin."'>".$skin."</option>";
              }
              echo "</select>";
             ?>
        </div>

        <div id="dimZone">
         Background dim value : <span id="dimValue"></span><br>
         <br>
          <input type="range" min="0" max="100" value=<?php echo $actualDim ?> class="slider" oninput="showDim()" name="dim" id="dimRange"> <br>

        <br>
        Background dim preview : <br>
          <img src="images/preview.jpg" id="dimPreview"></img>
        </div>

        <br>
        <input type="submit" value="Save all modifications" />
      </form>

      <br>

      <div id="uploadSkinZone">
        <h3>- Custom skin uploader -</h3>
        <form action="php/profile/uploadSkin.php" method="post" enctype="multipart/form-data">
          Select skin to upload (or drag and drop): <br>
          <br>

          <input type="file" name="fileToUpload" id="fileToUpload"> <br>
          <input type="submit" value="Upload Skin" name="submit">
        </form>
      </div>
    </div>



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
