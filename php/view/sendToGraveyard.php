<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/cron/ReplayCompressor.php';

//Redirect if user is not auth or missing arguments
if (!isset($_GET['replayId']) || empty($_SESSION['userId'])) {
    header('Location:../index.php');
}

$replayId = filter_var($_GET['replayId'], FILTER_SANITIZE_STRING);

//Engage replay compression
$compressor = new ReplayCompressor();
$compressor->compressReplay($replayId);

//Redirect user to index or requested page
if (isset($_GET['redirect'])) {
    $redirect = filter_var($_GET['redirect'], FILTER_SANITIZE_STRING);
    //header("Location:../../editProfile.php?block=" . $redirect);
} else {
    //header('Location:../../index.php');
}
