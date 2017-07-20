<?php
 /*
 * Template Name: EMP result
 */
include 'DO_NOT_DELETE.php';
include('attack_functions.php');
include 'units_array.php';
include 'constants.php';
$timestamp = current_time('timestamp');
$attacking_units = $_POST;
$defender_ID     = $_SESSION['target_id'];

$SEA_ATT_power   = 0;
$AIR_ATT_power   = 0;
$INF_ATT_power   = 0;
$VEH_ATT_power   = 0;
$BLD_ATT_power   = 0;

$SEA_ATT_life = 0;
$AIR_ATT_life = 0;
$INF_ATT_life = 0;
$VEH_ATT_life = 0;

$no_air_types = 0;
$no_veh_types = 0;
$no_inf_types = 0;
$no_sea_types = 0;

$_total_air_units_att = 0;
$_total_inf_units_att = 0;
$_total_veh_units_att = 0;
$_total_sea_units_att = 0;


$user_ID = get_current_user_id();
$winner_ID = $user_ID;

$turns = get_user_meta($user_ID, 'turns',true);




/* check satellite morale */

$sat_morale = get_user_meta($user_ID, 'sat_morale',true);
if (100 > $sat_morale) {
	
	$_SESSION['status'] = 'Insufficient satellite power';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);

	exit;

}

/* check if target is alive */

$target_status = get_user_meta($defender_ID,'status',true);
if($target_status == 'dead'){
	$_SESSION['status'] = 'This player is dead';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}






$defender_clan_ID = get_user_meta($defender_ID, 'clan_id_user',true);
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user',true);



$war_type = get_war_type($attacker_clan_ID, $defender_clan_ID);
$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);



/* check if target in range */

$attack_type = 'satellite';
$in_range = target_in_range($attack_type, $networth_att, $networth_def, $war_type);

/* validate target in range */
if (!$in_range) {
	$_SESSION['status'] = 'Out of networth range';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}


$result = 'success';

$sat_status = get_user_meta($defender_ID, 'stealth_sat_status',true);
if($sat_status == 'active'){
	$result = 'failure';
	
}


include('units_array.php');
include('building_array.php');
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
		
				
				
				
<?php if($result == 'success'){ ?>
<?php $winner_ID = $user_ID;?>
<center>
	<h2>S U C C E S S</h2>
		
		<p>Your EMP satellite hit the base of 
		<strong>
		<a href="/users/profile/?id=<?php echo $defender_ID;?>"><?php $playername = get_userdata($defender_ID);
			echo $playername->display_name;
			echo ' (#' . $defender_ID . ')';
				?>
		</a>
		</strong>
<?php
	$emps = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'emp',
	'meta_key'		=> 'defender_emp',
	'meta_value'	=> $defender_ID
));
	
	?>
<?php if(count($emps) < 3){
	
$args = array(	
	'post_title'    => 'EMP '.$defender_ID,
	'post_status'   => 'publish',
	'post_type'		=> 'emp',
	'post_author'   => $user_ID
);
				
$new_emp_id = wp_insert_post( $args );

update_field('defender_emp', $defender_ID, $new_emp_id);
update_field('timestamp_emp', $timestamp+3600*6, $new_emp_id);
update_field('deduction_emp',20, $new_emp_id);
?>
<div class="notice_message"><span class="rdw-line">Power of target reduced by 20% for the next 6 hours.</span></div>

<?php } else { ?>
<div class="notice_message"><span class="rdw-line">Power of target reduced by 0% for the next 6 hours.</span></div>
<?php }}?>




					
					
<?php if($result == 'failure'){ ?>
<center>
					<h2>F A I L U R E</h2>
					<p>Your satellite missed the base of <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->display_name;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	
			
				




			
			
<?php 


////// CREATE EVENT POST ////////////

$args = array(	
				'post_title'    => 'EMP satellite attack made by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );
	
	
			update_field('time_attacked',$timestamp, $new_event_id);

			

			
			update_field('defender_id',$defender_ID, $new_event_id);
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype','empsat', $new_event_id);
			update_field('outcome',$result, $new_event_id);
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			update_user_meta($user_ID,'turns',$turns-3);
			
			update_user_meta($user_ID,'sat_morale',$sat_morale-100);
			
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events',true)+1);
			/* Add globals to defender */

$clan = get_user_meta($defender_ID, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}


/* Add globals to attacker */

$clan_att = get_user_meta($user_ID, 'clan_id_user', true);
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
foreach ($clan_members_att[0] as $member_att) {
	$globals = get_user_meta($member_att, 'new_global_events', true);
	update_user_meta($member_att, 'new_global_events', $globals+1);
}}




count_all_stats($defender_ID);
count_all_stats($user_ID);
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>