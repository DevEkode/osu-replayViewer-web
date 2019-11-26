<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';
include_once './autoAccountDelete.php';
include_once 'ReplayCompressor.php';

//-- Regroup every cron file for execution --

//Time limit in days
$timeLimit = 1;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_HOST'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//FTP
$conn_id = ftp_connect(getenv('FTP_HOST'));
$login_result = ftp_login($conn_id, getenv('FTP_USER'), getenv('FTP_PASS'));


// check connection
if ((!$conn_id) || (!$login_result)) {
    die("FTP connection has failed !");
}

if (ftp_chdir($conn_id, getenv('FTP_DIR'))) {
    echo "Current directory is now: " . ftp_pwd($conn_id) . "\n";
} else {
    echo "Couldn't change directory\n";
}

// --- Functions
function cleanFolder($dir)
{
    //delete all folder files
    $files = glob($dir . "/*"); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file); // delete file
        }
    }
}

function removeFolder($dir)
{
    cleanFolder($dir);
    //delete folder
    rmdir($dir);
}

function cleanRemoteFolder($dir, $conn_id)
{
    //delete all folder files
    $files = ftp_nlist($conn_id, "./" . $dir); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            ftp_delete($conn_id, $file);
        }
    }
}

function removeRemoteFolder($dir, $conn_id)
{
    cleanRemoteFolder($dir, $conn_id);
    //delete folder
    ftp_rmdir($conn_id, $dir);
}

function cleanRequestFolder($conn)
{
    $array = array();

    $result = $conn->query("SELECT * FROM requestlist");
    while ($row = $result->fetch_assoc()) {
        $replayId = $row['replayId'];
        array_push($array, $replayId);
    }

    $folders = scandir("./requestList/");
    foreach ($folders as $folder) {
        if (!in_array($folder, $array) && $folder != "." && $folder != "..") {
            removeFolder("./requestList/" . $folder);
        }
    }
}

function cleanReplayFolder($conn, $conn_id)
{
    $array = array();

    $result = $conn->query("SELECT * FROM replaylist");
    while ($row = $result->fetch_assoc()) {
        $replayId = $row['replayId'];
        array_push($array, $replayId);
    }

    $folders = ftp_nlist($conn_id, ".");
    foreach ($folders as $folder) {
        if (!in_array($folder, $array) && $folder != "." && $folder != "..") {
            removeRemoteFolder($folder, $conn_id);
        }
    }
}

echo "==== Deleting account with register time expired ====";

$accountsToRemove = getRemovableAccounts($conn,$timeLimit);
if(!empty($accountsToRemove)){
    foreach($accountsToRemove as $userId){
        deleteAccount($conn,$userId);
        echo 'Deleted account with id '.$userId.' <br>';
    }
}else{
    echo 'No account to delete';
}

echo "==== Deleting replays expired  ====";

$compressor = new ReplayCompressor();
$compressor->compressExpiredReplays();

echo "==== Clean database ====";

echo "==== Clean filesystem ====";
cleanRequestFolder($conn);
cleanReplayFolder($conn, $conn_id);


