<?php
	ini_set('display_errors', 1);

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

	//Function
	function closeUpload($conn){
		$conn->close();
		exit;
	}

	function getBeatmapJSON($md5,$api){
		$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&h=$md5");
		$json = json_decode($apiRequest, true);
		if(empty($json)){
			//header("Location:index.php?error=12");
			//closeUpload($conn);
		}
		return $json;
	}

	//Core
	$result = $conn->query("SELECT * FROM replaylist WHERE playMod<>0");

	if($result->num_rows > 0){
				while($row = $result->fetch_assoc()){
					//Updates mods for each replay
					$replayId = $row['replayId'];
					$beatmapMd5 = $row['md5'];
					$beatmapJSON = getBeatmapJSON($beatmapMd5,$apiKey);
					$mode = $beatmapJSON[0]["mode"];

					$sql = "UPDATE replaylist SET playMod='$mode' WHERE replayId='$replayId'";
					if ($conn->query($sql) === TRUE) {
						//row created
					} else {
						echo "Error: " . $sql . "<br>" . $conn->error;
						//header("Location:index.php?error=3&sqlErr=".$conn->error);
						closeUpload($conn);
					}
				}
	}
?>
