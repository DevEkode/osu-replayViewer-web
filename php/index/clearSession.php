<?php
//session_start();
//unset session
unset($_SESSION['filename']);
unset($_SESSION['skinName']);
unset($_SESSION['replay_playerId']);
unset($_SESSION['replayStructure']);
unset($_SESSION['beatmapAvailable']);
unset($_SESSION['playerOsuAccount']);
unset($_SESSION['replayBelow10']);
unset($_SESSION['replayNotDuplicate']);
unset($_SESSION['replayNotWaiting']);
unset($_SESSION['beatmapName']);
unset($_SESSION['beatmapSetId']);
unset($_SESSION['difficulty']);
unset($_SESSION['playername']);
unset($_SESSION['duration']);
unset($_SESSION['mods']);

function clear(){
  unset($_SESSION['filename']);
  unset($_SESSION['skinName']);
  unset($_SESSION['replay_playerId']);
  unset($_SESSION['replayStructure']);
  unset($_SESSION['beatmapAvailable']);
  unset($_SESSION['playerOsuAccount']);
  unset($_SESSION['replayBelow10']);
  unset($_SESSION['replayNotDuplicate']);
  unset($_SESSION['replayNotWaiting']);
  unset($_SESSION['beatmapName']);
  unset($_SESSION['beatmapSetId']);
  unset($_SESSION['difficulty']);
  unset($_SESSION['playername']);
  unset($_SESSION['duration']);
  unset($_SESSION['mods']);
}
 ?>
