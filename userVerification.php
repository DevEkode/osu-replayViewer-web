<?php
session_start();
// ******************** Variables **********************************
//--Connect to osu API --
require 'php/osuApiFunctions.php';
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

$imageOK = "images/ok.png";
$imageNOK = "images/cross.png";
$timeToVerif = 1; //day
// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

if(isset($_GET['id'])){
  $userId = $_GET['id'];
}else{
  close($conn);
}

// ******************** Functions **********************************
function getUserInterests($userId){
	$page = file_get_contents('https://osu.ppy.sh/users/'.$userId);
	preg_match("/\"interests\":\".*\",\"occupation\"/", $page, $output_array);
	if(!empty($output_array)){
		$web = explode("\"", $output_array[0]);
		return $web[3];
	}else{
		return "";
	}
}

function close($conn){
  header("Location:index.php");
}

// ******************** Core **********************************
$queryInfos = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
$queryInfos->bind_param("s",$userId);
$queryInfos->execute();
$result = $queryInfos->get_result();
$queryInfos->close();

if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $username = $row['username'];
    $verfUserId = $row['verificationId'];
    $verfIdEmail = $row['verfIdEmail'];
    $email = $row['email'];
    $date = $row['date'];
    $canBeDeleted = $row['canBeDeleted'];
  }
}else{
  close($conn);
}

//get the maximum date
$date2 = new DateTime($date);
date_add($date2,date_interval_create_from_date_string("1 day"));

if(getUserInterests($userId) == $verfUserId && !empty($verfUserId)){
  $updateInfo = $conn->prepare("UPDATE accounts SET verificationId='' WHERE userId=?");
  $updateInfo->bind_param("i",$userId);
  $updateInfo->execute();
  $updateInfo->close();
  $verfUserId = '';
}

//Redirect to login in already verified
if(empty($verfUserId) && empty($verfIdEmail)){
  $query = $conn->prepare("UPDATE accounts SET canBeDeleted=0 WHERE userId=?");
  $query->bind_param("i",$_GET['id']);
  $query->execute();
  $query->close();

  close($conn);
  if(!empty($_SESSION["userId"])){
    header("Location:index.html");
  }else{
    header("Location:login.php?error=4");
  }
  exit;
}

//prepare Variables
$profileUrl = "https://osu.ppy.sh/users/".$userId;

//email verification statut
if(empty($verfIdEmail)){
  $statutEmail = "Already verified";
}else{
  $statutEmail = "Verification needed";
}

//user verification statut
if(empty($verfUserId)){
  $statutUser = "Already verified";
}else{
  $statutUser = "Verification needed";
}



 ?>

<!DOCTYPE html>
<html>
  <head>
    <title> osu!replayViewer - verification </title>
    <link rel="stylesheet" type="text/css" href="css/userVerification.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
    <link rel="icon" type="image/png" href="images/icon.png" />


  <!-- Countdown script form w3school.com -->
  <script>
    // Set the date we're counting down to
    var countDownDate = new Date(<?php echo "'".date_format($date2, 'Y-m-d H:i:s')."'";?>).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get todays date and time
      var now = new Date().getTime();

      // Find the distance between now an the count down date
      var distance = countDownDate - now;

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
        document.getElementById("timer").innerHTML = "EXPIRED";
      }
      }, 1000);
    </script>
  </head>

  <body>
    <div class="loader"></div>
    <!-- Top navigation bar -->
    <div class="top-nav">
      <div class="floatleft">
        <a href="search.php" class="nav-link">
          <i class="material-icons">search</i> Search</a>
        <a href="#news" class="nav-link">
          <i class="material-icons">question_answer</i> FAQ</a>
      </div>

      <a href="index.html" id="logo">
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

    <?php
    if($canBeDeleted){
      echo '<div class="block">';
      echo    '<h2>Time left to finish the verification :</h2>';
      echo    '<span>after this time this account will be deleted</span>';
      echo    '<p id="timer"></p>';
      echo '</div>';
    }
    ?>

    <div class="block">
      <h2> Step 1 : email verification</h2>
      <?php
      if(!empty($verfIdEmail)){
        echo '<span> Click on the link provided in the verification email </span> <br>';
        echo '<form action="php/sendVerificationEmail.php" method="get">';
        echo  "<input type=\"hidden\" name=\"userId\" value=$userId>";
        echo   '<input type="submit" value="Send another verification email">';
        echo '</form>';
        $imgUrl = $imageNOK;
      }else{
        $imgUrl = $imageOK;
      }
      ?>
      <h3> Statut : <?php echo $statutEmail; ?></h3><br>
      <img src=<?php echo $imgUrl ?>>
    </div>

    <div class="block">
    <h2> Step 2 : user verification </h2>
      <?php
      if(!empty($verfUserId)){
        echo "<span> Please copy this code : </span>";
        echo "<input type=\"text\" value=$verfUserId id=\"myInput\" readonly=\"readonly\">";
        echo "<br>Into your interests field on your osu profile page.";
        echo "<br>And click Refresh";
        echo "<br>";
        $imgUrl = $imageNOK;
      }else{
        echo "<span> you can now delete this code from your interests field </span> <br>";
        $imgUrl = $imageOK;
      }
      ?>
      <h3> Statut : <?php echo $statutUser; ?></h3><br>
      <img src=<?php echo $imgUrl ?> />
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
