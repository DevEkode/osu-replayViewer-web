<!DOCTYPE html>
<!-- récupération d'info -->
<?php
	require_once 'secure/mysql_pass.php';
	$servername = "mysql.hostinger.fr";
    $username = "u611457272_code";
    require_once 'secure/mysql_pass.php';
    $password = $mySQLpassword;
	
	//connect to mysql database
	$conn = new mysqli($servername, $username, $password, "u611457272_osu");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	//get the replayId variable in the header
	$replayId = $_GET['id'];
	
	//get all the needed info
	$result = $conn->query("SELECT * FROM replaylist WHERE replayId='$replayId'");
	while ($row = $result->fetch_assoc()) {
		$beatmapId = $row['beatmapId'];
		$playerId = $row['userId'];
		$replayUploadDate = $row['date'];
		$OFN = base64_decode($row['OFN']);
		$BFN = base64_decode($row['BFN']);
		$youtubeId = $row['youtubeId'];
	}
	
	$videoPath = "replayList/".$replayId."/".$replayId.".mp4";
	
	$youtubeURL = "https://www.youtube.com/embed/$youtubeId";
?>

<!-- page html -->
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/view.css">
		<link rel="icon" type="image/png" href="images/icon.png" />
	</head> 

	<body>
		<span> 
			<?php echo $BFN ?>
		</span>
	
		<!-- <video poster="" controls>
			<source src=<?php echo $videoPath ?> type='video/mp4'>
		</video> -->
		
		
		<iframe width="1280" height="720" src=<?php echo $youtubeURL ?> frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		
		<section>
			<?php
				echo "Beatmap ID : $beatmapId<br>";
				echo "Player ID : $playerId<br>";
				echo "Upload date : $replayUploadDate";
			?>
		</section>
	</body>
	
	<footer>
		osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
		| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
	</footer>
</html>