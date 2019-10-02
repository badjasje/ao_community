<?php
/**
 * Template Name: Local events
 */

get_header();
global $userData;
global $userId;
$researches = Researches::get();
$array_for_filter = array(
	'empsat', 'empmissile', 'satellite', 'regular', 'air_sea', 'ground', 'missile',
	'thief', 'nukeprotection', 'aid', 'research_ready', 'user_kicked', 'sat_crash', 'sniper', 'killed', 'spy');

$units = Units::get();
include('building_array.php');

update_user_meta($userId,'new_events',0);
$clan_ID = $userData['clan_id_user'][0];

if($userId != 0){
	$members = get_post_meta($clan_ID,'clan_members');
}
?>
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
			array('key' => 'defender_id', 'value' => $userId, 'compare' => '='),
			array('key' => 'attacktype', 'value' => $array_for_filter, 'compare' => 'IN'),
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

			$defender_points = (isset($eventData['defender_points']) ? $eventData['defender_points'][0] : 0);

			$member_data = get_userdata($attacker_id);

			$def_unitslost = (isset($eventData['defender_lost']) ? maybe_unserialize($eventData['defender_lost'][0]) : 0);
			$att_unitslost = (isset($eventData['attacker_lost']) ? maybe_unserialize($eventData['attacker_lost'][0]) : 0);

			$def_tot_unitslost = (isset($eventData['def_total_units_lost']) ? $eventData['def_total_units_lost'][0] : 0);
			$att_tot_unitslost = (isset($eventData['att_total_units_lost']) ? $eventData['att_total_units_lost'][0] : 0);

			if(empty($def_tot_unitslost)) $def_tot_unitslost = 0;
			if(empty($att_tot_unitslost)) $att_tot_unitslost = 0;

			$def_tot_buildingslost = (isset($eventData['total_buildings_lost']) ? $eventData['total_buildings_lost'][0] : 0);
			$landlost = (isset($eventData['land_lost']) ? $eventData['land_lost'][0] : 0);
			$moneylost = (isset($eventData['money_lost']) ? $eventData['money_lost'][0] : 0);

			$status_defender = (isset($eventData['status_defender']) ? $eventData['status_defender'][0] : '');

			$defender_NW_lost = (isset($eventData['nw_damage_defender']) ? $eventData['nw_damage_defender'][0] : 0);
			$attacker_NW_lost = (isset($eventData['nw_damage_attacker']) ? $eventData['nw_damage_attacker'][0] : 0);

			$tomahawkHit = (isset($eventData['tomahawk_hit']) ? $eventData['tomahawk_hit'][0] : 0);
			$tomahawkDown = (isset($eventData['tomahawk_down']) ? $eventData['tomahawk_down'][0] : 0);

			$timeattacked = $eventData['time_attacked'][0];
			$timestamp = current_time('timestamp');
			$attack_type = $eventData['attacktype'][0];
			$winner_id = (isset($eventData['winner_id']) ? $eventData['winner_id'][0] : 0);

			// We will be putting this in an Event-object, so the other pages use the same code
			$reportHeader = '';
			switch($attack_type) {
				case 'air_sea':
					$icon = 'flaticon-ship';
					$reportHeader = 'Air & Sea attack battle report';
				break;
				case 'regular':
					$icon = 'flaticon-fighter-plane';
					$reportHeader = 'Regular attack battle report';
				break;
				case 'ground':
					$icon = 'flaticon-tank';
					$reportHeader = 'Ground attack battle report';
				break;
				case 'nukeprotection':
					$icon = 'flaticon-compass';
					$reportHeader = 'Protection removed';
				break;
				case 'aid':
					$icon = 'flaticon-compass';
					$reportHeader = 'Aid received';
				break;
				case 'research_ready':
					$icon = 'flaticon-compass';
					$reportHeader = 'Research completed';
				break;
				case 'missile':
					$icon = 'flaticon-radioactive';
					$reportHeader = 'Missile attack report';
				break;
				case 'satellite':
					$icon = 'flaticon-objective';
					$reportHeader = 'Satellite attack report';
				break;
				case 'empsat':
					$icon = 'flaticon-objective';
					$reportHeader = 'EMP satellite attack report';
				break;
				case 'sat_crash':
					$icon = 'flaticon-objective';
					$reportHeader = 'Satellite crash report';
				break;
				case 'empmissile':
					$icon = 'flaticon-objective';
					$reportHeader = 'EMP missile attack report';
				break;
				case 'sniper':
					$icon = 'flaticon-bullet';
					$reportHeader = 'Sniper attack report';
				break;
				case 'spy':
					$icon = 'flaticon-fighter-plane-1';
					$reportHeader = 'Spy infiltration report';
				break;
				case 'thief':
					$icon = 'flaticon-secret-agent';
					$reportHeader = 'Thief infiltration report';
				break;
				case 'user_kicked':
					$icon = 'flaticon-boots';
					$reportHeader = 'You were kicked from your clan';
				break;
				case 'killed':
					$icon = 'flaticon-badge';
					$reportHeader = 'You died';
				break;
			}
			?>
			<div class="fw-row" id="event-<?=$eventId?>">
				<div class="iconBlockHeader"><i class="<?=$icon?>"></i></div>
				<div class="blockHeader"><?=$reportHeader?></div>
			</div>
			<?
			// We will be putting this in an Event-object, so the other pages use the same code
			switch($attack_type) {
				case 'ground':
				case 'air_sea':
				case 'regular':
					include('pages/events/incoming/attack.php');
					break;
				case 'missile': include('pages/events/incoming/missile.php'); break;
				case 'empmissile': include('pages/events/incoming/emp-missile.php'); break;
				case 'satellite': include('pages/events/incoming/satellite.php'); break;
				case 'empsat': include('pages/events/incoming/emp-sat.php'); break;
				case 'sniper': include('pages/events/incoming/sniper.php'); break;
				case 'thief': include('pages/events/incoming/thief.php'); break;
				case 'aid': include('pages/events/incoming/aid.php'); break;
				case 'research_ready': include('pages/events/incoming/research_ready.php'); break;
				case 'nukeprotection': include('pages/events/incoming/protection-removed.php'); break;
				case 'user_kicked': include('pages/events/incoming/kicked.php'); break;
				case 'sat_crash': include('pages/events/incoming/satellite-crashed.php'); break;
				case 'killed': include('pages/events/incoming/killed.php'); break;
				case 'spy': include('pages/events/incoming/spy.php'); break;
			}

		endwhile;
	endif; ?>

	<div class="row fw-row no-gutters">
		<?php previous_posts_link('<i class="fas fa-arrow-left"></i> Previous') ?>
		<?php next_posts_link('Next <i class="fas fa-arrow-right"></i>') ?>
	</div>

	<?php
	wp_reset_postdata();
	$wp_query = NULL;
	$wp_query = $temp_query;
	?>
</div>
<?php
get_footer();
