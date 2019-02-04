<?php
	session_start();
	require 'php/navbar.php';
	include 'php/analytics.php';
	ini_set('display_errors', 0);
	include 'php/osuApiFunctions.php';
	include 'php/search/blockModel.php';

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
	  $conn = new mysqli($servername, $username, $password, $mySQLdatabase);

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

		$queryUserReplayReq = $conn->prepare("SELECT * FROM requestlist WHERE playerId=?");
		$queryUserReplayReq->bind_param("i",$playerId);

		$queryUserReplay = $conn->prepare("SELECT * FROM replaylist WHERE userId=?");
		$queryUserReplay->bind_param("i",$playerId);

		$queryUserReplayOrder = $conn->prepare("SELECT * FROM replaylist WHERE userId=? ORDER BY date DESC LIMIT ?, ?");
		$queryUserReplayOrder->bind_param("iii",$playerId,$index,$blockPerPages);

	// ******************** Functions **********************************
	function getUserId($api,$username){
		//Query the playerId from osu api
		$json = getUserJSON($username, $api);
		if(empty($json)){
			header("Location:search.php?error=1");
			exit;
		}
		return $json[0]['user_id'];
	}
?>

<!-- ********************** HTML ********************************** -->
<!DOCTYPE html>
<html>
	<head>
		<title>osu!replayViewer - Search page</title>
	  <link rel="icon" type="image/png" href="images/icon.png" />
		<link rel="stylesheet" type="text/css" href="css/search.css">
		<link rel="stylesheet" type="text/css" href="css/navbar.css">
		<link rel="stylesheet" type="text/css" href="css/footer.css">
		<link rel="stylesheet" type="text/css" href="css/loader.css">
		<meta charset="utf-8" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
		<script src="js/loader.js"></script>
	</head>

	<body>
		<div class="loaderCustom"></div>
		<!-- Top navigation bar -->
    <?php showNavbar(); ?>

		<h1 id="title">Search page</h1>

		<section id="form">
			<form action="php/search/queryReplays.php" method="get">
				<input type="search" name="playerId" placeholder="Enter osu! player name or ID"/>
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

		$inReplay = true;
		$inRequest = true;
		$inProfile = true;

		if(!isset($_GET['u']) || !isset($_GET["pn"]) || !isset($_GET['p'])){
			echo '<div id="placeholder"></div>';
		}

		if(isset($_GET['u']) || isset($_GET["pn"]) || isset($_GET['p'])){
			$playerId = $_GET["u"];
			$pageNbr = $_GET["pn"];
			$currentPage = $_GET["p"];
		}

		//Query
		if($playerId != 0){
			//show corresponding profile page (if it exists)
			if($currentPage==0){
				$query = $conn->prepare("SELECT * FROM accounts WHERE userId=? AND verificationId=\"\" AND verfIdEmail=\"\" ");
				$query->bind_param("i",$playerId);
				$query->execute();
				$result = $query->get_result();
				if($result->num_rows > 0){
					while($row = $result->fetch_assoc()){
						drawProfile($row['userId'],$row['username']);
					}
				}else{
					$inProfile = false;
				}
				$query->close();
			}


			//show pending requests
			$queryUserReplayReq->execute();
			$result = $queryUserReplayReq->get_result();
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$tab = explode(" ",$beatmapName);
					unset($tab[0]);
					$beatmapName = implode(" ",$tab);
					drawRequest($row['replayId'],$beatmapName,$row['beatmapSetId']);
				}

			}else{
				$inRequest = false;
			}
			$queryUserReplayReq->close();

			if(orderUpStars == true){
				$index = $currentPage * 5;
				$queryUserReplayOrder->execute();
				//$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId ORDER BY date DESC LIMIT $index, $blockPerPages");
				$result = $queryUserReplayOrder->get_result();
			}else{
				$queryUserReplay->execute();
				//$result = $conn->query("SELECT * FROM replaylist WHERE userId=$playerId");
				$result = $queryUserReplay->get_result();
				var_dump($result);
			}
			if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					$beatmapSetId = $row['beatmapSetId'];
					$binaryMods = $row['binaryMods'];
					$modsListing = drawMods($binaryMods);
					$beatmapName = base64_decode($row['BFN']);
					$beatmapName = str_replace(".osz", "", $beatmapName);
					$tab = explode(" ",$beatmapName);
					unset($tab[0]);
					$beatmapName = implode(" ",$tab);
					$json = getBeatmapJSON($row['beatmapId'],$apiKey);
					drawReplay($row['replayId'],$beatmapName,$row['beatmapSetId'],$json[0]['creator'],$json[0]['version'],$row['playMod'],$modsListing);
				}

			}else{
				$inReplay = false;
			}

			if(!$inReplay && !$inRequest && !$inProfile){
				header("Location:search.php?error=2");
				exit;
			}
		}

		//--- Page switch ---

		if($pageNbr > 1){
			$index = $currentPage+1;
			echo	'<div class="div-align">';

			if($currentPage != 0){
				$previous = $currentPage-1;
				$url = "./search.php?error=0&u=$playerId&pn=$pageNbr&p=$previous";
				echo	"<a href=$url title=\"Previous page\"><img class=\"img-valign\" src=\"images/arrow_button_left.png\" alt=\"\"/></a>";
			}

			echo	"<span id=\"pageNumbers\"> $index / $pageNbr </span>";

			if($currentPage != $pageNbr-1){
				$next = $currentPage+1;
				$url = "./search.php?error=0&u=$playerId&pn=$pageNbr&p=$next";
				echo	"<a href=$url title=\"Next page\"><img class=\"img-valign\" src=\"images/arrow_button_right.png\" alt=\"\" /></a>";
			}
			echo	'</div>';
		}

		end:
		?>

		<div class="spacer">
			<br>
		</div>

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
