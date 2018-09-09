<?php
  session_start();
  include 'php/analytics.php';

  if(empty($_SESSION['userId'])){
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

  $skinUploadError = array(
    0 => "Upload successfully finished",
    1 => "This skin has already been uploaded",
    2 => "Only .osk are allowed",
    3 => "Sorry your skin could't be uploaded",
    4 => "Your skin file name cannot contain special characters",
    5 => "Your skin file size is more that 50Mo"
  );

  $skinRemoveError = array(
    0 => "",
    1 => "This skin doesn't exists",
    2 => "Remove error"
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
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <script src="js/profile/uploadSkin.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
  </head>

  <body onload="showDim(); updateCustomSkin()">
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
          echo    '<i class="material-icons">account_circle</i> Profile</a>';
          echo  '<a href="logout.php" class="nav-link">';
          echo    '<i class="material-icons">cloud_off</i> Logout</a>';
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

    <h1 id="title"> Edit profile </h1>

    <div class="block" id="replay">
      <h2> Edit replays config </h2>

      <!-- UPLOAD SKIN -->

      <div id="uploadSkinZone">
        <h3>- Custom skin uploader -</h3>
        <form action="php/profile/uploadSkin.php" method="post" enctype="multipart/form-data" id="submit_skin">
          Select skin to upload (or drag and drop): <br>

          <?php
          if(isset($_GET['skinError'])){
            echo "<span id=\"pswError\" style=\"color:red\">".$skinUploadError[$_GET['skinError']]."</span><br>";
          }
          ?>

          <br>

          <input type="file" name="fileToUpload" id="fileToUpload"> <br>

        </form>
      </div>

      <!-- REMOVE SKIN -->

      <div id="removeSkinZone">
        <h3>- Custom skin remover -</h3>
        <form action="php/profile/removeSkin.php" method="post" enctype="multipart/form-data">

          <?php
          if(isset($_GET['removeError'])){
            echo "<span id=\"pswError\" style=\"color:red\">".$skinRemoveError[$_GET['removeError']]."</span><br><br>";
          }
          ?>

          Choose your custom skin to remove : <br>
              <?php
              //Combobox with all skins uploaded
              echo "<select id='skinsSelector2' name='skin'>";
                foreach($skins as $skin)
                {
                  if($skin == $actualSkin){
                    echo "<option value='".$skin."' selected>".$skin."</option>";
                  }else{
                    echo "<option value='".$skin."'>".$skin."</option>";
                  }
                }
                echo "</select>";
               ?>
          <input type="submit" value="Remove this skin" name="submit">
        </form>
      </div>

      <!-- REPLAY EDITION -->

      <form action="php/profile/saveUserIni.php" method="post">
        <div id="skinZone">
          <h3>- Custom skin and dim chooser -</h3>

        <?php
          if(empty($skins)){
            echo "<h2 style=\"color:red\"> You have to upload at least one skin to use this functionnality</h2>";
          }else{
            //Check box to enable custom skin
            echo 'Enable custom skin: <br>';
            echo '<span style="font-size:13px"> By default the osu!replayViewer skin is used</span><br>';
            if($customSkin == 1){
              echo '<input type="checkbox" name="customSkin" id="checkBox" oninput="updateCustomSkin()" checked>';
            }else{
              echo '<input type="checkbox" name="customSkin" oninput="updateCustomSkin()" id="checkBox">';
            }
            echo '<br><br>';

          echo "Choose your custom skin : <br>";

          //Combobox with all skins uploaded
          echo "<select id='skinsSelector' name='skin'>";
            foreach($skins as $skin)
            {
              if($skin == $actualSkin){
                echo "<option value='".$skin."' selected>".$skin."</option>";
              }else{
                echo "<option value='".$skin."'>".$skin."</option>";
              }
            }
            echo "</select>";
          }
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
    </div>



    <div class="block" id="password">
      <h2> Change password </h2>

      <form action="php/profile/changePassword.php" method="post">
        Current password : <br>
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
