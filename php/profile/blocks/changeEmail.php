<?php
function block_changeEmail(){
    echo <<<EOF
    <form action="php/profile/changeEmail.php" method="post" id="columnBack" class="passForm">
    <h1 class="title is-4">- Update email -</h1>
EOF;

    showSuccess(3);

    echo <<<EOF
    New email address : <br>
    <input class="input" type="email" name="newEmail" placeholder="Email" required /><br>
    <br>
    <input type="submit" value="Update email" class="button is-light"/>
    </form>
EOF;
}         
    
?>