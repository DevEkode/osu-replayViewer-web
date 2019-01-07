<?php

require_once 'blocks/skinChooser.php';
require_once 'blocks/skinUploader.php';
require_once 'blocks/skinRemover.php';

function generateBlocks(){
    switch($_GET['block']){
        case 'skin':
            block_skinChooser(); echo '<br>';
            block_skinUploader(); echo '<br>';
            block_skinRemover(); echo '<br>';
            break;
    }
}

?>