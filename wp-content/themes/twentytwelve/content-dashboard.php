<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_ID();
$new_events = get_user_meta($user_ID, 'new_events');
$new_messages = get_user_meta($user_ID, 'new_messages');
$user_status = get_user_meta($user_ID, 'status');
$nuke_protection_timestamp = get_user_meta($user_ID,'nuke_protection_timestamp');
$clan_ID = get_user_meta($user_ID, 'clan_id_user')[0];

$level_money_production = get_user_meta($user_ID, 'level_money_production',true);
$sat_level = get_user_meta($user_ID, 'level_satellite_construction',true);
$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

$morale = get_user_meta($user_ID, 'morale',true);
$moralepool = get_user_meta($user_ID, 'morale_pool',true);

if($user_status[0] == 'dead'){
	
	after_death($user_ID);
}
$user = get_userdata($user_ID);

 ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<div class="entry-content">
			<center><h1>Dashboard <a href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a></h1></center><br/>
			<center>A reset will take place the <strong><u>1st of november</u></strong>, effectively 'ending' the round. Your inbox is kept, so is the forum.</center><br/><br/>
			<center><a href="/reset_province.php">{ { RESET PROVINCE } }</a> <br/>(cannot be undone, no verification so watch it.)</center>
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice">Nuke protection removed.</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Build more warfactories</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 20):?>
				<div class="marketnotice">Message sent to all users</div>
			<?php endif;?><?php endif;?>
			
			
			<div class="container2">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Status</th>
							<th scope="col">Points rank</th>
							<th scope="col">Networth rank</th>
							<th scope="col">Power usage</th>
							<th scope="col">Events</th>
							<th scope="col">Inbox</th>
							<th scope="col">Hourly income</th>
							<th scope="col">Morale <sup>(Pool)</sup></th>
							<?php if(!empty($sat_level) || $sat_level != 0):?>
							<th scope="col">Satellite power</th>
							<?php endif;?>
						</tr>
					</thead>
				<tbody>
					
					
					
			
			
			
	
			<tr>
				<th scope="row">
				<?php if($user_status[0] =='nukeprotection'):
					$timestamp = strtotime(date('Y-m-d H:i:s'));
			
					$timeleft = $nuke_protection_timestamp[0]-$timestamp;
		
				
					if($timeleft < 0){
					update_user_meta($user_ID, 'status', 'online');}
					$timeleft = date('H:i:s', $timeleft);
					
					
					
				?>
				<center>You are currently under Nuke Protection | Time left: <strong><?php echo $timeleft;?></strong></center>
				<?php elseif($user_status[0] =='online'):
				
				?>
				Current status: Online
				<?php endif;?>
				</th>
				<td data-title="Points rank">
					<?php $power_usage = get_user_meta($user_ID, 'points_position');echo number_format($power_usage[0], 0, ',', ' ');?>
				</td>
				<td data-title="Networth rank">
					<?php $power_usage = get_user_meta($user_ID, 'networth_position');echo number_format($power_usage[0], 0, ',', ' ');?>
				</td>
				<td data-title="Power usage">
					<?php $power_usage = get_user_meta($user_ID, 'power');echo number_format($power_usage[0], 0, ',', ' ');?>%
				</td>
				
				<td data-title="New events">
					<a href="/events/incoming/">
					<?php if($new_events[0] > 0):?>
					<span style="color:#ff0000">
					<?php echo $new_events[0];?></strong> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?>
					</span>
					<?php else:?>
					<?php echo $new_events[0];?></strong> new event<?php if($new_events[0] > 1 || $new_events[0] == 0){echo 's';}?>
					<?php endif;?>
					</a>
				</td>
				<td data-title="Inbox">
					<a href="/inbox/">
					<?php if($new_messages[0] > 0):?>
					<span style="color:#ff0000">
					<?php echo $new_messages[0];?></strong> new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?>
					</span>
					<?php else:?>
					<?php echo $new_messages[0];?></strong> new message<?php if($new_messages[0] > 1 || $new_messages[0] == 0){echo 's';}?>
					<?php endif;?>
					</a>
				</td>
				<td data-title="Hourly income">
					<?php if($level_money_production == 0){
						echo '$ 15 000';
						}elseif($level_money_production == 1){
						echo '$ 20 000';	
						}elseif($level_money_production == 2){
						echo '$ 30 000';	
						}
					
					
					?>
				</td>
				<td data-title="Morale & pool">
					<?php echo $morale.'% <sup>('.$moralepool.'%)</sup>';?>
				</td>
				<?php if(!empty($sat_level) || $sat_level != 0):?>
				<td data-title="Satellite power">
					<?php echo $sat_morale;?>%
				</td>		
				<?php endif;?>
			</tr>
			
		</table>
		</div>
		
		
		<?php if(current_user_can('activate_plugins')){ ?>			
			<br/><br/>
			<center><h2>Message all users</h2></center>
			<form class="form" action="<?php echo home_url() ?>/message_all_users.php" name="" id="message" method="post">
				<table style="margin-left:auto;margin-right:auto;max-width:450px;">
					<tr>
						<td><center>
				<input style="width:95%;" type="text" id="title" placeholder="Subject" name="title"/>

						</td></center>
					</tr>
					<tr>
						<td><center>
				​<textarea style="width:95%;"id="message" rows="10" name="message"placeholder="Your message..."cols="70"></textarea>
						</td></center>
					</tr>
				</table>
				<center><input type="submit" value="Send message to all"></center>
			</form>
			
			<?php }?>
		
		<?php if($clan_ID != 0){ ?>
		<div class="container2">
			<table>
				<thead>
					<tr>
						<td>
							<center>Clan Message</center>
						</td>
					</tr>
				</thead>
			<tbody>
			<tr>
				<td>
				<?php if(!empty(get_post_meta($clan_ID, 'clan_message')[0])){echo get_post_meta($clan_ID, 'clan_message')[0];}?>
				</td>
			</tr>
			</tbody>
		</table>
		</div>
		<?php }?>
		
		
		
		
		
		
		<?php if($user_status[0] =='nukeprotection'):?>
		<center><strong><a href="/remove_np.php/?user=<?php echo get_current_user_ID();?>">REMOVE NUKEPROTECTION</a></strong></center>
		<?php endif;?>
		
	
		
		</div><!-- .entry-content -->
		<?php session_unset(); ?>
	</article><!-- #post -->
