<?php
use PHPUnit\Framework\TestCase;

final class UploadTest extends TestCase {

  //Testing replay submiting
  public function testReplaySubmit(){
    $array = array(
      "fileToUpload" => array(
        'name' => 'replay.osr',
        'type' => 'application/octet-stream',
        'tmp_name' => __DIR__ . "/../tests/replay.osr",
        'error' => 0,
        'size' => 38750
      )
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://osureplayviewer.xyz/php/index/replayFileVerf.php");
    curl_setopt($ch, CURLOPT_POST, true);
    $args['file'] = new CurlFile(__DIR__ ."/replay.osr", 'application/octet-stream','replay.osr');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    $result = curl_exec($ch);
    var_dump($result);
    curl_close($ch);
  }
}

 ?>
