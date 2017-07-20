<?php
 /*
 * Template Name: EMP Missile Result
 */
include 'DO_NOT_DELETE.php';


$attacking_units 	= 		$_POST;
$defender_ID     	= 		$_SESSION['target_id'];
$target_id 			= 		$_SESSION['target_id'];

$shotdown			= 		false;
$AMS 		= 	get_user_meta($defender_ID, 'antimissile', true);
$def_land 	= 	get_user_meta($defender_ID, 'builtland', true);


/* check if target isn't dead, else redirect */
$target_status = get_user_meta($defender_ID,'status',true);
if($target_status == 'dead'){
	$_SESSION['status'] = 'This player is dead';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}

$shootdown_chance = (($AMS*100)/$def_land)*100;



if($shootdown_chance >= 75){
	$shootdown_chance = 75;
}

$shootdown = rand(1, 100);



if($shootdown < $shootdown_chance){
	$shotdown = true;
}

if($AMS == 0){
	$shotdown = false;
}

$power = get_user_meta($defender_ID, 'power', true);
if($power > 100){
	$shotdown = false;
}

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
$networth_att = get_user_meta($user_ID, 'networth',true);
$turns = get_user_meta($user_ID, 'turns',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);


$defender_clan_ID = get_user_meta($defender_ID, 'clan_id_user',true);
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$missile_research = get_user_meta($user_ID, 'level_missile_accuracy', true);

$calculate_points = 0;


get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <?php


$mutual = 0;
if($defender_clan_ID != 0 && $attacker_clan_ID != 0){


$one_sided = 0;
$wars = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'declared_on',
						'value'	  	=> $defender_clan_ID,
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'declared_by',
						'value'	  	=> $attacker_clan_ID,
						'compare' 	=> '=',
						),
),));

if(count($wars) != 0){
	$calculate_points = count($wars);
	$mutual = $mutual+1;
}

}






/* check for onesided war */
$wars = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'wars',
	'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'declared_on',
						'value'	  	=> $attacker_clan_ID,
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'declared_by',
						'value'	  	=> $defender_clan_ID,
						'compare' 	=> '=',
						),
),));


$onesided = count($wars);

if($onesided == 1){
	$mutual = $mutual+1;
	$calculate_points = 1;
	$one_sided = 1;
}

$missile_hit = rand(1,100);

if($missile_hit >= 90){
$result = 'success';
}else{
$result = 'failure';
}

if($missile_research == 1){
$missile_hit = rand(1,100);
if($missile_hit >= 50){
$result = 'success';
}else{
$result = 'failure';
}
}

if($missile_research == 2){
$missile_hit = rand(1,100);
if($missile_hit > 5){
$result = 'success';
}else{
$result = 'failure';
}
}

if($shotdown == true){
	$result = 'failure';
}

if($mutual == 2){
	$one_sided = 0;
}


// NW Check between attacker & Defender

if($mutual != 2){
	
	if (($networth_def > $networth_att/1.4 && $networth_def < $networth_att*1.4)){
	
	}else{
	
	$_SESSION['status'] = 'Out of networth range';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;

	}
	
}

/* determine morale cost */
if ($networth_att > $networth_def) {

	$moralecost = 35;
    
    } else {
	
	$moralecost = 30;
}
    


/* check if attacker has enough morale */    
$oldmorale = get_user_meta($user_ID, 'morale', true);
 
if ($oldmorale < $moralecost) {
	    	    
	$_SESSION['status'] = 'Insufficient morale';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;

}


    
 
$key = $_SESSION['attack_array']['missile'];
$missile_type = $_SESSION['attack_array']['missile'];
$owned_miss = get_user_meta($user_ID, $key.'_owned',true);
     	
/* Check if attacker has enough missiles */

if($owned_miss <= 0 ){
	     	
	$_SESSION['status'] = 'Not enough missiles of this type';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit; 	
	
}

/* check if user has enough turns */
if($turns < 3){ 
	
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
	}

/* update morale */
update_user_meta($user_ID, 'morale', $oldmorale - $moralecost);

/* update attacker missile */
update_user_meta($user_ID,$key.'_owned',$owned_miss-1);


?>

<article id="post-<?php the_ID();?>" <?php post_class(); ?>>
	<div class="entry-content">

		
<?php


/* add stats */
   
	// attacker
	
    $missiles_launched = get_user_meta($user_ID, 'missiles_launched', true);
	update_user_meta($user_ID, 'missiles_launched', $missiles_launched+1);
		
	// defender
		
	$missiles_received = get_user_meta($target_id, 'missiles_received', true);
	update_user_meta($target_id, 'missiles_received', $missiles_received+1);
	
	
	

if($result == 'success'){
	
/* add stats */
   
	// attacker
	
    $missiles_hit = get_user_meta($user_ID, 'missiles_hit', true);
	update_user_meta($user_ID, 'missiles_hit', $missiles_hit+1);
		
	// defender
		
	$missiles_hit_rec = get_user_meta($target_id, 'missiles_hit_rec', true);
	update_user_meta($target_id, 'missiles_hit_rec', $missiles_hit_rec+1);
	
	
}

?>
				
				
<?php if($result == 'success'){ ?>
<?php $winner_ID = $user_ID;?>
<center>
	<h2>S U C C E S S</h2>
		
		<p>Your EMP missile hit the base of 
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
update_field('deduction_emp',15, $new_emp_id);
?>
<div class="notice_message"><span class="rdw-line">Power of target reduced by 15% for the next 6 hours.</span></div><br/>

<?php } else { ?>
<div class="notice_message"><span class="rdw-line">Power of target reduced by 0% for the next 6 hours.</span></div><br/>
<?php }}?>





	
					
<?php if($result == 'failure' && $shotdown != true){ ?>
					<center><h2>F A I L U R E</h2>
					<p>Your missile missed the base of <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->display_name;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	
			
<?php if($result == 'failure' && $shotdown == true){ ?>
					<center><h2>F A I L U R E</h2>
					<p>Your missile was shot down by <a href="/users/profile/?id=<?php
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
$timestamp = current_time('timestamp');
$args = array(	
				'post_title'    => 'EMP Missile launched by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );
			


			update_field('time_attacked',$timestamp, $new_event_id);
			
			update_field('nw_damage_defender',0, $new_event_id);
			update_field('missile_type',$missile_type, $new_event_id);
			
		

			update_field('defender_id',$defender_ID, $new_event_id);
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype','empmissile', $new_event_id);
			update_field('outcome',$result, $new_event_id);
			
			if($shotdown == true){
			update_field('shotdown','shotdown', $new_event_id);
			}
			
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			
			
			update_user_meta($user_ID,'turns',$turns-3);
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events')[0]+1);
			
		
			
			/* Add globals to defender */

$clan = get_user_meta($defender_ID, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');


if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}

/* add globals attacker */

$clan_att = get_user_meta($user_ID, 'clan_id_user', true);
$clan_members_att = get_post_meta($clan_att,'clan_members');

if(!empty($clan_att) || $clan_att != 0){
foreach ($clan_members_att[0] as $member_att) {
	$globals = get_user_meta($member_att, 'new_global_events', true);
	update_user_meta($member_att, 'new_global_events', $globals+1);
}}


count_all_stats($target_id);
count_all_stats($user_ID);
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>