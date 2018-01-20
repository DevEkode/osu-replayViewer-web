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
	}
?>

<!-- page html -->
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/view.css">
	</head> 

	<body>
		<span> 
			Replay <?php echo $replayId ?>
		</span>
	
		<video poster="" controls>
			<source src="terracid.flv" type='video/flv'>
			<!-- <source src="terracid.mp4"  type='video/mp4'> -->
		</video>
		
		<section>
			
		</section>
	</body>
</html>