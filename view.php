<?php
  session_start();
  require 'php/view/functions.php';
  require 'php/osuApiFunctions.php';
  require 'secure/osu_api_key.php';

  $replayDATA = getReplayArray($_GET['id']);

  //Check if the replay exist
  if(empty($replayDATA)){
    header("Location:index.php");
  }

  $userJSON = getUserJSON($replayDATA['userId'],$osuApiKey);

  $urlRaw = "/replayList/".$_GET['id']."/".$_GET['id'].".mp4";
  if(file_exists($urlRaw)){
    $showRaw = true;
    $videoURL = $urlRaw;
  }else{
    $showRaw = false;
    $videoURL = "http://www.youtube.com/v/".generateYoutubeLink($replayDATA['youtubeId']);
  }

  $osrUrl = "/replayList/".$_GET['id']."/".rawurlencode($replayDATA['OFN']);
  if(file_exists($osrUrl)){
    $showOsr = true;
  }else{
    $showOsr = false;
  }

  $BFN = base64_decode($replayDATA['BFN']);
  $BFN = str_replace(".osz",'', $BFN);
  $BFN = str_replace($replayDATA['beatmapSetId'],'', $BFN);

  $twitterText = 'Come and see my osu! performace on '.$BFN.' - http://osureplayviewer.xyz/view.php?id='.$_GET['id'];
  $twitterURL = '"'."https://twitter.com/intent/tweet?text=".$twitterText.'"';

  $facebookURL = '"'."https://www.facebook.com/plugins/share_button.php?href=".urlencode('http://osureplayviewer.xyz/view.php?id='.$_GET['id'])."&layout=button&size=large&mobile_iframe=true&width=91&height=28&appId".'"';

  $redditURL = '"'."http://www.reddit.com/submit?url=".urlencode('http://osureplayviewer.xyz/view.php?id='.$_GET['id']).'"';
?>

<!DOCTYPE html>
<html>

  <head>
    <!-- Discord metadata -->
    <meta content="osu!replayViewer - osu! replays sharing" property="og:title">
    <meta content=<?php echo '"'."View ".$userJSON[0]['username']." replay".'"'; ?> property="og:description">
    <meta content="osu!replayViewer" property="og:site_name">
    <meta content=<?php echo '"'.'https://b.ppy.sh/thumb/'.$replayDATA['beatmapSetId']."l.jpg".'"'; ?> property='og:image'>

    <title>osu!replayViewer - View <?php echo $userJSON[0]['username']; ?> replay</title>
    <link rel="icon" type="image/png" href="images/icon.png" />

    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/view.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
  </head>

  <body>
    <div class="loader"></div>
    <!-- navigation bar -->
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

    <!-- Video -->
    <h1 id="title"><?php echo $BFN;?></h1>
    <h1 id="subtitle"><?php echo $userJSON[0]['username']; ?></h1>

    <div class="first_block">

      <?php
        if(empty($replayDATA['youtubeId'])){
          echo '<video class="video" poster="" controls>';
    			echo "<source src=$urlRaw  type='video/mp4'>";
    			echo '</video>';
        }else{
          echo "<iframe class=\"videoYt\" src=".generateYoutubeLink($replayDATA['youtubeId'])." frameborder=\"1\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";
        }
      ?>
      <!-- Right content block -->
      <div class="right_content">
        <span id="section_title">Visit <?php echo $userJSON[0]['username']; ?> profiles</span>
        <!-- <img id="beatmap_image" src=<?php echo '"'.'https://b.ppy.sh/thumb/'.$replayDATA['beatmapSetId']."l.jpg".'"'; ?> /> -->
        <br>

        <?php generateAccountBlock($replayDATA['userId'],$userJSON[0]['username']); ?>

        <span id="section_title">Mods</span>
        <?php drawMod($replayDATA['binaryMods']);   ?>

        <span id="section_title">Downloads</span>
        <div id="download_section">
          <?php
            if($showRaw){
              echo "<a href=$urlRaw><img src=\"images/view/video_source.png\"/></a>";
            }else{
              echo "<img src=\"images/view/video_source.png\" class=\"disabled\"/>";
            }

            if($showOsr){
              echo "<a href=$osrUrl><img src=\"images/view/download_replay.png\"/></a>";
            }else{
              echo "<img src=\"images/view/download_replay.png\" class=\"disabled\"/>";
            }
          ?>
          <!-- <a href="#"><img src="images/view/download_skin.png"/> -->
        </div>
      </div>
    </div>

    <div class="second_block">
      <span id="section_title">Share</span>
      <div id="share_section">
        <a class="twitter-share-button" target="_blank"
        href=<?php echo $twitterURL; ?>>
        <img src="images/twitter_icon.png"/></a>

        <a class="reddit-share-button" target="_blank"
        href=<?php echo $redditURL; ?>>
        <img src="images/reddit_icon.png"/></a>

        <iframe src=<?php echo $facebookURL; ?> width="91" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>
      </div>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

    <!-- Footer -->
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
