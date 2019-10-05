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
    <script src="js/profile/replaySelect.js"></script>
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

<div class="modal" id="delete_replay_modal">
    <div class="modal-content">
        <h2>Do you really want to delete this replay ?</h2>
        <form action="php/view/deleteReplay.php" method="get" id="form_delete_replay_modal">
            <input type="submit" id="button_yes" value="Yes please !"/>
            <input type="hidden" id="value_delete_replayId" name="replayId" value=""/>
            <input type="hidden" id="value_delete_redirect" name="redirect" value=""/>
        </form>
        <button id="button_no" onclick="closeModalDeleteReplay()">No stop !</button>
    </div>
</div>

<div class="modal" id="graveyard_replay_modal">
    <div class="modal-content">
        <h2>Do you really want to graveyard this replay ?</h2>
        <h4>Only the replay and skin file will be saved</h4>
        <form action="php/view/sendToGraveyard.php" method="get" id="form_graveyard_replay_modal">
            <input type="submit" id="button_yes" value="Yes please !"/>
            <input type="hidden" id="value_graveyard_replayId" name="replayId" value=""/>
            <input type="hidden" id="value_graveyard_redirect" name="redirect" value=""/>
        </form>
        <button id="button_no" onclick="closeModalGraveyardReplay()">No stop !</button>
    </div>
</div>

<div class="modal" id="pending_replay_modal">
    <div class="modal-content">
        <h2>Do you really want to cancel this replay ?</h2>
        <form action="php/progress/cancelReplay.php" method="post" id="form_pending_replay_modal">
            <input type="submit" id="button_yes" value="Yes please !"/>
            <input type="hidden" id="value_pending_replayId" name="replayId" value=""/>
            <input type="hidden" id="value_pending_redirect" name="redirectTo" value=""/>
            <input type="hidden" id="value_pending_md5" name="replayMd5" value=""/>
        </form>
        <button id="button_no" onclick="closeModalPendingReplay()">No stop !</button>
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
        </div>
    </div>
</div>

<div class="spacer">
    <br>
</div>

<?php showFooter() ?>
</body>
</html>
