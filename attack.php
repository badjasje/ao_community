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

include('attack_functions.php');

$array = array();



/* initialize core variables */
    global $userId;
	global $userData;
    update_user_meta($userId, 'user_lock', 0);
    if (! defined('ABSPATH')) {
        exit;
    }
    if (empty($userId)) {
        $array['status'] = 'You must log in to perform this action';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    if (!is_user_logged_in()) {
        $array['status'] = 'You must log in to perform this action';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

    $clan_id = $userData['clan_id_user'][0];
    $turns = $userData['turns'][0];
    $morale = $userData['morale'][0];
	$maintarget = (isset($_POST['maintarget']) ? $_POST['maintarget'] : '');

	if($userData['status'][0] == 'dead' || $userData['status'][0] == 'nukeprotection'){
		$array['status'] = 'You cannot attack while dead or under protection';
		$array['next'] = false;
		echo json_encode($array);
		exit;
	}
    $sat_morale = $userData['sat_morale'][0];

    $target_id = round($_POST['target_id']);
   /* if(intval($target_id) <= 10){
        $array['status'] = 'Cannot attack an administrator';
		$array['next'] = false;
		echo json_encode($array);
		exit;

    }*/
    //count_all_stats($target_id);

	$attackmode = (isset($_POST['attackmode']) ? $_POST['attackmode'] : '');
    $attack_type = $_POST['attacktype'];
    $extra_morale_cost = 0;
    if ($attackmode == 'aggressive') {
        $extra_morale_cost = 10;
    }
/* == validate the attack == */
/* target id cannot be blank */
    if ($target_id == '') {
        $array['status'] = 'Choose a player ID to attack';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* validate target id must be numeric */
    if (!is_numeric($target_id)) {
        $array['status'] = 'Enter a valid number';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* target cannot be yourself */
    if ($target_id == $userId) {
        $array['status'] = 'You cannot target yourself';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* target must be a real user */
    if (get_userdata($target_id) == false) {
        $array['status'] = 'Player ID does not exist';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* target cannot be dead or in protection */
    $status = get_user_meta($target_id, 'status')[0];
    if ($status == 'dead') {
        $array['status'] = 'This player is dead';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }
    if ($status == 'nukeprotection') {
        $array['status'] = 'You cannot attack someone who is under Assault Protection';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* check if target is in own clan */
    if ($clan_id != 0) {
        $members = get_post_meta($clan_id, 'clan_members')[0];
        if (in_array($target_id, $members)) {
            $array['status'] = 'You can not attack members of your own clan.';
			$array['next'] = false;
			echo json_encode($array);
			exit;
        }
    }

/* determine war type and war points multiplier */
    $attacker_clan_ID = $userData['clan_id_user'][0];
    $defender_clan_ID = get_user_meta($target_id, 'clan_id_user')[0];

    $war_type = get_war_type($attacker_clan_ID, $defender_clan_ID);
    $war_multiplier = get_war_multiplier($war_type);

/* determine if target is in range */
    $attacktype = $_POST['attacktype'];
    $networth_att = $userData['networth'][0];
    $networth_def = get_user_meta($target_id, 'networth')[0];



/* determine if target is in range */

    $in_range = target_in_range($attacktype, $networth_att, $networth_def, $war_type);
    if (!$in_range) {
		$array['status'] = 'Out of networth range';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

// Check if user is member of clan for 24h, if not, cannot attack out of range in mutual
    $join_timestamp = $userData['clan_join_stamp'][0];
    $timestamp = current_time('timestamp');
    $in_range = target_in_range($attack_type, $networth_att, $networth_def, 'none');

    if ($war_type == 'mutual' && $timestamp < $join_timestamp && $in_range != true) {
        $array['status'] = 'Cannot attack out of networth range in mutual war the first 24 hours after joining a clan';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* determine if attacker has required resourced */
    $turnCost = get_attack_cost_turns($attacktype);
    $moraleCost = get_attack_cost_morale($attacktype, $networth_att, $networth_def);
/* check morale */
    if ($moraleCost+$extra_morale_cost > $morale) {
        $array['status'] = 'Insufficient morale';
		$array['next'] = false;
		echo json_encode($array);
		exit;

    }

/* check satellite morale */
    if ($attacktype == 'satellite') {
        if (100 > $sat_morale) {
            $array['status'] = 'Not enough satellite power';
			$array['next'] = false;
			echo json_encode($array);
			exit;

        }
    }

/* check turns */
    if ($turnCost > $turns) {
        $array['status'] = 'Not enough turns';
		$array['next'] = false;
		echo json_encode($array);
		exit;
    }

/* check power */
    $user_power = $userData['power'][0];
    if ($user_power > 100) {
        $array['status'] = 'You cannot attack while your power is offline';
		$array['next'] = false;
		echo json_encode($array);
		exit;

    }

/* validations passed - advance to step 2 */


    $array['next'] = true;
    $array['attacktype'] = $attacktype;
    $array['target_id'] = $target_id;
    $array['attackmode'] = $attackmode;
    $array['maintarget'] = $maintarget;
	echo json_encode($array);
	exit;