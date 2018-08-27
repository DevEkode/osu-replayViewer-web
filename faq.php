<?php
  session_start();
  include 'php/analytics.php';
?>

<!DOCTYPE html>
<html>

  <head>
    <title>osu!replayViewer - A online osu replay viewer</title>
    <link rel="stylesheet" type="text/css" href="css/faq.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
  </head>

  <body>
    <div class="loader"></div>
    <!-- Top navigation bar -->
    <div class="top-nav">
      <div class="floatleft">
        <a href="search.php" class="nav-link">
          <i class="material-icons">search</i> Search</a>
        <a href="#" class="nav-link active">
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

    <h1 id="title">FAQ</h1>

    <div class="question">
      <h2 class="question_title">How long does it usually take until a Replay is uploaded?</h2>

      <span class="question_text">
        It all depends on several factors:<br>
        - the number of replays before your turn.<br>
        - their duration (maximum 10min).<br>
        - time to download the beatmap and skin (if configured).<br>

        After leaving waiting list, expect to wait about 15 minutes.
      </span>
    </div>

    <div class="question">
      <h2 class="question_title">How do I upload my skin on my replays ?</h2>

      <span class="question_text">
        1. Login to your account.<br>
        2. Go to your profile and find the "edit profile" button.<br>
        3. In the "Custom skin uploader" select your .osk file and click "Upload skin".<br>
        4. Your skin should be uploaded.
      </span>
    </div>

    <div class="question">
      <h2 class="question_title">How do I change my skin on my replays ?</h2>

      <span class="question_text">
        First read this(up question) to upload your skin.<br>
        1. Login to your account.<br>
        2. Go to your profile and find the "edit profile" button.<br>
        3. In the "custom skin and dim chooser" check the "Enable custom skin" checkbox.<br>
        4. Choose your custom skin in the list below.<br>
        5. Click on "Save all modifications".
      </span>
    </div>

    <div class="question">
      <h2 class="question_title">How do I change the background dim of my replays ?</h2>

      <span class="question_text">
        1. Login to your account.<br>
        2. Go to your profile and find the "edit profile" button.<br>
        3. Go in the "custom skin and dim chooser".<br>
        4. Here you can choose the dim and view a preview below.<br>
        5. Click on "Save all modifications".
      </span>
    </div>

    <div class="question">
      <h2 class="question_title">The youtube video of my replay has no sound, why?</h2>

      <span class="question_text">
        It's due to youtube content id bot.<br>
        Sometimes, when it detect a song copyrighted, it remove the sound of the video.<br><br>
        Solution : Check out the source .mp4 file on the download section.
      </span>
    </div>

    <div class="question">
      <h2 class="question_title">Why do I have to confirm that I'm the owner of this osu! account during registering ?</h2>

      <span class="question_text">
        To prevent your nickname from being stolen and your replays from being modified by someone else.
      </span>
    </div>

    <h3 class="mail">If your question is not in this page, please ask with this email : <a href="mailto:contact@osureplayviewer.xyz">contact@osureplayviewer.xyz</a></h3>

    <br>
    <br>
    <br>
    <br>

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
