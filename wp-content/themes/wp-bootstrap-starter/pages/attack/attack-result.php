<?php
if(!isset($debug)) $debug = false;
if(!$debug) require_once("../../../../../wp-load.php");
nocache_headers();

include(ABSPATH."/attack_functions.php");
include ABSPATH.'/constants.php';
$timestamp = current_time('timestamp');

$backColor = "45, 67, 81";
$attack_type = ($debug ? $_POST['attacktype'] : filter_input(INPUT_POST, 'attacktype', FILTER_SANITIZE_STRING));
$target_id = ($debug ? $_POST['target_id'] : filter_input(INPUT_POST, 'target_id', FILTER_VALIDATE_INT));
$token = ($debug ? $_POST['token'] : filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING));
if(!empty($token) && isset($_SESSION['tokens'][$target_id])) { // Auto spy
	if($_SESSION['tokens'][$target_id] == $token) {
		$_SESSION['token'][$target_id] = false;
	} else {
		$array['status'] = 'Back button used, no worries, no extra spies were sent.';
		$array['next'] = false;
		echo json_encode($array);
		exit;
	}
}

global $userId;
global $userData;
$attackerData = $userData;
$defenderData = get_user_meta($target_id);

// LOCK
$userLock = ($debug ? 0 : intval($attackerData['user_lock'][0]));
$moraleLock = ($debug ? 0 : intval($attackerData['morale_lock'][0]));

// Attack spam protection
if($userLock == 1){
	$array['status'] = 'It seems you tried to do something Assault.Online was unable to process. Please restart the attacking sequence by refreshing the page.';
	$array['next'] = false;
	echo json_encode($array);
	update_user_meta($userId, 'user_lock', 0);
	exit;
}
if(!$debug) update_user_meta($userId, 'user_lock', 1);

// Cannot attack when morale is being updated
if($moraleLock == 1){
	$array['status'] = 'Morale is updating. Please try again in a few moments';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

// Check if target is alive
if($defenderData['status'][0] == 'dead' || $defenderData['status'][0] == 'nukeprotection'){
	$array['status'] = 'This player is dead';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if($defenderData['status'][0] == 'banned') {
	$array['status'] = 'This player is banned';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

// Check if attacker is alive
if($attackerData['status'][0] == 'dead' || $attackerData['status'][0] == 'nukeprotection'){
	$array['status'] = 'You cannot attack while dead or under protection';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* check if target isn't under protection, else redirect */
if($defenderData['status'][0] == 'nukeprotection'){
	$array['status'] = 'This player is under protection';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

// Check if target is in range
$attacker_clan_ID = $attackerData['clan_id_user'][0];
$defender_clan_ID = $defenderData['clan_id_user'][0];

$war_type = get_war_type($attacker_clan_ID,$defender_clan_ID);

$networth_att = $attackerData['networth'][0];
$networth_def = $defenderData['networth'][0];

$in_range = target_in_range($attack_type, $networth_att, $networth_def, $war_type);
if($debug) $in_range = true;
if (!$in_range) {
	$array['status'] = 'Out of networth range';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

if(!$debug && $attack_type != 'satellite'){
	// Check if attacker has enough morale
	$moralecost = get_attack_cost_morale($attack_type, $networth_att, $networth_def);
	$oldmorale = $attackerData['morale'][0];

	if ($oldmorale < $moralecost) {
		$array['status'] = 'Insufficient morale';
		$array['next'] = false;
		echo json_encode($array);
		exit;
	}
}

if(!$debug) echo '<div class="pageSpacer"></div>';

$path = dirname(__FILE__);
if($attack_type == 'regular'  || $attack_type == 'ground' || $attack_type == 'air_sea'){
	include($path."/unit-result.php");
}

if($attack_type == 'missile'){
	if($_POST['missiletype'] == 'empmis'){
		include($path."/emp-missile-result.php");
	}else{
		include($path."/missile-result.php");
	}
}

if($attack_type == 'spy'){
	include($path."/spy-result.php");
}

if($attack_type == 'thief'){
	include($path."/thief-result.php");
}

if($attack_type == 'sniper'){
	include($path."/sniper-result.php");
}

if($attack_type == 'satellite'){
	if($_POST['satellitetype'] == 'empsat'){
		include($path."/emp-satellite-result.php");
	}else{
		include($path."/satellite-result.php");
	}
}

if($attack_type == 'saboteur'){
	include($path."/saboteur-result.php");
}
if(!$debug) {
	if($result == 'success'):?>
	<script>
		jQuery(document).ready(function() {
			jQuery( ".splashmessage" ).html('S U C C E S S');
			jQuery( "#splashback" ).addClass( "successsplash" );
			jQuery( "#splashback,.splashmessage" ).show();
			jQuery( "#splashback,.splashmessage" ).delay(750).fadeOut( "slow")
			jQuery('.pageTitle').html('S U C C E S S');
		});
	</script>
	<?php else:?>
	<script>
		jQuery(document).ready(function() {
			jQuery( ".splashmessage" ).html('F A I L U R E');
			jQuery( "#splashback" ).addClass( "failsplash" );
			jQuery( "#splashback,.splashmessage" ).show();
			jQuery( "#splashback,.splashmessage" ).delay(750).fadeOut( "slow")
			jQuery('.pageTitle').html('F A I L U R E');
		});
	</script>
	<?php endif;

	update_user_meta($userId, 'user_lock', 0);
	count_all_stats($target_id);
	count_all_stats($userId);
	if($attack_type != 'saboteur'){
		fcm_send_notification($target_id,$attack_type,$userId);
	}
	?>
	<script>
	(function($) {
		$(document).ready(function() {
			updateHeaderData();
		});
	})(jQuery);
	</script><?php
}