<?php
session_start();
// ******************** Variables **********************************
//--Connect to osu API --
//require_once 'secure/osu_api_key.php';
//$apiKey = $osuApiKey;


//-- Connect to mysql request database --
require 'secure/mysql_pass.php';
$servername = $mySQLservername;
$username = $mySQLusername;
$password = $mySQLpassword;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "u611457272_osu");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
	exit;
}


//**************************** Fonctions *************************
function getReplayNumber($conn){
	$result = $conn->query("SELECT COUNT(replayId) AS count FROM replaylist");

	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["count"];
		}
	}
	return $id;
}

function getUserNumber($conn){
	$result = $conn->query("SELECT COUNT(userId) AS count FROM playerlist");

	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["count"];
		}
	}
	return $id;
}

function getRandomId($conn){
	$replayNbr = getReplayNumber($conn);
	$result = $conn->query("SELECT replayId FROM replaylist ORDER BY rand()");

	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["replayId"];
		}
	}
	return $id;
}
?>


<!DOCTYPE html>
<html>

<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113523918-1"></script>
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
	  (adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-3999116091404317",
		enable_page_level_ads: true
	  });
	</script>

	<title>osu!replayViewer - A online osu replay viewer</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="icon" type="image/png" href="images/icon.png" />
</head>

<body>

<?php
  if(empty($_SESSION)){
    echo '<a href = "/register.php"><img src="images/signUp.png" class="login"></a>';
    echo '<a href="/login.php"><img src="images/login.png" class="login"></a>';
  }else{
    echo '<a href = "/userProfile.php"><img src="images/profile.png" class="login"></a>';
    echo '<a href = "/logout.php"><img src="images/logOut.png" class="login"></a>';
  }
?>

<div id="logo">
	<img src="images/logo.png" />
</div>

<form action="upload.php" method="post" enctype="multipart/form-data" id="uploadReplay">
    <h2>Select osu replay to upload (.osr): </h2>
	<h4>Drag and drop or open the explorer </h4>
    <input type="file" name="fileToUpload" id="fileToUpload"> <br>
	<input id="checkBox" type="checkbox" name="checkbox"> do not delete my replay after 30 days<br>
	<font color="#ff0066"><h4>Warning! After 30 days your replay will be deleted from the website if you do not check this box</h4></font>
    <input type="submit" value="Upload Replay" name="submit">
	<?php
		$errors = array (
			0 => "",
			1 => "File is not a osu replay",
			2 => "File has already been requested",
			3 => "Database connection error",
			4 => "Upload error",
			5 => "File has already been processed",
			6 => "Upload successful",
			7 => "You must have an osu account to upload",
			8 => "Only beatmaps below 5 min are allowed",
			9 => "The ban hammer was used for this player",
			10 => "The beatmap does not exist or was deleted from osu",
			11 => "Uploads are disabled, please come back later",
			12 => "This beatmap is not available to download"
		);

		$error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
		if ($error_id != -1) {
			echo '<br>';
			echo '<span style="text-align:center" class=errorText>'.$errors[$error_id].'</span>';
		}
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if ($id != 0){
			echo'<br>';
			echo '<a href="view.php?id='.$id.'">Click here to watch the replay</a>';
		}
		$pid = isset($_GET['pid']) ? $_GET['pid'] : 0;
		if ($pid != 0){
			echo'<br>';
			echo '<a href="progress.php?id='.$pid.'">Click here to observe the progress of your replay</a>';
		}
	?>
</form>

<div id=buttonBlock>
	<!-- <a href="/view.php?id=">
		<img src="images/rndButton.png">
	</a> -->
	<a href="/search.php">
		<img src="images/searchButton.png">
	</a>
	 <a href="https://github.com/codevirtuel/osu-replayViewer-web/issues">
		<img src="images/reportButton.png">
	</a>
</div>

<span>
	<h2>Already <?php echo getReplayNumber($conn); ?> replays recorded !</h2>
	<h2>For <?php echo getUserNumber($conn); ?> osu players registered</h2>
<span>
<footer>
	osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
	 | Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
</footer>
</body>
</html>
