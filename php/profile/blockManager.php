<?php

require_once 'blocks/skinChooser.php';
require_once 'blocks/skinUploader.php';
require_once 'blocks/skinRemover.php';
require_once 'blocks/dimChooser.php';
require_once 'blocks/gameSettingsChooser.php';
require_once 'blocks/cursorSizeChooser.php';
require_once 'blocks/changePassword.php';
require_once 'blocks/changeEmail.php';
require_once 'blocks/removeAccount.php';
require_once 'blocks/volumeChooser.php';

function generateBlocks(){
    switch($_GET['block']){
        case 'posted':
            break;
        case 'graveyard':
            break;
        case 'skin':
            block_skinChooser(); echo '<br>';
            block_skinUploader(); echo '<br>';
            block_skinRemover(); echo '<br>';
            break;
        case 'game':
            block_dimChooser(); echo '<br>';
            block_gameSettingsChooser(); echo '<br>';
            block_cursorSizeChooser(); echo '<br>';
            block_volumeChooser(); echo '<br>';
            break;
        case 'security':
            block_changePassword(); echo '<br>';
            block_changeEmail(); echo '<br>';
            block_removeAccount(); echo '<br>';
            break;
    }
}

function generateMenu(){
    echo <<<EOF
          <aside class="menu">
            <p class="menu-label" id="itemLabel">Replay database</p>
            <ul class="menu-list">
EOF;
    //Replay database
    if ($_GET['block'] == 'posted') {
        echo '<li><a href="#" class="is-active">📡 Posted replays</a></li>';
    } else {
        echo '<li><a href="editProfile.php?block=posted">📡 Posted replays</a></li>';
    }
    //Replay graveyard
    if ($_GET['block'] == 'graveyard') {
        echo '<li><a href="#" class="is-active">⚰️ Graveyard</a></li>';
    } else {
        echo '<li><a href="editProfile.php?block=graveyard">⚰️ Graveyard</a></li>';
    }
    echo <<<EOF
          </ul>
            <p class="menu-label" id="itemLabel">Replay customisation</p>
            <ul class="menu-list">
EOF;
    //Skin
    if($_GET['block'] == 'skin'){
        echo '<li><a href="#" class="is-active">📖 Skin</a></li>';
    }else{
        echo '<li><a href="editProfile.php?block=skin">📖 Skin</a></li>';
    }

    //Game
    if($_GET['block'] == 'game'){
        echo '<li><a href="#" class="is-active">⚙️ Game settings</a></li>';
    }else{
        echo '<li><a href="editProfile.php?block=game">⚙️ Game settings</a></li>';
    }
    
    echo <<<EOF
    
            <p class="menu-label" id="itemLabel">Account</p>
            <ul class="menu-list">
EOF;

    //Security
    if($_GET['block'] == 'security'){
        echo '<li><a href="#" class="is-active">🔐 Security</a></li>';
    }else{
        echo '<li><a href="editProfile.php?block=security">🔐 Security</a></li>';
    }
              
    echo '</ul></aside>';



}

            

?>