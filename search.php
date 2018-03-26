<!DOCTYPE html>
<?php
	ini_set('display_errors', 1);

	//********************* Variables **********************************
	global $orderUpStars;
	$orderUpStars = true;
	$blockPerPages = 5;

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

		//--Connect to osu API --
		require_once 'secure/osu_api_key.php';
		$apiKey = $osuApiKey;

		//Prepared statements
		$queryUserId = $conn->prepare("SELECT COUNT(*) AS nbr FROM replaylist WHERE userId=?");
		$queryUserId->bind_param("i",$playerId);

		$queryUserReplay = $conn->prepare("SELECT * FROM requestlist WHERE playerId=?");
		$queryUserReplay->bind_param("i",$playerId);

	// ******************** Functions **********************************
	function getBeatmapJSON($beatmapId,$api){
		$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&b=$beatmapId");
		$json = json_decode($apiRequest, true);
		return $json;
	}

	function getUserJSON($username, $api){
		$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$api&u=$username");
		$json = json_decode($apiRequest, true);
		return $json;
	}

	function getUserId($api,$username){
		//Query the playerId from osu api
		$json = getUserJSON($username, $api);
		if(empty($json)){
			header("Location:search.php?error=1");
			exit;
		}
		return $json[0]['user_id'];
	}

	function drawMods($bin){
		$modsArray = array(1,2,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,16777216,33554432,67108864,134217728,268435456);
		$modsName = array("NF","EZ","HD","HR","SD","DT","RL","HT","NC","FL","AT","SO","AP","PF","4K","5K","6K","7K","8K","FI","RD","CM","9K","COOP","1K","3K","2K");
		$string = "";
		if($bin != 0){
			$string = "Mods : ";
		}

		for($i=0;$i<count($modsArray)-1;$i++){
			$result = $modsArray[$i] & $bin;
			if($result != 0){
				$string = $string.$modsName[$i].",";
			}
		}

		return substr($string, 0, -1);
	}
?>

<!-- ********************** HTML ********************************** -->

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

		<link rel="stylesheet" type="text/css" href="css/search.css">
		<meta charset="utf-8" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<link rel="icon" type="image/png" href="images/icon.png" />
	</head>

	<body>

		<section id="form">
			Enter the osu player name or id :
			<form action="./search.php?error=0" method="post">
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
		if(isset($_POST['SubmitButton']) || $_GET['u'] != 0){ //check if form was submitted
			//form submited
		}else{
			//form not submitted --> exit
			goto end;
		}

		//Get the post information
		$playerId = $_POST['playerId'];
		if($_POST['choice'] == "username"){
			$playerId = getUserId($apiKey,$playerId);
		}

		//Avoid SQL Injection
		$playerId = intval($playerId);

		$inReplay = true;
		$inRequest = true;

		//Calculation
		$queryUserId->execute();
		$queryUserId->bind_result($recordsNbr);
  	$row = $queryUserId->fetch();
		$queryUserId->close();

		$pageNbr = ceil($recordsNbr / $blockPerPages); //nbr of pages in total

		if(!isset($_GET['u']) && !isset($_GET["pn"]) && !isset($_GET['p'])){
			header("Location:search.php?error=0&u=$playerId&pn=$pageNbr&p=0");
		}else{
			$playerId = $_GET["u"];
			$pageNbr = $_GET["pn"];
			$currentPage = $_GET["p"];
		}

		//Query
		if($playerId != 0){
			$queryUserReplay->execute();
			$queryUserReplay->store_result();
			if($queryUserReplay->num_rows > 0){
				while($row = $queryUserReplay->fetch()){
					$beatmapSetId = $row['beatmapSetId'];
					$beatmapId = $row['beatmapId'];
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$replayId = $row['replayId'];
					$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
					$replayUrl = "http://osureplayviewer.xyz/progress.php?id=$replayId";
					echo "<a class='requestContent' href=$replayUrl>";
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
			$queryUserReplay->close();

			if(orderUpStars == true){
				$index = $currentPage * 5;
				$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId ORDER BY date DESC LIMIT $index, $blockPerPages");
			}else{
				$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId");
			}
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapSetId = $row['beatmapSetId'];
					$beatmapId = $row['beatmapId'];
					$binaryMods = $row['binaryMods'];
					$modsListing = drawMods($binaryMods);
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$json = getBeatmapJSON($beatmapId,$apiKey);
					$stars = $json[0]['difficultyrating'];
					$stars = floor($stars * 100) / 100;
					$diff = $json[0]['version'];
					$replayId = $row['replayId'];
					$url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
					$replayUrl = "http://osureplayviewer.xyz/view.php?id=$replayId";

					//play mod
					switch($row['playMod']){
						case 0 : $modUrl = "images/osuStdr.png"; break;
						case 1 : $modUrl = "images/osuTaiko.png"; break;
						case 2 : $modUrl = "images/osuCTB.png"; break;
						case 3 : $modUrl = "images/osuMania.png"; break;
						case 4 : $modUrl = ""; break;
						default : $modUrl = ""; break;
					}

					echo "<a class='content' href=$replayUrl>";
					echo 	'<div id="anim">';
					echo 		"<img src=$url>";
					echo 	'</div>';
					echo	'<div id="alignRight">';
					echo		"<img src=$modUrl>";
					echo	'</div>';
					echo	"<h3>$beatmapName</h3>";
					echo 	"<h4>Stars : $stars &nbsp;&nbsp;&nbsp; Difficulty : $diff &nbsp;&nbsp;&nbsp; $modsListing</h4>";
					echo	"<span></span>";
					echo "</a>";
				}

			}else{
				$inReplay = false;
			}

			if(!$inReplay && !$inRequest){
				header("Location:search.php?error=2");
				exit;
			}
		}

		if($pageNbr > 1){
			$index = $currentPage+1;
			echo	'<div class="div-align">';

			if($currentPage != 0){
				$previous = $currentPage-1;
				$url = "./search.php?error=0&u=$playerId&pn=$pageNbr&p=$previous";
				echo	"<a href=$url title=\"Previous page\"><img class=\"img-valign\" src=\"images/arrow_button_left.png\" alt=\"\"/></a>";
			}

			echo	"<span> $index / $pageNbr </span>";

			if($currentPage != $pageNbr-1){
				$next = $currentPage+1;
				$url = "./search.php?error=0&u=$playerId&pn=$pageNbr&p=$next";
				echo	"<a href=$url title=\"Next page\"><img class=\"img-valign\" src=\"images/arrow_button_right.png\" alt=\"\" /></a>";
			}
			echo	'</div>';
		}

		end:
		?>

		<footer>
			osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
			| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
		</footer>
	</body>


</html>
