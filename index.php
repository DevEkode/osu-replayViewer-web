<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/index.css">
</head> 

<body>

<div id="logo">
	<img src="images/logo.png" />
</div>

<form action="upload.php" method="post" enctype="multipart/form-data" id="uploadReplay">
    <h2>Select osu replay to upload (.osr): </h2>
    <input type="file" name="fileToUpload" id="fileToUpload"> <br>
    <input type="submit" value="Upload Replay" name="submit">
	<?php
		$errors = array (
			0 => "Upload successful",
			1 => "File is not a osu replay",
			2 => "File has already been requested",
			3 => "Database connection error",
			4 => "Upload error",
			5 => "File has been already processed"
		);

		$error_id = isset($_GET['error']) ? (int)$_GET['error'] : 0;
		if ($error_id != -1) {
			echo '<br>';
			echo '<span style="text-align:center" class=errorText>'.$errors[$error_id].'</span>';
		}
	?>
</form>

<div id=buttonBlock>
	<a href="view.php"> 
		<img src="images/viewButton.png">
	</a>
	<a href="search.php"> 
		<img src="images/searchButton.png">
	</a>
	<a href=""> 
		<img src="images/button.png">
	</a>
</div>





<footer>
	osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert
	 | Website created by <a href="https://osu.ppy.sh/u/3481725">codevirtuel</a>
</footer>
</body>
</html>
