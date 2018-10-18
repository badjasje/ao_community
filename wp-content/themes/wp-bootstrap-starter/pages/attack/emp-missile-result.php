<?php
 
$shotdown = false;
$AMS = $defenderData['antimissile'][0];
$def_land = $defenderData['builtland'][0];


$shootdown_chance = (($AMS*100)/$def_land)*100;

if($shootdown_chance >= 75){
	$shootdown_chance = 75;
}

$shootdown = rand(1, 100);



if($shootdown < $shootdown_chance){
	$shotdown = true;
}

if($AMS == 0){
	$shotdown = false;
}

$power = $defenderData['power'][0];
if($power > 100){
	$shotdown = false;
}


$turns = $attackerData['turns'][0];
$missile_research = $attackerData['level_missile_accuracy'][0];


$missile_hit = rand(1,100);

if($missile_hit >= 90){
$result = 'success';
}else{
$result = 'failure';
}

if($missile_research == 1){
$missile_hit = rand(1,100);
if($missile_hit >= 50){
$result = 'success';
}else{
$result = 'failure';
}
}

if($missile_research == 2){
$missile_hit = rand(1,100);
if($missile_hit > 5){
$result = 'success';
}else{
$result = 'failure';
}
}

if($shotdown == true){
	$result = 'failure';
}

 
$key = 'empmis';
$missile_type = 'empmis';
$owned_miss = $attackerData[$key.'_owned'][0];
     	
/* Check if attacker has enough missiles */

if($owned_miss <= 0 ){
	$array['status'] = 'Not enough missiles of this type';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* check if user has enough turns */
if($turns < 3){ 
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* update morale */
update_user_meta($userId, 'morale', $oldmorale - $moralecost);
/* update attacker missile */
update_user_meta($userId,$key.'_owned',$owned_miss-1);


$missiles_launched = $attackerData['missiles_launched'][0];
update_user_meta($userId, 'missiles_launched', $missiles_launched+1);
	
// defender

$missiles_received = $defenderData['missiles_received'][0];
update_user_meta($target_id, 'missiles_received', $missiles_received+1);

if($result == 'success'){
	
/* add stats */
   
// attacker
$missiles_hit = $attackerData['missiles_hit'][0];
update_user_meta($userId, 'missiles_hit', $missiles_hit+1);
	
// defender
$missiles_hit_rec = $defenderData['missiles_hit_rec'][0];
update_user_meta($target_id, 'missiles_hit_rec', $missiles_hit_rec+1);
	
$winner_ID = $userId;?>

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
	
$args = array(	
	'post_title'    => 'EMP '.$target_id,
	'post_status'   => 'publish',
	'post_type'		=> 'emp',
	'post_author'   => $userId
);
				
$new_emp_id = wp_insert_post( $args );

update_field('defender_emp', $target_id, $new_emp_id);
update_field('timestamp_emp', $timestamp+3600*6, $new_emp_id);
update_field('deduction_emp',15, $new_emp_id);
?>
<div class="blockHeader spaceNotice">Power of target reduced by 15% for the next 6 hours</div>

<?php } else { ?>
<div class="blockHeader spaceNotice">Power of target reduced by 0% for the next 6 hours</div>
<?php }}?>





	
					
<?php if($result == 'failure' && $shotdown != true){ $winner_ID = $target_id; ?>



<div class="blockHeader">Your EMP missile missed the base of <?php echo get_user_name($target_id);?></div>
<div class="blockHeader spaceNotice">Power of target not affected</div>
<?php }?>	

<?php if($result == 'failure' && $shotdown == true){ $winner_ID = $target_id; ?>



<div class="blockHeader">Your EMP missile was shot down by <?php echo get_user_name($target_id);?></div>
<div class="blockHeader spaceNotice">Power of target not affected</div>
<?php }?>	
			
				



<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Strike Again</div>
			
			
<?php 



////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
				'post_title'    => 'EMP Missile launched by '.$userId.' Defender: '.$target_id,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $userId
				);
				
			
			$new_event_id = wp_insert_post( $args );
			
			
			
			update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());

			update_field('time_attacked',$timestamp, $new_event_id);
			
			update_field('nw_damage_defender',0, $new_event_id);
			update_field('missile_type',$missile_type, $new_event_id);
			
		

			update_field('defender_id',$target_id, $new_event_id);
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$userId, $new_event_id);
			update_field('attacktype','empmissile', $new_event_id);
			update_field('outcome',$result, $new_event_id);
			
			if($shotdown == true){
			update_field('shotdown','shotdown', $new_event_id);
			}
			
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			
			
			update_user_meta($userId,'turns',$turns-3);
			turn_spread('emp_missile',3);
			update_user_meta($target_id, 'new_events', get_user_meta($target_id, 'new_events')[0]+1);
			
		
			
			/* Add globals to defender */

$clan = get_user_meta($target_id, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');


if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}

/* add globals attacker */

$clan_att = get_user_meta($userId, 'clan_id_user', true);
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
foreach ($clan_members_att[0] as $member_att) {
	$globals = get_user_meta($member_att, 'new_global_events', true);
	update_user_meta($member_att, 'new_global_events', $globals+1);
}}
