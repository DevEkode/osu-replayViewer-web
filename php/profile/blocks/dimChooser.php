<?php

//Generate html block for skin remover
function block_dimChooser(){
  $actualDim = getIniKey($_SESSION["userId"],"dim");

  echo <<<EOF
  <form action="php/profile/saveUserIni.php" method="post">
        <div id="dimZone">
        <h1 class="title is-4">- Custom dim chooser -</h1>
EOF;
  
  showSuccess(7);

  echo <<<EOF
         Background dim value : <span id="dimValue"></span><br>
         <br>
EOF;

  echo '<input type="range" min="0" max="100" value='.$actualDim.' class="slider" oninput="showDim()" name="dim" id="dimRange"> <br>';

  echo <<<EOF
  <br>
        Background dim preview : <br>
          <img src="images/preview.jpg" id="dimPreview"></img>
        

        <br>
        <input type="submit" value="Save background dim" class="button is-light"/>
        </div>
      </form>
EOF;

}


        
?>