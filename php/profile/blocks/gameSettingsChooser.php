<?php

function block_gameSettingsChooser(){
echo <<<EOF
    <form action="php/profile/form_gameSettings.php" method="post" id="columnBack">
            <h1 class="title is-4">- Custom game settings -</h1>
EOF;
            showSuccess(8);

echo <<<EOF
            <h2 class="title is-6">Activate or disable osu! settings</h1>

            <div class="grid-container">
              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="snaking_sliders">
                  <span class="slider_check round"></span>
                </label>
                <span>Snaking sliders</span>
              </div>

              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="storyboards">
                  <span class="slider_check round"></span>
                </label>
                <span>Storyboards</span>
              </div>

              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="background_videos">
                  <span class="slider_check round"></span>
                </label>
                <span>Background videos</span>
              </div>

              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="leaderboards">
                  <span class="slider_check round"></span>
                </label>
                <span>Leaderboard</span>
              </div>
              
              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="combo_bursts">
                  <span class="slider_check round"></span>
                </label>
                <span>Combo bursts</span>
              </div>

              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="hit_lighting">
                  <span class="slider_check round"></span>
                </label>
                <span>Hit lighting</span>
              </div>

              <div class="grid-item">
                <label class="switch_check">
                  <input type="checkbox" name="replay_hud">
                  <span class="slider_check round"></span>
                </label>
                <span>Replay HUD</span>
              </div>
            </div>

            <input type="submit" value="Save all modifications" class="button is-light"/>
          </form>
EOF;
}         

?>