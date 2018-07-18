<?php
  //------- GET JSONS ---------
  function getBeatmapJSON($beatmapId,$api){ //Source : https://stackoverflow.com/questions/16700960/how-to-use-curl-to-get-json-data-and-decode-the-data
    $url = "https://osu.ppy.sh/api/get_beatmaps?k=$api&b=$beatmapId";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result=curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
  }

  function getBeatmapJSONwMD5($beatmapMD5,$api){ //Source : https://stackoverflow.com/questions/16700960/how-to-use-curl-to-get-json-data-and-decode-the-data
    $url = "https://osu.ppy.sh/api/get_beatmaps?k=$api&h=$beatmapMD5";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result=curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
  }

  function getUserJSON($username, $api){
		$url = "https://osu.ppy.sh/api/get_user?k=$api&u=$username";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$url);
		$result=curl_exec($ch);
		curl_close($ch);
		return json_decode($result, true);
	}

  //Return mods name from the binary (divided by ' ')
  function drawMods($bin){
		$modsArray = array(1,2,8,16,32,64,128,256,512,1024,2048,4096,8192,16384,32768,65536,131072,262144,524288,1048576,2097152,4194304,16777216,33554432,67108864,134217728,268435456);
		$modsName = array("NF","EZ","HD","HR","SD","DT","RL","HT","NC","FL","AT","SO","AP","PF","4K","5K","6K","7K","8K","FI","RD","CM","9K","COOP","1K","3K","2K");
		$string = "";
		if($bin != 0){
			$string = "";
		}else{
      $string = "None";
    }

		for($i=0;$i<count($modsArray)-1;$i++){
			$result = $modsArray[$i] & $bin;
			if($result != 0){
				$string = $string.$modsName[$i]." ";
			}
		}

		return substr($string, 0, -1);
	}

  //Check if a beatmap is still downloable
  function isBeatmapAvailable($beatmapSetId){
    $page = file_get_contents('https://osu.ppy.sh/beatmapsets/'.$beatmapSetId);
    preg_match("/download_disabled/", $page, $output_array);
    if(empty($output_array)){
      return true;
    }else{
      return false;
    }
  }

  function getReplayContent($filedir){
  	$myfile = fopen($filedir, "r") or die("Unable to open file!");
  	$replay_content = fread($myfile,filesize($filedir));

  	$array = unpack("C1gamemode/iversion/x/clength/A32md5/x/clength2/Auser", $replay_content);
      $userLength = $array['length2'];
      $array = unpack("C1gamemode/iversion/x/clength/A32md5/x/clength2/A".$userLength."user/x/clength3/A32md5Replay/sx300/sx100/sx50/sGekis/sKatus/sMiss/iScore/sMaxCombo/C1perfectCombo/iMods", $replay_content);

  	return $array;
  }

  function isDT($binary){ //Return player name of the replay from the name of the file
  	$filter = 0b0000000000000000000001000000;
  	$result = $binary & $filter;

  	if($result != 0){
  		return true;
  	}else{
  		return false;
  	}
  }

  function generateBtFileNamewAPI($beatmapId,$api){
  	//Setid Artist - Title
    $json = getBeatmapJSON($beatmapId,$api);
  	$beatmapSetId = $json[0]["beatmapset_id"];
  	$artist = $json[0]["artist"];
  	$title = $json[0]["title"];
  	$BFN = $beatmapSetId." ".$artist." - ".$title.".osz";

  	return $BFN;
  }

  function generateBtFileNamewJSON($beatmapJSON){
  	//Setid Artist - Title
  	$beatmapSetId = $beatmapJSON[0]["beatmapset_id"];
  	$artist = $beatmapJSON[0]["artist"];
  	$title = $beatmapJSON[0]["title"];
  	$BFN = $beatmapSetId." ".$artist." - ".$title.".osz";

  	return $BFN;
  }

 ?>
