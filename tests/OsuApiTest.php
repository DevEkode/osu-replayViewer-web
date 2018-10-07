<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . "/../src/OsuApi.php";
include_once __DIR__ . "/../secure/osu_api_key.php";

final class OsuApiTest extends TestCase {

  public function testGetBeatmapJson(){
    $beatmapId = "22538";
    $JSON = OsuApi::getBeatmapJSON($beatmapId);
    $this->assertNotEmpty($JSON);
  }

  public function testGetBeatmapJSONwMD5(){
    $md5 = "c8f08438204abfcdd1a748ebfae67421";
    $JSON = OsuApi::getBeatmapJSONwMD5($md5);
    $this->assertEquals($JSON[0]['beatmap_id'],'252002');
  }

  public function testGetUserJsonNotEmpty(){
    $JSON = OsuApi::getUserJSON('3481725');
    $this->assertNotEmpty($JSON);
  }

  public function testGetUserJSON(){
    $JSON = OsuApi::getUserJSON('3481725');
    $this->assertEquals($JSON[0]['username'],'codevirtuel');
  }

  public function testDrawMods(){
    $string = osuApi::drawMods(17528);
    $this->assertEquals($string,'HD HR SD DT FL PF');
  }

  public function testIsBeatmapAvailable(){
    $this->assertEquals(OsuApi::isBeatmapAvailable('56791'),false);
  }

  public function testGetReplayContent(){
    $replay = OsuApi::getReplayContent(__DIR__ . "/../tests/replay.osr");
    $testArray = array(
      'gamemode' => 0,
      'version' => 20171227,
      'length' => 32,
      'md5' => '441ad85e44c13280c6203639015832b2',
      'length2' => 11,
      'user' => 'codevirtuel',
      'length3' => 32,
      'md5Replay' => 'dd043796431b3fe18232b12eb9d0a122',
      'x300' => 330,
      'x100' => 28,
      'x50' => 0,
      'Gekis' => 79,
      'Katus' => 21,
      'Miss' => 2,
      'Score' => 1221270,
      'MaxCombo' => 148,
      'perfectCombo' => 0,
      'Mods' => 0,
      'length4' => -108
    );
    $this->assertEquals($testArray,$replay);
  }

  public function testIsValidMd5_invalid(){
    $this->assertNotTrue(OsuApi::isValidMd5('dd043796431b3fe1822b12eb9d0a1'));
  }

  public function testIsValidMd5_valid(){
    $this->assertNotFalse(OsuApi::isValidMd5('dd043796431b3fe18232b12eb9d0a122'));
  }

  public function testValidateReplayStructure(){
    $this->assertNotFalse(OsuApi::validateReplayStructure(__DIR__ . "/../tests/replay.osr"));
  }

  public function testIsDT(){
    $this->assertNotFalse(OsuApi::isDT(17528));
  }

  public function testGenerateBtFileNamewAPI(){
    $this->assertEquals(OsuApi::generateBtFileNamewAPI('252002'),'93398 Luxion - High-Priestess.osz');
  }

  public function testGenerateBtFileNamewJSON(){
    $JSON = OsuApi::getBeatmapJSON('22538');
    $this->assertEquals(OsuApi::generateBtFileNamewJSON($JSON),'3756 Peter Lambert - osu! tutorial.osz');
  }
}


 ?>
