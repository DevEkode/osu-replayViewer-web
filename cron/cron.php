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

echo "==== Deleting account with register time expired ===";

$accountsToRemove = getRemovableAccounts($conn,$timeLimit);
if(!empty($accountsToRemove)){
    foreach($accountsToRemove as $userId){
        deleteAccount($conn,$userId);
        echo 'Deleted account with id '.$userId.' <br>';
    }
}else{
    echo 'No account to delete';
}

?>