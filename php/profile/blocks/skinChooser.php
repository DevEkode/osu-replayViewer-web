<?php
require_once 'php/profile/ini.class.php';

//Generate html block for skin uploader
function block_skinChooser(){
    //Show first part
    echo '<form action="php/profile/form_skinChooser.php" method="post" id="columnBack">';
    echo '<h1 class="title is-4">- Custom skin chooser -</h1>';
    showSuccess(2);

    block_skinChooser_body();
    
    //Show last part
    echo <<<EOF
    
    </form>
EOF;
}

function block_skinChooser_body(){
    //Get all users skins
    $skins = listAllSkins($_SESSION["userId"]);
    $customSkin = getIniKey($_SESSION["userId"],'skin',"enable");
    $actualSkin = getIniKey($_SESSION["userId"],'skin',"fileName");

    if(empty($skins)){
        echo "<h2 style=\"color:red\">You have to upload at least one skin to use this functionnality</h2>";

        //Check ini file
        $ini_dir = './accounts/'.$_SESSION["userId"].'/'.$_SESSION["userId"].'.ini';
        $ini = new Ini();
        $ini->read($ini_dir);

        $ini->set('skin','enable',"0");
        $ini->write($ini_dir);

        echo '<br/>';
      }else{
        //Check box to enable custom skin
        echo "Enable custom skin: <br>";
        echo "<span style=\"font-size:13px\"> By default the osu!replayViewer skin is used</span><br>";
        echo '<label class="checkbox">';
        if($customSkin == 'true'){
          echo '<input type="checkbox" name="customSkin" id="checkBox" oninput="updateCustomSkin()" checked>';
        }else{
          echo '<input type="checkbox" name="customSkin" oninput="updateCustomSkin()" id="checkBox">';
        }
        echo '</label>';
        echo '<br><br>';

      echo "Choose your custom skin : <br>";

      //Combobox with all skins uploaded
      echo '<div class="select">';
      echo "<select id='skinsSelector' name='skin'>";
        foreach($skins as $skin)
        {
          if($skin == $actualSkin){
            echo "<option value='".$skin."' selected>".$skin."</option>";
          }else{
            echo "<option value='".$skin."'>".$skin."</option>";
          }
        }
        echo "</select></div>";
        echo '<br/><br/>';
        echo '<input type="submit" value="Save all modifications" class="button is-light"/>';
    }
}
            

?>