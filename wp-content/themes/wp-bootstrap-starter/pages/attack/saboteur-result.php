<?php 

$missileLevel = $defenderData['level_missile_accuracy'][0];
$sat_status = $defenderData['stealth_sat_status'][0];

$turns = $attackerData['turns'][0];
$totalSaboteurs = $attackerData['saboteur_owned'][0];

$tot_snipers = $defenderData['sniper_owned'][0];

$silos = $defenderData['silo'][0];



// Various validations
/* Check if attacker has enough saboteurs */
if($totalSaboteurs <= 0){
	$array['status'] = 'Not enough saboteurs';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}

/* Check if attacker has enough turns */
if($turns < 2){
	$array['status'] = 'Not enough turns';
	$array['next'] = false;
	echo json_encode($array);
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
update_user_meta($userId, 'morale', $oldmorale - 30);

if ($result == 'success') {


$silo1Status = get_user_meta($target_id, 'silo_disable_1', true);
if($silos >= 1){
	if($silo1Status == '' || $silo1Status == 'inactive'){
		update_user_meta($target_id, 'silo_disable_1', 'active');
		}else{
			if($silos >= 2 && $missileLevel < 3){
				update_user_meta($target_id, 'silo_disable_2', 'active');
			}
	}
}
	
$winner_id = $userId; ?>


<div class="blockHeader">Your saboteur entered the base of <?php echo get_user_name($target_id);?></div>
<div class="blockHeader spaceNotice">
<?php if($silos > 0){
		echo'One missile silo disabled';
		}
		else{ 
		echo 'Target has no missile silos to disable';
		} ?>
</div>





		
<?php } else {

	$winner_id = $target_id;
	$saboteurs = $attackerData['saboteur_owned'][0];
	update_user_meta($userId,'saboteur_owned',$saboteurs-1);
	?>

<div class="blockHeader">Your saboteur was killed in action</div>
<div class="blockHeader spaceNotice">
No missile silos disabled
</div>
	
<?php } ?>

<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Strike Again</div>
<?php 


////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
		'post_title'    => 'Saboteur sent by '.$userId.' Defender: '.$target_id,
		'post_status'   => 'publish',
		'post_type'		=> 'event_local',
		'post_author'   => $userId
);
				
			
$new_event_id = wp_insert_post( $args );

update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());
update_field('time_attacked',$timestamp, $new_event_id);
update_field('defender_id',$target_id, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('attacktype','saboteur', $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);


update_user_meta($userId,'turns',$turns-2);

	