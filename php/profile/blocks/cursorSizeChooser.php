<?php

//Generate html block for skin remover
function block_cursorSizeChooser(){
  $actualCursorSize = getIniKey($_SESSION["userId"],"cursor_size");

  echo <<<EOF
  <form action="php/profile/form_cursorSize.php" method="post" id="columnBack">
            <h1 class="title is-4">- Custom cursor size chooser -</h1>
EOF;

  showSuccess(9);

  echo 'Cursor size value : <span id="cursorSizeValue"></span><br>';
  echo '<input type="range" step="0.01" min="0.01" max="2" value='.$actualCursorSize.' class="slider" oninput="showCursorSize()" name="cursorSize" id="cursorSizeRange"> <br>';
  echo '<input type="submit" value="Save cursor size" /></form>';
}


        
?>