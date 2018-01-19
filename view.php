<!DOCTYPE html>
<html>
<?php
	include("./class/VideoStream.php");

	//get the replay_id requested in the header
	$replay_id = $_GET['id'];
		if ($replay_id != -1) {
			//Erreur
		}
		
	$stream = new VideoStream("./replayList/".$replay_id."/".$replay_id.".mp4");
	//$stream->start();
?>
<body>
<video poster="/images/background.png" controls>
	<source src="osu! 03-08-2017 12-45-17.avi"  type='video/mp4; codecs="h264"'>
	<p>This is fallback content to display for user agents that do not support the video tag.</p>
</video>
</body>
</html>