<?php
session_start();
require 'php/errors.php';
require 'php/success.php';
require 'php/navbar.php';

require 'php/profile/blockManager.php';

if (empty($_SESSION['userId'])) {
    header("Location:index.php");
}

require 'php/profile/replaySettings.php';

//Query user information
checkUserFile($_SESSION["userId"]);
/*$skins = listAllSkins($_SESSION["userId"]);
$customSkin = getIniKey($_SESSION["userId"],,"enable");
$actualSkin = getIniKey($_SESSION["userId"],"fileName");
$actualDim = getIniKey($_SESSION["userId"],"dim");
$actualCursorSize = getIniKey($_SESSION["userId"],"cursor_size");*/
?>

<!DOCTYPE html>
<html>
<head>
    <script src="js/request.js"></script>
    <script src="js/editProfile.js"></script>

    <title>osu!replayViewer - edit profile</title>
    <link rel="stylesheet" type="text/css" href="css/navbar.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">

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


    <link rel="icon" type="image/png" href="images/icon.png"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="node_modules/bulma/css/bulma.css">
    <link rel="stylesheet" type="text/css" href="node_modules/bulma-tooltip/dist/css/bulma-tooltip.min.css">
    <link rel="stylesheet" type="text/css"
          href="node_modules/cool-checkboxes-for-bulma.io/dist/css/bulma-radio-checkbox.min.css">
    <link rel="stylesheet" type="text/css" href="css/editProfile.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="js/loader.js"></script>
    <script src="js/profile/uploadSkin.js"></script>
    <script src="js/profile/modal.js"></script>
    <script src="js/profile/cursorSize.js"></script>
    <script src="js/profile/volume.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- Cookie bar -->
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>

    <link rel="stylesheet" type="text/css" href="css/loader.css">
    <link rel="stylesheet" type="text/css" href="css/checkboxSwitch.css">
</head>

<body onload="showDim(); showCursorSize(); updateCustomSkin(); updateVolume();">
<div class="loaderCustom"></div>
<?php showNavbar(); ?>
<?php showError(); ?>

<!-- Modal -->
<div class="modal" id="delete_modal">
    <div class="modal-content">
        <h2>Do you really want to delete your account ?</h2>
        <h4>A verification email will be send if you accept</h4>
        <form action="php/profile/deleteProfile.php" method="post">
            <input type="submit" id="button_yes" value="Yes please !"/>
            <input type="hidden" name="id" value=<?php echo $_SESSION['userId']; ?>/>
        </form>
        <button id="button_no" onclick="closeModalDelete()">No stop !</button>
    </div>
</div>

<h1 id="TopTitle"> Edit profile </h1>

<div class="block" id="replay">

    <div class="columns is-desktop">
        <div class="column is-one-quarter-desktop is-full-tablet" id="columnBack">
            <!-- First column -->
            <?php generateMenu(); ?>
        </div>
        <div class="column">
            <!-- Second column -->
            <?php generateBlocks(); ?>
            <div class="columns is-desktop is-multiline">
                <div class="column is-4-fullhd is-6-desktop is-12-tablet">
                    <div class="card grow">
                        <div class="card-image">
                            <figure class="image is-4by2 container_check">
                                <img src="images/preview.jpg" alt="Placeholder image">
                                <div class="b-checkbox checkbox_card">
                                    <input id="checkbox" class="styled" type="checkbox">
                                    <label for="checkbox">
                                    </label>
                                </div>
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-48x48 tooltip" data-tooltip="Osu!CTB">
                                        <img class="is-rounded has-background-grey-dark" src="images/osuCTB.png"
                                             alt="Placeholder image">
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-4 card_title">Watashi no Tame no Monogatari ~My Uncompleted
                                        Story~</p>
                                    <p class="subtitle is-6">Shizuku's Expert</p>
                                </div>
                            </div>

                            <div class="content">
                                <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-footer-item tooltip is-tooltip-bottom"
                               data-tooltip="Copy link">🔗</a>
                            <a href="#" class="card-footer-item tooltip is-tooltip-bottom"
                               data-tooltip="Send to graveyard">⚰️</a>
                            <a href="#" class="card-footer-item tooltip is-tooltip-bottom" data-tooltip="Delete">🗑️</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="spacer">
    <br>
</div>

<?php showFooter() ?>
</body>
</html>
