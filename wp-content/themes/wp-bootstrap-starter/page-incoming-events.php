<?php
 /*
 * Template Name: Local events
*/
global $userData;
global $userId;
include('research_array.php');
$array_for_filter = array(	
						'empsat',
						'empmissile',
						'satellite',
						'regular',
						'air_sea',
						'ground',
						'missile',
						'thief',
						'nukeprotection',
						'aid',
						'research_ready',
						'user_kicked',
						'sat_crash',
						'sniper',
						'killed',
						'spy');


include('units_array.php');
include('building_array.php');




update_user_meta($userId,'new_events',0);
$clan_ID = get_user_meta($userId, 'clan_id_user',true);

if($userId != 0){
	$members = get_post_meta($clan_ID,'clan_members');
} 

get_header(); ?>

<div class="row pageRow">
	
<div class="row no-gutters fw-row profileButtonRow">
	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/events/incoming">
		<i class="fa fa-arrow-circle-down" aria-hidden="true"></i> &nbsp;Incoming events
	</a>
 	
 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);"href="/events/outgoing">
 		<i class="fa fa-arrow-circle-up" aria-hidden="true"></i> &nbsp;Outgoing events
 	</a>
 	
 	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);"href="/events/global">
 		<i class="fa fa-globe" aria-hidden="true"></i> &nbsp;Global events
	</a>
</div>	
<div class="pageSpacer"></div>
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
	
		
		$reportHeader = '';
		if($attack_type == 'air_sea'){
			$icon = 'flaticon-ship';
			$reportHeader = 'Air & Sea attack battle report';
		}
		if($attack_type == 'regular'){
			$icon = 'flaticon-fighter-plane';
			$reportHeader = 'Regular attack battle report';
		}
		if($attack_type == 'ground'){
			$icon = 'flaticon-tank';
			$reportHeader = 'Ground attack battle report';
		}
		if($attack_type == 'nukeprotection'){
			$icon = 'flaticon-compass';
			$reportHeader = 'Protection removed';
		}
		if($attack_type == 'aid'){
			$icon = 'flaticon-compass';
			$reportHeader = 'Aid received';
		}
		if($attack_type == 'research_ready'){
			$icon = 'flaticon-compass';
			$reportHeader = 'Research completed';
		}
		if($attack_type == 'missile'){
			$icon = 'flaticon-radioactive';
			$reportHeader = 'Missile attack report';
		}
		if($attack_type == 'satellite'){
			$icon = 'flaticon-objective';
			$reportHeader = 'Satellite attack report';
		}
		if($attack_type == 'empsat'){
			$icon = 'flaticon-objective';
			$reportHeader = 'EMP satellite attack report';
		}
		if($attack_type == 'sat_crash'){
			$icon = 'flaticon-objective';
			$reportHeader = 'Satellite crash report';
		}
		if($attack_type == 'empmissile'){
			$icon = 'flaticon-objective';
			$reportHeader = 'EMP missile attack report';
		}
		if($attack_type == 'sniper'){
			$icon = 'flaticon-bullet';
			$reportHeader = 'Sniper attack report';
		}
		if($attack_type == 'spy'){
			$icon = 'flaticon-fighter-plane-1';
			$reportHeader = 'Spy infiltration report';
		}
		if($attack_type == 'thief'){
			$icon = 'flaticon-secret-agent';
			$reportHeader = 'Thief infiltration report';
		}
		if($attack_type == 'user_kicked'){
			$icon = 'flaticon-boots';
			$reportHeader = 'You were kicked from your clan';
		}
		if($attack_type == 'killed'){
			$icon = 'flaticon-badge';
			$reportHeader = 'You died';
		}
		
		
		
		
		?>
<div class="fw-row">
<div class="iconBlockHeader">
	<i class="<?php echo $icon;?>"></i>
</div>
<div class="blockHeader"><?php echo $reportHeader;?></div>
</div>
<?php if($attack_type == 'ground' || $attack_type == 'air_sea' || $attack_type == 'regular'): ?>

	<?php include('pages/events/incoming/attack.php'); ?>
	
<?php elseif($attack_type == 'missile'): ?>

	<?php include('pages/events/incoming/missile.php'); ?>

<?php elseif($attack_type == 'empmissile'): ?>

	<?php include('pages/events/incoming/emp-missile.php'); ?>
	
<?php elseif($attack_type == 'satellite'): ?>

	<?php include('pages/events/incoming/satellite.php'); ?>
	
<?php elseif($attack_type == 'empsat'): ?>

	<?php include('pages/events/incoming/emp-sat.php'); ?>
	
<?php elseif($attack_type == 'sniper'): ?>

	<?php include('pages/events/incoming/sniper.php'); ?>
	
<?php elseif($attack_type == 'thief'): ?>

	<?php include('pages/events/incoming/thief.php'); ?>
	
<?php elseif($attack_type == 'aid'): ?>

	<?php include('pages/events/incoming/aid.php'); ?>
	
<?php elseif($attack_type == 'research_ready'): ?>

	<?php include('pages/events/incoming/research_ready.php'); ?>

<?php elseif($attack_type == 'nukeprotection'): ?>

	<?php include('pages/events/incoming/protection-removed.php'); ?>

<?php elseif($attack_type == 'user_kicked'): ?>

	<?php include('pages/events/incoming/kicked.php'); ?>

<?php elseif($attack_type == 'sat_crash'): ?>

	<?php include('pages/events/incoming/satellite-crashed.php'); ?>

<?php elseif($attack_type == 'killed'): ?>

	<?php include('pages/events/incoming/killed.php'); ?>	
	
<?php elseif($attack_type == 'spy'): ?>

	<?php include('pages/events/incoming/spy.php'); ?>
	
<?php endif;?>
				
				
				

<?php endwhile; endif; ?>
<div class="row fw-row no-gutters">

		<?php previous_posts_link('<i class="fas fa-arrow-left"></i> Previous') ?>


		<?php next_posts_link('Next <i class="fas fa-arrow-right"></i>') ?>
	
</div>
				
				<?php wp_reset_postdata(); // fixes bug where below ACF fields wont display 
					
					$wp_query = NULL;
					$wp_query = $temp_query; 
				?>
				

	
</div> <!-- End pageRow -->
<?php
get_footer();