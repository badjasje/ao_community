<?php
 /*
 * Template Name: General admin tools
 */

$visiting_user = get_current_user_id();

$ip_array = get_field('login_array_general',139664);
include('units_array.php');
include('building_array.php');
include('research_array.php');

$admin_IDs = get_field('admin_ids','option');
$admin_IDs = explode(',', $admin_IDs);


if(!in_array($visiting_user, $admin_IDs)){
	wp_redirect(get_permalink(3486)); exit;
}


get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
<style>
	.tab-content{
		display:block;
	}
	table{
		border:none;
	}
	.responsive-table tbody tr{
		border:0px;
	}
</style>

<!-- Nav tabs -->
<ul id="myTab" class="nav nav-tabs nav-justified" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#ips" role="tab">IP & Logins</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#attacks" role="tab">Attack events</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#thiefs" role="tab">Thief events</a>
  </li>
</ul>

<script>
jQuery(function(){
  var hash = window.location.hash;
  hash && jQuery('ul.nav a[href="' + hash + '"]').tab('show');

  jQuery('.nav-tabs a').click(function (e) {
    jQuery(this).tab('show');
    var scrollmem = jQuery('body').scrollTop() || jQuery('html').scrollTop(100);
    window.location.hash = this.hash;
    jQuery('html,body').scrollTop(scrollmem);
  });
});

</script>	          

<div class="tab-content">
	<div class="tab-pane active" id="ips" role="tabpanel">
<?php foreach ($ip_array as $ip => $users) {?>
<hr/>
<h2><?php echo $ip;?></h2>

<?php 
	$count = 0;
	foreach ($users as $user => $stuff) {
	$member_data = get_userdata($user);
	$count++;
	$locData = json_decode($stuff[3]);
	
?>

<a href="/users/profile/?id=<?php echo $user;?>"><?php echo $member_data->display_name.' (#'.$user.')';?></a><br/>

<?php echo $stuff[0];?><br/>

<?php echo $stuff[1];?><br/>
<?php echo $stuff[2];?><br/>
<?php echo '<pre>';
print_r($locData->data);
echo '</pre>'; ?><br/>
<br/>

<?php if($count >= 2){?>
<h1 style="color:#ff0000;">MULTI</h1>

<?php }}}?>
</div>








<div class="tab-pane" id="attacks" role="tabpanel">
	<br/>
<form action="" method="get">
<div class="row">
  <div class="col-md-6"><input type="text" name="landlost" placeholder="filter by land lost" value="<?php echo $_GET['landlost'];?>"></div>
  <div class="col-md-6"><input type="text" name="moneylost" placeholder="filter by money lost" value="<?php echo $_GET['moneylost'];?>"></div>
</div>	
<div class="row">
  <div class="col-md-6"><input type="text" name="attackerid" placeholder="attacker ID" value="<?php echo $_GET['attackerid'];?>"></div>
  <div class="col-md-6"><input type="text" name="defenderid" placeholder="defender ID" value="<?php echo $_GET['defenderid'];?>"></div>
</div>	





<button class="btn btn-filter" type="submit" value="FILTER">Filter</button>
	
</form>
	
<?php
	
$money_lost = $_GET['moneylost'];
if(empty($money_lost)){
	$money_lost = 0;
}

$land_lost = $_GET['landlost'];
if(empty($land_lost)){
	$land_lost = 0;
}

$all_users = array();
$users = get_users();
foreach ($users as $user) {
	$all_users[] = $user->ID;
}

$attackerIDs = $all_users;
$attacker = $_GET['attackerid'];
if(!empty($attacker)){
	$attackerIDs = $_GET['attackerid'];
}


$defenderIDs = $all_users;
$defender = $_GET['defenderid'];
if(!empty($defender)){
	$defenderIDs = $_GET['attackerid'];
}





$filter_array = array('regular','air_sea','ground');
	
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = array(

	'posts_per_page'	=> 20,
	'orderby'          	=> 'date',
	'order'            	=> 'DESC',
	'paged'				=>  $custom_query_args['paged'],
	'post_type'        	=> 'event_local',
	'post_status'      	=> 'publish',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key'     => 'money_lost',
			'value'   => $money_lost,
			'type'    => 'numeric',
			'compare' => '>=',
		),
		array(
			'key'     => 'land_lost',
			'value'   => $land_lost,
			'type'    => 'numeric',
			'compare' => '>=',
		),
		array(
             'key' => 'attacker_id',
             'value' => $attackerIDs,
             'compare' => 'IN'
           ),
        array(
             'key' => 'defender_id',
             'value' => $defenderIDs,
             'compare' => 'IN'
           ),
         array(
             'key' => 'attacktype',
             'value' => $filter_array,
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
	$timestamp = current_time('timestamp');
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
				<div class="attack-profile-image" 
					style="background: url(<?php echo $avatar;?>);background-size: cover;">
				</div>
				<center>
				<strong>Current land:</strong><br/><?php echo number_format(get_user_meta($attacker_id, 'land', true), 0, ',', ' ');?> m<sup>2</sup><br/>
				<strong>Current money:</strong><br/>$ <?php echo number_format(get_user_meta($attacker_id, 'money', true), 0, ',', ' ');?><br/>
				<br/><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
	
	
						
		
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
		<strong>won the battle</strong>
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
				
				</div>
			
			</div>
			
		</div>
	</div>

<?php endif; // End regular, ground & air&sea attacks ?>

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






<div class="tab-pane" id="thiefs" role="tabpanel">
	<br/>
<form action="" method="get">
<div class="row">
  <div class="col-md-6"></div>
  <div class="col-md-6"><input type="text" name="moneylostthief" placeholder="filter by money lost" value="<?php echo $_GET['moneylostthief'];?>"></div>
</div>	
<div class="row">
  <div class="col-md-6"><input type="text" name="attackeridthief" placeholder="attacker ID" value="<?php echo $_GET['attackeridthief'];?>"></div>
  <div class="col-md-6"><input type="text" name="defenderidthief" placeholder="defender ID" value="<?php echo $_GET['defenderidthief'];?>"></div>
</div>	





<button class="btn btn-filter" type="submit" value="FILTER">Filter</button>
	
</form>
	
<?php
	
$money_lost = $_GET['moneylostthief'];
if(empty($money_lost)){
	$money_lost = 0;
}



$all_users = array();
$users = get_users();
foreach ($users as $user) {
	$all_users[] = $user->ID;
}

$attackerIDs = $all_users;
$attacker = $_GET['attackeridthief'];
if(!empty($attacker)){
	$attackerIDs = $_GET['attackeridthief'];
}


$defenderIDs = $all_users;
$defender = $_GET['defenderidthief'];
if(!empty($defender)){
	$defenderIDs = $_GET['attackeridthief'];
}


	
$custom_query_args['paged'] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args = array(

	'posts_per_page'	=> 20,
	'orderby'          	=> 'date',
	'order'            	=> 'DESC',
	'paged'				=>  $custom_query_args['paged'],
	'post_type'        	=> 'event_local',
	'post_status'      	=> 'publish',
	'meta_query' => array(
		'relation' => 'AND',
		array(
			'key'     => 'money_lost',
			'value'   => $money_lost,
			'type'    => 'numeric',
			'compare' => '>=',
		),
		array(
             'key' => 'attacker_id',
             'value' => $attackerIDs,
             'compare' => 'IN'
           ),
        array(
             'key' => 'defender_id',
             'value' => $defenderIDs,
             'compare' => 'IN'
           ),
         array(
             'key' => 'attacktype',
             'value' => 'thief',
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
	$timestamp = current_time('timestamp');
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


<?php 	
	$member_data = get_userdata($attacker_id);
	$defender_data = get_userdata($defender_id);
?>


<!-- Event header -->
<div class="row battlereport-header" <?php if(in_array($defender_id, $members[0])): // attack by clanmember ?> style="background-color:#607785;"<?php endif;?>>
	<div class="col-md-12">
		<img class="attack-image" src="http://assault.online/wp-content/uploads/2016/03/<?php echo $attack_type;?>.png"> 
		Thief report
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
				<div class="attack-profile-image" 
					style="background: url(<?php echo $avatar;?>);background-size: cover;">
				</div>
				<center>
				<strong>Current land:</strong><br/><?php echo number_format(get_user_meta($attacker_id, 'land', true), 0, ',', ' ');?> m<sup>2</sup><br/>
				<strong>Current money:</strong><br/>$ <?php echo number_format(get_user_meta($attacker_id, 'money', true), 0, ',', ' ');?><br/>
				<br/><?php echo human_time_diff( $timeattacked, $timestamp );?> ago</center>
			</div>
		</div>
	</div>
<!-- Attacker image -->			
	
	
	<div class="col-md-10">
			<div class="row">
				<div class="col-md-12 event-message">
	
	
						
		
		<!-- attacker -->
		<?php echo clan_tag($attacker_id);?> <a href="/users/profile/?id=<?php echo $attacker_id;?>">
		<?php echo $member_data->display_name.' (#'.$attacker_id.')';?></a> thiefed
		
		<!-- defender -->	
		<?php echo clan_tag($defender_id);?> <a href="/users/profile/?id=<?php echo $defender_id;?>">
		<?php echo $defender_data->display_name.' (#'.$defender_id.')';?></a> and 
						
		
		<?php if($winner_id == $attacker_id){?>
		was successful<br/>
		
		In this thief attempt <strong>$ <?php echo number_format($moneylost, 0, ',', ' '); ?></strong> was stolen. 
		
		
		<?php } else { ?>
		
		<strong>lost the battle</strong>
		<?php }?>
	
	
						
				
				
				</div>
			</div>
			
			
			<div class="row">
			
				<div class="col-md-12 event-result">				
					
				</div>
			
			</div>
			
		</div>
	</div>



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
    </div>
</div>
<?php get_footer(); ?>