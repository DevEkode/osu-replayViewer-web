<!DOCTYPE html>
<?php
	if(isset($_POST['SubmitButton'])){ //check if form was submitted
		$playerId = $_POST['playerId']; //get input text

		//-- Connect to mysql request database --
		$servername = "localhost";
		$username = "root";
		require_once 'secure/mysql_pass.php';
		$password = $mySQLpassword;

		// ******************** Connection **********************************
		// Create connection
		$conn = new mysqli($servername, $username, $password, "osureplay");

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			header("Location:index.php?error=3");
			exit;
		}
	}     
?>

<!-- ********************** HTML ********************************** -->

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/search.css">
		<meta charset="utf-8" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	</head> 
	
	<body>
		<section id="form">
			Enter the id of the player :
			<form action="" method="post">
				<input type="text" name="playerId"/>
				<input type="submit" name="SubmitButton"/>
			</form>
		</section>
		
		<!-- Result boxes -->
		<?php
			//Query
		if($playerId != 0){
			$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId");
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapSetId = $row['beatmapSetId'];
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$replayId = $row['replayId'];
					$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
					$replayUrl = "http://osu-replayviewer-web/view.php?id=$replayId";
					echo "<a class='content' href=$replayUrl>";
					echo 	'<div id="anim">';
					echo 		"<img src=$url>";
					echo 	'</div>';
					echo	"<h3>$beatmapName</h3>";
					echo	"<span></span>";
					echo "</a>";
				}
			
			}
		}
		?> 
	</body>
	
	<footer>
		osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
		| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
	</footer>
</html>
