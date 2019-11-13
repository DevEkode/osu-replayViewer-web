<?php
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 02/03/2019
 * Time: 13:25
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

//Get pledgers names
$files = array(
    0 => '1_pledgers.txt',
    1 => '5_pledgers.txt'
);

//Creating URL request

$url = 'https://www.patreon.com/api/oauth2/api/current_user/campaigns?access_token=' . getenv('PATREON_KEY');

//Get response json
$url_content = file_get_contents($url);
$json = json_decode($url_content);

//Get rewards
$rewards = array();
$index = 0;
foreach($json->included as $data){
    //Check if the data is a reward
    if(strcmp($data->type,'reward') == 0){
        if($data->id == -1 || $data->id == 0) continue;
        $newData = $data->attributes;

        //Add pledgers names
        $pledgers = explode(";",file_get_contents('./'.$files[$index]));
        $newData->pledgers = $pledgers;

        array_push($rewards,$newData);
        $index++;
    }
}

header('Content-type: application/json');
echo json_encode($rewards);
?>