<?php
session_start();
require 'secure/uploadKey.php';
require 'php/errors.php';
require 'php/navbar.php';
$name='index';
include('php/analytics.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Metadatas -->
    <meta content="osu!replayViewer" property="og:title">
    <meta content="Share your osu! performance to everyone !" property="og:description">
    <meta content="osu!replayViewer" property="og:site_name">
    <meta content="http://osureplayviewer.xyz/images/icon.png" property='og:image'>
    <meta charset="UTF-8">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="osu!replayViewer">
    <meta itemprop="description" content="Share your osu! performance to everyone !">
    <meta itemprop="image" content="http://osureplayviewer.xyz/images/icon.png">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134700452-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-134700452-1');
    </script>

    <title>osu!replayViewer - A online osu replay viewer</title>
    <link rel="icon" type="image/png" href="images/icon.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- libraries -->
    <script src="lib/jquery/jquery.min.js"></script> <!-- jQuery -->
    <script src="lib/bootstrap/bootstrap.bundle.min.js"></script> <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/bootstrap.css">

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    
    <!-- javascript -->
    <script src="js/loader.js"></script>
    <script src="js/index/validateUsername.js"></script>

    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
  </head>

  <body>
    <?php showError(); ?>
    <script type="text/javascript" src="js/index/upload.js"></script>
    <div class="loaderCustom"></div>
    <!-- Modal -->
    <div id="myModal" class="modal" onmouseover="disableClear()">

      <!-- Modal content -->
      <div class="modal-content">
        <h2 class="modal_title">Informations about your replay</h2>

        <div id="replay_box">
          <div>
            <?php
              if(isset($_SESSION['beatmapSetId'])){
                $url = "https://b.ppy.sh/thumb/".$_SESSION['beatmapSetId']."l.jpg";
                echo "<img src='".$url."' alt=\"replay preview image\" />";
              }else{
                echo '<img src="images/preview.jpg" alt="replay preview image" />';
              }
            ?>
          </div>

          <div id="replay_text">
            <span id="beatmap"><span class="info_text">Beatmap :</span>
              <?php
                echo $_SESSION['beatmapName'];
              ?>
            </span>

            <span id="diff"><span class="info_text">Difficulty :</span>
              <?php
                echo $_SESSION['difficulty'];
              ?>
            </span>
            <span id="player"><span class="info_text">Player :</span>
              <?php
                echo $_SESSION['playername'];
              ?>
            </span>
            <span id="mods"><span class="info_text">Mods :</span>
              <?php
                echo $_SESSION['mods'];
              ?>
            </span>
            <span id="skin"><span class="info_text">Skin :</span>
              <?php echo $_SESSION['skinName']; ?>
            </span>
            <span id="time"><span class="info_text">Duration :</span>
              <?php
              $mins = floor($_SESSION['duration'] / 60 % 60);
              $secs = floor($_SESSION['duration'] % 60);
                echo $mins.'min '.$secs;
              ?>
            </span>
          </div>
        </div>

        <h2 class="modal_title" >Checklist : </h2>
        <div id="check_list">
          <div class="modal_item" >
            <img src="images/index/modal1.png" id="replayS"/>
            <span class="modal_caption">Replay structure</span>
          </div>

          <div class="modal_item" >
            <img src="images/index/modal2.png" id="beatmapA"/>
            <span class="modal_caption">Beatmap is available</span>
          </div>

          <div class="modal_item" >
            <?php
            if($_SESSION['replay_playerId'] != null){
              echo "<img src='"."https://a.ppy.sh/".$_SESSION['replay_playerId']."' id=\"playerA\"/>";
            }else{
              echo '<img src="images/index/modal3.png" id="playerA"/>';
            }
            ?>
            <span class="modal_caption">The player has an <br>osu account</span>
          </div>

          <div class="modal_item" >
            <img src="images/index/modal4.png" id="replayD"/>
            <span class="modal_caption">Replay is under 10min</span>
          </div>

          <div class="modal_item" >
            <img src="images/index/modal5.png" id="replayDup"/>
            <span class="modal_caption">Replay is not a duplicate</span>
          </div>

          <div class="modal_item" >
            <img src="images/index/modal6.png" id="replayW"/>
            <span class="modal_caption">Replay is not in <br>waiting list</span>
          </div>

        </div>

        <div id="replay_start">
          <?php
          if($_SESSION['replayStructure'] && $_SESSION['beatmapAvailable'] && $_SESSION['playerOsuAccount'] && $_SESSION['replayBelow10'] && $_SESSION['replayNotDuplicate'] && $_SESSION['replayNotWaiting']){
          echo '<form class="align_center" method="post" enctype="multipart/form-data" action="php/index/upload.php">';
          echo '<input id="checkBox" type="checkbox" name="checkbox"> <span id="checkboxText"> do not delete my replay after 30 days</span><br>';
          echo '<input id="checkBox" type="checkbox" name="checkboxTU"> <span id="checkboxText"> I accept the <a href="legal/TU.php?TU=replay" target="_blank">terms of uses</a></span><br>';
          echo '<input id="filename" name="filename" type="hidden" value='.'"'.$_SESSION['filename'].'"'.'>';
          echo '<input id="duration" name="duration" type="hidden" value='.'"'.$_SESSION['duration'].'"'.'>';
          echo '<input id="duration" name="keyHash" type="hidden" value='.'"'.password_hash($upload_replay_key,PASSWORD_DEFAULT).'"'.'>';
          echo '<input name="userId" type="hidden" value='.'"'.$_SESSION['replay_playerId'].'"'.'>';
          echo '<input type="submit" value="Start processing" id="start_processing">';
          echo '</form>';
          }
          ?>

          <button onclick="closeModal(); clearSession();">Cancel</button>
        </div>
      </div>

    </div>

    <div id="askUsername_modal" class="modal">

      <!-- Modal content -->
      <div class="modal-content">
        <h2 class="modal_title">Information needed !</h2>
        <h3 class="modal_par">The username for this replay doesn't exists</h3>
        <h3 class="modal_par">Please enter the username to which the replay will be assigned</h3>

        <form onsubmit="return validateName()" class="align_center" method="post" enctype="multipart/form-data" action="php/index/newUsername.php">
          <div id="userBox">
            <img src="https://a.ppy.sh/1" id="userImage"/>
            <input type="text" name="newUsername" id="newUsername" onchange="updatePicture()" placeholder="username or osu!ID" required>
            <button type="button" onclick="updatePicture()" id="newUserButton"><i class="material-icons">refresh</i></button>
          </div>

          <?php
            $newArray = array_diff_key($_SESSION,array_flip(array('username','userId')));
            $arraySerial = serialize($newArray);
            $array64 = base64_encode($arraySerial);
           ?>

          <input type="hidden" name="session" value=<?php echo "'".$array64."'" ?>>
          <input type="submit" value="Continue" id="continue_btn">
        </form>

        <div id="replay_start">
          <button onclick="closeModalUsername(); clearSession();">Cancel</button>
        </div>
      </div>

    </div>

    <!-- Activate modal view when session is full -->
    <?php
      if(isset($_SESSION['replayStructure'])){
        $string = '';
        if(!$_SESSION['replayStructure']){$string .= "setItemFalse('replayS'); ";}
        if(!$_SESSION['beatmapAvailable']){$string .= "setItemFalse('beatmapA'); ";}
        if(!$_SESSION['playerOsuAccount']){$string .= "setItemFalse('playerA'); "; }
        if(!$_SESSION['replayBelow10']){$string .= "setItemFalse('replayD'); ";}
        if(!$_SESSION['replayNotDuplicate']){$string .= "setItemFalse('replayDup'); ";}
        if(!$_SESSION['replayNotWaiting']){$string .= "setItemFalse('replayW'); ";}

        if($_SESSION['playerOsuAccount']){
          echo '<script type="text/javascript">',
               'openModal();',
               '</script>';
        }else{
          echo '<script type="text/javascript">',
               'openModalUsername();',
               '</script>';
        }


        echo "<script type=\"text/javascript\">".$string."</script>";

        if(!$_SESSION['replayStructure'] || !$_SESSION['beatmapAvailable'] || !$_SESSION['playerOsuAccount'] || !$_SESSION['replayBelow10'] || !$_SESSION['replayNotDuplicate'] || !$_SESSION['replayNotWaiting']){
          echo '<script type="text/javascript">',
               '</script>';
        }
      }
    ?>

    <!-- Top navigation bar -->
    <?php showNavbar(); ?>

    <!-- presentation -->
    <h1 id="title">osu!replayViewer</h1>

    <h2 id="slogan">Share your osu! performance to everyone !</h2>

    <div id="etapes">
      <div class="item">
        <picture>
          <img
          sizes="(max-width: 500px) 100vw, 500px"
          srcset="
          images/small/index/etape1.png 200w,
          images/medium/index/etape1.png 361w,
          images/large/index/etape1.png 500w"
          src="images/large/index/etape1.png 500w"
          alt="First step of replay processing">
        </picture>
        <span class="caption">1. Upload your replay</span>
      </div>

      <div class="item">
        <img src="images/etape2.png" alt="Etape 2 of replay processing" />
        <span class="caption">2. Wait processing time</span>
      </div>

      <div class="item">
        <img src="images/etape3.png" alt="Last step of replay processing" />
        <span class="caption">3. Share it !</span>
      </div>
    </div>

    <form action="#upload_section" class="align_center">
      <input type="submit" value="Begin !" class="button" />
    </form>

    <!-- Announcement -->
    <!--
    <div class="alert alert-warning" role="alert" style="width:50vw; margin: auto;">
    <b style="color: red">Announcement !</b><br>
    <br>
    Because of holidays and schedules issues with the development, upload and replay processing are suspended temporarily.<br>
    <br>
    <b>A note from the main developer :</b> <br>
    A lot of work is done into the core functionality of the website. This is why updates are a lot less frequent these days. <br>
    I'm trying to build new features (like adding more replay settings) and making the website more secure.<br>
    Building this website is not my all day project, I have also an IT school to fulfill. <br>
    So I hope you do understand why this takes a lot of time.<br>
        <br>
    And also, have a happy new year everyone!<br>
    <i style="color:gray">codevirtuel ~ main dev of osu!replayViewer</i>
    </div>
    -->

    <!-- Upload -->
    <section id="upload_section"> </section>

    <h2 id="upload_title">Select your osu replay to upload (.osr)</h2>
    <h2 id="upload_subtitle">Drag and drop or open the explorer</h2>

    <!-- Upload box -->
    <?php
    require_once 'php/disableUploads.php';
    require_once 'secure/admins.php';
    if(isset($_SESSION['userId']) && in_array($_SESSION['userId'],$admins)){
      $disableUploads = false;
    }

    if(!$disableUploads){
      echo '<form action="php/index/replayFileVerf.php" method="post" enctype="multipart/form-data" id="upload_box">';
      echo    '<input type="file" name="fileToUpload" id="fileToUpload" oninput="submitForm()">';
      echo '</form>';
    }else{
      echo '<div id="upload_box">';
      echo  '<h2 id=\'fileToUpload\'>🚧 Uploads are disabled for now, try again later 🚧<br>';
      echo    '<span style="font-size:10px;font-style: oblique;">We\'re sorry for that btw</span>';
      echo  '</h2>';
      echo '</div>';
    }
    ?>

    <?php showFooter() ?>
</body>

</html>
