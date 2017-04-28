<?php
 /*
 * Template Name: Incoming New
 */
$user_ID = get_current_user_ID();
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
						'spy');


include('units_array.php');
include('building_array.php');
include('research_array.php');



update_user_meta($user_ID,'new_events',0);
$clan_ID = get_user_meta($user_ID, 'clan_id_user',true);

if($user_ID != 0){
	$members = get_post_meta($clan_ID,'clan_members');
} 

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            
	            
<div class="row">
  <div class="col-md-3">



<form action="" method="get">
  <div class="multiselect">
    <div class="selectBox" onclick="showCheckboxes()">
      <select>
        <option>Filter events</option>
      </select>
      <div class="overSelect"></div>
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
		}
		
		;?>
      <label for="<?php echo $item;?>">
        <input <?php echo $checked;?> name="filter_<?php echo $end_par;?>" value="<?php echo $item;?>"type="checkbox" id="<?php echo $item;?>" /><?php echo $item;?></label>
<?php 
	
	
	}?>
    </div>
  </div>
  <button class="btn btn-filter" type="submit" value="FILTER">Filter</button><a class="btn btn-filter" href="http://assault.online/events/incoming">RESET</a>
</form>

<br/>	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
  </div>
 <div class="col-md-6">
	 <center>
	 <a class="btn btn-general current_but eventbutton" href="/events/incoming/"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i> Incoming</a>
	 <a class="btn btn-general eventbutton" href="/events/outgoing/"><i class="fa fa-arrow-circle-up" aria-hidden="true"></i> Outgoing</a>
	 <a class="btn btn-general eventbutton" href="/events/global/"><i class="fa fa-globe" aria-hidden="true"></i> Global</a></div>
	 </center>
	 <br/>
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
						'value'	  	=> $user_ID,
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

	
							
	$event_ID = get_the_id();
	$defender_id = get_post_meta($event_ID,'defender_id',true);
	$attacker_id = get_post_meta($event_ID,'attacker_id',true);

	$member_data = get_userdata($attacker_id);
	
	$def_unitslost = get_post_meta($event_ID,'defender_lost');
	$att_unitslost = get_post_meta($event_ID,'attacker_lost');
	
	$def_tot_unitslost = get_post_meta($event_ID,'def_total_units_lost',true);
	$att_tot_unitslost = get_post_meta($event_ID,'att_total_units_lost',true);
	
	if(empty($def_tot_unitslost)){
	$def_tot_unitslost = 0;
	}
	if(empty($att_tot_unitslost)){
	$att_tot_unitslost = 0;
	}
	
	
	$def_tot_buildingslost = get_post_meta($event_ID,'total_buildings_lost',true);
	$landlost = get_post_meta($event_ID,'land_lost',true);
	$moneylost = get_post_meta($event_ID,'money_lost',true);
	
	$status_defender = get_post_meta($event_ID,'status_defender',true);
	
	$defender_NW_lost = get_post_meta($event_ID, 'nw_damage_defender', true);
	$attacker_NW_lost = get_post_meta($event_ID, 'nw_damage_attacker', true);
	
	
	$timeattacked = get_post_meta($event_ID,'time_attacked',true);
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	$attack_type = get_post_meta($event_ID,'attacktype',true);
	$winner_id = get_post_meta($event_ID,'winner_id',true);
	
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
				
				</div>
			
			</div>
			
		</div>
	</div>

<?php endif; // End regular, ground & air&sea attacks ?>	   






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
				<?php if(get_post_meta($event_ID, 'shotdown', true) == 'shotdown'){?>
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
$thiefs_lost = get_post_meta($event_ID, 'thiefs_lost', true);

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
				<?php echo small_avatar($user_ID,'attack-profile-image');?>
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
$avatar = get_user_meta($user_ID, 'avatar_user', true);
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
				<?php echo small_avatar($user_ID,'attack-profile-image');?>
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





<?php if($attack_type == 'aid'): ?>

<?php $money = get_post_meta($event_ID, 'money_lost', true);?>

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

<?php $kicked_clan = get_post_meta($event_ID,'attacker_clan_id',true);?>

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
				<?php echo small_avatar($user_ID,'attack-profile-image');?>
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
	
$show = get_post_meta($event_ID, 'show_spy_sender', true);
$spy_type = get_post_meta($event_ID, 'event_spy_type', true);
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
	<div class="btn btn-general"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> <?php previous_posts_link('Previous') ?></div>
	<div class="btn btn-general"><?php next_posts_link('Next') ?> <i class="fa fa-arrow-circle-right" aria-hidden="true"></i></div>
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