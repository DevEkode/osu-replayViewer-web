<?php
    //Generate the top navigation bar

$unstable_url = "unstable.osureplayviewer.xyz";
if ($_SERVER['HTTP_HOST'] === $unstable_url) {
    $icon_url = "images/u_icon.png";
} else {
    $icon_url = "images/icon.png";
}

    function showNavbar(){
        global $icon_url;
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
                <img src="$icon_url" alt="osu!replayViewer logo" />
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

    function showFooter(){
        echo <<<EOF
        <footer>
      <h3 class="align_center">osu!replayViewer is not affiliated with osu! - All credit to Dean Herbert</h3>
      <div class="footer_img">
        <a href="https://discord.gg/pqvhvxx" title="join us on discord!" target="_blank">
          <picture>
            <img
            sizes="(max-width: 1400px) 100vw, 1400px"
            srcset="images/small/index/Discord_logo.png 200w,
            images/medium/index/Discord_logo.png 699w,
            images/large/index/Discord_logo.png 1048w,
            images/xlarge/index/Discord_logo.png 1400w"
            src="images/xlarge/index/Discord_logo.png"
            alt="Discord_logo">
          </picture>
        </a>
        <a href="https://osu.ppy.sh/community/forums/topics/697883" target="_blank">
          <picture>
            <img
            sizes="(max-width: 280px) 100vw, 280px"
            srcset="
            images/small/index/osu_forums.png 200w,
            images/medium/index/osu_forums.png 280w"
            src="images/medium/index/osu_forums.png"
            alt="osu forums logo">
          </picture>
        </a>
        <a href="https://github.com/codevirtuel/osu-replayViewer-web" target="_blank">
          <picture>
            <img
            sizes="(max-width: 512px) 100vw, 512px"
            srcset="
            images/small/index/github_logo.png 200w,
            images/medium/index/github_logo.png 512w"
            src="images/medium/index/github_logo.png"
            alt="github logo">
          </picture>
        </a>
        <a href="patreon.php">
          <img src="images/index/patreon_logo.png"/>
        </a>
      </div>

      <div id="created">
        <span> website created by Ekode <a href="https://osu.ppy.sh/u/3481725" target="_blank">
          <picture>
            <img
            sizes="(max-width: 400px) 100vw, 400px"
            srcset="
            images/small/index/codevirtuel.jpg 200w,
            images/medium/index/codevirtuel.jpg 400w"
            src="images/medium/index/codevirtuel.jpg"
            alt="codevirtuel profile image">
          </picture>
        </a></span>
      </div>
    </footer>
EOF;
    }

?>