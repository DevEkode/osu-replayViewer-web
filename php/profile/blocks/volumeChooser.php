<?php

//Generate html block
function block_volumeChooser(){
  $music_volume = getIniKey($_SESSION["userId"],'osu',"music_volume");
  $effects_volume = getIniKey($_SESSION["userId"],'osu',"effects_volume");

  echo <<<EOF
  <form action="php/profile/form_volume.php" method="post" id="columnBack">
            <h1 class="title is-4">- Custom game volume chooser -</h1>
EOF;

  showSuccess(10);

  echo 'Music volume : <span id="musicVolumeValue"></span><br>';
  echo '<input type="range" step="1" min="0" max="100" value='.$music_volume.' class="slider" oninput="updateVolume()" name="musicVolume" id="musicVolumeRange"> <br/><br/>';
  echo 'Effects volume : <span id="effectsVolumeValue"></span><br>';
  echo '<input type="range" step="1" min="0" max="100" value='.$effects_volume.' class="slider" oninput="updateVolume()" name="effectsVolume" id="effectsVolumeRange"> <br/><br/>';
  echo '<input type="submit" value="Save volume settings" class="button is-light"/></form>';
}


        
?>