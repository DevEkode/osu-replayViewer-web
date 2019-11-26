<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

$osuApiKey = getenv('OSU_KEY');

// ******************** Connection **********************************
// Create connection
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASS'), getenv('MYSQL_DB'));

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    header("Location:index.php?error=3");
    exit;
}

//--Connect to osu API --
$apiKey = $osuApiKey;

//Function
function closeUpload($conn)
{
    $conn->close();
    exit;
}

function getBeatmapJSON($md5, $api)
{
    $apiRequest = file_get_contents("https://osu.ppy.sh/api/get_beatmaps?k=$api&h=$md5");
    $json = json_decode($apiRequest, true);
    if (empty($json)) {
        //header("Location:index.php?error=12");
        //closeUpload($conn);
    }
    return $json;
}

//Core
$result = $conn->query("SELECT * FROM replaylist WHERE playMod<>0");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        //Updates mods for each replay
        $replayId = $row['replayId'];
        $beatmapMd5 = $row['md5'];
        $beatmapJSON = getBeatmapJSON($beatmapMd5, $apiKey);
        $mode = $beatmapJSON[0]["mode"];

        $sql = "UPDATE replaylist SET playMod='$mode' WHERE replayId='$replayId'";
        if ($conn->query($sql) === TRUE) {
            //row created
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
            //header("Location:index.php?error=3&sqlErr=".$conn->error);
            closeUpload($conn);
        }
    }
}
?>
