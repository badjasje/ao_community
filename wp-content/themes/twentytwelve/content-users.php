<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_id();

$users = get_users();
$networth_you = get_user_meta($user_ID, 'networth');
include 'constants.php';


$timestamp = strtotime(date('Y-m-d H:i:s'));


?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	

		<div class="entry-content">
		<center><h1>Users</h1>
		<div class="container">

			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">All users</li>
			<li class="tab-link" data-tab="tab-2">In range</li>
			<li class="tab-link" data-tab="tab-3">Online</li>
			</ul>	
		</center>
		<div id="tab-1" class="tab-content current">
	
			
			<table>
					<tr>
						<td>Name</td>
						<td>Networth</td>
						<td>Clan</td>
						<td>Land</td>
  					</tr>
			
			<?php 
				
				
				foreach ($users as $user) {
				$user_NW = get_user_meta($user->ID, 'networth');
				$user_land = get_user_meta($user->ID, 'land');
				$networth = get_user_meta($user->ID, 'networth');
				$user_status = get_user_meta($user->ID, 'status');
				$clan_id = get_user_meta($user->ID, 'clan_id_user');
				$last_online = get_user_meta($user->ID, 'last_online');
				if(!empty($last_online)){
				$last_seen = $timestamp - $last_online[0];}
				?>
				<tr>
				<td>
					<a class="<?php echo $user_status[0];?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a><?php
						if(!empty($last_online)){
						if($last_seen < 7200 && !empty($last_online[0])){echo ' <span style="color:#ff0000">*</span';}}?>
				
				</td>
				<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php if($clan_id[0] == 0){
							echo 'none';}else{
							echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
							}?></td>
				<td><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>
				
				</tr>
				<?php }?>
			</table>
			
			
		</div>
			
			
			
			
		<div id="tab-2" class="tab-content">
				<center>You can target provinces with a networth between <?php echo '$ '.number_format($networth_you[0]/$ATTACK_RANGE_MULT, 0, ',', ' ').' and $ '.number_format($networth_you[0]*$ATTACK_RANGE_MULT, 0, ',', ' ');?></center><br/>
			
			
			<table>
					<tr>
						<td>Name</td>
						<td>Networth</td>
						<td>Clan</td>
						<td>Land</td>
  					</tr>
			
			<?php 
			
				
				foreach ($users as $user) {
				$user_land = get_user_meta($user->ID, 'land');
				
				$user_NW = get_user_meta($user->ID, 'networth');
				if (($user_NW[0] > $networth_you[0]/$ATTACK_RANGE_MULT && $user_NW[0] < $networth_you[0]*$ATTACK_RANGE_MULT)){	
					$clan_id = get_user_meta($user->ID, 'clan_id_user');
				?>
				<tr>
				<td><a class="<?php echo $user_status[0];?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a></td>
				<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php if($clan_id[0] == 0){
							echo 'none';}else{
							echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
							}?></td>
				<td><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>
				
				</tr>
				<?php }}?>
			</table>
		</div>
		
		
		
		<div id="tab-3" class="tab-content">
	
			
			<table>
					<tr>
						<td>Name</td>
						<td>Networth</td>
						<td>Clan</td>
						<td>Land</td>
  					</tr>
			
			<?php 
				
				
				foreach ($users as $user) {
				$user_NW = get_user_meta($user->ID, 'networth');
				$user_land = get_user_meta($user->ID, 'land');
				$networth = get_user_meta($user->ID, 'networth');
				$user_status = get_user_meta($user->ID, 'status');
				$clan_id = get_user_meta($user->ID, 'clan_id_user');
				$last_online = get_user_meta($user->ID, 'last_online');
				
				if(!empty($last_online[0])){
				$last_seen = $timestamp - $last_online[0];
			
				if($last_seen < 7200 && !empty($last_online[0])) { ?>
				<tr>
				<td>
					<a class="<?php echo $user_status[0];?>" href="/users/profile/?id=<?php echo $user->ID;?> "><?php echo $user->display_name.' (#'.$user->ID;?>)</a><span style="color:#ff0000"> *</span>
				
				</td>
				<td>$ <?php echo number_format($user_NW[0], 0, ',', ' ')?></td>
				<td><?php if($clan_id[0] == 0){
							echo 'none';}else{
							echo '<a href="'.get_the_permalink($clan_id[0]).'">'.get_the_title($clan_id[0]).' (#'.$clan_id[0].')</a>';
							} ?></td>
				<td><?php echo number_format($user_land[0], 0, ',', ' ')?>m<sup>2</sup></td>
				
				</tr>
				<?php }}} ?>
			</table>
			
			
		</div>
		
		</div>
		</div>
		
		
	
		</div><!-- .entry-content -->
	</article><!-- #post -->
