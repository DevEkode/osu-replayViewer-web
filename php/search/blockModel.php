<?php

function drawProfile($userId,$username){
  $profileURL = "userProfile.php?id=".$userId;
  $userImgURL = "https://a.ppy.sh/".$userId;
  echo "<a class='requestContent' href=$profileURL>";
  echo 	'<div id="anim">';
  echo 		"<img src=$userImgURL>";
  echo 	'</div>';
  echo	"<h3 id=\"beatmap_name\">".$username."</h3>";
  echo 	"<h4 id=\"profileDesc\">Click here to visit his profile</h4>";
  echo	"<span></span>";
  echo'</a>';
}

function drawRequest($replayId,$beatmapName,$beatmapSetId){
  $beatmapName = str_replace(".osz", "", $beatmapName);
  $url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
  $replayUrl = "progress.php?id=$replayId";
  echo "<a class='requestContent' href=$replayUrl>";
  echo 	'<div id="anim">';
  echo 		"<img src=$url>";
  echo 	'</div>';
  echo	"<h3 id=\"beatmap_name\">$beatmapName</h3>";
  echo 	"<h4 id=\"block_content\">Currently in processing queue</h4>";
  echo	"<span></span>";
  echo "</a>";
}

function drawReplay($replayId,$beatmapName,$beatmapSetId,$artist,$diff,$gamemode,$modsListing){

  //gamemode
  switch($gamemode){
    case 0 : $modUrl = "images/osuStdr.png"; break;
    case 1 : $modUrl = "images/osuTaiko.png"; break;
    case 2 : $modUrl = "images/osuCTB.png"; break;
    case 3 : $modUrl = "images/osuMania.png"; break;
    case 4 : $modUrl = ""; break;
    default : $modUrl = ""; break;
  }

  $url = "https://b.ppy.sh/thumb/$beatmapSetId"."l.jpg";
  $replayUrl = "view.php?id=$replayId";

  echo "<a class='content' href=$replayUrl>";
  echo 	'<div id="anim">';
  echo 		"<img src=$url>";
  echo 	'</div>';
  echo	'<div id="alignRight">';
  echo		"<img src=$modUrl>";
  echo	'</div>';
  echo	"<h3 id=\"beatmap_name\">$beatmapName</h3>";
  echo 	"<h4 id=\"block_content\">Creator : $artist <br>
        Difficulty : $diff <br>
        Mods : $modsListing</h4>";
  echo	"<span></span>";
  echo "</a>";
}

 ?>
