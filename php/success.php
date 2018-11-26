<?php

// ----- Success list -----
$uploadSkinSuccess = array(
  0 => 'The skin has successfully been uploaded',
  1 => 'The skin has successfully been removed',
  2 => 'All settings has been saved',
  3 => 'Email successfully updated, an verification email has been sent',
  4 => 'Password successfully changed'
);

// ---- Index ----
//Link array with corresponding page
$indexOfPages = array(
  '/editProfile.php' => $uploadSkinSuccess
);

function showSuccessMessage($success){
  echo '<span style="color:green; text-align: center;">'.$success."</span><br>\n";
}

//Take the error var in GET and show the error modal
function showSuccess($id){
  global $indexOfPages;
  $array = $indexOfPages[$_SERVER['PHP_SELF']];
  if(isset($_GET['success']) && !empty($array) && in_array($_GET['success'],array_keys($array))){
    if(strcmp($id,$_GET['success']) == 0){
      showSuccessMessage($array[$_GET['success']]);
    }
  }
}
 ?>
