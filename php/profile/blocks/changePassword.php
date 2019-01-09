<?php
function block_changePassword(){
    echo <<<EOF
    <form action="php/profile/changePassword.php" method="post" id="columnBack" class="passForm">
        <h1 class="title is-4">- Change password -</h1>
EOF;

    showSuccess(4);

    echo <<<EOF
    Current password : <br>
          <input class="input" type="password" name="oldPassword" required /><br>
          New password : <br>
          <input class="input" type="password" name="newPassword" id="pass" required /><br>
          Retype new password : <br>
          <input class="input" type="password" name="newPasswordVerf" id="confPass" onkeyup="showCheckPass()" required /><br>
          <span style="text-shadow:1px 1px 0 #444;color:red" id="checkPass"></span><br>
          <input type="submit" value="Submit" class="button is-light"/>
        </form>
EOF;
}

          
    
?>