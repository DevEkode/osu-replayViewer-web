<?php
// Autoloader composer
define('DIR_VENDOR', $_SERVER['DOCUMENT_ROOT'] . '/vendor/');
if (file_exists(DIR_VENDOR . 'autoload.php')) {
    require_once(DIR_VENDOR . 'autoload.php');
}

//Import env
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

//Debug config
if (getenv('DEBUG_VERSION')) {
    # Debug
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    # Production
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_STRICT);
}