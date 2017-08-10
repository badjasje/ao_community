<?php 
	
	
	require( dirname(__FILE__) . '/wp-load.php' );
	
if(get_field('game_status','option') == 'Live'){
$timestamp = current_time('timestamp');
	
$users = get_users();
foreach ($users as $user) {

	$user_ID = $user->ID;	
	
	/* sat crash */
	$sat_owned = get_user_meta($user_ID, 'sat_owned', true);
	$sat_endlife = get_user_meta($user_ID, 'sat_endlife',true);
	$timeleft = $sat_endlife-$timestamp;
		
		if($timeleft <= 0 && $sat_owned != '0'){
			
			update_user_meta($user_ID, 'sat_owned', 0);
			update_user_meta($user_ID, 'sat_endlife', 0);
			
			$args = array(	
				'post_title'    => 'Sat crash: '.$user_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			$new_event_id = wp_insert_post( $args );
			update_field('attacktype','sat_crash', $new_event_id);



			update_field('attacker_id',0, $new_event_id);
			update_field('defender_id',$user_ID, $new_event_id);
			update_field('time_attacked',$timestamp, $new_event_id);

			/* update event count */
			$event_count = get_user_meta($user_ID, 'new_events',true);
			update_user_meta($user_ID, 'new_events', $event_count + 1);
		} // End sat crash
		
		
	/* deactivate stealth sat */
	$stealth_sat_time = get_user_meta($user_ID, 'stealth_sat_time',true);
	$timeleft = $stealth_sat_time-$timestamp;
		if($timeleft <= 0){
			update_user_meta($user_ID, 'stealth_sat_status', 'inactive');
		}

	
	$args = array(
	'posts_per_page'   => -1,
	'author'	   		=> $user_ID,
	'post_type'        => 'research',
	);
	$researches = get_posts( $args ); 
	
	foreach ($researches as $research) {
	$researchtime_left = $research->post_title-$timestamp;
	$research_in_progress = $research->post_content;

	
	/* Check research time left */
	if($researchtime_left <= 0){
		/* Update user */
		update_user_meta($user_ID, 'research_in_progress', 0);
		$current_level = get_user_meta($user_ID, 'level_'.$research_in_progress);
		update_user_meta($user_ID, 'level_'.$research_in_progress, $current_level[0]+1);
		
		
		
		
			/* Create research event */
			$args = array(	
				'post_title'    => 'Research done for '.$user_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
				
				$new_event_id = wp_insert_post( $args );
				update_field('attacktype',$research_in_progress, $new_event_id);
				update_user_meta($user_ID, 'new_events', get_user_meta($user_ID, 'new_events')[0]+1);
				update_field('defender_id',$user_ID, $new_event_id);
				update_field('attacker_id',$user_ID, $new_event_id);
				update_field('time_attacked',$timestamp, $new_event_id);
				
				/* Delete research post */
				wp_trash_post($research->ID);
				
		$queued_research = get_user_meta($user_ID, 'queued_research',true);
		
		if(!empty($queued_research) || $queued_research != 0){
		
			include 'research_array.php';
			$time = $researches[$queued_research]['duration'];
			$args = array(
				'post_title'    => $timestamp+($time*60*60),  /* Receive research timestamp */
				'post_status'   => 'publish',
				'post_content'	=> $queued_research, 
				'post_type'		=> 'research',
				'post_author'   => $user_ID
				);
				
			
			$new_research_id = wp_insert_post( $args );
			
			update_user_meta($user_ID, 'research_in_progress', $queued_research);
			update_user_meta($user_ID, 'queued_research', 0);
		}
		
	}
	}
	
	
	
	
	
	
	
	$status = get_user_meta($user_ID,'status');
	if($status[0] == 'nukeprotection'){
	$nuke_protection_timestamp = get_user_meta($user_ID,'nuke_protection_timestamp');
	
	
	$nuke_protection_timeleft = $nuke_protection_timestamp[0]-$timestamp;

	if($nuke_protection_timeleft < 0){
		
	update_user_meta($user_ID, 'status', 'online');
	
	
	/* Create nuke protection event */
			$args = array(	
				'post_title'    => 'Nukeprotection removed for '.$user_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
				
				$new_event_id = wp_insert_post( $args );
				update_field('attacktype','nukeprotection', $new_event_id);
				update_user_meta($user_ID, 'new_events', get_user_meta($user_ID, 'new_events')[0]+1);
				update_field('defender_id',$user_ID, $new_event_id);
				update_field('attacker_id',$user_ID, $new_event_id);
				update_field('time_attacked',$timestamp, $new_event_id);

	}}}
	


include 'bonus_array.php';
	
	$timestamp = current_time('timestamp');
	$args = array(
		
		'post_type'		=>	'clan',
		'posts_per_page' => -1,
		);
	
	$clans = get_posts($args);
	foreach ($clans as $clan) {
		$clan_ID = $clan->ID;
		
		$cooldownlist = get_post_meta($clan_ID, 'cooldown_list', true);
	 
		foreach ($cooldownlist as $key => $unset_time) {
			if($unset_time < $timestamp){
			unset($cooldownlist[$key]);
			}
	
			update_post_meta($clan_ID, 'cooldown_list',$cooldownlist ); 
	
	}
	
	if(empty($clan_points)){
		$clan_points = 0;
	}
	
	
	$clan_members	= get_post_meta($clan_ID,'clan_members');
	$clan_points	= get_post_meta($clan_ID,'clan_points',true);
	$bonus_level	= get_post_meta($clan_ID,'bonus_level',true);
	

	$level = "level_";
	$level .= $bonus_level;
	$high_end	=	$bonus[$level]['points'];


	if($clan_points >= $high_end){
			
			update_post_meta($clan_ID, 'bonus_level', $bonus_level+1);
			
			
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
				
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		
	
		
		
		}
		
$bonuses = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'event_local',
	'meta_key'		=> 'attacktype',
	'meta_value'	=> 'bonus'
));

foreach ($bonuses as $bonus) {
	$used = get_post_meta($bonus->ID,'bonus_used', true);
	
	$receiver_ID = get_post_meta($bonus->ID, 'defender_id', true);
	$moraleLock = get_user_meta($receiver_ID, 'morale_lock', true);
	$turnLock = get_user_meta($receiver_ID, 'turn_lock', true);
	
	if($used != 'yes'){
		$time = get_post_meta($bonus->ID,'time_attacked', true)+(86400*2);
		if($timestamp > $time && $moraleLock == 0 && $turnLock == 0){
		
		$bonus_money = get_post_meta($bonus->ID,'bonus_money', true);
		
		$money = get_user_meta($receiver_ID, 'money',true);	
		$money_new = $money + $bonus_money;
		update_user_meta($receiver_ID, 'money', $money_new);
	
		/* Add bonus turns */
		$turns = get_user_meta($receiver_ID, 'turns',true);
		$bonus_turns = get_post_meta($bonus->ID,'bonus_turns', true);
	
		$turns_new = $turns + $bonus_turns;

		update_user_meta($receiver_ID, 'turns', $turns_new);
		update_post_meta($bonus->ID, 'bonus_used', 'yes');	
			
		
			
		}
		
		
	}
	
}


/* Get all current wars */

$wars = get_posts(array(
	'numberposts'	=> -1,
	'post_status'   => 'publish',
	'post_type'		=> 'wars',

));



foreach ($wars as $war) {
	
	/* get war declared time */
	$war_time = $war->post_title;
	
	/* check if 3 days have passed */
	if($war_time+(86400*3) < $timestamp){
		
		$declarer_clan_ID = get_post_meta($war->ID, 'declared_by', true);
		$declarer_ID = get_post_meta($declarer_clan_ID, 'clan_leader', true);
		
		$declared_on = get_post_meta($war->ID, 'declared_on', true);
		$def_clan_leader = get_post_meta($declared_on, 'clan_leader', true);
		
		
		/* Create peace event */
		$args = array(	
			'post_title'    => 'PEACE',
			'post_status'   => 'publish',
			'post_type'		=> 'event_local',
			'post_author'   => 1
			);
			$new_event_id = wp_insert_post( $args );


			update_field('attacktype','peace_declared', $new_event_id);

			update_field('attacker_clan_id',$declarer_clan_ID, $new_event_id);
			update_field('defender_clan_id',$declared_on, $new_event_id);

			update_field('attacker_id',$declarer_ID, $new_event_id);
			update_field('defender_id',$def_clan_leader, $new_event_id);

			update_field('time_attacked',$timestamp, $new_event_id);

/* add clan to cooldown list */
$cooldownlist = get_post_meta($declarer_clan_ID, 'cooldown_list', true);

$clan_ID = $declared_on;

$cooldownlist[$clan_ID] = $timestamp+(48 * 3600);
update_post_meta($declarer_clan_ID, 'cooldown_list',$cooldownlist );


/* update events */

$clan_members = get_post_meta($declared_on,'clan_members');

foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}


$clan_members2 = get_post_meta($declarer_clan_ID,'clan_members');

foreach ($clan_members2[0] as $member2) {
	$globals = get_user_meta($member2, 'new_global_events', true);
	update_user_meta($member2, 'new_global_events', $globals+1);
}	
	
	
	
wp_trash_post($war->ID);
		
		
		
	}
	
	
	
	}

		
	} // Closure Pause/Live statement