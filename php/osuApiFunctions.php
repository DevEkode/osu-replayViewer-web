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

  function getReplayJSON($beatmapId,$user,$gamemode,$mods,$api){
    $url = "https://osu.ppy.sh/api/get_scores?k=$api&b=$beatmapId&u=$user&m=$gamemode&mods=$mods";
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
		if($bin != 0){
			$string = "";
		}else{
      $string = "none";
    }

		for($i=0;$i<count($modsArray)-1;$i++){
			$result = $modsArray[$i] & $bin;
			if($result != 0){
				$string = $string.$modsName[$i]." ";
			}
		}

    if(strcmp($string,'none') == 0){
      return $string;
    }else{
      return substr($string, 0, -1);
    }
	}

function getModsArray($bin)
{
    $modsArray = array(1, 2, 8, 16, 32, 64, 128, 256, 512, 1024, 2048, 4096, 8192, 16384, 32768, 65536, 131072, 262144, 524288, 1048576, 2097152, 4194304, 16777216, 33554432, 67108864, 134217728, 268435456);
		$modsName = array("NF","EZ","HD","HR","SD","DT","RL","HT","NC","FL","AT","SO","AP","PF","4K","5K","6K","7K","8K","FI","RD","CM","9K","COOP","1K","3K","2K");
		$resultArray = array();
		if($bin != 0){
			$resultArray = array();
		}

		for($i=0;$i<count($modsArray)-1;$i++){
			$result = $modsArray[$i] & $bin;
			if($result != 0){
        array_push($resultArray,$modsName[$i]);
			}
		}
    return $resultArray;

  }

  //Check if a beatmap is still downloable
function isBeatmapAvailable($beatmapId, $key)
{
    $bt_json = getBeatmapJSON($beatmapId, $key);
    $d_unavailable = $bt_json[0]['download_unavailable'];
    return $d_unavailable == '0';
  }

  function getReplayContent($filedir){
  	$myfile = fopen($filedir, "r") or die("Unable to open file!");
  	$replay_content = fread($myfile,filesize($filedir));

  	$array = unpack("C1gamemode/iversion/x/clength/A32md5/x/clength2/Auser", $replay_content);
    $userLength = $array['length2'];
    $array = unpack("C1gamemode/iversion/x/clength/A32md5/x/clength2/A".$userLength."user/x/clength3/A32md5Replay/sx300/sx100/sx50/sGekis/sKatus/sMiss/iScore/sMaxCombo/C1perfectCombo/iMods/x/clength4", $replay_content);

  	return $array;
  }

  function isValidMd5($md5 ='')
  {
    return preg_match('/^[a-f0-9]{32}$/', $md5);
  }

  function validateReplayStructure($filedir,$api){
    $replayDATA = getReplayContent($filedir);
    $valide = true;

    //Check gamemode
    if(!in_array($replayDATA['gamemode'], array(0,1,2,3), true)){
      echo 'Gamemode not valid <br>';
      $valide = false;
    }

    //Check version
    if(strlen($replayDATA['version']) != 8){
      echo 'Version not valid <br>';
      $valide = false;
    }

    //Check md5
    if(!isValidMd5($replayDATA['md5'])){
      echo 'Beatmap md5 not valid <br>';
      $valide = false;
      $beatmapJSON = null;
    }else{
      $beatmapJSON = getBeatmapJSONwMD5($replayDATA['md5'],$api);
    }

    //Check username
    /*$userJSON = getUserJSON($replayDATA['user'],$api);
    if(empty($userJSON)){
      echo 'Username not valid <br>';
      $valide = false;
    }*/

    //Check replay md5
    if(!isValidMd5($replayDATA['md5Replay'])){
      echo 'Replay md5 not valid <br>';
      $valide = false;
    }

    //Check beatmap info
    if(empty($beatmapJSON)){
      echo 'Beatmap json not valid <br>';
      $valide = false;
    }else{
      //Check Max combo and miss
      if($replayDATA['gamemode'] == 0){
        if($replayDATA['Miss'] > $beatmapJSON[0]['max_combo']){
          echo 'Miss count not valid <br>';
          $valide = false;
        }
        if($replayDATA['MaxCombo'] > $beatmapJSON[0]['max_combo']){
          echo 'MaxCombo not valid <br>';
          $valide = false;
        }
      }
    }

    //Check perfectCombo
    if(!in_array($replayDATA['perfectCombo'],array(0,1),true)){
      echo 'Perfect combo not valid <br>';
      $valide = false;
    }

    //Check mods
    if($replayDATA['Mods'] > 268435456){
      echo 'Mods not valid <br>';
      $valide = false;
    }

    return $valide;
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
