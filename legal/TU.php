<?php
  //Arrays
  $filesReplay = array(
    "en" => 'EN/Terms of use for replay processing - EN.pdf',
    "fr" => 'FR/Termes d\'utilisation du traitement des replays - FR.pdf'
  );

  $filesUser = array(
    "en" => 'EN/Terms of use for user accounts - EN.pdf',
    "fr" => 'FR/Termes d\'utilisation compte utilisateur FR.pdf'
  );

  $TU = array(
    'replay' => $filesReplay,
    'user' => $filesUser
  );

  //Get what is the Terms of Use requested
  if(isset($_GET['TU'])){
    if(!in_array($_GET['TU'], array_keys($TU))){
      $array = $filesReplay;
    }else{
      $array = $TU[$_GET['TU']];
    }
  }else{
    $array = $filesReplay;
  }

  //Get the language (if the get value is not set)
  if(isset($_GET['lang'])){
    //With the get variable
    $lang = $_GET['lang'];
  }else{
    //With the browser language
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  }

  if(!in_array($lang, array_keys($array))){
    $lang = "en";
  }

  //Set the header
  header('Content-type: application/pdf');
  readfile($array[$lang]);

 ?>
