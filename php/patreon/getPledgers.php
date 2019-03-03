<?php
/**
 * Created by PhpStorm.
 * User: codev
 * Date: 02/03/2019
 * Time: 13:25
 */
require_once '../../vendor/autoload.php';
require_once '../../secure/patreon_key.php';

use Patreon\API;
use Patreon\OAuth;

$api_client = new API($patreon_access_token);
$current_campaign = $api_client->fetch_campaign();
$current_patreons = $api_client->fetch_page_of_pledges(2487929,5);


$tiers = array(3,4);
$tiers_temp = array();

//Get tiers data
foreach($tiers as &$tier_id){
    $tiers_temp[$tier_id] = $current_campaign->get('included')->get($tier_id);
}
$tiers = $tiers_temp;

//Get pledgers
$pledgers_data = $current_patreons->get('data');
$pledgers = array();
foreach($pledgers_data->getKeys() as $key=>$value){
    $pledger_temp = $pledgers_data->get($key);

    $user = array();
    $user['id'] = $pledger_temp->get('id');

    $user_info = $api_client->fetch_user($user['id'])->get('data');

    $user['name'] = $user_info->get('attributes')->get('full_name');

    $pledgers[$key] = $user;
}

var_dump($pledgers);

//Construct json
$json_model = array();
$final_json = array();

foreach ($tiers as $key=>$tiers_obj){
    $json_temp = $json_model;

    $json_temp['id'] = $tiers_obj->get('id');
    $json_temp['name'] = $tiers_obj->get('attributes')->get('title');
    $json_temp['url'] = $tiers_obj->get('attributes')->get('url');
    $json_temp['price_cents'] = $tiers_obj->get('attributes')->get('amount_cents');
    $json_temp['remaining'] = $tiers_obj->get('attributes')->get('remaining');
    $json_temp['pledgers_nbr'] = $tiers_obj->get('attributes')->get('patron_count');

    //Find pledgers for this tier


    $final_json[$key] = $json_temp;
}




var_dump($final_json);
?>