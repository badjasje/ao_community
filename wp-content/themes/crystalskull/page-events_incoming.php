<?php
 /*
 * Template Name: Events Incoming
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
				
					array(
						'key'	 	=> 'defender_id',
						'value'	  	=> $user_ID,
						'compare' 	=> '=',
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

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<center>
		<p><a class="btn btn-general current_but" href="/events/incoming/"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Incoming</a> <a class="btn btn-general" href="/events/outgoing/"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> Outgoing</a> <a class="btn btn-general" href="/events/global/"><i class="fa fa-globe" aria-hidden="true"></i> Global</a></p>
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
			
			
						
						$def_unitslost = get_post_meta($event_ID,'defender_lost');
						$att_unitslost = get_post_meta($event_ID,'attacker_lost');
						
						$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost');
						$landlost = get_post_meta($event_ID,'land_lost');
						$moneylost = get_post_meta($event_ID,'money_lost');
						
						$status_defender = get_post_meta($event_ID,'status_defender',true);
						
						
					
						$timeattacked = get_post_meta($event_ID,'time_attacked');
						$timestamp = strtotime(date('Y-m-d H:i:s'));
						$attack_type = get_post_meta($event_ID,'attacktype')[0];
						$winner_id = get_post_meta($event_ID,'winner_id');
						
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
					<!-- check if defender died -->
					<?php if($status_defender == 'death'):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id[0]);?>
					You were attacked by <?php clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo get_post_meta($event_ID,'attacktype')[0];?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id[0]);?>
					You were attacked by <?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> and you 
					
					<?php if($winner_id[0] == $defender_id[0]){?>
					<strong>won</strong> the battle.
					
					<?php }else{ ?>
					
					<strong>lost</strong> the battle. In this attack <strong><?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
					
					<?php }?>
					
					<br/><hr/>
					
					
					
					
					<?php endif;?>
					
					
					<?php if(get_post_meta($event_ID,'attacktype')[0] != 'missile'){?>
					<strong>Attacker losses: <?php echo $att_tot_unitslost[0];?> units</strong><br/>
					<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}}
				?>
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
				?></i></td>
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
					<!-- check if defender died -->
					<?php if($status_defender == 'death'):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id[0]);?>
				<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> launched a missile at your base and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo get_post_meta($event_ID,'attacktype')[0];?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id[0]);?>
					<?php echo clan_tag($attacker_id[0]);?> <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> launched a missile at your base and 
					
					<?php if($winner_id[0] == $defender_id[0]){?>
						<?php if(get_post_meta($event_ID, 'shotdown', true) == 'shotdown'){?>
						you shot down the missile.
					<?php } else {?>
					<strong>missed</strong> your base.
					<?php }?>
					<?php }else{ ?>
					
					<strong>hit</strong> your base.
					<br/><hr/>
					<?php }?>
					
					
					
					
					
					
					<?php endif;?>
					
					
					<?php if($winner_id[0] == $attacker_id[0]):?>
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
				?></i>
				<?php endif;?>
				</td>
				</tbody>
				</table>
				</div>
				
				<?php endif;?>
				
				
				
				
				<?php if($attack_type == 'satellite'):?>
				
				
				
				
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
					<?php if(!empty($status_defender)):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($event->post_author);?>
					You were attacked by <?php clan_tag($event->post_author);?> <a href="/users/profile/?id=<?php echo $event->post_author;?>"><?php echo $member_data->display_name.' (#'.$event->post_author.')';?></a> and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo get_post_meta($event_ID,'attacktype')[0];?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($attacker_id[0]);?>
					<a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a> fired a satellite and 
					
					<?php if($winner_id[0] == $defender_id[0]){?>
					<strong>missed</strong> your base.
					
					<?php }else{ ?>
					
					<strong>hit</strong> your base.
					
					<?php }?>
					
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
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo get_post_meta($event_ID,'attacktype')[0];?>"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?></center>
					</td>
					
					<td style="max-width: 280px;">
						<?php $member_data = get_userdata($attacker_id[0]);?>
					<?php if($winner_id[0] != $defender_id[0]):?>
						Someone sent a thief and stole <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong>
					<?php elseif($winner_id[0] == $defender_id[0]):?>
						You killed <?php echo $thiefs_lost. ' thief';if($thiefs_lost > 1){echo 's ';}?>sent by <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a>
					<?php endif;?>
					
			
				
				
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				<?php elseif(array_key_exists($attack_type,$researches)):?>
				
				
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Research complete</th>
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
						<?php echo $researches[$attack_type]['name'];?> completed. You can now start a new research.
					
					
			
				
				
					
				</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				<?php elseif($attack_type == 'nukeprotection'):?>
				
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Nukeprotection removed</th>
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
						Your Nukeprotection has been removed. 
					</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				<?php endif;?>
				
				<?php if($attack_type == 'bonus'):?>
				<?php 
					$money = get_post_meta($event_ID, 'bonus_money', true);
					$turns = get_post_meta($event_ID, 'bonus_turns', true);
					$used = get_post_meta($event_ID, 'bonus_used', true);
				?>
				
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Clan bonus</th>
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
						You can now receive a clan bonus of $ <?php echo number_format($money, 0, ',', ' '); ?> and <?php echo $turns;?> turns. 
						<?php if($used == 'yes'):?>
						Bonus used
						<?php else:?>
						<a href="/receive_bonus.php/?id=<?php echo $event_ID;?>">Receive Bonus</a>
						<?php endif;?>
					</td>
				</tr>
				</tbody>
				</table>
				</div>
				
				<?php endif;?>
				
				<?php if($attack_type == 'aid'):?>
				<?php 
					$money = get_post_meta($event_ID, 'money_lost', true);
					$member_data = get_userdata($attacker_id[0]);
				?>
				
				
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Aid received</th>
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
						You received $ <?php echo number_format($money, 0, ',', ' ');?> aid from <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a>
					</td>
				</tr>
				</tbody>
				</table>
			
				
				<?php endif;?>
				
				<?php if($attack_type == 'user_kicked'):
					$member_data = get_userdata($attacker_id[0]);
					$kicked_clan = get_post_meta($event_ID,'attacker_clan_id',true);
				?>
			
				
				
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="row" colspan='3'>Kicked from <?php echo get_the_title($kicked_clan);?> (#<?php echo $kicked_clan;?>)</th>
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
						You were kicked by <a href="/users/profile/?id=<?php echo $attacker_id[0];?>"><?php echo $member_data->display_name.' (#'.$attacker_id[0].')';?></a>
					</td>
				</tr>
				</tbody>
				</table>
			
				
				<?php endif;?>
				
				
				

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
</div>
<?php get_footer(); ?>