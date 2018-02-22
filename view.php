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
		
		<div id=buttonBlock>
			<!-- <a href="/view.php?id="> 
				<img src="images/rndButton.png">
			</a> -->
			<a href="/replayList/<?php echo "$replayId/$replayId.mp4"?>" title="Click here if the video is not available"> 
				<img src="images/viewButton.png">
			</a>
			<!-- <a href="https://github.com/codevirtuel/osu-replayViewer-web/issues"> 
				<img src="images/reportButton.png">
			</a> -->
		</div>
	</body>
	
	<footer>
		osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
		| Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
	</footer>
</html>