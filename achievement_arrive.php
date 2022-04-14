<?php
require(dirname(__FILE__) . '/wp-load.php');
$timestamp = current_time('timestamp');

include 'achievements_array.php';
$args = array();

if (get_field('game_status', 'option') == 'Live') {






    $users = get_users($args);
    foreach ($users as $user) {
        $user_ID = $user->ID;
        $province = Province::make($user_ID);
        
       
    
		/* Achievement checker */
		
		$achievements = maybe_unserialize(get_user_meta( $user_ID, 'achievements', true ));
		/* create achievements array for saving */
		if(empty($achievements)){
			
			foreach ($achievementsArray as $key => $singleAchievement) {
				$achievements[$key] = 0;
			}
			
		}
		

		foreach ($achievementsArray as $key => $singleAchievement) {
			
			$buildings = $province->getBuildingsNum();
			$clanPts = $province->getClanPoints();
			$units = $province->getUnitsNum();
			$land = $province->getLand();
			$networth = $province->getNetworth();
			
			$targetNo = 500000;
			if($networth >= $targetNo && $achievements[$targetNo.'_networth'] == 0 && $key == $targetNo.'_networth') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 500000 NW check
			
			$targetNo = 1000000;
			if($networth >= $targetNo && $achievements[$targetNo.'_networth'] == 0 && $key == $targetNo.'_networth') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 1000000 NW check
			
			$targetNo = 2000000;
			if($networth >= $targetNo && $achievements[$targetNo.'_networth'] == 0 && $key == $targetNo.'_networth') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 2000000 NW check
			
			$targetNo = 3000000;
			if($networth >= $targetNo && $achievements[$targetNo.'_networth'] == 0 && $key == $targetNo.'_networth') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 3000000 NW check
			
			$targetNo = 4000000;
			if($networth >= $targetNo && $achievements[$targetNo.'_networth'] == 0 && $key == $targetNo.'_networth') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 4000000 NW check
			
			$targetNo = 60000;
			if($land >= $targetNo && $achievements[$targetNo.'_land'] == 0 && $key == $targetNo.'_land') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 60000 land check
			
			$targetNo = 100000;
			if($land >= $targetNo && $achievements[$targetNo.'_land'] == 0 && $key == $targetNo.'_land') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 100000 land check
			
			
			$targetNo = 1000;
			if($units >= $targetNo && $achievements[$targetNo.'_units'] == 0 && $key == $targetNo.'_units') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 1000 units check
			
			$targetNo = 5000;
			if($units >= $targetNo && $achievements[$targetNo.'_units'] == 0 && $key == $targetNo.'_units') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 5000 units check
			
			$targetNo = 10000;
			if($units >= $targetNo && $achievements[$targetNo.'_units'] == 0 && $key == $targetNo.'_units') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 10000 units check
			
			$targetNo = 20000;
			if($units >= $targetNo && $achievements[$targetNo.'_units'] == 0 && $key == $targetNo.'_units') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 20000 units check
			
			
			$targetNo = 30000;
			if($units >= $targetNo && $achievements[$targetNo.'_units'] == 0 && $key == $targetNo.'_units') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 30000 units check

			$targetNo = 50;
			if($buildings >= $targetNo && $achievements[$targetNo.'_buildings'] == 0 && $key == $targetNo.'_buildings') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 50 buildings check
			
			$targetNo = 1000;
			if($buildings >= $targetNo && $achievements[$targetNo.'_buildings'] == 0 && $key == $targetNo.'_buildings') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 1000 buildings check
			
			
			$targetNo = 5000;
			if($buildings >= 5000 && $achievements[$targetNo.'_buildings'] == 0 && $key == $targetNo.'_buildings') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 5000 buildings check
			
			
			$targetNo = 10000;
			if($buildings >= $targetNo && $achievements[$targetNo.'_buildings'] == 0 && $key == $targetNo.'_buildings') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 10000 buildings check
			
			$targetNo = 100;
			if($clanPts >= 100 && $achievements[$targetNo.'_points'] == 0 && $key == $targetNo.'_points') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 100 pts check
			
			
			$targetNo = 1000;
			if($clanPts >= 1000 && $achievements[$targetNo.'_points'] == 0 && $key == $targetNo.'_points') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 1000 pts check
			
			$targetNo = 2000;
			if($clanPts >= 2000 && $achievements[$targetNo.'_points'] == 0 && $key == $targetNo.'_points') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 2000 pts check
			
			$targetNo = 3000;
			if($clanPts >= 3000 && $achievements[$targetNo.'_points'] == 0 && $key == $targetNo.'_points') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end 3000 pts check
			
			
			if($province->getClan() != false && $achievements['clan_joined'] == 0 && $key == 'clan_joined') {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end in clan check
			
			

			
			
		}
		
				
		update_user_meta( $user_ID, 'achievements', maybe_serialize($achievements));
		
		
			
           
    }
}else{ // IF GAME IS NOT LIVE, SO END OF ROUND
	
	



    $users = get_users($args);
    foreach ($users as $user) {
        $user_ID = $user->ID;
        $province = Province::make($user_ID);
        
       
		/* Achievement checker */
		$achievements = maybe_unserialize(get_user_meta( $user_ID, 'achievements', true ));
		/* create achievements array for saving */
		if(empty($achievements)){
			
			foreach ($achievementsArray as $key => $singleAchievement) {
				$achievements[$key] = 0;
			}
			
		}
		

		foreach ($achievementsArray as $key => $singleAchievement) {
			
			
			
			
			if($key == 'dont_die_1' && $achievements['dont_die_1'] != 1 && get_user_meta( $user_ID, 'times_killed', true ) == 0 && $user_ID == 1) {
			
			$eventMsg = $singleAchievement['event_message'];
			$xp = $singleAchievement['xp'];
			Event::create(array(
            'title' => 'Achievement for '.$user_ID, 'author' => $user_ID, 'outcome' => $eventMsg, 'type' => 'achievement',
            'defender_id' => $user_ID, 'attacker_id' => $user_ID
        ), $user_ID);
        
        	$achievements[$key] = 1;
			$province->updateXP('single_achievement',0,$xp);
			
			} // end in clan check
			
			

			
			
		}
		
				
		update_user_meta( $user_ID, 'achievements', maybe_serialize($achievements));
		
		
			
           
    }
    

	
	
	
	
}
