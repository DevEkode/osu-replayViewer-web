<?php
  session_start();
  require '../osuApiFunctions.php';
  require '../../secure/osu_api_key.php';

  $session = base64_decode($_POST['session']);
  $session = unserialize($session);

  //Add new username
  $userJSON = getUserJSON($_POST['newUsername'],$osuApiKey);
  $playername = $_POST['newUsername'];
  $replay_playerId = $userJSON[0]['user_id'];

  //Modify session array
  $remplacement = array('playername' => $playername, 'replay_playerId' => $replay_playerId, 'playerOsuAccount' => true);
  $session = array_replace($session,$remplacement);

  $_SESSION = array_merge($_SESSION,$session);

  header('Location:../../index.php');
?>
