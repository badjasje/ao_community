
<?php $args = array(  
		'author'        =>  $userId, 
		'numberposts'	=> -1,
		'orderby'       =>  'post_date',
		'post_type'		=>	'event_local',		
		'meta_query'	=> array(
			'relation'		=> 'AND',
				array(
					'key'	 	=> 'attacktype',
					'value'	  	=> array('bonus'),
					'compare' 	=> 'IN',
						),
					),			
		'order'         =>  'ASC' );	
					
				
		$bonus_posts = get_posts( $args ); // Get all bonusses for player
			
			foreach ($bonus_posts as $bonus) {
				$event_ID = $bonus->ID;
				$used = get_post_meta($event_ID, 'bonus_used', true);
					
				if($used != 'yes'){ // Check if bonus is used
					$money = get_post_meta($event_ID, 'bonus_money', true);
					$turns = get_post_meta($event_ID, 'bonus_turns', true);
					$time = get_post_meta($bonus->ID,'time_attacked', true)+(86400*2);
					$autoreceive = $time - $timestamp;
						
					?>
	
<div class="blockHeader">You can now receive a clan bonus of $ <?php echo number_format($money, 0, ',', ' '); ?> and 
					<?php echo $turns;?> turns.</div>
					<div class="blockHeader spaceNotice">Auto receive in <?php echo human_time_diff( $time,$timestamp);?></div>
						
					<a class="mainSubmit" href="/receive_bonus.php/?id=<?php echo $event_ID;?>">Receive Bonus</a>
	
<div class="pageSpacer"></div>
<?php }} // End clan bonus check ?>