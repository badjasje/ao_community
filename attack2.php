<?php
/**
 * Handles attacks
 *
 * @package WordPress
 */

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

if (! defined('ABSPATH') || get_field('game_status', 'option') != 'Live') {
    $array['status'] = 'The round has ended';
    $array['next'] = false;
    echo json_encode($array);
    exit;
}

nocache_headers();
$array = array();
include 'units_array.php';

global $userId;
global $userData;

$entireArray = $_POST;

$arraydif = array_diff_key($entireArray,$units);

$removeKeys = array_keys($arraydif);


foreach($removeKeys as $key) {
   unset($entireArray[$key]);
}

$attackArray = $entireArray;
if(array_key_exists('tomahawk', $_POST)){
	$attackArray['tomahawk'] = $_POST['tomahawk'];
}
if (get_field('game_status', 'option') == 'Live') {
    if (! defined('ABSPATH')) {
        exit;
    }
    include('attack_functions.php');

	
    
    $attack_nw = $userData['networth'][0];
    $attack_clan_id = $userData['clan_id_user'][0];

    $target_id = $_POST['target_id'];
    /*if(intval($target_id) <= 10){
        $array['status'] = 'Cannot attack an administrator';
		$array['next'] = false;
		echo json_encode($array);
		exit;
	    
    }*/
    $defend_nw = get_user_meta($target_id, 'networth')[0];
    $defend_clan_id = get_user_meta($target_id, 'clan_id_user')[0];

    $attack_type = $_POST['attacktype'];

/* determine war type */
    $war_type = get_war_type($attack_clan_id, $defend_clan_id);

/* check if target in range */
    $in_range = target_in_range($attack_type, $attack_nw, $defend_nw, $war_type);

    if (!$in_range) {
       	$array['status'] = 'Target is not in range';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    
if($_POST['attacktype'] != 'missile'){
$finalAttackArray = array();
	foreach ($attackArray as $key => $attacking) {
		
		$unitsOwned = $userData[$key.'_owned'][0];
		
		if($attacking >= $unitsOwned){
			$percentage = 1;
		}else{
			$percentage = $attacking/$unitsOwned;
		}
		if($attacking > 0){
			$finalAttackArray[$key] = $percentage;
		}
	}
	
	
$array['attackarray'] = $finalAttackArray;
}
if($_POST['attacktype'] == 'missile'){
	$array['missiletype'] = $_POST['missiletype'];
}
if($_POST['attacktype'] == 'spy'){
	$array['spytype'] = $_POST['spytype'];
}
if($_POST['attacktype'] == 'thief'){
	$array['nothiefs'] = $_POST['nothiefs'];
}
if($_POST['attacktype'] == 'satellite'){
	$array['satellitetype'] = $_POST['satellitetype'];
}

$array['attacktype'] = $_POST['attacktype'];
$array['target_id'] = $target_id;
$array['attackmode'] = $_POST['attackmode'];
$array['maintarget'] = $_POST['maintarget'];


echo json_encode($array);
exit;

}// End live check