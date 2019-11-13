<?php

//Generate html block for skin uploader
function block_skinUploader(){
    //Show first part
    echo <<<EOF
    <form action="php/profile/uploadSkin.php" method="post" enctype="multipart/form-data" id="columnBack">
      <h1 class="title is-4">- Custom skin uploader -</h1>
EOF;

    showSuccess(0);

    echo <<<EOF
      Select skin to upload (or drag and drop): <br>
      <br>
      <input type="file" name="fileToUpload" id="fileToUpload" accept=".osk" oninput="onClick(this)"/> <br>
    </form>
EOF;
    
}
          
            

?>