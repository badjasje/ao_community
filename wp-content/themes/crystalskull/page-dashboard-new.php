<?php
 /*
 * Template Name: Dashboard new
 */
get_header(); 
include('startingbonus_array.php');
include('weather_array.php');

$startingDate = get_field('starting_date','options');
$endDate = get_field('end_date','options');
$currentWeather = get_field('weather','options');

$userId 					= get_current_user_ID();
$pageId 					= get_the_id();
$userData					= get_user_meta($userId);

$savedUsers 				= $userData['saved_users'][0];
$decodedSavedUsers          = json_decode($savedUsers);
$savedUsers 				= is_array($decodedSavedUsers) ? $decodedSavedUsers : [];
update_user_meta($userId, 'user_lock', 0);
$new_events 				= $userData['new_events'][0];
$new_messages 				= $userData['new_messages'][0];
$user_status 				= $userData['status'][0];
$nuke_protection_timestamp 	= $userData['nuke_protection_timestamp'][0];
$clan_ID 					= $userData['clan_id_user'][0];
$PtsRank 					= $userData['moh_position'][0];
$NwRank 					= $userData['mog_position'][0];
$PwrUsage 					= $userData['power'][0];
$AMS 						= $userData['antimissile'][0];
$def_land 					= $userData['builtland'][0];

$level_money_production 	= $userData['level_money_production'][0];
$sat_level 					= $userData['level_satellite_construction'][0];
$sat_morale 				= $userData['sat_morale'][0];

$morale 					= $userData['morale'][0];
$moralepool 				= $userData['morale_pool'][0];

$startingbonus 				= $userData['starting_bonus'][0];
$boni 						= array('offensive','defensive','finance','shipping');

$finance_multi = 1;

if($startingbonus == 'finance'){
	$finance_multi = 1.1;
}

/* Check for nightmode */

$nightmode = $userData['nightmode'][0];
$regular = '';
if($nightmode == 'regular'){
	$regular = 'selected';
}
$night = '';
if($nightmode == 'night'){
	$night = 'selected';
}
$nostalgia = '';
if($nightmode == 'nostalgia'){
	$nostalgia = 'selected';
}
$blackwhite = '';
if($nightmode == 'blackwhite'){
	$blackwhite = 'selected';
}
$grayscale = '';
if($nightmode == 'grayscale'){
	$grayscale = 'selected';
}


$shootdown_chance = 0;
if($AMS > 0){
    $shootdown_chance = (($AMS*100)/$def_land)*100;

    if($shootdown_chance >= 75){
        $shootdown_chance = 75;
    }
}

if ($level_money_production == 0){
    $income = 15000*$finance_multi;
}elseif($level_money_production == 1){
    $income = 25000*$finance_multi;
}elseif($level_money_production == 2){
    $income = 35000*$finance_multi;
}

if($user_status == 'dead'){
    after_death($userId);
}
$user = get_userdata($userId); 

if($clan_ID == 0){
    $clans = get_posts(
        [
            'numberposts'	=> -1,
            'post_type'		=> 'clan',
            'meta_key'		=> 'autojoin_allowed',
            'meta_value'	=> 'yes'
        ]
    );

    $clanCount = 0;

    foreach ($clans as $clan) {
        $members = count(get_post_meta($clan->ID,'clan_members',true));
        if ($members < 7) {
            $clanCount++;
        }
    }
}
?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	        
<div class="row">
	<div class="col-md-12">
		<?php if(!empty($_SESSION['status'])):?>
			<?php echo alert_notification($_SESSION['status']); // Display notification messages ?>
		<?php endif; // End empty status check ?>
	</div>
</div>



<?php if(get_field('game_status','option') == 'Pause' && $userId != 1): // Check if game is live or not ?>
			
	<div class="notice_message">
		<span class="rdw-line">The round has ended! Expect a new round on the 18th of january.</span>
	</div>
	
	
<div classs="row">	
	<div class="col-md-12 status_block">
		<div class="col-md-12 status_header">
			
			<?php if($user_status =='nukeprotection'):?>
			
			<?php
				$timestamp = current_time('timestamp');	                                                                                                                            
				$timeleft = $nuke_protection_timestamp-$timestamp;
				$timer_left = $nuke_protection_timestamp-$timestamp;								                                                                                                                        
									                                                                                                                                
				if($timeleft < 0){
					update_user_meta($userId, 'status', 'online');
									}
					$timeleft = date('H:i:s', $timeleft); ?>
						
					Protection time left: n.a
						<?php elseif($user_status =='online'): ?>
							Current status: Online 
						<?php endif;?>

		</div>
		
		<div class="col-md-6 status_column">
			<div class="row">
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Points rank</strong></div>
					<div class="col-xs-6"><?php echo number_format($PtsRank, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>AMS coverage</strong></div>
					<div class="col-xs-6"><?php echo number_format($shootdown_chance, 0, ',', ' ');?>%</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Events</strong></div>
					<div class="col-xs-6">
						<a href="/events/incoming/">
							<?php if($new_events > 0):?> 
								<span style="color:#ff0000"><?php echo $new_events;?></span> new event<?php if($new_events > 1 || $new_events == 0){echo 's';}?> 
								<?php else:?> <?php echo $new_events;?> new event<?php if($new_events > 1 || $new_events == 0){echo 's';}?> 
							<?php endif;?>
						</a>
					</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Morale & pool</strong></div>
					<div class="col-xs-6"><?php echo $morale.'% <sup>('.$moralepool.'%)</sup>';?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Starting bonus</strong></div>
					<div class="col-xs-6">
					
						<?php if(in_array($startingbonus, $boni)):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $bonuses[$startingbonus]['description'];?>" data-placement="left">
								<i class="fa <?php echo $bonuses[$startingbonus]['icon'];?>" aria-hidden="true"></i> <?php echo $bonuses[$startingbonus]['name'];?>
							</span>
						<?php else:?>
							<u>No starting bonus picked</u>
						<?php endif;?>
					
					</div>
				</div>
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Weather</strong></div>
					<div class="col-xs-6">
						<span 	class="hover-tip"  
								data-toggle="tooltip" 
								data-original-title="<?php echo $weather[$currentWeather]['effect'];?>" 
								data-placement="right">
							<i class="fa fa-info-circle" aria-hidden="true"></i>
						</span>
						<?php echo $weather[$currentWeather]['name'];?>					
					</div>
				</div>
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Color scheme</strong></div>
					<div class="col-xs-6">
						<div style="padding-bottom: 4px;">
						<form class="form"  action="<?php echo home_url() ?>/nightmode.php" name="" id="nightmode" method="post">
						
						<select name="mode" onChange="this.form.submit()" >
							<option name="mode" <?php echo $regular;?> value="regular">Regular</option>
							<option name="mode" <?php echo $night;?> value="night">Night mode</option>
							<option name="mode" <?php echo $nostalgia;?> value="nostalgia">Nostalgia</option>
							<option name="mode" <?php echo $grayscale;?> value="grayscale">Grayscale</option>
							<option name="mode" <?php echo $blackwhite;?> value="blackwhite">Black & White</option>
						</select>	
						
						</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	
	
		<div class="col-md-6 status_column">
			<div class="row">
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Networth rank</strong></div>
					<div class="col-xs-6"><?php echo number_format($NwRank, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6">
						<?php if($PwrUsage > 100):?>
							<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<?php endif;?>
						<strong>Power usage</strong></div>
					<div class="col-xs-6"><?php echo number_format($PwrUsage, 0, ',', ' ');?>%</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Inbox</strong></div>
					<div class="col-xs-6">
						<a href="/inbox/">
							<?php if($new_messages > 0):?> 
								<span style="color:#ff0000"><?php echo $new_messages;?></span> new message<?php if($new_messages > 1 || $new_messages == 0){echo 's';}?> 
							<?php else:?> 
								<?php echo $new_messages;?> new message<?php if($new_messages > 1 || $new_messages == 0){echo 's';}?> 
							<?php endif;?>
						</a>
					
					</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Hourly income</strong></div>
					<div class="col-xs-6">$ <?php echo number_format($income, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Satellite power</strong></div>
					<div class="col-xs-6"><?php echo $sat_morale;?>%</div>
				</div>
				
				<div class="row profile_row_last">
					<div class="col-xs-6"><strong>Like us on Facebook</strong></div>
					<div class="col-xs-6"><div style="padding-bottom: 9px;" class="fb-like" data-href="https://www.facebook.com/assault.online/" data-layout="button" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
					</div>
				</div>
				
				
				
			</div>
		</div>
	
	</div>
</div> <!-- // End status column -->
				
<?php else:?>



<?php if(!in_array($startingbonus, $boni)): // Check if player has starting bonus ?>
		
	<div class="col-md-12">
		<div class="startBonusCol">
	    	<div class="alert alert-warning alert-dismissible blue_alert" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<strong>Notice!</strong> Pick a starting bonus.
			</div>
			
			
	    	<form class="form" action="<?php echo home_url() ?>/startingbonus.php" name="" id="starting_bonus" method="post">	
	       	            
				<input required style="display:none;" type="radio" name="bonustype" id="offensive" value="offensive" >
				
					<label class="startingbonus" for="offensive">
						<h3 class="startinghead"><i class="fa fa-fire" aria-hidden="true"></i> Offensive</h3>
						Gain twice the land and money during attacks, plus 75 turns.
					</label>
	    
	    
				<input required style="display:none;" type="radio" name="bonustype" id="defensive" value="defensive" >
	    	
					<label class="startingbonus" for="defensive">
						<h3 class="startinghead"><i class="fa fa-shield" aria-hidden="true"></i> Defensive</h3>
						Constructing 10 buildings per turn by default (to a maximum of 20 with full research), 
						plus 20% extra defense for all defending units, plus 10% time deduction when researching, plus 3 500 m<sup>2</sup> of land.
					</label>
	    
	    
				<input required style="display:none;" type="radio" name="bonustype" id="finance" value="finance" >
	    	
					<label class="startingbonus" for="finance">
						<h3 class="startinghead"><i class="fa fa-usd" aria-hidden="true"></i> Finance</h3>
						Hourly income increased by 10%, bank capacity is raised by 50% and $400 000 money.
					</label>
					
	    
				<input required style="display:none;" type="radio" name="bonustype" id="shipping" value="shipping" >
					
					<label class="startingbonus" for="shipping">
						<h3 class="startinghead"><i class="fa fa-truck" aria-hidden="true"></i> Shipping</h3>
						Missile orders ship 50% faster, plus ability to choose exact arrival time for units (up to 6 hours delayed), 
						plus 10% default market discount (max 40% with research), 2 500 m<sup>2</sup> land and $250 000 money.
					</label>
	    
	    
				<input type="submit" value="Pick starting bonus" class="noTopMargin">
				
			</form>
			
		</div> <!-- // End startBonusCol -->
	</div> <!-- // End pick starting bonus column -->
<?php endif; // End starting bonus picking ?>
       
       

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
	<div classs="row">	
		<div class="col-md-12">
			<div class="startBonusCol">
				<div class="bonus_message">
					You can now receive a clan bonus of $ <?php echo number_format($money, 0, ',', ' '); ?> and 
					<?php echo $turns;?> turns. Auto receive in <?php echo human_time_diff( $time,$timestamp);?>
						
					<a class="btn btn-bonus" href="/receive_bonus.php/?id=<?php echo $event_ID;?>">Receive Bonus</a>
				</div>
			</div>
		</div>
	</div>	

<?php }} // End clan bonus check ?>


<?php if($clan_ID != 0){ // Check if user is part of clan ?>
	<div classs="row">	
		<div class="col-md-12">
			<div class="notice_message nostmessage">
				<h2 class="cmheader"><i class="fa fa-info-circle" aria-hidden="true"></i> Clan Message</h2>
				<?php if(!empty(get_post_meta($clan_ID, 'clan_message')[0])){
					echo get_post_meta($clan_ID, 'clan_message')[0];
					}?>
			</div>
		</div>
	</div>
			
<?php } // End clan message check ?>


<div classs="row">	
	<div class="col-md-12 status_block">
		<div class="col-md-12 status_header">
			
			<?php if($user_status =='nukeprotection'):?>
			
			<?php
				$timestamp = current_time('timestamp');	                                                                                                                            
				$timeleft = $nuke_protection_timestamp-$timestamp;
				$timer_left = $nuke_protection_timestamp-$timestamp;								                                                                                                                        
									                                                                                                                                
				if($timeleft < 0){
					update_user_meta($userId, 'status', 'online');
									}
					$timeleft = date('H:i:s', $timeleft); ?>
						
					Protection time left: <span id="countdown_time"></span>
					<?php if($timer_left < 86400){?>
						<a onclick="return confirm('Are you sure you want to remove protection?')" class="btn btn-danger" href="/remove_np.php/?user=<?php echo $userId;?>">
						<i class="fa fa-trash-o fa-lg"></i> Remove Protection</a>
					<?php }?>
					
						<?php elseif($user_status =='online'): ?>
							Current status: Online 
						<?php endif;?>

		</div>
		
		<div class="col-md-6 status_column">
			<div class="row">
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Points rank</strong></div>
					<div class="col-xs-6"><?php echo number_format($PtsRank, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>AMS coverage</strong></div>
					<div class="col-xs-6"><?php echo number_format($shootdown_chance, 0, ',', ' ');?>%</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Events</strong></div>
					<div class="col-xs-6">
						<a href="/events/incoming/">
							<?php if($new_events > 0):?> 
								<span style="color:#ff0000"><?php echo $new_events;?></span> new event<?php if($new_events > 1 || $new_events == 0){echo 's';}?> 
								<?php else:?> <?php echo $new_events;?> new event<?php if($new_events > 1 || $new_events == 0){echo 's';}?> 
							<?php endif;?>
						</a>
					</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Morale & pool</strong></div>
					<div class="col-xs-6"><?php echo $morale.'% <sup>('.$moralepool.'%)</sup>';?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Starting bonus</strong></div>
					<div class="col-xs-6">
					
						<?php if(in_array($startingbonus, $boni)):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $bonuses[$startingbonus]['description'];?>" data-placement="left">
								<i class="fa <?php echo $bonuses[$startingbonus]['icon'];?>" aria-hidden="true"></i> <?php echo $bonuses[$startingbonus]['name'];?>
							</span>
						<?php else:?>
							<u>No starting bonus picked</u>
						<?php endif;?>
					
					</div>
				</div>
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Weather</strong></div>
					<div class="col-xs-6">
						<span 	class="hover-tip"  
								data-toggle="tooltip" 
								data-original-title="<?php echo $weather[$currentWeather]['effect'];?>" 
								data-placement="right">
							<i class="fa fa-info-circle" aria-hidden="true"></i>
						</span>
						<?php echo $weather[$currentWeather]['name'];?>					
					</div>
				</div>
				<div class="row profile_row_last">
					<div class="col-xs-6"><strong>Color scheme</strong></div>
					<div class="col-xs-6">
						<div style="padding-bottom: 4px;">
						<form class="form"  action="<?php echo home_url() ?>/nightmode.php" name="" id="nightmode" method="post">
						
						<select name="mode" onChange="this.form.submit()" >
							<option name="mode" <?php echo $regular;?> value="regular">Regular</option>
							<option name="mode" <?php echo $night;?> value="night">Night mode</option>
							<option name="mode" <?php echo $nostalgia;?> value="nostalgia">Nostalgia</option>
							<option name="mode" <?php echo $grayscale;?> value="grayscale">Grayscale</option>
							<option name="mode" <?php echo $blackwhite;?> value="blackwhite">Black & White</option>
						</select>	
						
						</form>
						</div>
					</div>
				</div>
			

				
			</div>
		</div>
	
	
		<div class="col-md-6 status_column">
			<div class="row">
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Networth rank</strong></div>
					<div class="col-xs-6"><?php echo number_format($NwRank, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6">
						<?php if($PwrUsage > 100):?>
							<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<?php endif;?>
						<strong>Power usage</strong></div>
					<div class="col-xs-6"><?php echo number_format($PwrUsage, 0, ',', ' ');?>%</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Inbox</strong></div>
					<div class="col-xs-6">
						<a href="/inbox/">
							<?php if($new_messages > 0):?> 
								<span style="color:#ff0000"><?php echo $new_messages;?></span> new message<?php if($new_messages > 1 || $new_messages == 0){echo 's';}?> 
							<?php else:?> 
								<?php echo $new_messages;?> new message<?php if($new_messages > 1 || $new_messages == 0){echo 's';}?> 
							<?php endif;?>
						</a>
					
					</div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Hourly income</strong></div>
					<div class="col-xs-6">$ <?php echo number_format($income, 0, ',', ' ');?></div>
				</div>
				
				<div class="row profile_row">
					<div class="col-xs-6"><strong>Satellite power</strong></div>
					<div class="col-xs-6"><?php echo $sat_morale;?>%</div>
				</div>
				
				<div class="row profile_row_last">
					<div class="col-xs-6"><strong>Like us on Facebook</strong></div>
					<div class="col-xs-6"><div style="padding-bottom: 9px;" class="fb-like" data-href="https://www.facebook.com/assault.online/" data-layout="button" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
					</div>
				</div>
				
				
				
			</div>
		</div>
	
	</div>
</div> <!-- // End status column -->
          

<div class="row">
	<div class="col-md-12">
		<div class="row button_block">
		
			<div class="col-md-4 buttoncol">
				<center><a class="btn btn-general profilebutton" href="/military-overview/?id=<?php echo $userId;?>">
				<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;Military overview</a></center>
			</div>
	
 	
			<div class="col-md-4 buttoncol">
	 			<center><a class="btn btn-general profilebutton" href="/users/profile/edit/">
		 		<i class="fa fa-wrench" aria-hidden="true"></i> &nbsp;Edit your profile</a></center>
			</div>
	
			<div class="col-md-4 buttoncol">
	 			<center><a class="btn btn-general profilebutton" href="/player-statistics/">
		 		<i class="fa fa-bar-chart" aria-hidden="true"></i> &nbsp;View statistics</a></center>
			</div>
		</div>
	</div>
</div> <!-- // End edit profile & statistics button block -->



<?php if($clan_ID == 0):?>
<div class="row">
	<div class="col-md-12">
		<div class="row textNotify">
			<div class="col-md-12">
				<center><span class="rdw-line">Join a clan to get the full assault.online experience.</span> <span class="rdw-line">
				<?php echo $clanCount;?> clan<?php if($clanCount == 0 || $clanCount > 1){ echo 's';}?> currently looking for players.</span></center><br/>
			</div>
			
			<div class="col-md-4">
			</div>
	
			<div class="col-md-4">
				<center><a class="btn btn-general profilebutton" href="/join-a-clan/">
				<i class="fa fa-user-plus" aria-hidden="true"></i> &nbsp;View clans</a>
				</center>
			</div>
	
			<div class="col-md-4">
			</div>
  
		</div>
	</div>
</div> <!-- // End join clan block -->
<?php endif;?>



<div classs="row">	
	<div class="col-md-12">
		<div class="notice_message nostmessage">
			Current round date: <?php echo $startingDate;?> - <?php echo $endDate;?>
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="The round will end on <?php echo $endDate;?>, at a random time." data-placement="right">
					<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
		</div>
	</div>
</div>

<?php endif; // End check if game is live ?>

<div classs="row">	
	<div class="status_block">
		
		<div class="col-md-6 secBlock">
			<div class="status_header status_header_left inbox_header">
				<div class="row">
					<div class="col-xs-4">Name</div>
					<div class="col-xs-4">Ordered</div>
					<div class="col-xs-4">Time left</div>
				</div>
			</div>
			
			<div class="status_header status_header_left mobile_dash_header">
				<div class="row">
					<center>Last 5 market orders</center>
				</div>
			</div>
				
			<div class="status_column">
		<?php	
		 $args = array(
			    'posts_per_page'   => 5,
			    'meta_key'      => 'user_placed_id',
			    'meta_value'    => $userId,
			    'post_type'        => 'market_order',
			    );
				
				$units = get_posts( $args ); 
				$NrOrders = count($units);

				$timestamp = current_time('timestamp');
				$count = 0;			    
			    foreach ($units as $order) {
				    $count++;
				    $AddClass = '';
				    if($count == $NrOrders){
					    $AddClass = '_last';
					    
				    }
			        $units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
			        $order_type = get_post_meta($order->ID,'order_type',true);

			        $userId = $order->post_author;
			        $delivery_time = get_post_meta($order->ID,'delivery_time',true);
			        
			    
			        $timeleft = $delivery_time-$timestamp;
			        
			        if($timeleft >= 0){
			    
			        $timeleft = date('H:i:s', $timeleft); ?>
				
				<div class="row profile_row<?php echo $AddClass;?>">
					<div class="col-md-4">
						<span class="clan_data_left">Name</span>
						<span class="clan_data_right"><?php echo get_the_title($order->ID);?></span>
					</div>
					
					<div class="col-md-4">
						<span class="clan_data_left">Ordered</span>
						<span class="clan_data_right"><?php echo $units_in_this_order;?></span>
					</div>
					
					<div class="col-md-4">
						<span class="clan_data_left">Time left</span>
						<span class="clan_data_right"><?php echo $timeleft;?></span>
					</div>
				</div>
				
				
				
				<?php }} ?>
				<?php if($NrOrders == 0):?>
				<div class="row profile_row_last">
					
					<div class="col-xs-12"><center>No market orders to display</center></div>
					
				</div>
				<?php endif;?>
			</div>
		</div>
		
		
		
		<div class="col-md-6 secBlock">
			<div class="status_header status_header_left inbox_header">
				<div class="row">
					<div class="col-xs-4">Subject</div>
					<div class="col-xs-4">From</div>
					<div class="col-xs-4">Date</div>
				</div>
			</div>
			
			<div class="status_header status_header_left mobile_dash_header">
				<div class="row">
					<center>Last 5 inbox messages</center>
				</div>
			</div>
			
		
				
			<div class="status_column">
				
				<?php 
				$count = 0;
                $custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
                
                $args = array(
                'posts_per_page'   => 5,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'paged'             =>  $custom_query_args['paged'],
                'post_type'        => 'sub_user_message',
                'meta_query'    => array(
                    'relation'      => 'OR',
                        array(
                            'key'       => 'receiver_id',
                            'value'     => $userId,
                            'compare'   => '=',
                            ),
						                                                                                                                        
						                                                                                                                            
						                                                                                                                        
                )
            );
        // Instantiate custom query
                $custom_query = new WP_Query( $args );
              
                // Pagination fix
                $temp_query = 	$wp_query;
                $wp_query   = 	NULL;
                $wp_query   = 	$custom_query;
				$postCount 	=	$wp_query->post_count;
				
                // Output custom query loop
                if ( $custom_query->have_posts() ) :
                    while ( $custom_query->have_posts() ) :
                    $custom_query->the_post();
				$count++;
                $message_ID = get_the_id();
                $parent_ID = get_post_meta($message_ID, 'parent_message_id',true);
                $sender = get_userdata( get_the_author_meta('ID') );
                $receiver_id = get_post_meta($message_ID, 'receiver_id',true);
                $sender_id = get_post_meta($message_ID, 'receiver_id',true);        
                $receiver = get_userdata( $receiver_id );   
                
       
			    $AddClass = '';
			    
			    if($count == $postCount){
				    $AddClass = '_last';
				    
			    }
			    
                ?>

				<div class="row profile_row<?php echo $AddClass;?>">
					<div class="col-md-4">
						<span class="clan_data_left">Subject</span>
						<span class="clan_data_right">
						<a href="<?php echo get_the_permalink($parent_ID);?>">
							<?php if (strlen(get_the_title($parent_ID)) > 55) {
	                        echo substr(get_the_title($parent_ID), 0, 55) . '...'; } else {
	                        echo get_the_title($parent_ID);
	                                    }?>
						</a>
						</span>
					</div>
					<div class="col-md-4">
						<span class="clan_data_left">From</span>
						<span class="clan_data_right ellipsedColumn">
						<?php if($sender->ID == $userId){
							echo 'Sent by you';}else{?>
							<a href="/users/profile/?id=<?php echo $sender->ID;?>">
								<?php echo $sender->display_name.' (#'.$sender->ID.')';?>
							</a> <?php }?>
						</span>
		
					</div>
					<div class="col-md-4">
						<span class="clan_data_left">Date</span>
						<span class="clan_data_right"><?php echo get_the_date('d-m-Y'); ?></span>
					</div>
				</div>
				
				<?php  endwhile; endif; ?>
				<?php 	wp_reset_postdata(); // fixes bug where below ACF fields wont display 
				                                                                        
	                    $wp_query = NULL;
	                    $wp_query = $temp_query; 
	                ?>
				<?php if($postCount == 0):?>
				<div class="row profile_row_last">
					
					<div class="col-xs-12"><center>No inbox messages</center></div>
					
				</div>
				<?php endif;?>
			</div>
		</div>
		
	</div>
</div>




<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Earth
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Highest land area at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_next'][0];?> m<sup>2</sup></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moe_prev'][0];?> m<sup>2</sup></div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Honor
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most clan points gained by a province." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_next'][0];?> pts</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moh_prev'][0];?> pts</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Growth
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Highest networth at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
					
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mog_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['mog_next'][0], 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['mog_prev'][0], 0, ',', ' ');?></div>
		</div>
	</div>
</div>



<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Courage
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most attacks made by a province during clan war." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_next'][0];?> attacks</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['moc_prev'][0];?> attacks</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Death
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Killed most provinces during clan wars." data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_next'][0];?> kills</div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mod_prev'][0];?> kills</div>
		</div>
	</div>
	
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Thievery
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most money stolen at the end of round." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['mot_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['mot_next'][0], 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['mot_prev'][0], 0, ',', ' ');?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Destruction
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most networth damage made using missiles." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['modes_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Next position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['modes_next'][0], 0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row">Previous position:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['modes_prev'][0], 0, ',', ' ');?></div>
		</div>
	</div>

	<div class="col-md-4 medal_col">
		<div class="row medal_box">
			<div class="col-md-12 medal_header">
				<strong>Medal of Devastation
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="Most networth damage done in a single attack. Attack must be done in clan war." data-placement="bottom">
					<i class="fa fa-info-circle" aria-hidden="true"></i></span>
				</strong></div>
			<div class="col-md-6 col-xs-6 medal_row">Position:</div>
			<div class="col-md-6 col-xs-6 medal_row"><?php echo $userData['modev_position'][0];?></div>
			<div class="col-md-6 col-xs-6 medal_row">Networth damage:</div>
			<div class="col-md-6 col-xs-6 medal_row">$ <?php echo number_format($userData['modev_damage'][0],0, ',', ' ');?></div>
			<div class="col-md-6 col-xs-6 medal_row"></div>
			<div class="col-md-6 col-xs-6 medal_row"></div>
		</div>
	</div>
</div>

<!-- NEWS -->

<div classs="row">	
	<div class="status_block">
		
		<div class="col-md-6 secBlock">
			<div class="status_header status_header_left inbox_header">
				<div class="row">
					<div class="col-md-12">News</div>
				</div>
			</div>
			
			<div class="status_header status_header_left mobile_dash_header">
				<div class="row">
					<center>News</center>
				</div>
			</div>
				
			<div class="status_column">
				<?php	
					$args = array(
						'posts_per_page'   => 5,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'post_status'      => 'publish',
						'post_type'        => 'post'
					);
				
					$posts = get_posts( $args ); 
				
				foreach ($posts as $post) { ?>
				
				
				<div class="row profile_row">
					<div class="col-md-12">
						<a href="<?php echo get_the_permalink($post->ID);?>">
							<?php echo $post->post_title;?>
						</a>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
       



<!-- SAVED USERS -->

<div classs="row">	
	<div class="status_block">
		
		<div class="col-md-6 secBlock">
			<div class="status_header status_header_left inbox_header">
				<div class="row">
					<div class="col-md-12"><a class="savedUsers" href="/saved-users/">Saved users</a></div>
				</div>
			</div>
			
			<div class="status_header status_header_left mobile_dash_header">
				<div class="row">
					<center><a href="/saved-users/">Saved users</a></center>
				</div>
			</div>
				
			<div class="status_column">
				<?php
				$usercount = count($savedUsers);
				foreach ($savedUsers as $key => $user) { ?>
				
				<div class="row profile_row">
					<div class="col-md-12">
						<a href="<?php echo get_the_permalink($post->ID);?>">
							<?php echo get_user_name($user);?>
							<a href="/remove.php/?id=<?php echo $user;?>&return=<?php echo $pageId;?>">
								<i class="fa fa-trash-o" aria-hidden="true"></i></a>
						</a>
					</div>
				</div>
				<?php }?>
				<?php if($usercount == 0):?>
					<div class="row profile_row">
						<div class="col-md-12">
							No saved users
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>

       
       
<?php if(current_user_can('activate_plugins')){ ?><br>
	

<center>
<h2>Message all users</h2>
</center>


<form action="<?php echo home_url() ?>/message_all_users.php" class="form" id="message" method="post" name="">
<table style="margin-left:auto;margin-right:auto;max-width:450px;">
<tr>
<td>
<center>
<input id="title" name="title" placeholder="Subject" style="width:95%;" type="text">
</center>
</td>
</tr>


<tr>
<td>
<center>
​ 

<textarea cols="70" id="message" name="message" placeholder="Your message..." rows="10" style="width:95%;"></textarea>
</center>
</td>
</tr>
</table>


<center>
<input type="submit" value="Send message to all">
</center>
</form>
<?php }?>
  
       
       
       
<?php if($user_status =='nukeprotection'):?>    
<script>
var
diff = <?php echo $timer_left*1000;?>;

function updateETime() {

function pad(num) {
return num > 9 ? num : '0'+num;
};


days = Math.floor( diff / (1000*60*60*48) ),
hours = Math.floor( diff / (1000*60*60) ),
mins = Math.floor( diff / (1000*60) ),
secs = Math.floor( diff / 1000 ),

dd = days,
hh = hours - days * 24,
mm = mins - hours * 60,
ss = secs - mins * 60;

document.getElementById("countdown_time")
.innerHTML =

pad(hh) + ':' + //' hours ' +
pad(mm) + ':' + //' minutes ' +
pad(ss) ; //+ ' seconds' ;

diff -= 1000;

}
setInterval(updateETime, 1000 );
</script>	       
<?php endif;?>
       
    <?php if($userData['first_visit'][0] == 0):?>

    <?php endif;?>
<?php //update_user_meta($userId, 'first_visit', 1);?>

            </div> <!-- // End main col-lg-12 col-md-12 wrapper -->
        </div> <!-- // End main row -->
    </div> <!-- // End main container -->
</div> <!-- // End page normal-page -->
<?php session_unset();?>
<?php get_footer(); ?>