<?php
// ******************** Variables **********************************
//--Connect to osu API --
require 'php/osuApiFunctions.php';
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


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

function emailAlreadyUsed($conn,$email){
  $queryEmails = $conn->prepare("SELECT email FROM accounts");
  $queryEmails->execute();
  $result = $queryEmails->get_result();
  $queryEmails->close();

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      if($row['email'] == $email){
        return true;
      }
    }
  }

  return false;
}

function isAlreadyUsedInAccount($conn,$column,$value){
  $queryEmails = $conn->prepare("SELECT * FROM accounts");
  $queryEmails->execute();
  $result = $queryEmails->get_result();
  $queryEmails->close();

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
      if($row[$column] == $value){
        return true;
      }
    }
  }

  return false;
}

function verifyCaptcha($secretCaptcha,$cResponse){
  //POST request
  $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretCaptcha&response=$cResponse";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$url);
  $result=curl_exec($ch);
  curl_close($ch);

  $json = json_decode($result, true);
  return $json['success'];
}

//----------------------------- core ----------------------------------
require_once 'secure/recaptcha.php';
if(isFormSubmitted()){
  //Check the reCaptcha
  if(!verifyCaptcha($secretCaptcha,$_GET['g-recaptcha-response'])){
    header("Location:request.php?error=1");
    exit();
  }

  //Check if the player exist in osu
  $userJSON = getUserJSON($_GET['userId'],$osuApiKey);
  if(empty($userJSON)){
    header("Location:request.php?error=2");
    exit();
  }

	//Check if the user is already registered //Check if the email is already used
  if(isAlreadyUsedInAccount($conn,'userId',$_GET['userId']) || isAlreadyUsedInAccount($conn,'email',$_GET['email'])){
    header("Location:request.php?error=3");
    exit();
  }
	//create a unique id for account verification
	$verfId = uniqid('verf_');
  $verfIdEmail = uniqid('eVerf_');
	//create a new row in accounts
  $insertAccount = $conn->prepare("INSERT INTO accounts (userId, username, email, password, verificationId, verfIdEmail) VALUES (?, ?, ?, ?, ?, ?)");
  $insertAccount->bind_param("isssss",$userId,$username,$email,$password,$verfId,$verfIdEmail);

  $userId = $_GET['userId'];
  $username = $userJSON['0']['username'];
  $email = $_GET['email'];
  $password = password_hash($_GET['password'],PASSWORD_BCRYPT);

  //Send e-mail
  require_once 'php/verificationFunctions.php';
  sendEmail($email,$username,$verfIdEmail);

  if($insertAccount->execute()){ //Insert Not ok
    header("Location:register.php?success=1");
    $insertAccount->close();
    exit();
  }


}
?>

<html>
	<head>
		 <script src="js/request.js"></script>
     <script src='https://www.google.com/recaptcha/api.js'></script>
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
    <div class="g-recaptcha" data-sitekey="6LcYyk8UAAAAAHmsgHYvmnCIr3I6hIlKv7VWANSo"></div>
		<input type="submit" value="Submit" id="submitButton">
		</form>
	</body>
</html>
