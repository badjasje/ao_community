<?php
/**
 * Handles attacks
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );
if(get_field('game_status','option') == 'Live'){
include('units_array.php');
include('attack_functions.php');
nocache_headers();

/* initialize core variables */
$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
$user_data = get_user_meta($user_ID);
$clan_id = $user_data['clan_id_user'][0];
$turns = $user_data['turns'][0];
$morale = $user_data['morale'][0];
$maintarget = $_POST['maintarget'];


$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

$target_id = $_POST['target_id'];
count_all_stats($target_id);

$attackmode = $_POST['attackmode'];
$extra_morale_cost = 0;
if($attackmode == 'aggressive'){
	$extra_morale_cost = 10;
}
/* == validate the attack == */
/* target id cannot be blank */
if ($target_id == '') {
	wp_redirect(get_permalink(3360).'?fail=4');
	exit;
}

/* validate target id must be numeric */
if(!is_numeric($target_id)) {
	wp_redirect(get_permalink(3360).'?fail=12')
	;exit;
}

/* target cannot be yourself */
if ($target_id == $user_ID) {
	wp_redirect(get_permalink(3360).'?fail=6');
	exit;
}

/* target must be a real user */
if (get_userdata($target_id) == false) {
	wp_redirect(get_permalink(3360).'?fail=5');
    exit;
}

/* target cannot be dead or in protection */
$status = get_user_meta($target_id, 'status')[0];
if($status == 'dead') {
	wp_redirect(get_permalink(3360).'?fail=8');
	exit;
}
if ($status == 'nukeprotection') {
	wp_redirect(get_permalink(3360).'?fail=13');
	exit;
}

/* check if target is in own clan */
if($clan_id != 0){
	$members = get_post_meta($clan_id, 'clan_members')[0];
	if(in_array($target_id, $members)){
		wp_redirect(get_permalink(3360).'?fail=11');
		exit;
	}
}

/* determine war type and war points multiplier */
$attacker_clan_ID = $user_data['clan_id_user'][0];
$defender_clan_ID = get_user_meta($target_id, 'clan_id_user')[0];

$war_type = get_war_type($attacker_clan_ID, $defender_clan_ID);
$war_multiplier = get_war_multiplier($war_type);

/* determine if target is in range */
$attacktype = $_POST['attacktype'];
$networth_att = $user_data['networth'][0];
$networth_def = get_user_meta($target_id, 'networth')[0];

if($networth_def < 3500){
	wp_redirect(get_permalink(3360).'?fail=9');
	exit;
}

/* determine if target is in range */

$in_range = target_in_range($attacktype, $networth_att, $networth_def, $war_type);
if (!$in_range) {
	wp_redirect(get_permalink(3360).'?fail=9');
	exit;
}

/* determine if attacker has required resourced */
$cost_arr = get_attack_cost($attacktype, $networth_att, $networth_def);
/* check morale */
if ($cost_arr['morale']+$extra_morale_cost > $morale) {
	wp_redirect(get_permalink(3360).'?fail=2');
	exit;
}
/* check satellite morale */
if($attacktype == 'satellite'){
if (100 > $sat_morale) {
	wp_redirect(get_permalink(3360).'?fail=20');
	exit;
}}

/* check turns */
if ($cost_arr['turns'] > $turns) {
	wp_redirect(get_permalink(3360).'?fail=1');
	exit;
}

/* check power */
$user_power = $user_data['power'][0];
if ($user_power > 100) {
	wp_redirect(get_permalink(3360).'?fail=15');
	exit;
}

/* validations passed - advance to step 2 */
$_SESSION['attacktype'] = $attacktype;
$_SESSION['target_id'] = $target_id;
$_SESSION['attackmode'] = $attackmode;
$_SESSION['maintarget'] = $maintarget;
wp_redirect(get_permalink(3363));	//stap 2
exit;
}