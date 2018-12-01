<?php
  session_start();
  include 'php/analytics.php';
  require 'php/errors.php';
  require 'php/success.php';

  if(empty($_SESSION['userId'])){
    header("Location:index.php");
  }

  require 'php/profile/replaySettings.php';

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
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
    <link rel="stylesheet" type="text/css" href="bulma/css/bulma.css">
    <link rel="stylesheet" type="text/css" href="css/editProfile.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <script src="js/profile/uploadSkin.js"></script>
    <script src="js/profile/modal.js"></script>
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

    <h1 id="TopTitle"> Edit profile </h1>

    <div class="block" id="replay">

      <div class="columns">
        <div class="column is-one-quarter" id="columnBack">
          <!-- First column -->
          <aside class="menu">
            <p class="menu-label" id="itemLabel">Replay customisation</p>
            <ul class="menu-list">
              <li><a href="#" class="is-active">Skin</a></li>
              <li><a>More</a></li>
            </ul>
            <p class="menu-label" id="itemLabel">Account</p>
            <ul class="menu-list">
              <li><a>Credentials</a></li>
              <li><a>More</a></li>
            </ul>
          </aside>
        </div>

        <div class="column">
        <!-- second column -->
          <!-- Skin chooser -->
          <form action="php/profile/form_skinChooser.php" method="post" id="columnBack">
            <h1 class="title is-4">- Custom skin chooser -</h1>
            <?php showSuccess(2); ?>
            <?php
              if(empty($skins)){
                echo "<h2 style=\"color:red\">You have to upload at least one skin to use this functionnality</h2>";
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
            <br>
            <br>
            <input type="submit" value="Save all modifications" />
          </form>
          <br>


          <!-- Skin uploader -->
          <?php showSuccess(0); ?>
          <form action="php/profile/uploadSkin.php" method="post" enctype="multipart/form-data" id="columnBack">
            <h1 class="title is-4">- Custom skin uploader -</h1>
            Select skin to upload (or drag and drop): <br>
            <br>
            <input type="file" name="fileToUpload" id="fileToUpload"> <br>
          </form>

          <br>

          <!-- Skin remover -->
          <?php showSuccess(1); ?>
          <form action="php/profile/removeSkin.php" method="post" enctype="multipart/form-data" id="columnBack">
            <h1 class="title is-4">- Custom skin remover -</h1>
            Choose your custom skin to remove :<br>
                <?php
                //Combobox with all skins uploaded
                echo "<select id='skinsSelector2' class=\"select\" name='skin'>";
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
      </div>
    </div>

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
