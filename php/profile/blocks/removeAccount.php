<?php
function block_removeAccount(){
    echo <<<EOF
    <div id="columnBack">
        <h1 class="title is-4">- Delete account -</h1>
        <a class="button is-danger" onclick="openModalDelete()">⚠️ Delete my account ⚠️</a>
    </div>
EOF;
}         
    
?>