<?php
session_start();
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

    <title>osu!replayViewer - Patreon supporters page</title>
    <link rel="icon" type="image/png" href="images/icon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- libraries -->
    <script src="lib/jquery/jquery.min.js"></script> <!-- jQuery -->
    <script src="lib/bootstrap/bootstrap.bundle.min.js"></script> <!-- Bootstrap -->
    <link rel="stylesheet" type="text/css" href="lib/bootstrap/bootstrap.css">

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/patreon.css">
    <link rel="stylesheet" type="text/css" href="css/footer.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">

    <!-- javascript -->
    <script src="js/loader.js"></script>
    <script src="js/patreon/loadBlocks.js"></script>

    <!-- Cookie bar -->
    <script type="text/javascript"
            src="https://cdn.jsdelivr.net/npm/cookie-bar/cookiebar-latest.min.js?theme=flying&tracking=1&always=1&scrolling=1"></script>
</head>

<body>
<!-- Top navigation bar -->
<?php showNavbar(); ?>

<!-- presentation -->
<h1 id="title">Patreon supporters</h1>

<h2 id="slogan">Help me build this project !</h2>

<div class="align_center" style="margin-bottom: 1%">
    <a href="https://www.patreon.com/bePatron?u=17710775" data-patreon-widget-type="become-patron-button">Become a Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>
</div>

<div id="tier_container" style="margin-left:5%; margin-right:5%">

</div>

<div class="loaderCustom"></div>
<?php showFooter() ?>
</body>