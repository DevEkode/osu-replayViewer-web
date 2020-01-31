<?php
require 'ini.class.php';
session_start();

//Check ini file
$ini_dir = '../../accounts/'.$_SESSION["userId"].'/'.$_SESSION["userId"].'.ini';
$ini = new Ini();
$ini->read($ini_dir);

//Get data
if(isset($_POST['snaking_sliders'])) $ini->set('osu','snaking_sliders',"true");
  else $ini->set('osu','snaking_sliders',"false");

if(isset($_POST['storyboards'])) $ini->set('osu','storyboards',"true");
  else $ini->set('osu','storyboards',"false");

if(isset($_POST['background_videos'])) $ini->set('osu','background_videos',"true");
  else $ini->set('osu','background_videos',"false");

if(isset($_POST['leaderboards'])) $ini->set('osu','leaderboards',"true");
  else $ini->set('osu','leaderboards',"false");

if(isset($_POST['combo_bursts'])) $ini->set('osu','combo_bursts',"true");
  else $ini->set('osu','combo_bursts',"false");

if(isset($_POST['hit_lighting'])) $ini->set('osu','hit_lighting',"true");
  else $ini->set('osu','hit_lighting',"false");

if(isset($_POST['replay_hud'])) $ini->set('osu','replay_hud',"true");
  else $ini->set('osu','replay_hud',"false");

if(isset($_POST['spec_hud'])) $ini->set('osu','spec_hud',"true");
    else $ini->set('osu','spec_hud',"false");

if(isset($_POST['beatmap_skin'])) $ini->set('osu','beatmap_skin',"true");
    else $ini->set('osu','beatmap_skin',"false");

if(isset($_POST['beatmap_sample'])) $ini->set('osu','beatmap_sample',"true");
    else $ini->set('osu','beatmap_sample',"false");

$ini->write($ini_dir);
$redirectUrl = "https://".$_SERVER['SERVER_NAME']."/editProfile.php?block=game&success=8";
header("Location:$redirectUrl");