<?php
$uploadErrors = array(
  1 => 'Mysql connection error',
  2 => 'Cannot get replay data',
  3 => 'Cannot get beatmap data',
  4 => 'Cannot get player data',
  5 => 'INSERT INTO mysql database failed',
  6 => 'Cannot create replay directory',
  7 => 'Cannot move replay to final destination',
  8 => 'You have to accept terms of uses',
  9 => 'Uploads are disabled'
);

$editProfileErrors = array(
  1 => 'Session error',
  2 => 'This user doesn\'t exists'
);

$progressErrors = array(
  1 => 'This file doesn\'t matches with the original'
);

//Index
$indexOfPages = array(
  '/index.php' => $uploadErrors,
  '/editProfile.php' => $editProfileErrors,
  '/progress.php' => $progressErrors
);

function showErrorModal($error){
  date_default_timezone_set("Europe/Paris");
  $date = date('d/m/Y - H:i');
  $page = $_SERVER['PHP_SELF'];
  echo <<<EOF
  <link rel="stylesheet" type="text/css" href="css/errorModal.css">
  <script type="text/javascript" src="js/closeErrorModal.js"></script>
  <div class="modal-error" id="myErrorModal">
    <div class="modal-content-error">
      <h2>Error :(</h2>
      <h3 id="errorModal">$error</h3><br>
      <span><b>date :</b> $date (UTC+2)</span><br>
      <span><b>page :</b> $page</span><br>
      <button class="close-error" onclick="closeErrorModal();">Close</button>
    </div>
  </div>
EOF;
}

//Take the error var in GET and show the error modal
function showError(){
  global $indexOfPages;
  $array = $indexOfPages[$_SERVER['PHP_SELF']];
  if(isset($_GET['error']) && !empty($array) && in_array($_GET['error'],array_keys($array))){
    showErrorModal($array[$_GET['error']]);
  }
}
 ?>
