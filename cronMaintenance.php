<?php
ini_set('display_errors', 1);
// ******************** Variables **********************************
//--Connect to osu API --
require_once 'secure/osu_api_key.php';
$apiKey = $osuApiKey;


//-- Connect to mysql request database --
$servername = "mysql.hostinger.fr";
$username = "u611457272_code";
require_once 'secure/mysql_pass.php';
$password = $mySQLpassword;

// ******************** Connection **********************************
// Create connection
$conn = new mysqli($servername, $username, $password, "u611457272_osu");
//$conn = new PDO('mysql:host=mysql.hostinger.fr;dbname=u611457272_osu','u611457272_code','123');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
	exit;
}

// ******************** Youtube API **********************************
if (!file_exists('google-api-php-client/vendor/autoload.php')) {
  throw new \Exception('please run "composer require google/apiclient:~2.0" in "' . __DIR__ .'"');
}
require_once __DIR__ . '/google-api-php-client/vendor/autoload.php';
session_start();

/*
 * Set $DEVELOPER_KEY to the "API key" value from the "Access" tab of the
 * Google Developers Console: https://console.developers.google.com/
 * Please ensure that you have enabled the YouTube Data API for your project.
 */
define('CREDENTIALS_PATH', 'jsons/php-yt-oauth2.json');

function getClient() {
  $client = new Google_Client();
  $client->setApplicationName('API Samples');
  $client->setScopes('https://www.googleapis.com/auth/youtube.force-ssl');
  // Set to name/location of your client_secrets.json file.
  $client->setAuthConfig('client_secret.json');
  $client->setAccessType('offline');

  // Load previously authorized credentials from a file.
  $credentialsPath = expandHomeDirectory(CREDENTIALS_PATH);
  if (file_exists($credentialsPath)) {
    $accessToken = json_decode(file_get_contents($credentialsPath), true);
  } else {
    // Request authorization from the user.
    $authUrl = $client->createAuthUrl();
    printf("Open the following link in your browser:\n%s\n", $authUrl);
    print 'Enter verification code: ';
    $authCode = trim(fgets(STDIN));

    // Exchange authorization code for an access token.
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

    // Store the credentials to disk.
    if(!file_exists(dirname($credentialsPath))) {
      mkdir(dirname($credentialsPath), 0700, true);
    }
    file_put_contents($credentialsPath, json_encode($accessToken));
    printf("Credentials saved to %s\n", $credentialsPath);
  }
  $client->setAccessToken($accessToken);

  // Refresh the token if it's expired.
  if ($client->isAccessTokenExpired()) {
    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
    file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
  }
  return $client;
}


/***** END BOILERPLATE CODE *****/

// Sample php code for videos.delete

function videosDelete($service, $id, $params) {
    $params = array_filter($params);
    $response = $service->videos->delete(
        $id,
        $params
    );

    print_r($response);
}

$client = getClient();
videosDelete($client,'hj5bJj1v3HQ', array('onBehalfOfContentOwner' => ''));

?>