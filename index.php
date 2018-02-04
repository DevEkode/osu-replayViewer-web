<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="icon" type="image/png" href="images/icon.png" />
</head> 

<body>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-113523918-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-113523918-1');
</script>

<div id="logo">
	<img src="images/logo.png" />
</div>

<form action="upload.php" method="post" enctype="multipart/form-data" id="uploadReplay">
    <h2>Select osu replay to upload (.osr): </h2>
	<h4>Drag and drop or open the explorer </h4>
    <input type="file" name="fileToUpload" id="fileToUpload"> <br>
    <input type="submit" value="Upload Replay" name="submit">
	<?php
		$errors = array (
			0 => "",
			1 => "File is not a osu replay",
			2 => "File has already been requested",
			3 => "Database connection error",
			4 => "Upload error",
			5 => "File has already been processed",
			6 => "Upload successful",
			7 => "You must have an osu account to upload"
		);

		$error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
		if ($error_id != -1) {
			echo '<br>';
			echo '<span style="text-align:center" class=errorText>'.$errors[$error_id].'</span>';
		}
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
		if ($id != 0){
			echo'<br>';
			echo '<a href="view.php?id='.$id.'">Click here to watch the replay</a>';
		}
	?>
</form>

<div id=buttonBlock>
	<!--<a href="/view.php"> 
		<img src="images/viewButton.png">
	</a> -->
	<a href="/search.php"> 
		<img src="images/searchButton.png">
	</a>
	 <a href="https://github.com/codevirtuel/osu-replayViewer-web/issues"> 
		<img src="images/reportButton.png">
	</a>
</div>





<footer>
	osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
	 | Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
</footer>
</body>
</html>
