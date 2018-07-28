<?php
require 'iniParser.php';

function userFileExists($userId){
  $user_URL = "accounts/".$userId;
  return is_dir($user_URL);
}

function checkIfIniExists($userId){
  $ini_URL = "accounts/".$userId.'/'.$userId.'.ini';
  return file_exists($ini_URL);
}

function checkUserFile($userId){
  //Check if his folder exists
  if(!userFileExists($userId)){
    mkdir("accounts/".$userId);
  }

  //Check if his ini file exists
  if(!checkIfIniExists($userId)){
    $ourFileHandle = fopen('accounts/'.$userId.'/'.$userId.'.ini', 'w') or die("can't open file");
    fclose($ourFileHandle);
    updateIniFile('accounts/',$userId,'false','null','50','true');
  }
}

function getIniKey($userId,$key){
  $ini = parse_ini_file('accounts/'.$userId.'/'.$userId.'.ini');
  return $ini[$key];
}

function updateIniFile($accountURL,$userId,$enableSkin,$skinFileName,$dim,$showVideo){
  $data = array(
    'skin' => array(
      'enable' => $enableSkin,
      'fileName' => $skinFileName,
    ),
    'osu' => array(
      'dim' => $dim,
      'showVideo' => $showVideo
    )
  );

  write_php_ini($data, $accountURL.'/'.$userId.'/'.$userId.'.ini');
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
  //Check ini file
  if(!file_exists($folder_dir."/skin.ini")){
    $valide = false;
  }
  return $valide;
}

 ?>
