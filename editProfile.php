<?php
  session_start();
  include 'php/analytics.php';
  require 'php/errors.php';
  require 'php/success.php';
  require 'php/navbar.php';

  require 'php/profile/blockManager.php';

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
    <?php showNavbar(); ?>
    <?php showError(); ?>

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
        <?php generateBlocks(); ?>

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
