<!DOCTYPE html>
<!-- récupération d'info -->
<?php
	//ini_set('display_errors', 1);

	require 'secure/mysql_pass.php';
	$servername = $mySQLservername;
	$username = $mySQLusername;
	$password = $mySQLpassword;

	//connect to mysql database
	$conn = new mysqli($servername, $username, $password, "u611457272_osu");
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	//get the replayId variable in the header
	$replayId = $_GET['id'];

	//******************** functions ******************************
	function replayExist($conn,$replayId){
		$result = $conn->query("SELECT * FROM replaylist WHERE replayId='$replayId'");
		if($result->num_rows > 0){
			return true;
		}
		else{
			return false;
		}
	}

	function draw($replayId, $conn){
		$showViewRaw = true;
		//get all the needed info
		$query = $conn->prepare("SELECT * FROM replaylist WHERE replayId=?");
		$query->bind_param("s",$replayId);
		$query->execute();
		$result = $query->get_result();
		while ($row = $result->fetch_assoc()) {
			$beatmapId = $row['beatmapId'];
			$beatmapSetId = $row['beatmapSetId'];
			$playerId = $row['userId'];
			$replayUploadDate = $row['date'];
			$OFN = base64_decode($row['OFN']);
			$BFN = base64_decode($row['BFN']);
			$youtubeId = $row['youtubeId'];
			$binaryMods = $row['binaryMods'];
			$permanent = $row['permanent'];
		}
		$query->close();

		$videoPath = "replayList/".$replayId."/".$replayId.".mp4";
		$youtubeURL = "https://www.youtube.com/embed/$youtubeId";

		//Title
		$BFNv2 = str_replace(".osz",'', $BFN);
		$BFNv2 = str_replace($beatmapSetId,'', $BFNv2);

		echo '<span>';
		echo $BFNv2;
		echo '</span>';

		//Video
		if($youtubeId != NULL){
			echo "<iframe width=\"1280\" height=\"720\" src=$youtubeURL frameborder=\"0\" allow=\"autoplay; encrypted-media\" allowfullscreen></iframe>";
		}else{
			$showViewRaw = false;
			echo '<video poster="" controls>';
			echo "<source src=$videoPath  type='video/mp4'>";
			echo '</video>';
		}

		echo '<section>';
				echo "Beatmap ID : $beatmapId<br>";
				echo "Player ID : $playerId<br>";
				echo "Upload date : $replayUploadDate <br>";
				if($permanent == 0){
					echo "Delete date : ".date('Y-m-d H:i:s', strtotime($replayUploadDate. ' + 30 days'));
				}
		echo '</section>';

		//User info block
		$query = $conn->prepare("SELECT * FROM accounts WHERE userId=?");
		$query->bind_param("i",$playerId);
		$query->execute();
		$result = $query->get_result();
		if($result->num_rows > 0){
			while($row=$result->fetch_assoc()){
				$profileURL = "userProfile.php?id=".$row['userId'];
				$userImgURL = "https://a.ppy.sh/".$row['userId'];
				echo "<a href=$profileURL class=\"block\" id=\"profileBlock\">";
				echo "<img src=$userImgURL>";
				echo '<div>';
				echo "<h3>".$row['username']."</h3>";
				echo "<h4>Click here to visit his profile</h4>";
				echo '</div>';
				echo '</a>';
			}
		}
		$query->close();

		drawMods($binaryMods);

		//info block
		$url = "/replayList/".$replayId."/".$replayId.".mp4";
		$oszUrl = "/replayList/".$replayId."/".rawurlencode($OFN);
		echo '<div id=buttonBlock>';
		if($showViewRaw){
			echo 	"<a href=$url title=\"Click here if the video is not available\">";
			echo		'<img src="images/viewButton.png">';
			echo	'</a>';
		}
		$check = "./replayList/".$replayId."/".$OFN;
		if(file_exists($check)){
			echo 	"<a href=$oszUrl title=\"Click here to download the replay file\">";
			echo		'<img src="images/download_osz.png">';
			echo	'</a>';
		}

		echo '</div>';
	}

	function drawMods($bin){
		$modsArray = array(1,2,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,16777216,33554432,67108864,134217728,268435456);
		$modsImage = array("NoFail","Easy","Hidden","HardRock","SuddenDeath","DoubleTime","Relax","HalfTime","Nightcore","Flashlight","Autoplay","SpunOut","Autopilot","Perfect","Key4","Key5","Key6","Key7","Key8","FadeIn","Random","Cinema","Key9","Coop","Key1","Key3","Key2");

		echo '<div id=modsBlock>';
		for($i=0;$i<count($modsArray)-1;$i++){
			$result = $modsArray[$i] & $bin;
			if($result != 0){
				$link = "images/mods/".$modsImage[$i].".png";
				echo "<img src=$link>";
			}
		}
		echo '</div>';
	}
?>

<!-- page html -->
<html>
	<head>
		<title>osu!replayViewer - View replay</title>
	  <link rel="icon" type="image/png" href="images/icon.png" />
			<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113523918-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-113523918-1');
		</script>

		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		  (adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-3999116091404317",
			enable_page_level_ads: true
		  });
		</script>
		<link rel="stylesheet" type="text/css" href="css/view.css">
		<link rel="icon" type="image/png" href="images/icon.png" />
	</head>

	<body>
		<?php
			if(replayExist($conn,$replayId)){
				draw($replayId,$conn);
			}
			else{
				header("Location:index.php");
				echo "This replay doesn't exist";
			}
		?>


		<footer>
			osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
			| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
		</footer>
	</body>


</html>
