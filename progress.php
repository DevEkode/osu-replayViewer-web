<?php
	ini_set('display_errors', 1);
	//get replay the id
	$replayId = $_GET['id'];

// ******************** Connection **********************************
	// Connect to mysql request database --
	require 'secure/mysql_pass.php';
	$servername = $mySQLservername;
	$username = $mySQLusername;
	$password = $mySQLpassword;


	// Create connection
	global $conn;
	$conn = new mysqli($servername, $username, $password, "u611457272_osu");

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		header("Location:index.php?error=3");
		exit;
	}

//*************************** Variables ********************************
//Max 75% - Min 5%
//0 = In queue, 1 = Begin processing, 2 = Recording ,3 = Encoding, 4 = Uploading,
$statutP = array (
			0 => 15,
			1 => 30,
			2 => 45,
			3 => 60,
			4 => 75
		);

$statutD = array (
			0 => "In queue",
			1 => "Begin processing",
			2 => "Recording",
			3 => "Encoding",
			4 => "Uploading"
		);

//*************************** Functions ********************************
function getInfo($conn,$replayId, $column){
	$queryRequests = $conn->prepare("SELECT * FROM requestlist WHERE replayId=?");
	$queryRequests->bind_param("s",$replayId);

	$queryRequests->execute();
	$result = $queryRequests->get_result();
	$queryRequests->close();

	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row[$column];
		}
	}
	return $id;
}

function getPlayerName($conn,$playerId){
	$queryPlayerName = $conn->prepare("SELECT * FROM playerlist WHERE userId=?");
	$queryPlayerName->bind_param("i",$playerId);

	$queryPlayerName->execute();
	$result = $queryPlayerName->get_result();
	$queryPlayerName->close();

	if($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			$id = $row["userName"];
		}
	}
	return $id;
}

function checkProcessFinished($conn,$replayId){
	$queryReplays = $conn->prepare("SELECT * FROM replaylist WHERE replayId=?");
	$queryReplays->bind_param("s",$replayId);

	$queryReplays->execute();
	$result = $queryReplays->get_result();
	$queryReplays->close();

	$bool = false;
	if($result->num_rows > 0){
		$bool = true;
	}
	return $bool;
}
//**************************** Core *************************************
$statut = getInfo($conn,$replayId,"currentStatut");
$beatmapSetId = getInfo($conn,$replayId,"beatmapSetId");
$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
$BFN = base64_decode(getInfo($conn,$replayId,"BFN"));
$BFN = str_replace(".osz", "", $BFN);
$userId = getInfo($conn,$replayId,"playerId");
$username = getPlayerName($conn,$userId);

$page = $_SERVER['PHP_SELF']."?id=".$replayId;
$sec = "10";

if(checkProcessFinished($conn,$replayId)){
	header("Location: https://osureplayviewer.xyz/view.php?id=".$replayId);
	$conn->close();
	exit;
}
?>

<!DOCTYPE html>
<html>

<head>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113523918-1"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-113523918-1');
	</script>

	<title><?php echo $statutP[$statut]*100/75 ?>% - osu!replayViewer - A online osu replay viewer</title>
	<link rel="stylesheet" type="text/css" href="css/progress.css">
	<link rel="icon" type="image/png" href="images/icon.png" />
	<meta http-equiv="refresh" content="<?php echo $sec?>;URL='<?php echo $page?>'">
</head>

<body>

	<section id="text">
		<img src=<?php echo $url ?>>
		<h3> <?php echo $username?> </h3>

		<h3><?php echo $BFN ?></h3>
		<h3> <?php echo $statutP[$statut]*100/75 ?>% - Current state : <?php echo $statutD[$statut] ?> </h3>
		<div id="bar" style="width:<?php echo $statutP[$statut]?>%"> </div>
	</section>

	<footer>
		osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
		 | Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
	</footer>

</body>
</html>
