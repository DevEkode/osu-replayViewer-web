<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//-- Connect to mysql request database --

//Time limit in days
$timeLimit = 1;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    exit;
}

function getRemovableAccounts($conn, $timeLimit)
{
    $array = array();

    $query = $conn->prepare("SELECT * FROM accounts WHERE date < DATE_SUB(now(), INTERVAL '$timeLimit' DAY) AND verificationId<>\"\" AND verfIdEmail<>\"\" ");
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_assoc()) {
        $userId = $row["userId"];
        $canBeDeleted = $row["canBeDeleted"];
        if ($canBeDeleted) {
            array_push($array, $userId);
        }
    }
    $query->close();
    return $array;
}

function deleteAccount($conn, $userId)
{
    //detete account from database
    $query = $conn->prepare("DELETE FROM accounts WHERE userId=?");
    $query->bind_param("i", $userId);
    $query->execute();
    $query->close();

    //TODO delete account folder form server
}

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

$accountsToRemove = getRemovableAccounts($conn, $timeLimit);
if (!empty($accountsToRemove)) {
    foreach ($accountsToRemove as $userId) {
        deleteAccount($conn, $userId);
        echo 'Deleted account with id ' . $userId . ' <br>';
    }
} else {
    echo 'No account to delete';
}
?>
