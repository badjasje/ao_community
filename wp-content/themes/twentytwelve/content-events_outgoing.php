<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_ID();
			

			
			
 $custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
 $args = array(

	'posts_per_page'	=> 20,
	'orderby'          	=> 'date',
	'order'            	=> 'DESC',
	'paged'				=>  $custom_query_args['paged'],
	'post_type'        	=> 'event_local',
	'post_status'      	=> 'publish',
	'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'attacker_id',
						'value'	  	=> $user_ID,
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'attacktype',
						'value'	  	=> array('satellite','air_sea','thief','ground','missile','regular','death'),
						'compare' 	=> 'IN',
						),)
);

include('units_array.php');
include('building_array.php');
include('research_array.php');

update_user_meta($user_ID,'new_events',0);
$clan_ID = get_user_meta($user_ID, 'clan_id_user');

if($user_ID != 0){
	$members = get_post_meta($clan_ID[0],'clan_members');
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<center><h1>Events : Outgoing</h1>
		<p><a href="/events/incoming/">Incoming</a> | <a href="/events/outgoing/">Outgoing</a> | <a href="/events/global/">Global</a></p>
		</center>
				
			
			
			
				<?php 
					
					

					

					// Instantiate custom query
					$custom_query = new WP_Query( $args );
					
					// Pagination fix
					$temp_query = $wp_query;
					$wp_query   = NULL;
					$wp_query   = $custom_query;

					// Output custom query loop
					if ( $custom_query->have_posts() ) :
						while ( $custom_query->have_posts() ) :
						$custom_query->the_post();
					
					
						$event_ID = get_the_id();
						
						$defender_id = get_post_meta($event_ID,'defender_id');
						$attacker_id = get_post_meta($event_ID,'attacker_id');
						$status_defender = get_post_meta($event_ID,'status_defender',true);
		
						
						$def_unitslost = get_post_meta($event_ID,'defender_lost');
						$att_unitslost = get_post_meta($event_ID,'attacker_lost');
						$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost');
						$timeattacked = get_post_meta($event_ID,'time_attacked');
						$timestamp = strtotime(date('Y-m-d H:i:s'));
						$landlost = get_post_meta($event_ID,'land_lost');
						$moneylost = get_post_meta($event_ID,'money_lost');
						$attack_type = get_post_meta($event_ID,'attacktype', true);
						$clan_points = get_post_meta($event_ID,'clan_points', true);
						
						$winner_id = get_post_meta($event_ID,'winner_id');
					?>
<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular' and $status_defender != 'death'): ?>
	<div class="container2">
		<table class="responsive-table">
		<thead>
			<tr>
				<th scope="row" colspan='3'>Battle report</th>
			</tr>
		</thead>
		<tbody>
			
			<tr>
				<td style="vertical-align: middle;">
					<center><div class="<?php echo $attack_type;?>"></div></center>
				</td>
					
				<td style="vertical-align: middle;">
					<?php echo human_time_diff( $timeattacked[0], $timestamp );?>
				</td>
					
				<td style="text-align:center">
					<?php $member_data = get_userdata($defender_id[0]);?>
					
You attacked <a href="/users/profile/?id=<?php echo $member_data->ID;?>"> <?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> and you 

<?php if($winner_id[0] == $attacker_id[0]){?>

	<strong>won the battle.</strong> In this attack <strong>
	<?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> 
	and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
	<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
<?php } else { 
	echo '<strong>lost the battle</strong>';} ?>
					<br/><hr/>
					
					
					<strong>Attacker losses: <?php echo $att_tot_unitslost[0];?> units</strong><br/>
					<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
					echo '<br/><br/>';
					
					?>
					<strong>Defender losses: <?php echo $def_tot_unitslost[0];?> units and <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
				<?php
				foreach ($units as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>
<?php endif;?>

<?php if($status_defender == 'death'):?>
				
				
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Kill report</th>
					</tr>
					</thead>
					<tbody>
					
					<tr>
					<td style="vertical-align: middle;">
						<center><div class="<?php echo $status_defender;?>"></div></center>
					</td>
					
					<td style="vertical-align: middle;"><?php echo human_time_diff( $timeattacked[0], $timestamp );?></td>
					<td style="max-width: 280px;"><?php $member_data = get_userdata($defender_id[0]);?>
						You attacked <a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you <strong>killed</strong> this player.  
							<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
					
			
				
				
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				
				
<?php endif;?>

<?php if($attack_type == 'missile'):?>
				
				
				
				
				
				<div class="container2">
						<table class="responsive-table">
						<thead>
							<tr>
							<th scope="row" colspan='3'>Missile report</th>
							</tr>
						</thead>
						<tbody>
					
					<tr>
					<td style="vertical-align: middle;">
						<center><div class="<?php echo $attack_type;?>"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<?php echo human_time_diff( $timeattacked[0], $timestamp );?>
					</td>
					
					<td style="text-align:center">
						
						<?php $member_data = get_userdata($defender_id[0]);?>
						You launched a missile at 
						<a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you 
					
					<?php if($winner_id[0] == $attacker_id[0]){?>
						<strong>hit the enemy base. </strong>
						<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
						<br/><hr/>
					<?php } else { 
						echo '<strong>missed the base</strong>';} ?>
					
					<?php if($winner_id[0] == $attacker_id[0]):?>
					<strong>Defender losses: <?php echo $def_tot_unitslost[0];?> units and <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
				<?php
				foreach ($units as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php endif;?>
				</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				
				
				
<?php elseif($attack_type == 'satellite'):?>
				
				
				
				
				
				<div class="container2">
						<table class="responsive-table">
						<thead>
							<tr>
							<th scope="row" colspan='3'>Satellite report</th>
							</tr>
						</thead>
						<tbody>
					
					<tr>
					<td style="vertical-align: middle;">
						<center><div class="<?php echo $attack_type;?>"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<?php echo human_time_diff( $timeattacked[0], $timestamp );?>
					</td>
					
					<td style="text-align:center">
						
						<?php $member_data = get_userdata($defender_id[0]);?>
						You fired a satellite at 
						<a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you 
					
					<?php if($winner_id[0] == $attacker_id[0]){?>
						<strong>hit the enemy base. </strong>
						<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
					
					<?php } else { 
						echo '<strong>missed the base</strong>';} ?><br/><hr/>
					
					
					<strong>Defender losses: <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
			
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>

				
					
				
				
<?php elseif($attack_type == 'thief'):
	$thiefs_lost = get_post_meta($event_ID, 'thiefs_lost', true);
?>
				
				
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Thief report</th>
					</tr>
					</thead>
					<tbody>
					
					<tr>
					<td style="vertical-align: middle;">
						<center><div class="<?php echo $attack_type;?>"></div></center>
					</td>
					
					<td style="vertical-align: middle;"><?php echo human_time_diff( $timeattacked[0], $timestamp );?></td>
					<td style="max-width: 280px;"><?php $member_data = get_userdata($defender_id[0]);?>
						<?php if($winner_id[0] != $defender_id[0]):?>
						You sent a thief to <a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you stole <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong>
						<?php endif;?>
						<?php if($winner_id[0] == $defender_id[0]):?>
						You sent thiefs to <a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> but your thiefs were caught. You lost <?php echo $thiefs_lost;?> thiefs.
						<?php endif;?>
				
				
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>

				<?php endif;?>

				<?php endwhile; ?>
					<center><?php previous_posts_link('Previous') ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php next_posts_link('Next') ?></center>
					<?php wp_reset_postdata(); ?>
					<?php endif; ?>
				
					
				
				<?php // fixes bug where below ACF fields wont display 
			
					$wp_query = NULL;
					$wp_query = $temp_query; 
				?>
				
	
			
			
		
		
		
	
		</div><!-- .entry-content -->
		
	</article><!-- #post -->