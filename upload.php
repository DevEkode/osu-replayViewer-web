<?php
// -- Fonctions --
function getPlayerName($fileName){ //Return player name of the replay
	$pName = explode(" ",$fileName);
	return $pName[0];
}

//--Connect to osu API --
$apiKey = "db27f0ffe486b0d734802a31bfc2deb9e8369c63";


//-- Connect to mysql request database --
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password, "osureplay");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:index.php?error=3");
}

//-- Upload check --
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_name = basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual replay osu file or fake
/*if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //osr
        $uploadOk = 1;
    } else {
        //not a osr
        $uploadOk = 0;
    }
}*/

// Allow certain file formats
if($imageFileType != "osr") {
    echo "Sorry, only osu replay files are allowed.";
    $uploadOk = 0;
	header("Location:index.php?error=1");
}

//Check if request already exists
$result = $conn->query('SELECT OFN FROM requestlist');

if($result->num_rows > 0){
	while($row = $result->fetch_assoc()){
		if($row["OFN"] == $file_name){
			echo 'file already requested';
			$uploadOk = 0;
			header("Location:index.php?error=2");
		}
	}
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
	header("Location:index.php?error=4");
// if everything is ok, try to upload file
} else {
	
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		//-- Check if player already exist in player database --
		//Sql request
		$playerName = getPlayerName($file_name);
		$result = $conn->query("SELECT userName FROM playerlist WHERE userName='$playerName'");
		if($result->num_rows == 0){
			//Player is not into database
			//Create entry for player
			$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$apiKey&u=$playerName");
			$json = json_decode($apiRequest, true);
			$playerId = $json[0]['user_id'];
			
			//Send info to database
			$sql = "INSERT INTO playerlist (userId,userName) VALUES ('$playerId','$playerName')";
			if ($conn->query($sql) === TRUE) {
				//row created
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		
		//-- Create a request ticket --
		date_default_timezone_set('Europe/Paris');
		$replayId = uniqid();
		$result = $conn->query("SELECT userId FROM playerlist WHERE userName='$playerName'");
		while ($row = $result->fetch_assoc()) {
			$playerId = $row['userId'];
		}
		$date = date('Y-m-d');
		$sql = "INSERT INTO requestlist (replayId,OFN,date,playerId) VALUES ('$replayId','$file_name','$date','$playerId')";
		if ($conn->query($sql) === TRUE) {
			//row created
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		//Deplacement du fichier en liste d'attente
		mkdir('requestList/'.$replayId, 0777, true);
		rename('uploads/'.$file_name,'requestList/'.$replayId.'/'.$file_name);
		
		//upload finised
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
		header("Location:index.php?error=0");
    } else {
        echo "Sorry, there was an error uploading your file.";
		header("Location:index.php?error=4");
    }
}
//Errors :
/*
	0="Upload successful"
	1="File is not a osu replay"
	2="File already been requested"
	3="Database connection error"
	4="Upload error"
*/
$conn->close();
exit;
?>

