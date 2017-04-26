<?php
/*
Plugin Name: Marketorders
Plugin URI: 
Description: 
Version: 1
Author: Kevin Bogaard
Author URI:
License: GPL
Copyright: Kevin Bogaard
*/
function small_avatar($user_ID,$type){
	
	$addClass = '';
	if(!empty($type)){
		$addClass = $type;
	}
	
	if($user_ID != 0){
	$avatar = get_user_meta($user_ID, 'avatar_user', true);
	
	if(!empty($avatar)){
                    
		return "<div class='setAvatar clan_avatar $addClass' style='background: url(".$avatar.");'></div>";
		
		}
		
		else {
			
		$member_data = get_userdata($user_ID);
		$userName = $member_data->display_name;
		$frstLetter = strtoupper (substr($userName,0,1));
		$color = '#2D434E';  // Basic color 
		if(in_array($frstLetter, array('A'))){
			$color = '#2D434E';
		}
		if(in_array($frstLetter, array('B'))){
			$color = '#607782';
		}
		if(in_array($frstLetter, array('C'))){
			$color = '#425D69';
		}
		if(in_array($frstLetter, array('D'))){
			$color = '#1B3642';
		}
		if(in_array($frstLetter, array('E'))){
			$color = '#0D2632';
		}
		if(in_array($frstLetter, array('F'))){
			$color = '#343855';
		}
		if(in_array($frstLetter, array('G'))){
			$color = '#6C708E';
		}
		if(in_array($frstLetter, array('H'))){
			$color = '#4C5173';
		}
		if(in_array($frstLetter, array('I'))){
			$color = '#212648';
		}
		if(in_array($frstLetter, array('J'))){
			$color = '#121636';
		}
		if(in_array($frstLetter, array('K'))){
			$color = '#315842';
		}
		if(in_array($frstLetter, array('L'))){
			$color = '#6A937C';
		}
		if(in_array($frstLetter, array('M'))){
			$color = '#49775D';
		}
		if(in_array($frstLetter, array('N'))){
			$color = '#1C4B31';
		}
		if(in_array($frstLetter, array('O'))){
			$color = '#0D3820';
		}
		if(in_array($frstLetter, array('P'))){
			$color = '#7B6C44';
		}
		if(in_array($frstLetter, array('Q'))){
			$color = '#CEBE95';
		}
		if(in_array($frstLetter, array('R'))){
			$color = '#CEBE95';
		}
		if(in_array($frstLetter, array('S'))){
			$color = '#A79566';
		}
		if(in_array($frstLetter, array('T'))){
			$color = '#695728';
		}
		if(in_array($frstLetter, array('U'))){
			$color = '#4F3E12';
		}
		if(in_array($frstLetter, array('V'))){
			$color = '#7B5044';
		}
		if(in_array($frstLetter, array('W'))){
			$color = '#CEA195';
		}
		if(in_array($frstLetter, array('X'))){
			$color = '#A77366';
		}
		if(in_array($frstLetter, array('Y'))){
			$color = '#693528';
		}
		if(in_array($frstLetter, array('Z'))){
			$color = '#4F1F12';
		}
		
		}
		
		return "<div class='clan_avatar smallAvatar $addClass' style='background-color:$color;'>$frstLetter</div>";
						
			
		
		}else{
			
			
		return "<div class='clan_avatar smallAvatar $addClass' style='background-color:#ddd;'>?</div>";	
		
		}
	
                    
	
	
	
	
}
function alert_notification($message){
	
	return '<div class="alert alert-warning alert-dismissible blue_alert" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<i class="fa fa-info-circle" aria-hidden="true"></i> '.$message.'.
			</div>';
	
}

function desktop_view($user_ID){
	$desktop = get_user_meta($user_ID, 'desktop_view', true);
	if($desktop == 'on'){
		return '<meta name="viewport" content="width=1280">';
	}else{
		return '<meta name="viewport" content="width=device-width, initial-scale=1">';
	}
	
	
	
}

function notify_user($user_ID,$type){

$phonenumber = get_user_meta($user_ID, 'phone_number', true);
if(!empty(is_numeric($phonenumber))){
	
$remove = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U","+");
$phonenumber = str_replace($remove, "", $phonenumber);
	
$message = '';
	
$LP_notified = get_user_meta($user_ID, 'low_power_notified', true);
$LB_notified = get_user_meta($user_ID, 'low_buildings_notified', true);


if($LP_notified == 'no' && $type == 'power'){
	$message = 'Assault.Online Warning! Your power is currently offline. Restore your power as soon as possible.';
	update_user_meta($user_ID, 'low_power_notified', 'yes');
	
}

if($LB_notified == 'no' && $type == 'buildings'){
	$message = 'Assault.Online Warning! You have 50 buildings or less. Rebuild as soon as possible.';
	update_user_meta($user_ID, 'low_buildings_notified', 'yes');
	
}
	

include('messagebird/autoload.php');


$MessageBird = new \MessageBird\Client('rDfeaa4JedfVIxfPDM60gjMvh'); // Set your own API access key here.

$Message             = new \MessageBird\Objects\Message();
$Message->originator = 'AO';
$Message->recipients = array($phonenumber);
$Message->body       = $message;

try { 
	if($message != ''){
    $MessageResult = $MessageBird->messages->create($Message);
    }
    

} catch (\MessageBird\Exceptions\AuthenticateException $e) {
    // That means that your accessKey is unknown
    echo 'wrong login';

} catch (\MessageBird\Exceptions\BalanceException $e) {
    // That means that you are out of credits, so do something about it.
    echo 'no balance';

} catch (\Exception $e) {
    echo $e->getMessage();
}

} // end empty phone number check

} // end notify user

function wpse_76815_remove_publish_box() {
    remove_meta_box( 'submitdiv', 'clan', 'side' );
}
add_action( 'admin_menu', 'wpse_76815_remove_publish_box' );

function multi_register( $login ) {
    $user = get_user_by('login',$login);
    $user_ID = $user->ID;
    $ip_array = get_field('login_array_general',139664);
	$useragent = $_SERVER['HTTP_USER_AGENT'];

	$ip_address = $_SERVER['REMOTE_ADDR'];
	if(empty($ip_array[$ip_address])){
	$ip_array[$ip_address] = array();}
	
	
	$ip_array[$ip_address][$user_ID] = array(date('Y-m-d H:i:s'),$useragent);


	update_field('login_array_general',$ip_array,139664);


}
add_action( 'wp_login', 'multi_register');


function count_deposits($user_ID){
	$args = array(
	'posts_per_page'   => -1,
	'author'	=> $user_ID,
	'post_type'        => 'deposit',

	);
	$deposits = get_posts( $args ); 
	return count($deposits);
}
function clan_tag($user_ID){
	
if(get_user_meta($user_ID, 'clan_id_user', true) != 0){
	$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
	$clantag = get_post_meta($clan_ID, 'clan_tag', true);
	$chars = array("[", "]");
	$clantag = str_replace($chars, "", $clantag);
	return '<strong>['.$clantag.']</strong>';	
			
			}
	
}

function wpse66094_no_admin_access() {
    $redirect = isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : home_url( '/' );
    global $current_user;
    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);
    if($user_role === 'subscriber'){
        exit( wp_redirect( $redirect ) );
    }
 }

add_action( 'admin_init', 'wpse66094_no_admin_access', 100 );


function create_post_type() {
	register_post_type( 'market_order',array(
      'labels' => array(
        'name' => __( 'Orders' ),
        'singular_name' => __( 'Order' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports'    => array( 'title', 'editor', 'author', 'excerpt' ),
    ));
	register_post_type( 'event_local',array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports'    => array( 'title', 'editor', 'author', 'excerpt' ),
    ));
    register_post_type( 'clan',array(
      'labels' => array(
        'name' => __( 'Clans' ),
        'singular_name' => __( 'Clan' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'user_message',array(
      'labels' => array(
        'name' => __( 'Messages' ),
        'singular_name' => __( 'Message' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'sub_user_message',array(
      'labels' => array(
        'name' => __( 'Sub Messages' ),
        'singular_name' => __( 'Sub Message' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'wars',array(
      'labels' => array(
        'name' => __( 'Wars' ),
        'singular_name' => __( 'War' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'deposit',array(
      'labels' => array(
        'name' => __( 'Deposits' ),
        'singular_name' => __( 'Deposit' )
      ),
      'public' => true,
      'show_ui' => true,
      'has_archive' => false,
      'supports'           => array( 'title', 'editor', 'author' ),
    ));
    register_post_type( 'research',array(
      'labels' => array(
        'name' => __( 'Researches' ),
        'singular_name' => __( 'Research' )
      ),
      'public' => true,
      'has_archive' => false,
      'supports'    => array( 'title', 'editor', 'author', 'excerpt' ),
    ));
    register_post_type( 'sat',array(
      'labels' => array(
        'name' => __( 'Satellites' ),
        'singular_name' => __( 'Satellite' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'spy_rep',array(
      'labels' => array(
        'name' => __( 'Spy report' ),
        'singular_name' => __( 'Spy report' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'award',array(
      'labels' => array(
        'name' => __( 'Clan award' ),
        'singular_name' => __( 'Clan award' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'medal',array(
      'labels' => array(
        'name' => __( 'Medal' ),
        'singular_name' => __( 'Medal' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
    register_post_type( 'emp',array(
      'labels' => array(
        'name' => __( 'EMP' ),
        'singular_name' => __( 'EMP' )
      ),
      'public' => true,
      'has_archive' => false,
    ));
}
add_action( 'init', 'create_post_type' );





add_action( 'user_register', 'myplugin_registration_save', 10, 1 );

function myplugin_registration_save( $user_id ) {
	
update_user_meta($user_id,'clan_id_user',0);
	
// Set points & NW position
update_user_meta($user_id, 'points_position', 0);
update_user_meta($user_id, 'networth_position', 0);
	
// SET BUILDING NEW USER
update_user_meta($user_id, 'silo', 0);
update_user_meta($user_id, 'command_centre', 0);
update_user_meta($user_id, 'shipyard', 0);
update_user_meta($user_id, 'airfield', 0);
update_user_meta($user_id, 'warfactory', 0);
update_user_meta($user_id, 'baracks', 0);
update_user_meta($user_id, 'powerplant', 50);
update_user_meta($user_id, 'advancedpowerplant', 0);
update_user_meta($user_id, 'torpedolauncher', 0);
update_user_meta($user_id, 'samsite', 0);
update_user_meta($user_id, 'missileturret', 0);
update_user_meta($user_id, 'machinegunturret', 0);
update_user_meta($user_id, 'antimissile', 0);


// SET MISSILES NEW USER
update_user_meta($user_id, 'nuke_owned', 0);
update_user_meta($user_id, 'nuke_ordered', 0);
update_user_meta($user_id, 'chemical_owned', 0);
update_user_meta($user_id, 'chemical_ordered', 0);
update_user_meta($user_id, 'bio_owned', 0);
update_user_meta($user_id, 'bio_ordered', 0);
update_user_meta($user_id, 'moab_owned', 0);
update_user_meta($user_id, 'moab_ordered', 0);
update_user_meta($user_ID, 'empmis_owned', 0);
update_user_meta($user_ID, 'empmis_ordered', 0);

// SET STATS
update_user_meta($user_id, 'money', 450000);
update_user_meta($user_id, 'sold_land_today', 0);
update_user_meta($user_id, 'explored_today', 0);
update_user_meta($user_id, 'turns', 200);
update_user_meta($user_id, 'networth', 0);
update_user_meta($user_id, 'land', 2000);
update_user_meta($user_id, 'power', 0);
update_user_meta($user_id, 'builtland', 1000);
update_user_meta($user_id, 'morale', 100);
update_user_meta($user_id, 'morale_pool', 100);
update_user_meta($user_id, 'clan_id_user', 0);
update_user_meta($user_id, 'new_events', 0);
update_user_meta($user_id, 'status', 'nukeprotection');
$timestamp = strtotime(date('Y-m-d H:i:s'));
update_user_meta($user_id, 'nuke_protection_timestamp', $timestamp+(48 * 3600));
update_user_meta($user_id, 'sat_in_progress', 0);
update_user_meta($user_id, 'sat_owned', 0);
update_user_meta($user_id, 'total_deposits', 0);
update_user_meta($user_id, 'new_messages', 0);
update_user_meta($user_id, 'new_events', 0);
update_user_meta($user_id, 'user_country', 0);
update_user_meta($user_ID,'user_clan_points',0);


// SET RESEARCH ///
update_user_meta($user_id, 'level_money_production', 0);
update_user_meta($user_id, 'level_missile_accuracy', 0);
update_user_meta($user_id, 'level_sattelite_construction', 0);
update_user_meta($user_id, 'level_sattelite_construction', 0);
update_user_meta($user_id, 'level_shipping_time', 0);
update_user_meta($user_id, 'level_market_discount', 0);
update_user_meta($user_id, 'level_thieving_effectiveness', 0);
update_user_meta($user_id, 'level_engineering_effectiveness', 0);
update_user_meta($user_id, 'level_bank_management', 0);
update_user_meta($user_id, 'level_powerplant_efficiency', 0);
update_user_meta($user_id, 'research_in_progress', 0);
update_user_meta($user_id, 'queued_research', 0);



include('units_array.php');

foreach ($units as $key => $unit) {
update_user_meta($user_id, $key.'_owned', 0);
update_user_meta($user_id, $key.'_ordered', 0);

}}


function after_death( $user_id ) {
 if(!empty($user_id)){
// SET BUILDING after death
update_user_meta($user_id, 'silo', 0);
update_user_meta($user_id, 'command_centre', 0);
update_user_meta($user_id, 'shipyard', 0);
update_user_meta($user_id, 'airfield', 0);
update_user_meta($user_id, 'warfactory', 0);
update_user_meta($user_id, 'baracks', 0);
update_user_meta($user_id, 'powerplant', 50);
update_user_meta($user_id, 'advancedpowerplant', 0);
update_user_meta($user_id, 'torpedolauncher', 0);
update_user_meta($user_id, 'samsite', 0);
update_user_meta($user_id, 'missileturret', 0);
update_user_meta($user_id, 'machinegunturret', 0);
update_user_meta($user_id, 'antimissile', 0);


// SET MISSILES after death
update_user_meta($user_id, 'nuke_owned', 0);
update_user_meta($user_id, 'nuke_ordered', 0);
update_user_meta($user_id, 'chemical_owned', 0);
update_user_meta($user_id, 'chemical_ordered', 0);
update_user_meta($user_id, 'bio_owned', 0);
update_user_meta($user_id, 'bio_ordered', 0);
update_user_meta($user_id, 'moab_owned', 0);
update_user_meta($user_id, 'moab_ordered', 0);

// SET STATS after death
update_user_meta($user_id, 'money', 450000);
update_user_meta($user_id, 'sold_land_today', 0);
update_user_meta($user_id, 'explored_today', 0);
update_user_meta($user_id, 'turns', 200);
update_user_meta($user_id, 'networth', 0);
update_user_meta($user_id, 'land', 2000);
update_user_meta($user_id, 'power', 0);
update_user_meta($user_id, 'builtland', 1000);
update_user_meta($user_id, 'morale', 100);
update_user_meta($user_id, 'total_deposits', 0);


// RESET RESEARCH ///
update_user_meta($user_id, 'level_money_production', 0);
update_user_meta($user_id, 'level_missile_accuracy', 0);
update_user_meta($user_id, 'level_satellite_construction', 0);
update_user_meta($user_id, 'level_shipping_time', 0);
update_user_meta($user_id, 'level_market_discount', 0);
update_user_meta($user_id, 'level_thieving_effectiveness', 0);
update_user_meta($user_id, 'level_engineering_effectiveness', 0);
update_user_meta($user_id, 'level_bank_management', 0);
update_user_meta($user_id, 'level_powerplant_efficiency', 0);
update_user_meta($user_id, 'research_in_progress', 0);
update_user_meta($user_id, 'queued_research', 0);
update_user_meta($user_id, 'sat_in_progress', 0);
update_user_meta($user_id, 'sat_owned', 0);
update_user_meta($user_id, 'starting_bonus','');
update_user_meta($user_id, 'stealth_sat_status',0);






$args = array(
		'posts_per_page'   => -1,
		'author'	   => $user_id,
		'post_type'        => 'research',
		);
		$researches_in_progress = get_posts( $args );
		foreach ($researches_in_progress as $research) {
			
			wp_delete_post($research->ID);
		}
$args = array(
		'posts_per_page'   => -1,
		'author'	   => $user_id,
		'post_type'        => 'deposit',
		);
		$deposits = get_posts( $args );
		foreach ($deposits as $deposit) {
			
			wp_trash_post($deposit->ID);
		}
		
$args = array(
		'posts_per_page'   => -1,
		'author'	   => $user_id,
		'post_type'        => 'market_order',
		);
		$orders = get_posts( $args );
		foreach ($orders as $order) {
			
			wp_trash_post($order->ID);
		}
}


include('units_array.php');

foreach ($units as $key => $unit) {
update_user_meta($user_id, $key.'_owned', 0);
update_user_meta($user_id, $key.'_ordered', 0);

}}

function count_all_stats($user_ID){
	if(!empty($user_ID)){
	
	
	
include('units_array.php');
include('missiles_array.php');
include('building_array.php');
include('research_array.php');
include('constants.php');
include('satellite_array.php');


$unit_networth = 0;
foreach($units as $key => $unit){
	$units_owned = get_user_meta($user_ID, $key.'_owned',true);
	if($units_owned > 0){
	$unit_networth+= $units_owned*$unit['price']*($unit['networth']/100);}
	
}

$missile_networth = 0;
foreach($missiles as $key => $missile){
	$missiles_owned = get_user_meta($user_ID, $key.'_owned');
	if($missiles_owned > 0){
	$missile_networth+= $missiles_owned[0]*$missile['price']*($missile['networth']/100);}
	
}

$building_networth 	= 0;
$totalbuildings 	= 0;
$used_power 		= 0;
$power_production 	= 0;

$PPE_level = get_user_meta($user_ID, 'level_powerplant_efficiency')[0];
	$PPE_multi = 1;
	if($PPE_level == 1){
		$PPE_multi = 1.5;
		}

foreach($buildings as $key => $building){
	$buildings_owned = get_user_meta($user_ID, $key,true);
	if($buildings_owned > 0){
	$totalbuildings+=$buildings_owned;
	$building_networth+= $buildings_owned*$building['price']*($building['networth']/100);
	$power_production+=$building['powerprod']*$buildings_owned;
	$used_power+=$building['power']*$buildings_owned;}
}



$research_NW = 0;
foreach($researches as $key => $research){
	$level = get_user_meta($user_ID, 'level_'.$key,true);
	if($level > 0){
	$research_NW+= $research['duration']*$RESEARCH_NW_PER_HOUR*$level;
	}
}

$sat_owned = get_user_meta($user_ID, 'sat_owned', true);

$sat_NW = 0;

if($sat_owned != 0 || !empty($sat_owned)){
$sat_NW = $satellites[$sat_owned]['price']*0.06;
}


$land = get_user_meta($user_ID, 'land',true);
$land_networth = round($land*0.85);


update_user_meta( $user_ID,'networth',round($sat_NW+$research_NW+$building_networth+$unit_networth+$land_networth+$missile_networth));

update_user_meta( $user_ID,'builtland',$totalbuildings*20);


$empReduction = 0;

$emps = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'emp',
	'meta_key'		=> 'defender_emp',
	'meta_value'	=> $user_ID
));
$empReduction = 0;
foreach ($emps as $emp) {
	$empReduction += get_post_meta($emp->ID, 'deduction_emp', true);
	
	
}


if($power_production > 0){
	update_user_meta( $user_ID,'power',$used_power/($power_production*$PPE_multi)*100);}
	else{
	update_user_meta( $user_ID,'power',$used_power*100);
	}
}

$power = get_user_meta($user_ID, 'power', true);
update_user_meta( $user_ID,'power',$power+$empReduction);

$status = get_user_meta($user_ID, 'status', true);


if($status == 'online'){

	if($totalbuildings < 50){
		$low_buildings = get_user_meta($user_ID, 'low_buildings', true);
		if($low_buildings == 'on'){
		notify_user($user_ID,'buildings');
		}
		}

	if($power+$empReduction > 100){
		$low_power = get_user_meta($user_ID, 'low_power', true);
		if($low_power == 'on'){
		notify_user($user_ID,'power');
		}
		}
	}
} // end count stats 



add_shortcode( 'current-satellites' , 'display_count_satellites' );
function display_count_satellites(){
	include('satellite_array.php');
	$user_ID = get_current_user_id();
    $sat = get_user_meta($user_ID, 'sat_owned', true);
    $sat_owned = 'none';
    if($sat != '0'){
	    $sat_owned = $satellites[$sat]['shortname'];
    }
    return '<span class="count_menu">'.$sat_owned.'</span>';
} 

add_shortcode( 'current-missiles' , 'display_count_missiles' );
function display_count_missiles(){
	$user_ID = get_current_user_id();
    $missiles = count_missiles($user_ID);
    return '<span class="count_menu">'.$missiles.'</span>';
} 



function count_missiles($user_ID){
	include('missiles_array.php');
	$totalmissiles = 0;
	foreach($missiles as $key => $missile){
	$missiles_owned = get_user_meta($user_ID, $key.'_owned',true);
	$totalmissiles+=$missiles_owned;
}
return $totalmissiles;
	
}


add_shortcode( 'current-buildings' , 'display_count_buildings' );
function display_count_buildings(){
	$user_ID = get_current_user_id();
    $buildings = count_buildings($user_ID);
    return '<span class="count_menu">'.$buildings.'</span>';
} 

function count_buildings($user_ID){
	include('building_array.php');
	$totalbuildings = 0;
	foreach($buildings as $key => $building){
	$buildings_owned = get_user_meta($user_ID, $key)[0];
	$totalbuildings+=$buildings_owned;
}
return $totalbuildings;
	
}




add_shortcode( 'current-units' , 'display_count_units' );
function display_count_units(){
	$user_ID = get_current_user_id();
    $units = count_units($user_ID);
    return '<span class="count_menu">'.$units.'</span>';
} 

function count_units($user_ID){
	include('units_array.php');
	$totalunits = 0;
	foreach($units as $key => $unit){
	$units_owned = get_user_meta($user_ID, $key.'_owned',true);
	$totalunits+=$units_owned;
}
return $totalunits;
	
}


function bonus_update(){
	include 'bonus_array.php';
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	$args = array(
		
		'post_type'		=>	'clan',
		'posts_per_page' => -1,
		);
	
	$clans = get_posts($args);
	foreach ($clans as $clan) {
		$clan_ID = $clan->ID;
		
		$clan_members	= get_post_meta($clan_ID,'clan_members');
		$clan_points	= get_post_meta($clan_ID,'clan_points',true);
		$bonus_level	= get_post_meta($clan_ID,'bonus_level',true);
	
	if(empty($clan_points)){
		$clan_points = 0;
	}
	
	$level = "level_";

		
		
	/* mini clan bonus level 1 */
	if($bonus_level == 0){
		if((5000 <= $clan_points) && ($clan_points <= 9999)){
			
			$level .= 1;
			update_post_meta($clan_ID, 'bonus_level', 1);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
				
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
		
	
	/* regular clan bonus level 2*/
	if($bonus_level == 1){
		if((10000 <= $clan_points) && ($clan_points <= 19999)){
			$level .= 2;
			update_post_meta($clan_ID, 'bonus_level', 2);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
		
	/* regular clan bonus level 3 */
	if($bonus_level == 2){
		if((200000 <= $clan_points) && ($clan_points <= 29999)){
			$level .= 3;
			update_post_meta($clan_ID, 'bonus_level', 3);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
	
	/* regular clan bonus level 3 */
	if($bonus_level == 3){
		if((30000 <= $clan_points) && ($clan_points <= 39999)){
			$level .= 4;
			update_post_meta($clan_ID, 'bonus_level', 4);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
	
	/* regular clan bonus level 4 */
	if($bonus_level == 4){
		if((30000 <= $clan_points) && ($clan_points <= 39999)){
			$level .= 5;
			update_post_meta($clan_ID, 'bonus_level', 5);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
		
	/* regular clan bonus level 5 */
	if($bonus_level == 5){
		if((40000 <= $clan_points) && ($clan_points <= 49999)){
			$level .= 6;
			update_post_meta($clan_ID, 'bonus_level', 6);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
		
	/* Mega clan bonus level 6 */
	if($bonus_level == 6){
		if((50000 <= $clan_points) && ($clan_points <= 59999)){
			$level .= 7;
			update_post_meta($clan_ID, 'bonus_level', 7);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}
		}
	
	
	/* Regular clan bonus level 7 */
	if($bonus_level == 7){
		if((60000 <= $clan_points) && ($clan_points <= 69999)){
			$level .= 8;
			update_post_meta($clan_ID, 'bonus_level', 8);
			
		
			foreach ($clan_members[0] as $member) {
				$args = array(	
					'post_title'    => 'Bonus for: #'.$member,
					'post_status'   => 'publish',
					'post_type'		=> 'event_local',
					'post_author'   => $member
					);
			
					$new_event_id = wp_insert_post( $args );
					update_field('attacktype','bonus', $new_event_id);
					update_field('bonus_money',$bonus[$level]['money'], $new_event_id);
					update_field('bonus_turns',$bonus[$level]['turns'], $new_event_id);
					update_field('defender_id',$member, $new_event_id);
					update_field('time_attacked',$timestamp, $new_event_id);
					
					$event_count = get_user_meta($member, 'new_events')[0];
					update_user_meta($member, 'new_events', $event_count + 1);
			}}

		
		
		
		
		
		
		}}}
/* Extra columns in user backend */
function new_modify_user_table( $column ) {
    $column['networth'] = 'Networth';
    $column['land'] = 'Land';
    $column['playername'] = 'Playername';
    $column['lastseen'] = 'Last seen';
    $column['clan'] = 'Clan';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
	
	$member_data = get_userdata($user_id);
	$lastseen = date('G:i:s | d-m-Y', get_user_meta($user_id, 'last_online', true));
	$clan_id = get_user_meta($user_id, 'clan_id_user',true);
   
    switch ($column_name) {
        case 'networth' :
            return '$ '.number_format(get_user_meta($user_id, 'networth', true), 0, ',', ' ');
            break;
        case 'land' :
            return number_format(get_user_meta($user_id, 'land', true), 0, ',', ' ').' m<sup>2</sup>';
            break;
        case 'playername' :
            return '<a target="_blank" href="/users/profile/?id='.$user_id.'">'.$member_data->display_name.' (#'.$user_id.')</a>';
            break;
		case 'lastseen' :
            return $lastseen;
            break;
        case 'clan' :
        
        if($clan_id == 0){
			return 'none';
			}
			else{
			return '<a target="_blank" href="'.get_the_permalink($clan_id).'">'.get_the_title($clan_id).' (#'.$clan_id.')</a>';
		}
        
           
            break;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );




/* extra medal columns */
add_filter( 'manage_medal_posts_columns', 'set_custom_edit_medal_columns' );
add_action( 'manage_medal_posts_custom_column' , 'custom_medal_column', 10, 2 );

function set_custom_edit_medal_columns($columns) {
   
    $columns['winner'] = 'Winner';
    $columns['round'] = 'Round';

    return $columns;
}

function custom_medal_column( $column, $post_id ) {
	$user_ID = get_post_meta($post_id, 'winning_user', true);
	$round = get_post_meta($post_id, 'medal_round', true);
		$member_data = get_userdata($user_ID);
		$userName = $member_data->display_name;
    switch ( $column ) {
		
		
        case 'winner' :
           echo $userName.' (#'.$user_ID.')';
            break;

        case 'round' :
            echo $round;
            break;

    }
}

/* extra award columns */


add_filter( 'manage_award_posts_columns', 'set_custom_edit_award_columns' );
add_action( 'manage_award_posts_custom_column' , 'custom_award_column', 10, 2 );

function set_custom_edit_award_columns($columns) {
   
    $columns['winner'] = 'Winner';
    $columns['position'] = 'Position';
    $columns['round'] = 'Round';

    return $columns;
}

function custom_award_column( $column, $post_id ) {
	
	$clanID = get_post_meta($post_id, 'winning_clan', true);
	$round = get_post_meta($post_id, 'round', true);
	$position = get_post_meta($post_id, 'position_clan', true);	
	
    switch ( $column ) {
		
        case 'winner' :
           	echo get_the_title($clanID).' (#'.$clanID.')';
            break;
            
		case 'position' :
			echo $position;
            break;

        case 'round' :
            echo $round;
            break;

    }
}

