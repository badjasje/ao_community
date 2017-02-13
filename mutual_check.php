<?php
	
	require_once("wp-load.php");
	
	
	$declarer_ID = get_current_user_ID();
$declarer_clan_ID = get_user_meta($declarer_ID, 'clan_id_user');
$defender_clan_ID = get_user_meta($_POST['target_id'], 'clan_id_user');
	$wars_on = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_by',
	'meta_value'	=> $declarer_clan_ID[0]
));
$declared = array();
foreach ($wars_on as $war_on) {
	
	$declared_on = get_post_meta($war_on->ID, 'declared_on');
	$declared[] = array_shift($declared_on);

	}
	
	
$wars_by = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_key'		=> 'declared_on',
	'meta_value'	=> $declarer_clan_ID[0]
));
$_declared = array();
foreach ($wars_by as $war_by) {
	
	$declared_by = get_post_meta($war_on->ID, 'declared_by');
	$_declared[] = array_shift($declared_by);

	}
echo '<pre>';
print_r($_declared);
echo '</pre>';
echo '<pre>declared on';
print_r($declared);
echo '</pre>';
echo '<pre>';
print_r($defender_clan_ID[0]);
echo '</pre>';