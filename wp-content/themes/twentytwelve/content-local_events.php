<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
 $args = array(
	'posts_per_page'   => -1,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'event_local',
	'post_status'      => 'publish'
);
$events = get_posts( $args );
$user_ID = get_current_user_ID();
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
		<center><h1>Events</h1>
		<div class="container">

			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Incoming events</li>
			<li class="tab-link" data-tab="tab-2">Outgoing events</li>
			<li class="tab-link" data-tab="tab-3">Global events</li>
			</ul>
		</center>
			
			
			<div id="tab-1" class="tab-content current"> <!-- Incoming events -->
			
				<?php foreach ($events as $event){
						$event_ID = $event->ID;
						$defender_id = get_post_meta($event_ID,'defender_id');
						if($defender_id[0] == $user_ID){
						
						
						$def_unitslost = get_post_meta($event_ID,'defender_lost');
						$att_unitslost = get_post_meta($event_ID,'attacker_lost');
						
						$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost');
						$landlost = get_post_meta($event_ID,'land_lost');
						$moneylost = get_post_meta($event_ID,'money_lost');
						
						$status_defender = get_post_meta($event_ID,'status_defender');
					
						
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
					<?php if(!empty($status_defender[0])):?>
					
					<td style="vertical-align: middle;min-width:100px;">
						<center><div class="death"></div></center>
					</td>
					
					<td style="vertical-align: middle;">
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($event->post_author);?>
					You were attacked by <a href="/users/profile/?id=<?php echo $event->post_author;?>"><?php echo $member_data->display_name.' (#'.$event->post_author.')';?></a> and <strong>you died</strong><br/><hr/>
					
					<?php else:?>
					
					<td style="min-width:100px;vertical-align: middle;">
						<center><div class="<?php echo get_post_meta($event_ID,'attacktype')[0];?>"></div></center>
					</td>
					
					<td>
						<center><?php echo human_time_diff( $timeattacked[0], $timestamp );?> ago</center>
					</td>
					
					<td style="text-align:center">
						<?php $member_data = get_userdata($event->post_author);?>
					You were attacked by <a href="/users/profile/?id=<?php echo $event->post_author;?>"><?php echo $member_data->display_name.' (#'.$event->post_author.')';?></a> and you 
					
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
				
				<?php elseif($attack_type == 'thief'):?>
				
				
				
				
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
						Someone sent a thief and stole <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong>
					
					
			
				
				
					
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
				
				
				
				<?php }}?>
				
			</div><!-- attacks received close tab -->
			
			
			
			
			
			
			<div id="tab-2" class="tab-content"> <!-- Outgoing  events -->
			
				<?php foreach ($events as $event){if($event->post_author == $user_ID){
						$event_ID = $event->ID;
						
						$defender_id = get_post_meta($event_ID,'defender_id');
						$attacker_id = get_post_meta($event_ID,'attacker_id');
						
						$def_unitslost = get_post_meta($event_ID,'defender_lost');
						$att_unitslost = get_post_meta($event_ID,'attacker_lost');
						$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost');
						$timeattacked = get_post_meta($event_ID,'time_attacked');
						$timestamp = strtotime(date('Y-m-d H:i:s'));
						$landlost = get_post_meta($event_ID,'land_lost');
						$moneylost = get_post_meta($event_ID,'money_lost');
						$attack_type = get_post_meta($event_ID,'attacktype')[0];
						$winner_id = get_post_meta($event_ID,'winner_id');
					?>
					<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular'): ?>
					
					
				<table>
					<tr>
						<th colspan="3" class="report_header"><center>Battle Report</center></th>
					</tr>
					
					<tr>
					<td style="vertical-align: middle;"><?php echo get_post_meta($event_ID,'attacktype')[0];?></td>
					<td style="vertical-align: middle;"><?php echo human_time_diff( $timeattacked[0], $timestamp );?></td>
					<td style="max-width: 280px;">
						
						<?php $member_data = get_userdata($defender_id[0]);?>
						You attacked 
						<a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you 
					
					<?php if($winner_id[0] == $attacker_id[0]){?>
						<strong>won the battle.</strong> In this attack <strong>
						<?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ 
						<?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
					
					<?php } else { 
						echo '<strong>lost the battle</strong>';} ?><br/><br/>
					
					<?php if(get_post_meta($event_ID,'attacktype')[0] != 'missile'){?>	
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
					}
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
				</table>
				<?php elseif($attack_type == 'thief'):?>
				
				
				
				
				<table>
					<tr>
						<th colspan="3" class="report_header"><center>Thief report</center></th>
					</tr>
					
					<tr>
					<td style="vertical-align: middle;"><?php echo get_post_meta($event_ID,'attacktype')[0];?></td>
					<td style="vertical-align: middle;"><?php echo human_time_diff( $timeattacked[0], $timestamp );?></td>
					<td style="max-width: 280px;"><?php $member_data = get_userdata($defender_id[0]);?>
						You sent a thief to <a href="/users/profile/?id=<?php echo $member_data->ID;?>">
							<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
							and you stole <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong>
					
					
			
				
				
					
				</td>
				</tr>
				</table>
				
				
				
				
				<?php endif;?>
				<?php }}?>
				
				
			</div> <!-- outgoing close tab -->

			
			
			
			
			
			
			
			
			<div id="tab-3" class="tab-content"> <!-- GLOBAL EVENTS -->

			<?php 
			
				
				
				$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'event_local',
			'meta_query'	=> array(
				'relation'		=> 'OR',
					array(
						'key'	 	=> 'attacker_id',
						'value'	  	=> $members[0],
						'compare' 	=> 'IN',
						),
					array(
						'key'	  	=> 'defender_id',
						'value'	  	=> $members[0],
						'compare' 	=> 'IN',
						),
						
					
					)
				);
				
			$globals = get_posts($args);
			
			foreach ($globals as $global){
						$global_event_ID = $global->ID;
						$defender_id = get_post_meta($global_event_ID,'defender_id');
						$attacker_id = get_post_meta($global_event_ID,'attacker_id');
						
						$winner_id = get_post_meta($global_event_ID,'winner_id');
						$def_unitslost = get_post_meta($global_event_ID,'defender_lost');
						$att_unitslost = get_post_meta($global_event_ID,'attacker_lost');
						$def_tot_unitslost = get_post_meta($global_event_ID,'def_total_units_lost');
						$att_tot_unitslost = get_post_meta($global_event_ID,'att_total_units_lost');
						$def_tot_buildingslost = get_post_meta($global_event_ID,'total_buildings_lost');
						$timeattacked = get_post_meta($global_event_ID,'time_attacked');
						$timestamp = strtotime(date('Y-m-d H:i:s'));
						
						$attack_type = get_post_meta($global_event_ID,'attacktype')[0];
					?>
					
					<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular'): ?>
				<table>
					<tr>
					<th colspan="3" class="report_header"><center>Battle Report</center></th>
					</tr>
					<tr>
					<td style="vertical-align: middle;">
						<?php echo get_post_meta($global_event_ID,'attacktype')[0];?>
					</td>
					<td style="vertical-align: middle;">
						<?php echo human_time_diff( $timeattacked[0], $timestamp );?>
					</td>
					<td style="max-width: 280px;">
						<?php 	$member_data = get_userdata($global->post_author);
								$defender_data = get_userdata($defender_id[0]);
						?>
						<?php if(in_array($attacker_id[0], $members[0])) {?>
						
						<a href="/users/profile/?id=<?php echo $global->post_author;?>"><?php echo $member_data->display_name.' (#'.$global->post_author.')';?></a> attacked
						
						
						<a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> and 
						
						<?php if($winner_id[0] == $global->post_author){?>
						won the battle. In this attack <strong><?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
						<?php } else { ?>
						<strong>lost the battle</strong>
						<?php }}?>
						
						
						<?php if(in_array($defender_id[0], $members[0])) {?>
						
						<a href="/users/profile/?id=<?php echo $defender_id[0];?>"><?php echo $defender_data->display_name.' (#'.$defender_id[0].')';?></a> was attacked by
						
						
						<a href="/users/profile/?id=<?php echo $global->post_author;?>"><?php echo $member_data->display_name.' (#'.$global->post_author.')';?></a> and 
						
						<?php if($winner_id[0] == $global->post_author){?>
						lost the battle. In this attack <strong><?php echo number_format($landlost[0], 0, ',', ' '); ?> m<sup>2</sup></strong> and <strong>$ <?php echo number_format($moneylost[0], 0, ',', ' '); ?></strong> was stolen.
						<?php } else { ?>
						<strong>won the battle</strong>
						<?php }}?>
						
						
						
						
						
					<br/><br/>
					
					
					
					
					<?php if(get_post_meta($global_event_ID,'attacktype')[0] != 'missile'){?>
					<strong>Attacker losses: <?php echo $att_tot_unitslost[0];?></strong><br/>
					<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}?><br/><br/><?php }
				?>
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
				?></i></td>
				</table>
				<?php endif;?>
				<?php }?>
				
			</div> <!--  globals close tab -->
			
			
			
			
			
			
			</div>
		
		
		
		
		
		
	
		</div><!-- .entry-content -->
		
	</article><!-- #post -->