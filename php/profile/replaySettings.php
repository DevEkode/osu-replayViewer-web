<?php
require 'iniParser.php';

//Default
$data = array(
  'skin' => array(
    'enable' => 'false',
    'fileName' => 'null',
  ),
  'osu' => array(
    'dim' => 50,
    'cursor_size' => 1,
    'snaking_sliders' => 'true',
    'storyboards' => 'false',
    'background_videos' => 'false',
    'leaderboards' => 'false',
    'combo_bursts' => 'false',
    'hit_lighting' => 'false',
    'replay_hud' => 'true',
      'spec_hud' => 'true',
      'beatmap_skin' => 'false',
      'beatmap_sample' => 'false',
    'music_volume' => 50,
    'effects_volume' => 50
  )
);

function userFileExists($userId){
  $user_URL = "accounts/".$userId;
  return is_dir($user_URL);
}

function checkIfIniExists($userId){
  $ini_URL = "accounts/".$userId.'/'.$userId.'.ini';
  return file_exists($ini_URL);
}

function checkUserFile($userId){
  global $data;

  //Check if account folder exists
  if(!is_dir("accounts")) mkdir("accounts");

  //Check if his folder exists
  if(!userFileExists($userId)){
    mkdir("accounts/".$userId);
  }

  $ini_dir = './accounts/'.$userId.'/'.$userId.'.ini';
  $ini = new Ini();

  //Check if his ini file exists
  if(!checkIfIniExists($userId)){
    $ourFileHandle = fopen('accounts/'.$userId.'/'.$userId.'.ini', 'w') or die("can't open file");
    fclose($ourFileHandle);

    //Create ini file
    $ini->writeArray($ini_dir,'skin',$data['skin']);
    $ini->writeArray($ini_dir,'osu',$data['osu']);
  }else{
    //Check file integrity
    $ini->read($ini_dir);

    $defaults_keys_skin = array_keys($data['skin']);
    $defaults_keys_osu = array_keys($data['osu']);

    //Check skin section
    foreach($defaults_keys_skin as &$key){
      if(!$ini->exists('skin',$key)){
        //Add missing line
        $ini->repairKey('skin',$key,$data);
      }
    }

    //Check osu section
    foreach($defaults_keys_osu as &$key){
      if(!$ini->exists('osu',$key)){
        //Add missing line
        $ini->repairKey('osu',$key,$data);
      }
    }
  }
}

function getIniKey($userId,$section,$key){
  $ini = new Ini();
  $ini->read('accounts/'.$userId.'/'.$userId.'.ini');
  return $ini->get($section,$key);
}


function listAllSkins($userId){
  $skins = array();
  foreach (glob('accounts/'.$userId.'/*.osk') as $filename) {
    $tab = explode("/",$filename);
    array_push($skins,$tab[2]);
  }
  return $skins;
}

/*Check skin content*/
function isSkinValid($folder_dir){
  $valide = true;
  $ignore_dir = array(
      ".",
      "..",
      "__MACOSX"
  );
  $dir_count = 0;
  $last_dir = "";

  //Check if another folder exists
  $files = scandir($folder_dir,1);

  foreach($files as $file){
    if(!in_array($file,$ignore_dir)){
      $dir_count++;
      $last_dir = $file;
    }
  }

  if($dir_count == 1) {
    $folder_dir .= "/" . $last_dir;
  }

  //Check ini file
  if(!file_exists($folder_dir."/skin.ini")){
    $valide = false;
  }
  return $valide;
}

 ?>
