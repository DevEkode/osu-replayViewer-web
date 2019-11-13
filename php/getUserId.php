<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
	//--Connect to osu API --
	require 'osuApiFunctions.php';

$osuApiKey = getenv('OSU_KEY');
$apiKey = $osuApiKey;

	$q = $_REQUEST["q"];

	$userJSON = getUserJSON($q,$osuApiKey);
	if(!empty($userJSON)){
		echo $userJSON[0]['user_id'];
	}else{
		echo "1";
	}

?>
