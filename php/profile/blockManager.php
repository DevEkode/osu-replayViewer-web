<?php

require_once 'blocks/skinChooser.php';
require_once 'blocks/skinUploader.php';
require_once 'blocks/skinRemover.php';
require_once 'blocks/dimChooser.php';
require_once 'blocks/gameSettingsChooser.php';
require_once 'blocks/cursorSizeChooser.php';

function generateBlocks(){
    switch($_GET['block']){
        case 'skin':
            block_skinChooser(); echo '<br>';
            block_skinUploader(); echo '<br>';
            block_skinRemover(); echo '<br>';
            break;
        case 'game':
            block_dimChooser(); echo '<br>';
            block_gameSettingsChooser(); echo '<br>';
            block_cursorSizeChooser(); echo '<br>';
            break;
    }
}

function generateMenu(){
    echo <<<EOF
          <aside class="menu">
            <p class="menu-label" id="itemLabel">Replay customisation</p>
            <ul class="menu-list">
EOF;
    //Skin
    if($_GET['block'] == 'skin'){
        echo '<li><a href="#" class="is-active">Skin</a></li>';
    }else{
        echo '<li><a href="editProfile.php?block=skin">Skin</a></li>';
    }

    //Game
    if($_GET['block'] == 'game'){
        echo '<li><a href="#" class="is-active">Game settings</a></li>';
    }else{
        echo '<li><a href="editProfile.php?block=game">Game settings</a></li>';
    }
    
    echo <<<EOF
    </ul>
            <p class="menu-label" id="itemLabel">Account</p>
            <ul class="menu-list">
              <li><a>Credentials</a></li>
              <li><a>More</a></li>
            </ul>
          </aside>
EOF;


}

            

?>