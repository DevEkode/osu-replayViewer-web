<?php

// ----- Error list -----
$uploadErrors = array(
  1 => 'Mysql connection error',
  2 => 'Cannot get replay data',
  3 => 'Cannot get beatmap data',
  4 => 'Cannot get player data',
  5 => 'INSERT INTO mysql database failed',
  6 => 'Cannot create replay directory',
  7 => 'Cannot move replay to final destination',
  8 => 'You have to accept terms of uses',
    9 => 'Uploads are disabled',
    10 => 'Upload rate exceeded'
);

$editProfileErrors = array(
  1 => 'Session error',
  2 => 'This user doesn\'t exists',
  3 => "Actual password doesn't match",
  4 => "Database error",
  5 => "New password doesn't match with the verification",
  6 => "Database error",
  7 => "This skin has already been uploaded",
  8 => "Only .osk files are allowed",
  9 => "Sorry, your skin couldn't be uploaded",
  10 => "Your skin file name cannot contain special characters",
  11 => "Your skin file size is more than 50Mb",
  12 => "This skin doesn't exist",
  13 => "Remove error",
  14 => 'The server has received a null $_FILES'
);

$progressErrors = array(
  1 => 'This file doesn\'t matches with the original'
);

// ---- Index ----
//Link array with corresponding page
$indexOfPages = array(
  '/index.php' => $uploadErrors,
  '/editProfile.php' => $editProfileErrors,
  '/progress.php' => $progressErrors
);

function showErrorModal($error,$errorMsg = null){
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
EOF;

  if($errorMsg != null){
    echo "<span><b>reason :</b> $errorMsg</span><br>";
  }
  echo <<<EOF
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
    if(isset($_GET['errorMsg'])){
      showErrorModal($array[$_GET['error']],$_GET['errorMsg']);
    }else{
      showErrorModal($array[$_GET['error']]);
    }
  }
}
 ?>
