<?php
 /*
 * Template Name: War statistics
 */
 
$user_ID = get_current_user_ID();
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$members = get_post_meta($clan_ID,'clan_members');

$war_array = get_post_meta($clan_ID, 'war_array', true);
$war_array = $war_array[$_GET['id']];
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">




<!-- owned/ordered unites block -->
<div class="row">
<div class="col-md-6">

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Outgoing</div>
	</div>
	
	

	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Attacks made
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['attacks_made'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Successful attacks
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['successfull_att'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Missiles sent
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['missiles_sent'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Missiles hit
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['missiles_hit_att'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Networth damage done
		</div>
		<div class="col-xs-6">
			$ <?php echo number_format($war_array['nw_dmg_done'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Highest networth damage
		</div>
		<div class="col-xs-6">
			$ <?php echo number_format($war_array['highest_nw_dmg'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Buildings killed
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['bds_killed'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Units killed
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['units_killed'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Land gained
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['land_gained'], 0, ',', ' ');?> m<sup>2</sup>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Money gained
		</div>
		<div class="col-xs-6">
			$ <?php echo number_format($war_array['money_gained'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Clan points
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['clan_points'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Kills
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['kills'], 0, ',', ' ');?>
		</div>
	</div>

	
</div>
	</div>

<div class="col-md-6">
<!-- on order block -->

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Outgoing</div>
	</div>
	
	

	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Attacks received
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['attacks_received'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Successful defends
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['successfull_def'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Missiles received
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['missiles_received'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Missiles hit
		</div>
		<div class="col-xs-6">
			<?php echo $war_array['missiles_hit_def'];?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Networth damage received
		</div>
		<div class="col-xs-6">
			$ <?php echo number_format($war_array['nw_dmg_rec'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Buildings lost
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['bds_lost'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Units lost
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['units_lost'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Land lost
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['land_lost'], 0, ',', ' ');?> m<sup>2</sup>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Money lost
		</div>
		<div class="col-xs-6">
			$ <?php echo number_format($war_array['money_lost'], 0, ',', ' ');?>
		</div>
	</div>
	
	<div class="row clan_profile_row">
		<div class="col-xs-6">
			Deaths
		</div>
		<div class="col-xs-6">
			<?php echo number_format($war_array['deaths'], 0, ',', ' ');?>
		</div>
	</div>

	
</div>
	</div>


</div>
</div>




        </div>
	<div class="col-lg-12 col-md-12">	<div class="col-lg-12 col-md-12">
<div class="row profile_block">
	<div class="col-lg-12 col-md-12">
<h1>Most devastating attack</h1>


<?php // Get attack data 
	
$global_event_ID = $war_array['highest_dmg_id'];
$defender_id = get_post_meta($global_event_ID,'defender_id',true);
$attacker_id = get_post_meta($global_event_ID,'attacker_id',true);

$winner_id = get_post_meta($global_event_ID,'winner_id',true);

$def_unitslost = get_post_meta($global_event_ID,'defender_lost');
$att_unitslost = get_post_meta($global_event_ID,'attacker_lost');

$defender_points = get_post_meta($global_event_ID,'defender_points',true);

$def_tot_unitslost = get_post_meta($global_event_ID,'def_total_units_lost',true);
$att_tot_unitslost = get_post_meta($global_event_ID,'att_total_units_lost',true);

if(empty($def_tot_unitslost)){
	$def_tot_unitslost = 0;
}
if(empty($att_tot_unitslost)){
	$att_tot_unitslost = 0;
}


$def_tot_buildingslost = get_post_meta($global_event_ID,'total_buildings_lost',true);
$timeattacked = get_post_meta($global_event_ID,'time_attacked',true);

$landlost = get_post_meta($global_event_ID,'land_lost',true);
$moneylost = get_post_meta($global_event_ID,'money_lost',true);
$outcome = get_post_meta($global_event_ID,'outcome',true);
$defender_NW_lost = get_post_meta($global_event_ID, 'nw_damage_defender', true);
$attacker_NW_lost = get_post_meta($global_event_ID, 'nw_damage_attacker', true);

$tomahawkHit = get_post_meta($global_event_ID,'tomahawk_hit',true);
$tomahawkDown = get_post_meta($global_event_ID,'tomahawk_down',true);

$timestamp = current_time('timestamp');

$clan_points = get_post_meta($global_event_ID,'clan_points', true);

$attack_type = get_post_meta($global_event_ID,'attacktype',true);

/* Determine attack name for header */
	if($attack_type == 'ground'){ $attack_name = 'Ground'; }
	if($attack_type == 'air_sea'){ $attack_name = 'Air & Sea'; }
	if($attack_type == 'regular'){ $attack_name = 'Regular'; }
	
	$avatar = get_user_meta($attacker_id, 'avatar_user', true);
		if(empty($avatar)){
			$avatar = '/wp-content/uploads/2016/11/default_large.png';
		}
	
	
?>

<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular'): ?>
<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
?>


<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Battle report - <?php echo $attack_name;?> attack 
		<span class="hover-tip" data-toggle="tooltip" data-html="true" 
		data-original-title="<center><strong>Defender networth lost:</strong><br/>
		$ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?><br/>
		<strong>Attacker networth lost:</strong><br/>
		$ <?php echo number_format($attacker_NW_lost, 0, ',', ' '); ?></center>" data-placement="right">
		<i class="fa fa-info-circle" aria-hidden="true"></i>
		</span>
	</div>
</div>
<!-- Event header -->


<div class="row event-row">
	
<!-- Attacker image -->	
	<div class="col-md-2">
		<div class="row">
			<div class="col-md-12">
				<?php echo small_avatar($attacker_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
	
	
	
	<?php if(in_array($attacker_id, $members[0])): // attack by clanmember ?>
						
		
		<!-- attacker -->
		<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
		<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> attacked
		
		<!-- defender -->	
		<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
		<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
		
		<?php if($winner_id == $attacker_id){?>
		won the battle.<br/>
		
		In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> 
		and <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen. 
		
		
		<?php if($clan_points != 0  && !empty($clan_points)):?>
			<?php echo $clan_points;?> clan points gained.
		<?php endif;?>
		
		<?php } else { ?>
		
		<strong>lost the battle</strong>
		<?php }?>
	
	<?php endif;?>
						
						
	
	<?php if(in_array($defender_id, $members[0])): // defense by clan member ?>
						
						
		<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
		<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> was attacked by
						
						
		<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
		<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and 
						
		<?php if($winner_id == $attacker_id){?>
		lost the battle. <br/>
		
		In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> 
		and <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.
		
		<?php } else { ?>
		<strong>won the battle.</strong>
			<?php if($defender_points != 0):?>
				<br/><?php echo $defender_points;?> clan point<?php if($defender_points>1){echo 's';}?> gained for successful base defense.
			<?php endif;?>
		<?php }?>
	<?php endif;?>
				
				
				</div>
			</div>
			
			
			<div class="row">
			
				<div class="col-md-12 event-result"><strong>Attacker losses: <?php echo $att_tot_unitslost;?> units</strong><br/>
				
				<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
				?><br/><br/>

				<strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
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
				
				<?php if(($tomahawkHit)>0):?>
				<br/><br/><?php echo ($tomahawkHit);?> tomahawk<?php echo plural_func($tomahawkHit);?> hit<br/>
				<?php endif;?>
				<?php if($tomahawkDown > 0):?>
				<?php echo $tomahawkDown;?> tomahawk<?php echo plural_func($tomahawkDown);?> shot down
				<?php endif;?>
				
				</div>
			
			</div>
			
		</div>
	</div>

<?php endif; // End regular, ground & air&sea attacks ?>

</div>
</div>


            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>