<?php
 /*
 * Template Name: Events Global
 */
$user_ID = get_current_user_ID();
include('units_array.php');
include('building_array.php');

update_user_meta($user_ID, 'new_global_events', 0);
$clan_ID = get_user_meta($user_ID, 'clan_id_user');

if($user_ID != 0){
	$members = get_post_meta($clan_ID[0],'clan_members');
}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <center>
		

		<p><a class="btn btn-general" href="/events/incoming/"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Incoming</a> <a class="btn btn-general" href="/events/outgoing/"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> Outgoing</a> <a class="btn btn-general current_but" href="/events/global/"><i class="fa fa-globe" aria-hidden="true"></i> Global</a></p></center>
			
			

			<?php 
			
				$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				
				$args = array(
			'posts_per_page'	=> 20,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'event_local',
			'paged'			=> $custom_query_args['paged'],
			'meta_query'	=> array(
                         'relation' => 'AND',
                         array(
                           'relation' => 'OR',
    
                           array(
                             'key' => 'attacker_id',
                             'value' => $members[0],
                             'compare' => 'IN'
                           ),
                           array(
                             'key' => 'defender_id',
                             'value' => $members[0],
                             'compare' => 'IN'
                           )
                         ),
                         array(
                           'key' => 'attacktype',
                           'value' => array('satellite','regular','air_sea','ground','missile','war_declared','peace_declared'),
                           'compare' => 'IN'
                         ),
                     array(
                           'relation' => 'OR',
                         array(
                           'key' => 'attacker_clan_id',
                           'value' => $clan_ID[0],
                           'compare' => 'IN'
                         ),
                         array(
                           'key' => 'defender_clan_id',
                           'value' => $clan_ID[0],
                           'compare' => 'IN'
                         ),
                         )
                      ),
				);
				
				
				
				
					
			
			// Define custom query parameters
				
					// Get current page and append to custom query parameters array
			

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
						
						
						
						$global_event_ID = get_the_id();
						$defender_id = get_post_meta($global_event_ID,'defender_id');
						$attacker_id = get_post_meta($global_event_ID,'attacker_id');
						
						$winner_id = get_post_meta($global_event_ID,'winner_id');
						$def_unitslost = get_post_meta($global_event_ID,'defender_lost');
						$att_unitslost = get_post_meta($global_event_ID,'attacker_lost');
						$def_tot_unitslost = get_post_meta($global_event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($global_event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($global_event_ID,'total_buildings_lost');
						$timeattacked = get_post_meta($global_event_ID,'time_attacked');
						$landlost = get_post_meta($global_event_ID,'land_lost');
						$moneylost = get_post_meta($global_event_ID,'money_lost');
						$timestamp = strtotime(date('Y-m-d H:i:s'));
						
						$clan_points = get_post_meta($global_event_ID,'clan_points', true);
						
						$attack_type = get_post_meta($global_event_ID,'attacktype')[0];
					?>
					
					<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular'): ?>
				
				
				
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
						<center><div class="<?php echo get_post_meta($global_event_ID,'attacktype')[0];?>"></div></center>
					</td>
					<td style="vertical-align: middle;">
						<?php echo human_time_diff( $timeattacked[0], $timestamp );?>
					</td>
					<td style="max-width: 280px;">
						<?php 	$member_data = get_userdata($attacker_id[0]);
								$defender_data = get_userdata($defender_id[0]);
						?>
						<?php if(in_array($attacker_id[0], $members[0])):?>
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> attacked
						
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						won the battle. In this attack <strong><?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen. 
						<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
						<?php } else { ?>
						<strong>lost the battle</strong>
						<?php }?>
						<?php endif;?>
						
						<?php if(in_array($defender_id[0], $members[0])):?>
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> was attacked by
						
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						lost the battle. In this attack <strong><?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
						<?php } else { ?>
						<strong>won the battle</strong>
						<?php }?>
						<?php endif;?>
						
						
						
						
					<br/><hr/>
					
					
					
					
				
					<strong>Attacker losses: <?php echo $att_tot_unitslost[0];?></strong><br/>
					<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}?><br/><br/>
					<strong>Defender losses: <?php echo $def_tot_unitslost[0];?> units and <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
				<i><?php
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
				?></td>
					</tbody>
				</table>
				</div>
				
				
				
				
				<?php elseif($attack_type == 'missile'):?>
				
				
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Missile report</th>
					</tr>
					</thead>
					<tbody>
					<tr>
					<!-- check if defender died -->
					<?php if(!empty($status_defender[0])):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id);?>
					You were attacked by <?php echo clan_tag($event->post_author);?> <a href="/users/profile/?id=<?php echo $event->post_author;?>"><?php echo $member_data->display_name.' (#'.$event->post_author.')';?></a> and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo $attack_type;?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="max-width: 280px;">
						<?php 	$member_data = get_userdata($attacker_id[0]);
								$defender_data = get_userdata($defender_id[0]);
						?>
						<?php if(in_array($attacker_id[0], $members[0])) {?>
						<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> shot down the missile of
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a>
						<?php }else {?>
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> launched a missile at
						
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						hit the enemy base.
						<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
						<?php } else { ?>
						<strong>missed the enemy base.</strong>
						<?php }}}?>
						
						
						<?php if(in_array($defender_id[0], $members[0])) {?>
						<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> shot down the missile of
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a>
						<?php }else {?>
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> was attacked by
						
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						lost the battle. 
						<br/><hr/>
						<?php } else { ?>
						<strong>won the battle</strong>
						<?php }}}?>
						
					
					
					
					
					<?php endif;?>
					
					
					<?php if($winner_id[0] == $attacker_id[0]):?>
					<br/><br/><strong>Defender losses: <?php echo $def_tot_unitslost[0];?> units and <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
				<i><?php
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
				?></i>
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
					<!-- check if defender died -->
					<?php if(!empty($status_defender[0])):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id);?>
					You were attacked by <?php echo clan_tag($event->post_author);?> <a href="/users/profile/?id=<?php echo $event->post_author;?>"><?php echo $member_data->display_name.' (#'.$event->post_author.')';?></a> and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo $attack_type;?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="max-width: 280px;">
						<?php 	$member_data = get_userdata($attacker_id[0]);
								$defender_data = get_userdata($defender_id[0]);
						?>
						<?php if(in_array($attacker_id[0], $members[0])) {?>
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> fired a satellite at
						
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						hit the enemy base.
						<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
						<?php endif;?>
						<?php } else { ?>
						<strong>missed the enemy base.</strong>
						<?php }}?>
						
						
						<?php if(in_array($defender_id[0], $members[0])) {?>
						
						<?php echo clan_tag($defender_id[0]);?> <a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> was attacked by
						
						
						<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $attacker_id[0]){?>
						lost the battle.
						<?php } else { ?>
						<strong>won the battle</strong>
						<?php }}?>
						<br/><hr/>
					
					
					
					
					<?php endif;?>
					
					
		
					<strong>Defender losses: <?php echo $def_tot_buildingslost[0];?> buildings.</strong><br/>
			
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?></td>
					</tr>
				</tbody>
				</table>
				</div>
			
			
			
			
			
			
			
			
			
				
				
				
				<?php endif;?>
				
				
				<?php if($attack_type == 'war_declared'):
					$declaring_clan = get_post_meta($global_event_ID,'attacker_clan_id',true);
					
					$declared_clan = get_post_meta($global_event_ID,'defender_clan_id',true);
					
				?>
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>New war</th>
					</tr>
					</thead>
					<tbody>
					
					<tr>
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="research"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?></center>
					</td>
					
					<td style="max-width: 280px;">
						<?php if($clan_ID[0] == $declaring_clan):?>
Declared war on <a href="<?php echo get_the_permalink($declared_clan);?>"><?php echo get_the_title($declared_clan);?> (#<?php echo $declared_clan;?>)</a>
						<?php elseif($clan_ID[0] == $declared_clan):?>
						<a href="<?php echo get_the_permalink($declaring_clan);?>"><?php echo get_the_title($declaring_clan);?> (#<?php echo $declaring_clan;?>)</a> declared war against your clan.
						<?php endif;?>
					</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				
				<?php endif;?>
				
				<?php if($attack_type == 'peace_declared'):
					$declaring_clan = get_post_meta($global_event_ID,'attacker_clan_id',true);
					
					$declared_clan = get_post_meta($global_event_ID,'defender_clan_id',true);
					
				?>
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Peace</th>
					</tr>
					</thead>
					<tbody>
					
					<tr>
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="research"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?></center>
					</td>
					
					<td style="max-width: 280px;">
						<?php if($clan_ID[0] == $declaring_clan):?>
Declared peace with <a href="<?php echo get_the_permalink($declared_clan);?>"><?php echo get_the_title($declared_clan);?> (#<?php echo $declared_clan;?>)</a>
						<?php elseif($clan_ID[0] == $declared_clan):?>
						<a href="<?php echo get_the_permalink($declaring_clan);?>"><?php echo get_the_title($declaring_clan);?> (#<?php echo $declaring_clan;?>)</a> declared peace with your clan.
						<?php endif;?>
					</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				
				<?php endif;?>
				
				<?php ?>
				<?php endwhile;
						endif; ?>
				
					<center><?php previous_posts_link('Previous') ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php next_posts_link('Next') ?></center>
				
				<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 
					
					$wp_query = NULL;
					$wp_query = $temp_query; 
				?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>