<?php
session_start();
//include 'php/analytics.php';
  include 'php/progress/functions.php';
  require_once 'php/errors.php';

  //Placeholder
  $placeholder = array(
    'beatmapSetId' => 0,
    'BFN' => "placeholder",
    'playerId' => 0,
    'currentStatut' => 0,
    'date' => '26-11-2018',
    'duration' => 0
  );

  if(strcmp($_GET['id'],0) == 0){
    $replayDATA = $placeholder;
  }else{
    $replayDATA = getRequestArray($_GET['id']);
    if(empty($replayDATA)){
      header("Location:view.php?id=".$_GET['id']);
    }
  }



  //Get beatmap name
  $beatmapName = base64_decode($replayDATA['BFN']);
  $beatmapName = str_replace(".osz", "", $beatmapName);
  $tab = explode(" ",$beatmapName);
  unset($tab[0]);
  $beatmapName = implode(" ",$tab);

  //Beatmap image
  $btUrl = "https://b.ppy.sh/thumb/".$replayDATA['beatmapSetId']."l.jpg";

  $date = new DateTime($replayDATA['date']);

  //User profile img
  $userImgURL = "https://a.ppy.sh/".$replayDATA['playerId'];

  //calculations of the width
  if($replayDATA['currentStatut'] != 4){
    $barWidth = 20*($replayDATA['currentStatut']+1);
  }else{
    $barWidth = 99;
  }
?>

<!DOCTYPE html>
<html>

  <head>
    <title>osu!replayViewer - <?php echo $barWidth.'%' ?></title>

    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/progress.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
    <script type="text/javascript" src="js/index/upload.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <script src="js/progress/autoUpload.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>

    <!-- Timer -->
    <script>
      // Set the date we're counting down to
      var countDownDate = new Date(<?php echo "'".date_format($date, 'Y-m-d H:i:s')."'";?>).getTime();

      // Update the count down every 1 second
      var x = setInterval(function() {

        // Get todays date and time
        var d = new Date();
        var utc = d.getTime() + (d.getTimezoneOffset() * 60000); //60000

        var now = new Date(utc + (3600000*1));
        // Find the distance between now an the count down date
        var distance = now - countDownDate;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"
        document.getElementById("timer").innerHTML = days + "d " + hours + "h "
        + minutes + "m " + seconds + "s ";

        // If the count down is finished, write some text
        if (distance < 0) {
          clearInterval(x);
          document.getElementById("timer").innerHTML = "less that 1sec";
        }
      }, 1000);
    </script>

    <!-- Update page -->
    <script>
      $(document).ready(function(){
           setInterval(checkUpdate, 10000);
       });

      function checkUpdate(){
        $.ajax({
            type: "POST",
            url: "../../php/progress/checkUpdate.php",
            data: {replayId:<?php echo '"'.$_GET['id'].'"'?>},
            success: function(response){
                //Check if the status has been updated
                if(response != <?php echo $replayDATA['currentStatut']?>){
                  window.location.reload();
                }
            }
        });
      }
    </script>

  </head>

  <body>
    <?php showError(); ?>
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

    <!-- Real content -->
    <h1 id="title">View progress page</h1>

    <div id="beatmap_section">
      <img src=<?php echo $btUrl; ?> id="beatmapImg"/>
      <img src=<?php echo $userImgURL; ?> id="playerImg"/>
      <div id="text_section">
        <h2><?php echo $beatmapName; ?></h2>
        <span id="duration">Duration : <?php
          $mins = floor($replayDATA['duration'] / 60 % 60);
          $secs = floor($replayDATA['duration'] % 60);
          echo $mins.'min '.$secs;
          ?>
        </span>
        <span id="span_timer">This replay has been waiting :</span>
        <p id="timer"></p>
      </div>

    </div>

    <div id="progressBar_section">
      <div id="underline"></div>
      <div id="line" style=<?php echo "width:$barWidth%";?> ></div>
      <span><?php echo $barWidth; ?>%</span>
    </div>

    <div id="list_section">
      <h3>State list</h3>
      <?php
      if(strcmp($_GET['id'],0) == 0){
        drawStates($replayDATA['currentStatut'],1);
      }else{
        drawStates($replayDATA['currentStatut'],getClassement($_GET['id']));
      }
      ?>
    </div>

    <div id="cancel_section">
      <h3>Cancel this replay</h3>
      <h4>Drag and drop or upload the original .osr</h4>
      <form id="upload_box" action="php/progress/cancelReplay.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" oninput="submitForm()">
        <input type="hidden" name="replayId" value=<?php echo $_GET['id']; ?> >
      </form>
      <h4>If this file matches with the original, the replay will be canceled</h4>
    </div>

    <div class="spacer">
			<br>
		</div>

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
