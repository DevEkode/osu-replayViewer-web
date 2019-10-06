<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require 'php/errors.php';
require 'php/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadatas -->
    <meta content="osu!replayViewer" property="og:title">
    <meta content="Share your osu! performance to everyone !" property="og:description">
    <meta content="osu!replayViewer" property="og:site_name">
    <meta content="http://osureplayviewer.xyz/images/icon.png" property='og:image'>
    <meta charset="UTF-8">

    <!-- Google / Search Engine Tags -->
    <meta itemprop="name" content="osu!replayViewer">
    <meta itemprop="description" content="Share your osu! performance to everyone !">
    <meta itemprop="image" content="http://osureplayviewer.xyz/images/icon.png">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-134700452-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-134700452-1');
    </script>


    <title>osu!replayViewer - An online osu replay viewer</title>
    <link rel="icon" type="image/png" href="images/icon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- libraries -->
    <script src="lib/jquery/jquery.min.js"></script> <!-- jQuery -->
    <script src="lib/bootstrap/bootstrap.bundle.min.js"></script> <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/bootstrap.css">

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">

    <!-- javascript -->
    <script src="js/loader.js"></script>
    <script src="js/index/validateUsername.js"></script>

    <!-- Cookie bar -->
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
</head>

<body>
<?php showError(); ?>
<script type="text/javascript" src="js/index/upload.js"></script>
<div class="loaderCustom"></div>
<!-- Modal -->
<div id="myModal" class="modal" onmouseover="disableClear()">

    <!-- Modal content -->
    <div class="modal-content">
        <h2 class="modal_title">Informations about your replay</h2>

        <div id="replay_box">
            <div>
                <?php
                if (isset($_SESSION['beatmapSetId'])) {
                    $url = "https://b.ppy.sh/thumb/" . $_SESSION['beatmapSetId'] . "l.jpg";
                    echo "<img src='" . $url . "' alt=\"replay preview image\" />";
                } else {
                    echo '<img src="images/preview.jpg" alt="replay preview image" />';
                }
                ?>
            </div>

            <div id="replay_text">
            <span id="beatmap"><span class="info_text">Beatmap :</span>
              <?php
              echo $_SESSION['beatmapName'];
              ?>
            </span>

                <span id="diff"><span class="info_text">Difficulty :</span>
              <?php
              echo $_SESSION['difficulty'];
              ?>
            </span>
                <span id="player"><span class="info_text">Player :</span>
              <?php
              echo $_SESSION['playername'];
              ?>
            </span>
                <span id="mods"><span class="info_text">Mods :</span>
              <?php
              echo $_SESSION['mods'];
              ?>
            </span>
                <span id="skin"><span class="info_text">Skin :</span>
              <?php echo $_SESSION['skinName']; ?>
            </span>
                <span id="time"><span class="info_text">Duration :</span>
              <?php
              $mins = floor($_SESSION['duration'] / 60 % 60);
              $secs = floor($_SESSION['duration'] % 60);
              echo $mins . 'min ' . $secs;
              ?>
            </span>
            </div>
        </div>

        <h2 class="modal_title">Checklist : </h2>
        <div id="check_list">
            <div class="modal_item">
                <img src="images/index/modal1.png" id="replayS" alt="replay structure image"/>
                <span class="modal_caption">Replay structure</span>
            </div>

            <div class="modal_item">
                <img src="images/index/modal2.png" id="beatmapA" alt="beatmap available image"/>
                <span class="modal_caption">Beatmap is available</span>
            </div>

            <div class="modal_item">
                <?php
                if ($_SESSION['replay_playerId'] != null) {
                    echo "<img src='" . "https://a.ppy.sh/" . $_SESSION['replay_playerId'] . "' id=\"playerA\"/>";
                } else {
                    echo '<img src="images/index/modal3.png" id="playerA" alt="Player account image"/>';
                }
                ?>
                <span class="modal_caption">The player has an <br>osu account</span>
            </div>

            <div class="modal_item">
                <img src="images/index/modal4.png" id="replayD" alt="Replay duration image"/>
                <span class="modal_caption">Replay is under 10min</span>
            </div>

            <div class="modal_item">
                <img src="images/index/modal5.png" id="replayDup" alt="Replay duplicate image"/>
                <span class="modal_caption">Replay is not a duplicate</span>
            </div>

            <div class="modal_item">
                <img src="images/index/modal6.png" id="replayW" alt="Waiting list image"/>
                <span class="modal_caption">Replay is not in <br>waiting list</span>
            </div>

        </div>

        <div id="replay_start">
            <?php
            require_once 'php/index/UploadLimiter.php';
            $limiter = UploadLimiter::getINSTANCE();
            $uploadRemaining = $limiter->getUploadsRemaining();
            $limit = getenv("UPLOAD_LIMIT_PER_DAY");

            echo "<div class='text-center'><span>Uploads remaining : $uploadRemaining / $limit</span></div><br>";

            if ($uploadRemaining != 0 && $_SESSION['replayStructure'] && $_SESSION['beatmapAvailable'] && $_SESSION['playerOsuAccount'] && $_SESSION['replayBelow10'] && $_SESSION['replayNotDuplicate'] && $_SESSION['replayNotWaiting']) {
                echo '<form class="align_center" method="post" enctype="multipart/form-data" action="php/index/upload.php">';
//                echo '<input id="checkBox" type="checkbox" name="checkbox"> <span id="checkboxText"> do not delete my replay after 30 days</span><br>';
                echo '<input id="checkBox" type="checkbox" name="checkboxTU"> <span id="checkboxText"> I accept the <a href="legal/TU.php?TU=replay" target="_blank">terms of uses</a></span><br>';
                echo '<input id="filename" name="filename" type="hidden" value=' . '"' . $_SESSION['filename'] . '"' . '>';
                echo '<input id="duration" name="duration" type="hidden" value=' . '"' . $_SESSION['duration'] . '"' . '>';
                echo '<input id="duration" name="keyHash" type="hidden" value=' . '"' . password_hash(getenv('UPLOAD_REPLAY_KEY'), PASSWORD_DEFAULT) . '"' . '>';
                echo '<input name="userId" type="hidden" value=' . '"' . $_SESSION['replay_playerId'] . '"' . '>';
                echo '<input type="submit" value="Start processing" id="start_processing">';
                echo '</form>';
            }
            ?>

            <button onclick="closeModal(); clearSession();">Cancel</button>
        </div>
    </div>

</div>

<div id="askUsername_modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <h2 class="modal_title">Information needed !</h2>
        <h3 class="modal_par">The username for this replay doesn't exists</h3>
        <h3 class="modal_par">Please enter the username to which the replay will be assigned</h3>

        <form onsubmit="return validateName()" class="align_center" method="post" enctype="multipart/form-data"
              action="php/index/newUsername.php">
            <div id="userBox">
                <img src="https://a.ppy.sh/1" id="userImage" alt="user image"/>
                <input type="text" name="newUsername" id="newUsername" onchange="updatePicture()"
                       placeholder="username or osu!ID" required>
                <button type="button" onclick="updatePicture()" id="newUserButton"><i class="material-icons">refresh</i>
                </button>
            </div>

            <?php
            $newArray = array_diff_key($_SESSION, array_flip(array('username', 'userId')));
            $arraySerial = serialize($newArray);
            $array64 = base64_encode($arraySerial);
            ?>

            <input type="hidden" name="session" value=<?php echo "'" . $array64 . "'" ?>>
            <input type="submit" value="Continue" id="continue_btn">
        </form>

        <div id="replay_start">
            <button onclick="closeModalUsername(); clearSession();">Cancel</button>
        </div>
    </div>

</div>

<!-- Activate modal view when session is full -->
<?php
if (isset($_SESSION['replayStructure'])) {
    $string = '';
    if (!$_SESSION['replayStructure']) {
        $string .= "setItemFalse('replayS'); ";
    }
    if (!$_SESSION['beatmapAvailable']) {
        $string .= "setItemFalse('beatmapA'); ";
    }
    if (!$_SESSION['playerOsuAccount']) {
        $string .= "setItemFalse('playerA'); ";
    }
    if (!$_SESSION['replayBelow10']) {
        $string .= "setItemFalse('replayD'); ";
    }
    if (!$_SESSION['replayNotDuplicate']) {
        $string .= "setItemFalse('replayDup'); ";
    }
    if (!$_SESSION['replayNotWaiting']) {
        $string .= "setItemFalse('replayW'); ";
    }

    if ($_SESSION['playerOsuAccount']) {
        echo '<script type="text/javascript">',
        'openModal();',
        '</script>';
    } else {
        echo '<script type="text/javascript">',
        'openModalUsername();',
        '</script>';
    }


    echo "<script type=\"text/javascript\">" . $string . "</script>";

    if (!$_SESSION['replayStructure'] || !$_SESSION['beatmapAvailable'] || !$_SESSION['playerOsuAccount'] || !$_SESSION['replayBelow10'] || !$_SESSION['replayNotDuplicate'] || !$_SESSION['replayNotWaiting']) {
        echo '<script type="text/javascript">',
        '</script>';
    }
}
?>

<!-- Top navigation bar -->
<?php showNavbar(); ?>

<!-- presentation -->
<h1 id="title">osu!replayViewer</h1>

<h2 id="slogan">Share your osu! performance to everyone !</h2>

<div id="etapes">
    <div class="item">
        <picture>
            <img
                    sizes="(max-width: 500px) 100vw, 500px"
                    srcset="
          images/small/index/etape1.png 200w,
          images/medium/index/etape1.png 361w,
          images/large/index/etape1.png 500w"
                    src="images/large/index/etape1.png 500w"
                    alt="First step of replay processing">
        </picture>
        <span class="caption">1. Upload your replay</span>
    </div>

    <div class="item">
        <img src="images/etape2.png" alt="Etape 2 of replay processing"/>
        <span class="caption">2. Wait processing time</span>
    </div>

    <div class="item">
        <img src="images/etape3.png" alt="Last step of replay processing"/>
        <span class="caption">3. Share it !</span>
    </div>
</div>

<form action="#upload_section" class="align_center">
    <input type="submit" value="Begin !" class="button"/>
</form>

<!-- Announcement -->
<!--
<div class="alert alert-warning" role="alert" style="width:50vw; margin: auto;">
    <b style="color: red">End of beta / pause</b><br><br>
    <p>
        I have chosen to pause this project that is dear to my heart.
        To be short, this is mainly due to a lack of time and equipment.
        I'm going to come back to this right after. But before that, here are the consequences of this pause:
    </p>
    <ul>
        <li>It will no longer be possible to post replays on the site.</li>
        <li>The site will remain open and all replays already present will still be accessible.</li>
        <li>Accounts will not be deleted.</li>
    </ul>
    <b style="color: red">Why ?</b><br><br>
    <p>
        As some may know, I am a computer science student and there is not enough time to maintain the site properly.<br>
        I don't even mention the problems of financing the site (and yes, servers and storage are not free).<br>
        But also I need to ask myself to think about the rest of the site, it's not a lack of motivation.<br>

        In fact, I already have several ideas.<br>
        That's why I invite you to join the official discord of the site: <a href="https://discord.gg/pqvhvxx">https://discord.gg/pqvhvxx</a><br>
        and also on my GitHub <a href="https://github.com/codevirtuel">https://github.com/codevirtuel</a>.<br>
        I would probably need the help of other developers for the good continuation of my project.
    </p>
    <b style="color: red">Conclusion</b><br><br>
    <p>
        I remain active on discord and GitHub, I will try to discuss more often (especially in voice chat) to shape a site even more functional than during the Beta.
        <br><br>
        Thank you for your loyalty ;)
    </p>

    <i style="color:gray">codevirtuel ~ main dev of osu!replayViewer</i>
</div>
-->


<!-- Upload -->
<section id="upload_section"></section>

<h2 id="upload_title">Select your osu replay to upload (.osr)</h2>
<h2 id="upload_subtitle">Drag and drop or open the explorer</h2>

<!-- Upload box -->
<?php
require_once 'php/disableUploads.php';
require_once 'php/admins.php';
if (isset($_SESSION['userId']) && in_array($_SESSION['userId'], $admins)) {
    $disableUploads = false;
}

if (!$disableUploads) {
    echo '<form action="php/index/replayFileVerf.php" method="post" enctype="multipart/form-data" id="upload_box">';
    echo '<input type="file" name="fileToUpload" id="fileToUpload" oninput="submitForm()">';
    echo '</form>';
} else {
    echo '<div id="upload_box">';
    echo '<h2 id=\'fileToUpload\'>🚧 Uploads are disabled for now, try again later 🚧<br>';
    echo '<span style="font-size:10px;font-style: oblique;">We\'re sorry for that btw</span>';
    echo '</h2>';
    echo '</div>';
}
?>

<?php showFooter() ?>
</body>

</html>
