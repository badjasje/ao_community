<?php
 /*
 * Template Name: Outgoing New
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
					'value'	  	=> array('satellite','air_sea','thief','ground','missile','regular','death','sniper'),
					'compare' 	=> 'IN',
					),)
);

include('units_array.php');
include('building_array.php');
include('research_array.php');


$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

if($user_ID != 0){
	$members = get_post_meta($clan_ID,'clan_members');
}
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
            
<div class="row button_block">
	
	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/events/incoming/">
		 	<i class="fa fa-arrow-circle-down" aria-hidden="true"></i> &nbsp;Incoming</a></center>
	</div>
 	
 	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general current_but profilebutton" href="/events/outgoing/">
		 	<i class="fa fa-arrow-circle-down" aria-hidden="true"></i> &nbsp;Outgoing</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/events/global/">
		 	<i class="fa fa-globe" aria-hidden="true"></i> &nbsp;Global</a></center>
	</div>

</div> 


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
	
	$defender_id = get_post_meta($event_ID,'defender_id',true);
	$attacker_id = get_post_meta($event_ID,'attacker_id',true);
	$status_defender = get_post_meta($event_ID,'status_defender',true);

	
	$def_unitslost = get_post_meta($event_ID,'defender_lost');
	$att_unitslost = get_post_meta($event_ID,'attacker_lost');
	
	$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost',true);
	$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost',true);
	
	$tomahawkHit = get_post_meta($event_ID,'tomahawk_hit',true);
	$tomahawkDown = get_post_meta($event_ID,'tomahawk_down',true);
	
	if(empty($def_tot_unitslost)){
	$def_tot_unitslost = 0;
	}
	if(empty($att_tot_unitslost)){
	$att_tot_unitslost = 0;
	}
	
	$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost',true);
	
	$timeattacked = get_post_meta($event_ID,'time_attacked',true);
	$timestamp = current_time('timestamp');
	
	$landlost = get_post_meta($event_ID,'land_lost',true);
	$moneylost = get_post_meta($event_ID,'money_lost',true);
	
	$attack_type = get_post_meta($event_ID,'attacktype', true);
	$clan_points = get_post_meta($event_ID,'clan_points', true);
	
	$winner_id = get_post_meta($event_ID,'winner_id',true);
	$member_data = get_userdata($defender_id);
	
	
	$defender_NW_lost = get_post_meta($event_ID, 'nw_damage_defender', true);
	$attacker_NW_lost = get_post_meta($event_ID, 'nw_damage_attacker', true);
	
	/* Determine attack name for header */
	if($attack_type == 'ground'){ $attack_name = 'Ground'; }
	if($attack_type == 'air_sea'){ $attack_name = 'Air & Sea'; }
	if($attack_type == 'regular'){ $attack_name = 'Regular'; }
	
	$avatar = get_user_meta($defender_id, 'avatar_user', true);
		if(empty($avatar)){
			$avatar = '/wp-content/uploads/2016/11/default_large.png';
		}
?>          




<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular' and $status_defender != 'death'): ?>



<!-- Event header -->
<div class="row battlereport-header">
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
				<?php echo small_avatar($defender_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($status_defender == 'death'):?>	
					
				You attacked <?php clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> and <strong>killed this player.</strong> 
				
				<?php else:?>
				
				You attacked <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> and you 
					
				<?php if($winner_id == $attacker_id):?>
					<strong>won</strong> the battle.
					<br/>In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> and 
					<strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.
					
					<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.
					<?php endif;?>
					
				<?php else: ?>
					
					<strong>lost</strong> the battle. 
					
					
				<?php endif; endif;?>
				
				
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
				<br/><br/><?php echo ($tomahawkHit);?> tomahawk<?php echo plural_func($tomahawkHit);?> hit the enemy base<br/>
				<?php endif;?>
				<?php if($tomahawkDown > 0):?>
				<?php echo $tomahawkDown;?> tomahawk<?php echo plural_func($tomahawkDown);?> shot down
				<?php endif;?>
				</div>
			</div>
		</div>
	</div>

<?php endif; // End regular, ground & air&sea attacks ?>	 










<?php if($attack_type == 'sniper'): ?>



<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Sniper report
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
				<?php echo small_avatar($defender_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($status_defender == 'death'):?>	
					
				You attacked <?php clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> and <strong>killed this player.</strong> 
				
				<?php else:?>
				
				You attacked <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> and you 
					
				<?php if($winner_id == $attacker_id):?>
					<strong>won</strong> the battle.
					
				
					
				<?php else: ?>
					
					<strong>lost</strong> the battle. 
					
					
				<?php endif; endif;?>
				
				
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
				?>
				<?php if($att_tot_unitslost > 0):?>
				<br/>
				<?php endif;?>
				<br/>

				<strong>Defender losses: <?php echo $def_tot_unitslost;?> units</strong><br/>
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
			</div>
		</div>
	</div>

<?php endif; // End sniper attacks ?>

     
            
            




<?php if($status_defender == 'death'):?>


<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $status_defender;?>.png"> 
		Kill report
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
				<?php echo small_avatar($defender_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				You attacked <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
							<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> 
							and you <strong>killed</strong> this player.  
							
							<?php if($clan_points != 0  && !empty($clan_points)):?>
							<?php echo $clan_points;?> clan points gained.	
							<?php endif;?>			
				
				</div>
			</div>
		</div>
	</div>





<?php endif; // End kill report ?>    
            
            
            
            



<?php if($attack_type == 'missile'): ?>

<?php
	$missile_type = get_post_meta($event_ID, 'missile_type', true);
	
	if(empty($missile_type)){
		$missile_name = 'Missile';
	}
	if($missile_type == 'nuke'){ $missile_name = 'Nuclear Missile'; }
	if($missile_type == 'chemical'){ $missile_name = 'Chemical Missile'; }
	if($missile_type == 'bio'){ $missile_name = 'Biochemical Missile'; }
	if($missile_type == 'moab'){ $missile_name = 'MOAB'; }
	
?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Missile report
		<span class="hover-tip" data-toggle="tooltip" data-html="true" 
		data-original-title="<center><strong>Defender networth lost:</strong><br/>
		$ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?></center>" data-placement="right">
		<i class="fa fa-info-circle" aria-hidden="true"></i>
		</span>
	</div>
</div>



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
		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				
				You launched a <?php echo $missile_name;?> at <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> 
					
				<?php if($winner_id == $defender_id):?>
				<?php if(get_post_meta($event_ID, 'shotdown', true) == 'shotdown'){?>
						but your missile was <strong>shot down.</strong>
					<?php } else {?>
						but you <strong>missed</strong> the enemy base.
					<?php }?>

					
				<?php else: ?>
					
					and you <strong>hit</strong> the enemy base. 
					
				<?php endif;?>
				
				
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

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Satellite report
		<span class="hover-tip" data-toggle="tooltip" data-html="true" 
		data-original-title="<center><strong>Defender networth lost:</strong><br/>
		$ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?></center>" data-placement="right">
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
				<?php echo small_avatar($defender_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				
				
				You fired a satellite at <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
				<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> and you
					
				<?php if($winner_id == $defender_id):?>
					
					<strong>missed</strong> the enemy base.

				<?php else: ?>
					
					<strong>hit</strong> the enemy base. 
					
				<?php endif;?>
				
				
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

       
<?php endif; // end sat event ?>        






<?php if($attack_type == 'thief'): ?>

<?php
$thiefs_lost = get_post_meta($event_ID, 'thiefs_lost', true);

?>



<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Thief report
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
				<?php if($winner_id == $attacker_id):?>
					
					You sent a thief to <?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
					<?php echo $member_data->display_name.' (#'.$defender_id.')';?></a> 
					and you stole <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong>
				<?php endif;?>
						
				<?php if($winner_id == $defender_id):?>
					You sent thiefs to <?php echo clan_tag($member_data->ID);?> <a href="/users/profile/?id=<?php echo $member_data->ID;?>">
					<?php echo $member_data->display_name.' (#'.$member_data->ID.')';?></a> 
					but your thiefs were caught. You lost <?php echo $thiefs_lost;?> thiefs.
				<?php endif;?>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End thief attacks ?>
        
        
        
        
        
            
            
            

	<?php endwhile; ?>
<center>
	<?php previous_posts_link('Previous') ?>
	<?php next_posts_link('Next') ?>
</center>
		<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	
		
	
	<?php // fixes bug where below ACF fields wont display 

		$wp_query = NULL;
		$wp_query = $temp_query; 
	?>
  
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>