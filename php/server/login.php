<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

$user = filter_var($_POST['user'],FILTER_SANITIZE_STRING);
$pass = filter_var($_POST['pass'],FILTER_SANITIZE_STRING);
$token = filter_var($_POST['token'],FILTER_SANITIZE_STRING);

$osuApiKey = getenv('OSU_KEY');

$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	header("Location:../../index.php?error=1");
	exit;
}

//Get userId
require_once '../osuApiFunctions.php';

$userJSON = getUserJSON($user,$osuApiKey);
$userId = $userJSON[0]['user_id'];

//Query
$query = $conn->prepare('SELECT * FROM accounts WHERE userId = ?');
$query->bind_param("i",$userId);
$query->execute();

$result = $query->get_result();
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $line = $row;
  }
}

//Get result
if(empty($line)){
  http_response_code(404);
  exit;
}

//Check password
if(!password_verify($pass,$line['password'])){
  http_response_code(404);
  exit;
}

//Check token
unset($query);
$query = $conn->prepare('SELECT * FROM accounts_client WHERE userId = ?');
$query->bind_param("i",$userId);
$query->execute();

$result = $query->get_result();
if($result->num_rows > 0){
  while($row = $result->fetch_assoc()){
    $line = $row;
  }
}

//Get result
if(empty($line)){
  http_response_code(404);
  exit;
}

//Analyse token
if(strcmp($token,$line['secretToken']) != 0){
  http_response_code(404);
  exit;
}

//generate json
echo $userId;
?>
