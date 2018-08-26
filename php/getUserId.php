<?php
	//--Connect to osu API --
	require_once '../secure/osu_api_key.php';
	$apiKey = $osuApiKey;
	require 'osuApiFunctions.php';

	$q = $_REQUEST["q"];

	$userJSON = getUserJSON($q,$osuApiKey);
	if(!empty($userJSON)){
		echo $userJSON[0]['user_id'];
	}else{
		echo "1";
	}

?>
