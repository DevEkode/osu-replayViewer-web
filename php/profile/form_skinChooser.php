<?php
  require 'ini.class.php';
  session_start();

  //Sanitize data
  $checkBox = filter_var($_POST['customSkin'],FILTER_SANITIZE_STRING);
  $skinPost = filter_var($_POST['skin'],FILTER_SANITIZE_STRING);

  //Check ini file
  $ini_dir = '../../accounts/'.$_SESSION["userId"].'/'.$_SESSION["userId"].'.ini';
  $ini = new Ini();
  $ini->read($ini_dir);

  //Set variables
  if(isset($checkBox)) $customSkin = "true";
  else $customSkin = "false";

  if(isset($skinPost) && $customSkin) $skin = $skinPost;
  else $skin = "default";

  $ini->set('skin','enable',$customSkin);
  $ini->set('skin','fileName',$skin);
  $ini->write($ini_dir);

  header('Location:../../editProfile.php');
 ?>
