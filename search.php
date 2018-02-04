<!DOCTYPE html>
<?php
	//ini_set('display_errors', 1);
	if(isset($_POST['SubmitButton'])){ //check if form was submitted
		$playerId = $_POST['playerId']; //get input text
		//-- Connect to mysql request database --
		$servername = "mysql.hostinger.fr";
		$usernameMysql = "u611457272_code";
		require_once 'secure/mysql_pass.php';
		$password = $mySQLpassword;

		// ******************** Connection **********************************
		// Create connection
	    $conn = new mysqli($servername, $usernameMysql, $password, "u611457272_osu");

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			header("Location:index.php?error=3");
			exit;
		}
		
		//--Connect to osu API --
		require_once 'secure/osu_api_key.php';
		$apiKey = $osuApiKey;
	}     
	
	// ******************** Functions **********************************
	function getBeatmapJSON($beatmapId,$api){
		$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&b=$beatmapId");
		$json = json_decode($apiRequest, true);
	return $json;
	
	
}
?>

<!-- ********************** HTML ********************************** -->

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/search.css">
		<meta charset="utf-8" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="icon" type="image/png" href="images/icon.png" />
	</head> 
	
	<body>
	
		<section id="form">
			Enter the osu player name or id :
			<form action="/search.php?error=0" method="post">
				 <select name="choice">
					<option value="username">username</option>
					<option value="userId">user ID</option>
				</select> 
				<input type="text" name="playerId"/>
				<input type="submit" name="SubmitButton"/>
			</form>
			<?php
				$errors = array (
					0 => "",
					1 => "This player doesn't exist",
					2 => "This player id doesn't exist in the database"
				);
				
				$error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
				if ($error_id != -1 && $error_id != 0) {
					echo '<br>';
					echo '<span style="text-align:center" class=errorText>'.$errors[$error_id].'</span>';
				}
			?>
		</section>
		
		<!-- Result boxes -->
		<?php
		function getUserJSON($username, $api){
			$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$api&u=$username");
			$json = json_decode($apiRequest, true);
			return $json;
		}
		
		//Query the playerId from osu api
		if($_POST['choice'] == "username"){
			$json = getUserJSON($playerId, $apiKey);
			if(empty($json)){
				header("Location:search.php?error=1");
				exit;
			}
			$playerId = $json[0]['user_id'];
		}
		
		$inReplay = true;
		$inRequest = true;
		
		//Query
		if($playerId != 0){
			$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId");
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapSetId = $row['beatmapSetId'];
					$beatmapId = $row['beatmapId'];
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$json = getBeatmapJSON($beatmapId,$apiKey);
					$stars = $json[0]['difficultyrating'];
					$stars = floor($stars * 100) / 100;
					$diff = $json[0]['version'];
					$replayId = $row['replayId'];
					$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
					$replayUrl = "http://osureplayviewer.xyz/view.php?id=$replayId";
					echo "<a class='content' href=$replayUrl>";
					echo 	'<div id="anim">';
					echo 		"<img src=$url>";
					echo 	'</div>';
					echo	"<h3>$beatmapName</h3>";
					echo 	"<h4>Stars : $stars &nbsp;&nbsp;&nbsp; Difficulty : $diff</h4>";
					echo	"<span></span>";
					echo "</a>";
				}
			
			}else{
				$inReplay = false;
			}
			
			$result = $conn->query("SELECT * FROM requestlist WHERE playerId=$playerId");
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapSetId = $row['beatmapSetId'];
					$beatmapId = $row['beatmapId'];
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$replayId = $row['replayId'];
					$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
					$replayUrl = "http://osureplayviewer.xyz/view.php?id=$replayId";
					echo "<a class='requestContent'>";
					echo 	'<div id="anim">';
					echo 		"<img src=$url>";
					echo 	'</div>';
					echo	"<h3>$beatmapName</h3>";
					echo 	"<h4>Currently in processing queue</h4>";
					echo	"<span></span>";
					echo "</a>";
				}
			
			}else{
				$inRequest = false;
			}
			
			if(!$inReplay && !$inRequest){
				header("Location:search.php?error=2");
				exit;
			}
		}
		?> 
		<footer>
			osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
			| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
		</footer>
	</body>
	
	
</html>
