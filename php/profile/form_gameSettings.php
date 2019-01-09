<?php
require 'ini.class.php';
session_start();

var_dump($_POST);

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

$ini->write($ini_dir);
header('Location:../../editProfile.php?block=game&success=8');
?>