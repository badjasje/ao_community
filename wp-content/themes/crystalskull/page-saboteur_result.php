<?php 

/*
 * Template Name: Saboteur result page
 * V1.0 kevin 12-12-2017
 */
include('constants.php');

$defender_ID = $_SESSION['target_id'];
$user_ID     = get_current_user_ID();
$missileLevel = get_user_meta($defender_ID, 'level_missile_accuracy', true);
$userLock = get_user_meta($user_ID, 'user_lock', true);
$moraleLock = get_user_meta($user_ID, 'morale_lock', true);

if($moraleLock == 1){
	$_SESSION['status'] = 'Morale updating, please try again in a few seconds.';
	wp_redirect(get_permalink(3360).'?id='.$target_id);
	exit;
}else{

if($userLock == 1){
	echo 'How about no.';
	die;
}
update_user_meta($user_ID, 'user_lock', 1);

$sat_status = get_user_meta($defender_ID, 'stealth_sat_status', true);

$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);

$turns = get_user_meta($user_ID,'turns',true);
$totalSaboteurs = get_user_meta($user_ID, 'saboteur_owned',true);

$tot_snipers = get_user_meta($defender_ID, 'sniper_owned',true);

$silos = get_user_meta($defender_ID, 'silo', true);



// Various validations
/* Check if attacker has enough saboteurs */
if($totalSaboteurs <= 0){

	$_SESSION['status'] = 'Not enough saboteurs.';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	
    exit;
	
}

/* Check if attacker has enough turns */
if($turns < 2){
	
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
    exit;
	
}

	

$successCount = mt_rand(1,100);
$result = 'success';

if($missileLevel == 3){
	if($successCount > 40){
		$result = 'failure';
	}
	else{
	if($successCount > 50){
		$result = 'failure';
	}	
}
}



if($sat_status == 'active'){
	$result = 'failure';
}

$sniperSuccess = mt_rand(1,100);
$saboteurProtection = $tot_snipers*0.45;
if($saboteurProtection > 90){
	$saboteurProtection = 90;
}
if($sniperSuccess < $saboteurProtection){
	$result = 'failure';
}

$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);



/* Check if in range */

if (($networth_def > $networth_att /1.4 && $networth_def < $networth_att * 1.4)) {
} else {
    $_SESSION['status'] = 'Out of networth range';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
    exit;
}

/* Check if attacker has sufficient morale */

$oldmorale = get_user_meta($user_ID, 'morale',true);

if ($oldmorale < 30) {
	
	$_SESSION['status'] = 'Insufficient morale';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
    exit;

} 

get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
<?php

if ($result == 'success') {
	//Nick money

	update_user_meta($user_ID, 'morale', $oldmorale - 30);
	
	$silo1Status = get_user_meta($defender_ID, 'silo_disable_1', true);
	if($silos >= 1){
		if($silo1Status == '' || $silo1Status == 'inactive'){
			update_user_meta($defender_ID, 'silo_disable_1', 'active');
			}else{
				if($silos >= 2 && $missileLevel < 3){
					update_user_meta($defender_ID, 'silo_disable_2', 'active');
				}
		}
	}
	
	/* FOLLOWS THE PAGE ITSELF */
	$winner_id = $user_ID;
	echo "<center><h2>S U C C E S S</h2>";
	
	
	?>
	<div class="notice_message">Your saboteur entered the base of 
		<a href="/users/profile/?id=<?php echo $defender_ID;?>">
			<strong>
				<?php $playername = get_userdata($defender_ID); 
				echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';?> 
			</strong>	
			
			<strong> 
				<?php if($silos > 0){
					echo'and disabled a missile silo.';
					}
					else{ 
					echo 'but there were no missile silos to disable.';
					} ?>
			</strong>
	</div>
				
				
	</div><!-- .entry-content -->
		
<?php 

}
else {
	//Failure 
	
	
	update_user_meta($user_ID, 'morale', $oldmorale - 30);

	
	$winner_id = $defender_ID;
	$saboteurs = get_user_meta($user_ID,'saboteur_owned',true);
	update_user_meta($user_ID,'saboteur_owned',$saboteurs-1);
	?>

	<center><h2>F A I L U R E</h2>
	<div class="notice_message">Your saboteur failed to get into the base of <a href="/users/profile/?id=<?php echo $defender_ID; ?>">
		<strong>
			<?php $playername = get_userdata($defender_ID); 
				echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';?> 
		</strong>
		
		<strong> and was <strong>killed</strong></div>


	<?php 	
	
}


////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
		'post_title'    => 'Saboteur sent by '.$user_ID.' Defender: '.$defender_ID,
		'post_status'   => 'publish',
		'post_type'		=> 'event_local',
		'post_author'   => $user_ID
);
				
			
$new_event_id = wp_insert_post( $args );


update_field('time_attacked',$timestamp, $new_event_id);
update_field('defender_id',$defender_ID, $new_event_id);
update_field('attacker_id',$user_ID, $new_event_id);
update_field('attacktype','saboteur', $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);


update_user_meta($user_ID,'turns',$turns-2);

	
	?>	
	 </div>
        </div>
    </div>
</div>
<?php get_footer();


//Actual calculation function follows	

update_user_meta($user_ID, 'user_lock', 0);
}


?>