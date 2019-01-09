<?php

//Generate html block for skin remover
function block_skinRemover(){
  echo <<<EOF
  <form action="php/profile/removeSkin.php" method="post" enctype="multipart/form-data" id="columnBack">
            <h1 class="title is-4">- Custom skin remover -</h1>
EOF;

showSuccess(1);

  echo "Choose your custom skin to remove :<br>";

  block_skinRemover_body();
  
  echo <<<EOF
  <input type="submit" value="Remove this skin" class="button is-light">
          </form>
EOF;
}

function block_skinRemover_body(){
  $skins = listAllSkins($_SESSION["userId"]);
  $actualSkin = getIniKey($_SESSION["userId"],"fileName");

  //Combobox with all skins uploaded
  echo '<div class="select">';
  echo "<select id='skinsSelector2' class=\"select\" name='skin'>";
  foreach($skins as $skin)
  {
    if($skin == $actualSkin){
      echo "<option value='".$skin."' selected>".$skin."</option>";
    }else{
      echo "<option value='".$skin."'>".$skin."</option>";
    }
  }
  echo "</select></div>";
}
?>