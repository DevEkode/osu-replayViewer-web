<!DOCTYPE html>
<!-- récupération d'info -->
<?php
	require_once 'secure/mysql_pass.php';
	$password = $mySQLpassword;
	$server = "localhost";
	$username = "root";
	
	//connect to mysql database
	$conn = new mysqli($server, $username, $password, "osureplay");
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
	}
	
	$videoPath = "replayList/".$replayId."/".$replayId.".mp4";
?>

<!-- page html -->
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/view.css">
	</head> 

	<body>
		<span> 
			<?php echo $OFN ?>
		</span>
	
		<video poster="" controls>
			<source src=<?php echo $videoPath ?> type='video/mp4'>
			<!-- <source src="terracid.mp4"  type='video/mp4'> -->
		</video>
		
		<section>
			<?php
				echo "Beatmap ID : $beatmapId<br>";
				echo "Player ID : $playerId<br>";
				echo "Upload date : $replayUploadDate";
			?>
		</section>
	</body>
</html>