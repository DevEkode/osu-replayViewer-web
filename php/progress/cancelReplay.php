<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

function cleanFolder($dir)
{
    //delete all folder files
    $files = glob($dir . "/*"); // get all file names
    foreach ($files as $file) { // iterate files
        if (is_file($file)) {
            unlink($file); // delete file
        }
        if (is_dir($file)) {
            removeFolder($file);
        }
    }
}

function removeFolder($dir)
{
    cleanFolder($dir);
    //delete folder
    rmdir($dir);
}

//Get post
//Construct sanitized replay array
$replays = array();
$json_get = json_decode($_POST['replayId']);
if (isset($_POST['replayMd5'])) $json_md5_get = json_decode($_POST['replayMd5']);

if ($json_get == null) {
    //Not an array
    $replayId = filter_var($_POST['replayId'], FILTER_SANITIZE_STRING);
    if (isset($_POST['replayMd5'])) $replayMd5 = filter_var($_POST['replayMd5'], FILTER_SANITIZE_STRING);
    else $replayMd5 = md5_file($_FILES["file"]["tmp_name"]);

    array_push($replays, array($replayId, $replayMd5));
} else {
    //Is an array
    for ($i = 0; $i < count($json_get); $i++) {
        $replayId = filter_var($json_get[$i], FILTER_SANITIZE_STRING);
        $replayMd5 = filter_var($json_md5_get[$i], FILTER_SANITIZE_STRING);
        array_push($replays, array($replayId, $replayMd5));
    }
}

$redirectTo = "index";
if (isset($_POST['redirectTo'])) {
    $redirectTo = filter_var($_POST['redirectTo'], FILTER_SANITIZE_STRING);
}


//Check md5
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

foreach ($replays as $replay) {
    //get md5 from database
    $query = $conn->prepare('SELECT md5 FROM requestlist WHERE replayId=?');
    $query->bind_param('s', $replay[0]);
    $query->execute();
    $result = $query->get_result();
    while ($row = $result->fetch_assoc()) {
        $line = $row;
    }

    if (empty($line)) {
        $databaseMD5 = uniqid();
    } else {
        $databaseMD5 = $line['md5'];
    }

//Compare md5
    echo 'file md5 : ' . $replay[1] . '<br>';
    echo 'database md5 : ' . $databaseMD5;

    if (strcmp($replay[1], $databaseMD5) == 0) {
        //Files are identical, deleting for database and server
        echo 'Same file, deleting...<br>';

        //Deleting from server
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/requestList/' . $replay[0];
        removeFolder($dir);

        //Remove from database
        $query2 = $conn->prepare('DELETE FROM requestlist WHERE replayId=?');
        $query2->bind_param('s', $replay[0]);
        $query2->execute();
    } else {
        //Not the same, sending error
        header("Location:/progress.php?id=" . $replay[0] . "&error=1");
    }
}


//Redirect
switch ($redirectTo) {
    case "profile" :
        header("Location:/editProfile.php?block=pending");
        break;
    default :
        header("Location:/index.php");
}

 ?>
