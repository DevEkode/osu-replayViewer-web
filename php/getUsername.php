<?php
	//--Connect to osu API --
	require_once '../secure/osu_api_key.php';
	$apiKey = $osuApiKey;
	
	function getPlayerId($userid,$api){
		$apiRequest = file_get_contents("https://osu.ppy.sh/api/get_user?k=$api&u=$userid");
		$json = json_decode($apiRequest, true);
		if(!empty($json)){
			return $json[0]['username'];
		}else{
			return "";
		}
	}
	
	$q = $_REQUEST["q"];
	
	$username = getPlayerId($q,$osuApiKey);
	if($username != ""){
		echo "Corresponding username : ".getPlayerId($q,$osuApiKey);
	}else{
		echo "this user doesn't exists";
	}
	
?>