<?php
// Autoloader composer
define('DIR_VENDOR', $_SERVER['DOCUMENT_ROOT'] . '/vendor/');
if (file_exists(DIR_VENDOR . 'autoload.php')) {
    require_once(DIR_VENDOR . 'autoload.php');
}

//Import env
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

//Load limiter
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/index/UploadLimiter.php';
UploadLimiter::getINSTANCE();

//Debug config
if (getenv('DEBUG_VERSION')) {
    # Debug
    error_reporting(E_ALL); // Error engine - always TRUE!
    ini_set('ignore_repeated_errors', TRUE); // always TRUE
    ini_set('display_errors', TRUE); // Error display - FALSE only in production environment or real server
    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/errors.log'); // Logging file path
    ini_set('log_errors_max_len', 1024); // Logging file size
} else {
    # Production
    error_reporting(E_ALL); // Error engine - always TRUE!
    ini_set('ignore_repeated_errors', TRUE); // always TRUE
    ini_set('display_errors', FALSE); // Error display - FALSE only in production environment or real server
    ini_set('log_errors', TRUE); // Error logging engine
    ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/errors.log'); // Logging file path
    ini_set('log_errors_max_len', 1024); // Logging file size
}