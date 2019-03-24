<?php

function block_gameSettingsChooser(){
echo <<<EOF
    <form action="php/profile/form_gameSettings.php" method="post" id="columnBack">
            <h1 class="title is-4">- Custom game settings -</h1>
EOF;
            showSuccess(8);

echo '<h2 class="title is-6">Activate or disable osu! settings</h1>';        
echo '<div class="grid-container">';


generateSwitch("Snaking sliders","snaking_sliders","snaking_sliders");
generateSwitch("Storyboards","storyboards","storyboards");
generateSwitch("Background videos","background_videos","background_videos");
generateSwitch("Leaderboard","leaderboards","leaderboards");
generateSwitch("Combo bursts","combo_bursts","combo_bursts");
generateSwitch("Hit lighting","hit_lighting","hit_lighting");
generateSwitch("Replay HUD","replay_hud","replay_hud");
generateSwitch("Spectator HUD","spec_hud","spec_hud");
generateSwitch("Ignore beatmap skins","beatmap_skin","beatmap_skin");

echo '</div>';
echo '<input type="submit" value="Save all modifications" class="button is-light"/>';
echo '</form>';
}

function generateSwitch($title,$name,$iniKey){
  $activated = getIniKey($_SESSION["userId"],'osu',$iniKey);
  echo '<div class="grid-item">';
  echo  '<label class="switch_check">';
  if($activated == 'true'){
    echo    "<input type=\"checkbox\" name=\"$name\" checked>";
  }else{
    echo    "<input type=\"checkbox\" name=\"$name\">";
  }
  echo    '<span class="slider_check round"></span>';
  echo  '</label>';
  echo  "<span>$title</span>";
  echo '</div>';
}

?>