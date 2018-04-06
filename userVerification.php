<?php
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
// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "u611457272_osu");

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
    $email = ['email'];
  }
}else{
  close($conn);
}

if(getUserInterests($userId) == $verfUserId && !empty($verfUserId)){
  $updateInfo = $conn->prepare("UPDATE accounts SET verificationId='' WHERE userId=?");
  $updateInfo->bind_param("i",$userId);
  $updateInfo->execute();
  $updateInfo->close();
  $verfUserId = '';
}

//Redirect to login in already verified
if(empty($verfUserId) && empty($verfIdEmail)){
  close($conn);
  header("Location:login.php");
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

<html>
  <head>
    <title> osu!replayViewer - verification </title>
    <link rel="stylesheet" type="text/css" href="css/userVerification.css">
    <link rel="icon" type="image/png" href="images/icon.png" />
  </head>

  <body>
    <div class="block">
      <h2> Step 1 : email verification</h2>
      <?php
      if(!empty($verfIdEmail)){
        echo '<span> Click on the link provided in the verification email </span> <br>';
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
        echo "<span> Please copy this code :";
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
      <img src=<?php echo $imgUrl ?>>
    </div>

  </body>
</html>
