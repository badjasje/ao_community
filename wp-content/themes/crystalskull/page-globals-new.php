<?php
 /*
 * Template Name: Globals New
 */
$user_ID = get_current_user_ID();
include('units_array.php');
include('building_array.php');

update_user_meta($user_ID, 'new_global_events', 0);
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

if($user_ID != 0){
	$members = get_post_meta($clan_ID,'clan_members');
}

get_header(); ?>


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
           'value' => array(
           		'aid',
           		'empmissile',
           		'empsat',
           		'satellite',
           		'regular',
           		'air_sea',
           		'ground',
           		'missile',
           		'war_declared',
           		'peace_declared',
           		'user_change'
           		),
           'compare' => 'IN'
         ),
     array(
           'relation' => 'OR',
         array(
           'key' => 'attacker_clan_id',
           'value' => $clan_ID,
           'compare' => 'IN'
         ),
         array(
           'key' => 'defender_clan_id',
           'value' => $clan_ID,
           'compare' => 'IN'
         ),
         )
      ),
);


?>
					
					
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">

<center>
	<a class="btn btn-general" href="/events/incoming/"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Incoming</a> 
	<a class="btn btn-general" href="/events/outgoing/"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> Outgoing</a> 
	<a class="btn btn-general current_but" href="/events/global/"><i class="fa fa-globe" aria-hidden="true"></i> Global</a>
</center>                         
<br/>
<?php
	
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
	            
	            
	            
	            
	            
	            

<?php if($attack_type == 'missile'): ?>

<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
	
	$missile_type = get_post_meta($global_event_ID, 'missile_type', true);
	
	if(empty($missile_type)){
		$missile_name = 'Missile';
	}
	if($missile_type == 'nuke'){ $missile_name = 'Nuclear Missile'; }
	if($missile_type == 'chemical'){ $missile_name = 'Chemical Missile'; }
	if($missile_type == 'bio'){ $missile_name = 'Biochemical Missile'; }
	if($missile_type == 'moab'){ $missile_name = 'MOAB'; }
?>

<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Missile report
		<span class="hover-tip" data-toggle="tooltip" data-html="true" 
		data-original-title="<center><strong>Defender networth lost:</strong><br/>
		$ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?><br/>
		<strong>Attacker networth lost:</strong></center>" data-placement="right">
		<i class="fa fa-info-circle" aria-hidden="true"></i>
		</span>
	</div>
</div>



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
		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">

		<?php if(in_array($attacker_id, $members[0])) { // Clanmember is attacker ?>
			<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the <?php echo $missile_name;?> of
			
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php }else {?>
						
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched a <?php echo $missile_name;?> at
						
						
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
			
			<?php if($winner_id == $attacker_id){?>
			
				hit the enemy base.
				
				<?php if($clan_points != 0  && !empty($clan_points)):?>
					<?php echo $clan_points;?> clan points gained.
				<?php endif;?>
			
			<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
			
			<?php }}}?>
						
						
		
		
		
		<?php if(in_array($defender_id, $members[0])) { // Clanmember is defender ?>
			<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the <?php echo $missile_name;?> of
			
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php }else {?>
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched a <?php echo $missile_name;?> at 
				
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a>
						
						
			
						
			
			<?php if($winner_id == $attacker_id){?>
				and <strong>hit</strong> the base.

			<?php } else { ?>
		
				and <strong>missed the base.</strong>
						
			<?php }}}?>
						
					
					

				
				
				</div>
			</div>
			
			
			<div class="row">
				<?php if($winner_id == $attacker_id):?>
				<div class="col-md-12 event-result">
									
				<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost[0] as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
				?><br/>

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
				</div>
				<?php endif;?>
			</div>
			
		</div>
	</div>

<?php endif; // End missile attacks ?>            
	            
	            





<?php if($attack_type == 'satellite'): ?>

<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
?>

<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Satellite report
		<span class="hover-tip" data-toggle="tooltip" data-html="true" 
		data-original-title="<center><strong>Defender networth lost:</strong><br/>
		$ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?><br/>
		<strong>Attacker networth lost:</strong></center>" data-placement="right">
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
				
		<?php if(in_array($attacker_id, $members[0])) { // Clan member is attacker?>
						
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> fired a satellite at
						
						
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
			
			<?php if($winner_id == $attacker_id){?>
				hit the enemy base.
			
				<?php if($clan_points != 0  && !empty($clan_points)):?>
					<?php echo $clan_points;?> clan points gained.
				<?php endif;?>
			
			
			<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
				
			<?php }}?>
						
						
		<?php if(in_array($defender_id, $members[0])) { //Clanmember is defender?>
						
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> was attacked by
						
						
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and 
						
			<?php if($winner_id == $attacker_id){?>
					lost the battle.
				<?php } else { ?>
				<strong>won the battle</strong>
			<?php }}?>

				
				
				
				</div>
			</div>
			
			
			<div class="row">
				<?php if($winner_id == $attacker_id):?>
				<div class="col-md-12 event-result">

				<strong>Defender losses: <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost[0] as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				</div>
				<?php endif;?>
			</div>
			
		</div>
	</div>

<?php endif; // End satellite attacks ?>	






<?php if($attack_type == 'empsat'): ?>

<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
?>

<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/satellite.png"> 
		EMP Satellite report
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
				
		<?php if(in_array($attacker_id, $members[0])) { // Clan member is attacker?>
						
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> fired an EMP satellite at
						
						
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
			
			<?php if($winner_id == $attacker_id){?>
				hit the enemy base.
			
			
			<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
				
			<?php }}?>
						
						
		<?php if(in_array($defender_id, $members[0])) { //Clanmember is defender?>
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> used an EMP satellite on
					
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a>
						
						
			 and 
						
			<?php if($winner_id == $attacker_id){?>
					missed the base.
				<?php } else { ?>
				<strong>hit the base</strong>
			<?php }}?>

				
				
				
				</div>
			</div>
			
			<?php if($winner_id == $attacker_id){?>
			<div class="row">
				<center>Power decreased by 20% for 6 hours.</center>
			</div>
			<?php }?>
			
		</div>
	</div>

<?php endif; // End EMP Sat hit ?>	




<?php if($attack_type == 'empmissile'): ?>

<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
?>

<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/missile.png"> 
		EMP Missile report
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
				
		<?php if(in_array($attacker_id, $members[0])) { // Clanmember is attacker ?>
			
			<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
			
			
				<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the EMP missile of
			
			
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php } else {?>
						
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile at
						
						
				<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
			
				<?php if($winner_id == $attacker_id){?>
			
				hit the enemy base.
				
	
			
				<?php } else { ?>
				
				<strong>missed the enemy base.</strong>
			
			<?php }}} ?>
						
						
		
		
		
		<?php if(in_array($defender_id, $members[0])) { // Clanmember is defender ?>
			<?php if(get_post_meta($global_event_ID, 'shotdown', true) == 'shotdown'){?>
			
			
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> shot down the EMP missile of
			
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
			
			
			<?php }else {?>
			
			<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
			<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile at 
				
			<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
			<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a>
						
						
			
						
			
			<?php if($winner_id == $attacker_id){?>
				and <strong>hit</strong> the base.

			<?php } else { ?>
		
				and <strong>missed the base.</strong>
						
			<?php }}}?>
						
					
					


				
				
				
				</div>
			</div>
			
			<?php if($winner_id == $attacker_id){?>
				<div class="row">
					<center>Power decreased by 15% for 6 hours.</center>
				</div>
			<?php } ?>
		</div>
	</div>

<?php endif; // End EMP missile hit ?>	







<?php if($attack_type == 'war_declared'): ?>

<?php

	$declaring_clan = get_post_meta($global_event_ID,'attacker_clan_id',true);
	$declared_clan = get_post_meta($global_event_ID,'defender_clan_id',true);
	
?>





<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		New war
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
				
				<?php if($clan_ID == $declaring_clan):?>
				
				Declared war on <a href="<?php echo get_the_permalink($declared_clan);?>">
				<?php echo get_the_title($declared_clan);?> (#<?php echo $declared_clan;?>)</a>
				
				
				<?php elseif($clan_ID == $declared_clan):?>
				
				
				<a href="<?php echo get_the_permalink($declaring_clan);?>">
				<?php echo get_the_title($declaring_clan);?> (#<?php echo $declaring_clan;?>)</a> 
				declared war against your clan.
				
				<?php endif;?>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End Declaration Event ?>





<?php if($attack_type == 'aid'): ?>


<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Aid sent
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
				
				<?php echo get_user_name($attacker_id);?> aided <?php echo get_user_name($defender_id);?> <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End Aid Event ?>
	            
	            




<?php if($attack_type == 'peace_declared'): ?>

<?php

	$declaring_clan = get_post_meta($global_event_ID,'attacker_clan_id',true);
	$declared_clan = get_post_meta($global_event_ID,'defender_clan_id',true);
	
?>





<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Peace
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
				
				<?php if($clan_ID == $declaring_clan):?>
				
				Declared peace with <a href="<?php echo get_the_permalink($declared_clan);?>">
				<?php echo get_the_title($declared_clan);?> (#<?php echo $declared_clan;?>)</a>
				
				<?php elseif($clan_ID == $declared_clan):?>
				<a href="<?php echo get_the_permalink($declaring_clan);?>"><?php echo get_the_title($declaring_clan);?> (#<?php echo $declaring_clan;?>)</a> 
				declared peace with your clan.
				
				<?php endif;?>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End Declaration Event ?>
	            

<?php if($attack_type == 'user_change'): ?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		<?php if($outcome == 'kicked'):?>
			User kicked
		<?php elseif($outcome == 'joined'):?>
			User joined
		<?php elseif($outcome == 'left'):?>
			User left
		<?php endif;?>
	</div>
</div>
<!-- Event header -->


<div class="row event-row">
	
<!-- Attacker image -->	
	<div class="col-md-2">
		<div class="row">
			<div class="col-md-12">
				<?php echo small_avatar($defender_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				
				<?php if($outcome == 'kicked'):?>
					<?php echo LinkUtil::user_link($attacker_id); ?> kicked <?php echo LinkUtil::user_link($defender_id); ?> from your clan.<br/>
					<?php echo $clan_points;?> clan points lost.
				<?php elseif($outcome == 'joined'):?>
					<?php echo LinkUtil::user_link($defender_id); ?> joined your clan.
				<?php elseif($outcome == 'left'):?>
					<?php echo LinkUtil::user_link($defender_id); ?> left your clan.
					<?php echo $clan_points;?> clan points lost.
				<?php endif;?>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End Declaration Event ?>

               
	            
	            
<?php endwhile;
endif; ?>

<center>
	<?php previous_posts_link('Previous') ?>
	<?php next_posts_link('Next') ?>
</center>

<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 

$wp_query = NULL;
$wp_query = $temp_query; 
?>	                     
	            
       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>