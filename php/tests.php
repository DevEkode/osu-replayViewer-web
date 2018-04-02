<?php
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

function isBeatmapAvailable($beatmapSetId){
  $page = file_get_contents('https://osu.ppy.sh/beatmapsets/'.$beatmapSetId);
  var_dump('https://osu.ppy.sh/beatmapsets/'.$beatmapSetId);
  preg_match("/download_disabled/", $page, $output_array);
  if(empty($output_array)){
    return false;
  }else{
    return true;
  }
}

var_dump(getUserInterests(3481725));
var_dump(isBeatmapAvailable(28332));
 ?>
