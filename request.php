<?php
// ******************** Variables **********************************
//--Connect to osu API --
//require_once 'secure/osu_api_key.php';
//$apiKey = $osuApiKey;


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

//------------------------------ functions ---------------------------
function getUserInterests($userId){
	$page = file_get_contents('https://osu.ppy.sh/users/'.$userId);
	preg_match("/\"interests\":\".*\",\"occupation\"/", $page, $output_array);
	if(!empty($output_array)){
		$web = explode("\"", $output_array[0]);
		return $web[3];
	}else{
		return "";
	}
}

function isFormSubmitted(){
	if(isset($_GET["userId"]) && isset($_GET["email"]) && isset($_GET["password"]) && isset($_GET["cPassword"])){
		return true;
	}else {return false;}
}


//----------------------------- core ----------------------------------
if(isFormSubmitted()){
	//Check if the user is already registered

	//Check if the email is already used

	//create a unique id for account verification
	$verfId = uniqid('verf_');

	//create a new row in accounts
}
?>

<html>
	<head>
		 <script src="js/request.js"></script>
	<head>

	<body onload="start()">
		<form id="form" onsubmit="submitted()">
		Osu! player id:
		<input type="text" name="userId" id="userId" onkeyup="showUsername(this.value); update()"> <span id="txtHint"></span><br>
		e-mail:
		<input type="text" name="email" id="email" onkeyup="showEmailValidity(); update()"><span id="emailHint"></span><br>
		password:
		<input type="password" name="password" id="pass" onkeyup="update()"><br>
		confirm password:
		<input type="password" name="cPassword" id="confPass" onkeyup="showCheckPass(); update()"> <span id="checkPass"></span><br>
		<input type="submit" value="Submit" id="submitButton">
		</form>
	</body>
</html>
