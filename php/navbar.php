<?php
    //Generate the top navigation bar

    function showNavbar(){
        //Show first part in html
        echo <<<EOF
        <link rel="stylesheet" type="text/css" href="css/navbar.css">
        <div class="top-nav">
            <div class="floatleft">
            <a href="search.php" class="nav-link">
                <i class="material-icons">search</i> Search</a>
            <a href="faq.php" class="nav-link">
                <i class="material-icons">question_answer</i> FAQ</a>
            </div>
    
            <a href="index.php" id="logo">
                <img src="images/icon.png" alt="osu!replayViewer logo" />
            </a>
EOF;
        //Show php part

        if(isset($_SESSION['userId']) && isset($_SESSION['username'])){
            $userUrl = "userProfile.php?id=".$_SESSION['userId'];
            echo '<div class="floatright">';
            echo  "<a href=$userUrl class=\"nav-link\">\n";
            echo    '<i class="material-icons">account_circle</i> Profile</a>';
            echo  '<a href="logout.php" class="nav-link">';
            echo    '<i class="material-icons">cloud_off</i> Logout</a>';
            echo '</div>';
          }else{
            echo '<div class="floatright">';
            echo  '<a href="register.php" class="nav-link">';
            echo    '<i class="material-icons">how_to_reg</i> Register</a>';
            echo  '<a href="login.php" class="nav-link">';
            echo    '<i class="material-icons">vpn_key</i> Login</a>';
            echo '</div>';
          }

        echo '</div>';
    }

?>