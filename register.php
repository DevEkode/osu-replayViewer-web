<?php
include 'php/analytics.php';
// ******************** Variables **********************************
$errors = array (
  0 => "",
  1 => "The reCaptcha is invalid, try again",
  2 => "This player doesn't exists",
  3 => "The user or the email is already used"
);
$error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;

//--Connect to osu API --
require 'php/osuApiFunctions.php';
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, $mySQLdatabase);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}

//------------------------------ functions ---------------------------
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

function isFormSubmitted(){
	if(isset($_POST["userId"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["cPassword"])){
		return true;
	}else {return false;}
}

function emailAlreadyUsed($conn,$email){
  $queryEmails = $conn->prepare("SELECT email FROM accounts");
  $queryEmails->execute();
  $result = $queryEmails->get_result();
  $queryEmails->close();

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      if($row['email'] == $email){
        return true;
      }
    }
  }

  return false;
}

function isAlreadyUsedInAccount($conn,$column,$value){
  $queryEmails = $conn->prepare("SELECT * FROM accounts");
  $queryEmails->execute();
  $result = $queryEmails->get_result();
  $queryEmails->close();

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      if($row[$column] == $value){
        return true;
      }
    }
  }

  return false;
}

function verifyCaptcha($secretCaptcha,$cResponse){
  //POST request
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretCaptcha&response=$cResponse";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  $result=curl_exec($ch);
  curl_close($ch);

  $json = json_decode($result, true);
  return $json['success'];
}

//----------------------------- core ----------------------------------
require_once 'secure/recaptcha.php';
if(isFormSubmitted()){
  //Check the reCaptcha
  if(!verifyCaptcha($secretCaptcha,$_POST['g-recaptcha-response'])){
    header("Location:register.php?error=1");
    exit();
  }

  //Check if the player exist in osu
  $userId = intval($_POST['userId']);
  $userJSON = getUserJSON($userId,$osuApiKey);
  if(empty($userJSON)){
    header("Location:register.php?error=2");
    exit();
  }

	//Check if the user is already registered //Check if the email is already used
  if(isAlreadyUsedInAccount($conn,'userId',$userId) || isAlreadyUsedInAccount($conn,'email',$_POST['email'])){
    header("Location:register.php?error=3");
    exit();
  }
	//create a unique id for account verification
	$verfId = uniqid('verf_');
  $verfIdEmail = uniqid('eVerf_');
	//create a new row in accounts
  $insertAccount = $conn->prepare("INSERT INTO accounts (userId, username, email, password, verificationId, verfIdEmail) VALUES (?, ?, ?, ?, ?, ?)");
  $insertAccount->bind_param("isssss",$userId,$username,$email,$password,$verfId,$verfIdEmail);

  $username = $userJSON['0']['username'];
  $email = $_POST['email'];
  $password = password_hash($_POST['password'],PASSWORD_BCRYPT);

  //Send e-mail
  require_once 'php/verificationFunctions.php';
  sendEmail($email,$username,$verfIdEmail);

  if($insertAccount->execute()){ //Insert ok
    header("Location:userVerification.php?id=".$userId);
    $insertAccount->close();
    exit();
  }


}
?>

<html>
	<head>
    <title>osu!replayViewer - Register</title>
    <link rel="icon" type="image/png" href="images/icon.png" />
		 <script src="js/request.js"></script>
     <link rel="stylesheet" type="text/css" href="css/register.css">
     <link rel="stylesheet" type="text/css" href="css/navbar.css">
     <link rel="stylesheet" type="text/css" href="css/footer.css">
     <link rel="stylesheet" type="text/css" href="css/loader.css">

     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
     <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
     <script src="js/loader.js"></script>
     <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
     <script src='https://www.google.com/recaptcha/api.js'></script>
	<head>

	<body onload="start()">
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

    <h1 id="title">Register</h1>

    <h3> Please register this form to create an account </h3>
		<form id="form" onsubmit="submitted()" method="post">
		<label>Osu user id (osu!ID):</label>
    <div class="tooltip">Find my osu!ID
      <img class="tooltiptext" src="images/tooltips/findOsuId.png">
    </div>
		<input type="text" name="userId" id="userId" onkeyup="showUsername(this.value); update()" autocomplete=off required> <span id="txtHint"></span><br>
		<label>e-mail: </label>
		<input type="email" name="email" id="email" onkeyup="showEmailValidity(); update()" required><span id="emailHint"></span><br>
    <label>confirm email: </label>
		<input type="email" name="cEmail" id="confEmail" onkeyup="showCheckEmail(); update()" required> <span id="checkEmail"></span><br>
		<label>password: </label>
		<input type="password" name="password" id="pass" onkeyup="update()" required><br>
		<label>confirm password: </label>
		<input type="password" name="cPassword" id="confPass" onkeyup="showCheckPass(); update()" required> <span id="checkPass"></span><br>
    <div class="g-recaptcha" data-sitekey="6LcYyk8UAAAAAHmsgHYvmnCIr3I6hIlKv7VWANSo" id="recaptcha" required></div>

    <?php
      if ($error_id != -1 && $error_id != 0) {
        echo "<p id=\"errorMsg\"> ".$errors[$error_id]." </p>";
      }
    ?>
    <button type="submit" id="submitButton">Create account</button>
		</form>

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
