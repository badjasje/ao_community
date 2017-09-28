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
if (get_field('game_status', 'option') == 'Live') {
    if (! defined('ABSPATH')) {
        exit;
    }
    include('attack_functions.php');
    nocache_headers();

    $_SESSION['attack_array'] = $_POST;

    $user_id = get_current_user_ID();
    $attack_nw = get_user_meta($user_ID, 'networth')[0];
    $attack_clan_id = get_user_meta($user_ID, 'clan_id_user')[0];

    $target_id = $_SESSION['target_id'];
    $defend_nw = get_user_meta($target_id, 'networth')[0];
    $defend_clan_id = get_user_meta($target_id, 'clan_id_user')[0];

    $attack_type = $_SESSION['attacktype'];

/* determine war type */
    $war_type = get_war_type($attack_clan_id, $defend_clan_id);

/* check if target in range */
    $in_range = target_in_range($attack_type, $attack_nw, $defend_nw, $war_type);

    if (!$in_range) {
        wp_redirect(get_permalink(3360).'?fail=9');
        exit;
    } else {
        $_SESSION['target_id'] = $target_id;
        wp_redirect(get_permalink(3366));   //step 3
        exit;
    }
}




/*
$user_ID = get_current_user_ID();
$networth_att = get_user_meta($user_ID, 'networth');
$networth_def = get_user_meta($target_id, 'networth');
$defender_clan_ID = get_user_meta($_SESSION['target_id'], 'clan_id_user');
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user');
// NW Check between attacker & Defender
$mutual = 0;
if($defender_clan_ID[0] != 0 && $attacker_clan_ID[0] != 0){


$one_sided = 0;
$wars = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'declared_on',
						'value'	  	=> $defender_clan_ID[0],
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'declared_by',
						'value'	  	=> $attacker_clan_ID[0],
						'compare' 	=> '=',
						),
),));

if(count($wars) != 0){
	$calculate_points = count($wars);
	$mutual = $mutual+1;
}

}

$wars = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'declared_on',
						'value'	  	=> $attacker_clan_ID[0],
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'declared_by',
						'value'	  	=> $defender_clan_ID[0],
						'compare' 	=> '=',
						),
),));


$onesided = count($wars);

if($onesided == 1){
	$mutual = $mutual+1;
	$calculate_points = 1;
	$one_sided = 1;
}




if($mutual != 2) {
	if($_SESSION['attacktype'] != 'spy') {	
		if (($networth_def[0] > $networth_att[0]/1.3 && $networth_def[0] < $networth_att[0]*1.30)) {

		}
		else {
			wp_redirect(get_permalink(3360).'?fail=9');
			exit;	
		}
	}
}

wp_redirect(get_permalink(3366));	//stap 3
exit;
*/
