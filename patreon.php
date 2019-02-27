<?php
    session_start();
    require 'php/navbar.php';
?>

<!DOCTYPE html>
<html>

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
        function gtag() { dataLayer.push(arguments); }
        gtag('js', new Date());
        gtag('config', 'UA-134700452-1');
    </script>

    <title>osu!replayViewer - Patreon supporters page</title>
    <link rel="icon" type="image/png" href="images/icon.png" />
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

    <div style="margin-left:5%; margin-right:5%">
    <div class="container-fluid" id="tier_block">
        <div class="row">
            <div class="col-xs-9 col-sm-9 col-md-9">
                <h3><b>Tier Name</b>
                <small class="text-muted">- X pledgers</small>
                </h3>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3" id="join_block_button">
                <button type="button" class="btn btn-danger">Join for 1$ !</button>
            </div>
        </div>
        <div class="row">
            </p>
            <div class="col-xs-9 col-sm-9 col-md-9">
                <h4>Thank you ❤️,<h4>
                <p class="lead">
                <small class="text-muted">Name1, Name2, Name3, ...</small>
            </div>
            <div class="col-xs-3 col-sm-3 col-md-3">
                <small class="text-muted">View all the benefits on the patreon page</small>
            </div>
        </div>
    </div>
</div>

    <div class="loaderCustom"></div>
    <?php showFooter() ?>
</body>