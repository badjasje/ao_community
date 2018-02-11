<?php
 /*
 * Template Name: Incoming New
 */
$userId = get_current_user_ID();
$filter_array = array(	'empsat',
						'empmissile',
						'satellite',
						'regular',
						'air_sea',
						'ground',
						'missile',
						'thief',
						'nukeprotection',
						'aid',
						'user_kicked',
						'sat_crash',
						'sniper',
						'killed',
						'spy');


include('units_array.php');
include('building_array.php');
include('research_array.php');



update_user_meta($userId,'new_events',0);
$clan_ID = get_user_meta($userId, 'clan_id_user',true);

if($userId != 0){
	$members = get_post_meta($clan_ID,'clan_members');
} 

get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            


<div class="row button_block">
	<div class="col-md-4">
		
		<form class="eventfilter" action="" method="get">
			<div class="multiselect">
				<div class="selectBox" onclick="showCheckboxes()">
				
					<select>
						<option>Filter events</option>
	      			</select>
		  			<div class="overSelect"></div>
		  		</div>
			</div>
		<div id="checkboxes">
	    
			<?php 
				$count = 0;
				$array_for_filter = array();
				
				foreach ($filter_array as $item){ 
					$end_par = $count++;
					$checked = '';
					
				if(!empty($_GET['filter_'.$end_par])){
					$array_for_filter[] = $_GET['filter_'.$end_par];
					$checked = 'checked';
				};?>
	
			<label for="<?php echo $item;?>">
	        	<input <?php echo $checked;?> name="filter_<?php echo $end_par;?>" 
	        	value="<?php echo $item;?>" type="checkbox" id="<?php echo $item;?>" />
	        		<?php echo $item;?>
	        </label>
			
			<?php }?>
    	</div>
  	</div>
  
  	<div class="col-md-4">
  		<button style="margin-top:0px;" class="btn respybutton" type="submit" value="FILTER">Filter</button>
  	</div>
  
  	<div class="col-md-4">
  		<a class="btn btn-general profilebutton" href="/events/incoming/">RESET</a>
  	</div>
</form>

</div>


<div class="row button_block">
	
	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general current_but profilebutton" href="/events/incoming/">
		 	<i class="fa fa-arrow-circle-down" aria-hidden="true"></i> &nbsp;Incoming</a></center>
	</div>
 	
 	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/events/outgoing/">
		 	<i class="fa fa-arrow-circle-down" aria-hidden="true"></i> &nbsp;Outgoing</a></center>
	</div>
	
	<div class="col-md-4 buttoncol">
	 	<center><a class="btn btn-general profilebutton" href="/events/global/">
		 	<i class="fa fa-globe" aria-hidden="true"></i> &nbsp;Global</a></center>
	</div>

</div>





<script>
	var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}
</script>



<?php 
	
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = array(

	'posts_per_page'	=> 20,
	'orderby'          	=> 'date',
	'order'            	=> 'DESC',
	'paged'				=>  $custom_query_args['paged'],
	'post_type'        	=> 'event_local',
	'post_status'      	=> 'publish',
	'meta_query'	=> array(
					'relation' => 'AND',
					array(
						'key'	 	=> 'defender_id',
						'value'	  	=> $userId,
						'compare' 	=> '=',
						),
					array(
						'key' => 'attacktype',
						'value' => $array_for_filter,
						'compare' => 'IN'
						),
						
						
						)
);
					
			
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

	
							
	$eventId = get_the_id();
	$eventData = get_post_meta($eventId);
	$defender_id = $eventData['defender_id'][0];
	$attacker_id = $eventData['attacker_id'][0];
	
	$defender_points = $eventData['defender_points'][0];

	$member_data = get_userdata($attacker_id);
	
	$def_unitslost = maybe_unserialize($eventData['defender_lost'][0]);
	$att_unitslost = maybe_unserialize($eventData['attacker_lost'][0]);
	
	$def_tot_unitslost = $eventData['def_total_units_lost'][0];
	$att_tot_unitslost = $eventData['att_total_units_lost'][0];
	
	if(empty($def_tot_unitslost)){
	$def_tot_unitslost = 0;
	}
	if(empty($att_tot_unitslost)){
	$att_tot_unitslost = 0;
	}
	
	
	$def_tot_buildingslost = $eventData['total_buildings_lost'][0];
	$landlost = $eventData['land_lost'][0];
	$moneylost = $eventData['money_lost'][0];
	
	$status_defender = $eventData['status_defender'][0];
	
	$defender_NW_lost = $eventData['nw_damage_defender'][0];
	$attacker_NW_lost = $eventData['nw_damage_attacker'][0];
	
	$tomahawkHit = $eventData['tomahawk_hit'][0];
	$tomahawkDown = $eventData['tomahawk_down'][0];
	
	
	$timeattacked = $eventData['time_attacked'][0];
	$timestamp = current_time('timestamp');
	$attack_type = $eventData['attacktype'][0];
	$winner_id = $eventData['winner_id'][0];
	
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
				<?php echo small_avatar($attacker_id,'attack-profile-image');?>

				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($status_defender == 'death'):?>	
					
				You were attacked by <?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and <strong>you died</strong> 
				
				<?php else:?>
				
				You were attacked by <?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and you 
					
				<?php if($winner_id == $defender_id):?>
					<strong>won</strong> the battle.
					<?php if($defender_points != 0):?>
						<br/><?php echo $defender_points;?> clan point<?php if($defender_points>1){echo 's';}?> gained for successful base defense.
					<?php endif;?>
					
				<?php else: ?>
					
					<strong>lost</strong> the battle. 
					<br/>In this attack <strong><?php echo number_format($landlost, 0, ',', ' '); ?> m<sup>2</sup></strong> and 
					<strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen.
					
				<?php endif; endif;?>
				
				
				</div>
			</div>
			
			
			<div class="row">
		
				<div class="col-md-12 event-result"><strong>Attacker losses: <?php echo $att_tot_unitslost;?> units</strong><br/>
				
				<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
				?><br/><br/>

				<strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
				<?php
				foreach ($units as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				
				<?php if(($tomahawkHit)>0):?>
				<br/><br/><?php echo ($tomahawkHit);?> tomahawk<?php echo plural_func($tomahawkHit);?> hit your base<br/>
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
				<?php echo small_avatar($attacker_id,'attack-profile-image');?>

				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($status_defender == 'death'):?>	
					
				You were attacked by <?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and <strong>you died</strong> 
				
				<?php else:?>
				
				You were attacked by <?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> and you 
					
				<?php if($winner_id == $defender_id):?>
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
					foreach ($att_unitslost as $att_unitlost) {
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
					foreach ($def_unitslost as $def_unitlost) {
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

<?php endif; // End sniper attack ?>	







<?php if($attack_type == 'missile'): ?>

<?php
	$missile_type = $eventData['missile_type'][0];
	
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
				<?php echo small_avatar($attacker_id,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($status_defender == 'death'):?>	
					
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> hit your base with a <?php echo $missile_name;?> and <strong>you died</strong> 
				
				<?php else:?>
				
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched a <?php echo $missile_name;?> at your base and 
					
				<?php if($winner_id == $defender_id):?>
				<?php if($eventData['shotdown'][0] == 'shotdown'){?>
						you shot down the missile.
					<?php } else {?>
					<strong>missed</strong> your base.
					<?php }?>

					
				<?php else: ?>
					
					<strong>hit</strong> your base. 
					
				<?php endif; endif;?>
				
				
				</div>
			</div>
			
			
			<div class="row">
				<?php if($winner_id == $attacker_id):?>
				<div class="col-md-12 event-result">
									
				<?php
				foreach ($units as $key => $order) {
					foreach ($att_unitslost as $att_unitlost) {
					if (isset($att_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $att_unitlost[$key] . ', ';
        			}
						}
					}
				?><br/>

				<strong>Defender losses: <?php echo $def_tot_unitslost;?> units and <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
				<?php
				foreach ($units as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
					if (isset($def_unitlost[$key])) {
						echo $order['normalname'] . ': ' . $def_unitlost[$key] . ', ';
        			}
						}
					}
				?>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
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
				<?php if($status_defender == 'death'):?>	
					
				<?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> hit your base with a satellite and <strong>you died</strong> 
				
				<?php else:?>
				
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> used a satellite and
					
				<?php if($winner_id == $defender_id):?>
					
					<strong>missed</strong> your base.

				<?php else: ?>
					
					<strong>hit</strong> your base. 
					
				<?php endif; endif;?>
				
				
				</div>
			</div>
			
			
			<div class="row">
				<?php if($winner_id == $attacker_id):?>
				<div class="col-md-12 event-result">

				<strong>Defender losses: <?php echo $def_tot_buildingslost;?> buildings</strong><br/>
				<?php
				foreach ($buildings as $key => $order) {
					foreach ($def_unitslost as $def_unitlost) {
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

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo 'satellite';?>.png"> 
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
				<?php if($status_defender == 'death'):?>	
					
				<?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> hit your base with a satellite and <strong>you died</strong> 
				
				<?php else:?>
				
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> used an EMP satellite and
					
				<?php if($winner_id == $defender_id):?>
					
					<strong>missed</strong> your base.

				<?php else: ?>
					
					<strong>hit</strong> your base. 
					
				<?php endif; endif;?>
				
				
				</div>
			</div>
			
			
			<div class="row">
				<center>Power decreased by 20% for 6 hours.</center>
			</div>
			
		</div>
	</div>

<?php endif; // End EMP attacks ?>


<?php if($attack_type == 'empmissile'): ?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo 'missile';?>.png"> 
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
				<?php if($status_defender == 'death'):?>	
					
				<?php clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> hit your base with a missile and <strong>you died</strong> 
				
				<?php else:?>
				
				<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> launched an EMP missile and
					
				<?php if($winner_id == $defender_id):?>
					
					<strong>missed</strong> your base.

				<?php else: ?>
					
					<strong>hit</strong> your base. 
					
				<?php endif; endif;?>
				
				
				</div>
			</div>
			
			<?php if($winner_id == $attacker_id):?>
			<div class="row">
				<center>Power decreased by 15% for 6 hours.</center>
			</div>
			<?php endif;?>
		</div>
	</div>

<?php endif; // End EMP attacks ?>






<?php if($attack_type == 'thief'): ?>

<?php
$thiefs_lost = $eventData['thiefs_lost'][0];

/* set unknown avatar if attacker wins */

if($winner_id != $defender_id){
$attacker_id = 0;
}

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
				<?php echo small_avatar($attacker_id,'attack-profile-image');?>
				
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<?php if($winner_id != $defender_id):?>
						Someone sent a thief and stole <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong>
					<?php elseif($winner_id == $defender_id):?>
						You killed <?php echo $thiefs_lost. ' thief';if($thiefs_lost > 1){echo 's ';}?> sent by <a href="/users/profile/?id=<?php echo $attacker_id;?>"><?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
					<?php endif;?>
				
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End thief attacks ?>
 
 
 


<?php if(array_key_exists($attack_type,$researches)):?>



<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Research complete
	</div>
</div>
<!-- Event header -->


<div class="row event-row">
	
<!-- Attacker image -->	
	<div class="col-md-2">
		<div class="row">
			<div class="col-md-12">
				<?php echo small_avatar($userId,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				<strong><?php echo $researches[$attack_type]['name'];?></strong> completed. You can now start a new research.
				
				
				</div>
			</div>
			
			
		</div>
	</div>









<?php endif; // End research event ?>





<?php if($attack_type == 'nukeprotection'): ?>

<?php

/* set avatar */
$avatar = get_user_meta($userId, 'avatar_user', true);
		if(empty($avatar)){
			$avatar = '/wp-content/uploads/2016/11/default_large.png';
		}

?>



<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Assault Protection removed
	</div>
</div>
<!-- Event header -->


<div class="row event-row">
	
<!-- Attacker image -->	
	<div class="col-md-2">
		<div class="row">
			<div class="col-md-12">
				<?php echo small_avatar($userId,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				
				Assault Protection removed
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End NP removal ?>







<?php if($attack_type == 'killed'): ?>


<!-- Event header -->
<div style="background-color:#ad4236" class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/death.png"> 
		You were killed
	</div>
</div>
<!-- Event header -->


<div style="border-color:#ad4236;" class="row event-row">
	
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
				
				You were killed by <?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>"><?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End NP removal ?>






<?php if($attack_type == 'aid'): ?>

<?php $money = $eventData['money_lost'][0];?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Aid received
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
				
				You received <strong>$ <?php echo number_format($money, 0, ',', ' ');?></strong> aid from 
				<a href="/users/profile/?id=<?php echo $attacker_id;?>"><?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End aid event ?>





<?php if($attack_type == 'user_kicked'): ?>

<?php $kicked_clan = $eventData['attacker_clan_id'][0];?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Kicked from <?php echo get_the_title($kicked_clan);?> (#<?php echo $kicked_clan;?>)
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
				
				You were kicked by <a href="/users/profile/?id=<?php echo $attacker_id;?>">
				<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End kick event ?>






<?php if($attack_type == 'sat_crash'): ?>

<?php
	
	$avatar = get_user_meta($defender_id, 'avatar_user', true);
		if(empty($avatar)){
			$avatar = '/wp-content/uploads/2016/11/default_large.png';
		}
?>


<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Satellite crashed
	</div>
</div>
<!-- Event header -->


<div class="row event-row">
	
<!-- Attacker image -->	
	<div class="col-md-2">
		<div class="row">
			<div class="col-md-12">
				<?php echo small_avatar($userId,'attack-profile-image');?>
				<center><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->		
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
				
				Your satellite crashed and burned up in the atmosphere.
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End sat crash event ?>





<?php if($attack_type == 'spy'): ?>

<?php 
	
$show = $eventData['show_spy_sender'][0];
$spy_type = $eventData['event_spy_type'][0];
$sender = '<a href="/users/profile/?id='.$attacker_id.'">'.$member_data->display_name.' (#'.$attacker_id.')</a>';

if($winner_id == $attacker_id){
if($show == 'no'){
$attacker_id = 0;
$avatar = '/wp-content/uploads/2016/11/default_large.png';
$sender = '<strong>Someone</strong>';
}

if($spy_type == 'spy'){
	$message = $sender.' sent a spy';
}
if($spy_type == 'spyplane'){
	$message = 'A <strong>spyplane</strong> flew over your base';
}
}

if($winner_id == $defender_id){


if($spy_type == 'spy'){
	$message = 'You killed a spy that was sent by '.$sender;
}
if($spy_type == 'spyplane'){
	$message = 'You shot down a spyplane that was sent by '.$sender;
}
}
	
?>

<!-- Event header -->
<div class="row battlereport-header">
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/research.png"> 
		Spy
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
				
				<?php echo $message;?>
				
				</div>
			</div>
			
			
		</div>
	</div>

<?php endif; // End Spy event ?>
    
	            
	            
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