<?php

$winner_ID = $userId;
$turns = $attackerData['turns'][0];

$powerReduction = 0;

$sat_morale = $attackerData['sat_morale'][0];
if (100 > $sat_morale) {
	$array['status'] = 'Not enough satellite power';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}


$result = 'success';

$sat_status = $defenderData['stealth_sat_status'][0];
if($sat_status == 'active'){
	$result = 'failure';
}

$failsat = mt_rand(1,100);

if($failsat > 94){
	$result = 'failure';
}

if($result == 'success'){ ?>


<div class="blockHeader">Your EMP satellite hit the base of <?php echo get_user_name($target_id);?></div>

<?php
	$emps = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'emp',
	'meta_key'		=> 'defender_emp',
	'meta_value'	=> $target_id
));
	
	?>
<?php if(count($emps) < 3){
$powerReduction = 20;
$args = array(	
	'post_title'    => 'EMP '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'emp',
	'post_author'   => $userId
);
				
$new_emp_id = wp_insert_post( $args );

update_field('defender_emp', $target_id, $new_emp_id);
update_field('timestamp_emp', $timestamp+3600*6, $new_emp_id);
update_field('deduction_emp',$powerReduction, $new_emp_id);
?>
<div class="blockHeader spaceNotice">Power of target reduced by <?php echo $powerReduction;?>% for the next 6 hours</div>

<?php } else { ?>
<div class="blockHeader spaceNotice">Power of target reduced by <?php echo $powerReduction;?>% for the next 6 hours</div>
<?php }}?>




					
					
<?php if($result == 'failure'){ $winner_ID = $target_id;?>

<div class="blockHeader">Your EMP satellite missed the base of <?php echo get_user_name($target_id);?></div>
<div class="blockHeader spaceNotice">Power of target not affected</div>

<?php }?>	
					
<?php  $args = array(	
				'post_title'    => 'EMP satellite attack made by '.$userId.' Defender: '.$target_id,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $userId
				);
				
			
			$new_event_id = wp_insert_post( $args );
	
	
			update_field('time_attacked',$timestamp, $new_event_id);

			

			
			update_field('defender_id',$target_id, $new_event_id);
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$userId, $new_event_id);
			update_field('attacktype','empsat', $new_event_id);
			update_field('outcome',$result, $new_event_id);
			update_field('nw_damage_defender',$powerReduction, $new_event_id); //Used to display power reduction in globals/locals/outgoing
			
			
			
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			update_user_meta($userId,'turns',$turns-3);
			
			update_user_meta($userId,'sat_morale',$sat_morale-100);
			
			update_user_meta($target_id, 'new_events', get_user_meta($target_id, 'new_events',true)+1);
			/* Add globals to defender */

$clan = get_user_meta($target_id, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}


/* Add globals to attacker */

$clan_att = get_user_meta($userId, 'clan_id_user', true);
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
foreach ($clan_members_att[0] as $member_att) {
	$globals = get_user_meta($member_att, 'new_global_events', true);
	update_user_meta($member_att, 'new_global_events', $globals+1);
}}