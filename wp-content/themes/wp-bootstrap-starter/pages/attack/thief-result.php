<?php 

$no_thiefs = $_POST['nothiefs'];



$attacker_money = $attackerData['money'][0];
$defender_money = $defenderData['money'][0];

$turns = $attackerData['turns'][0];
$tot_thiefs = $attackerData['thief_owned'][0];
$tot_snipers = $defenderData['sniper_owned'][0];



// Various validations
/* Check if attacker has enough thiefs */
if($no_thiefs > $tot_thiefs){
	$array['status'] = 'Not enough thiefs';
	$array['next'] = false;
	echo json_encode($array);
	exit;
}
if($tot_thiefs <= 0){
	$array['status'] = 'Not enough thiefs';
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


$thief_level = $attackerData['level_thieving_effectiveness'][0];
$cashMultiplier = 0;


if($thief_level == 0){
$money_stolen = ceil($defender_money*pow(1+((rand(10, 20) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(75, 100)+($no_thiefs*7)+($tot_snipers*0.39);
}
if($thief_level == 1){
$money_stolen = ceil($defender_money*pow(1+((rand(20, 30) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(70, 100)+($no_thiefs*6)+($tot_snipers*0.39);
}
if($thief_level == 2){
$money_stolen = ceil($defender_money*pow(1+((rand(30, 40) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(65, 100)+($no_thiefs*5)+($tot_snipers*0.39);
}
if($thief_level == 3){
$money_stolen = ceil($defender_money*pow(1+((rand(40, 50) / 1000)),$no_thiefs))-$defender_money;
$caught = rand(50, 100)+($no_thiefs*2.5)+($tot_snipers*0.39);
}

if($defender_money <= $money_stolen){
	$money_stolen = $defender_money;
}

$result = 'success';
if($caught > 90){
	$result = 'failure';
}

if ($defender_money == 0) {
    $money_stolen = 0;
}

$success = do_thief($thief_level, $no_thiefs, $tot_snipers, $defender_money);
$sat_status = $defenderData['stealth_sat_status'][0];

if($sat_status == 'active'){
	$result = 'failure';
}





if($sat_status == 'active'){
	$success = 0;
}



if ($success > 0) { $result = 'success';
	//Nick money
	$money_stolen = floor($defender_money*$success);
	update_user_meta($userId, 'morale', $oldmorale - 5);
	
	/* FOLLOWS THE PAGE ITSELF */
	$winner_id = $userId;
	
	/* add stats */
	// attacker
	$money_gained_thieving = get_user_meta($userId, 'money_gained_thieving', true);
	update_user_meta($userId, 'money_gained_thieving', $money_gained_thieving+$money_stolen);
	//defender
	$money_lost_thieving = get_user_meta($target_id, 'money_lost_thieving', true);
	update_user_meta($target_id, 'money_lost_thieving', $money_lost_thieving+$money_stolen);
	?>
	




<div class="blockHeader">Your thief<?php echo plural_func($no_thiefs);?> entered the base of <?php echo get_user_name($target_id);?></div>
<div class="blockHeader spaceNotice">
	<h1><u>$ <?php echo number_format($money_stolen, 0, ',', ' ');?></u> stolen</h1>
</div>

	
	
	
	<?php //// UPDATE MONEY
	if ($money_stolen > $defender_money) {
		update_user_meta($target_id, 'money', $defender_money);
	} else {
		update_user_meta($target_id, 'money', $defender_money - $money_stolen);
	}
	update_user_meta($userId, 'money', $attacker_money + $money_stolen);

	
	
}
else { $result = 'failure';
	//Failure 
	
	$money_stolen = 0;
	update_user_meta($userId, 'morale', $oldmorale - 5);

	
	$winner_id = $target_id;
	$money_stolen = 0;
	update_user_meta($userId,'thief_owned',$tot_thiefs-$no_thiefs);
	?>


<div class="blockHeader">Your thief<?php echo plural_func($no_thiefs);?> failed to enter the base of <?php echo get_user_name($target_id);?> and were killed</div>

<?php } ?>


<div id="strikeagain" class="mainSubmit"><i class="fas fa-sync" aria-hidden="true"></i> Strike Again</div>
<?php 	
//These happen regardless of success or failure
$thieving_attempts = get_user_meta($userId, 'thieving_attempts', true);
update_user_meta($userId, 'thieving_attempts', $thieving_attempts+1);

$attempts_received = get_user_meta($target_id, 'attempts_received', true);
update_user_meta($target_id, 'attempts_received', $attempts_received+1);

////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
		'post_title'    => 'Thieving done by '.$userId.' Defender: '.$target_id,
		'post_status'   => 'publish',
		'post_type'		=> 'event_local',
		'post_author'   => $userId
);
				
			
$new_event_id = wp_insert_post( $args );
update_post_meta( $new_event_id, 'event_ip_address', get_user_ip_address());

update_field('money_lost', $money_stolen, $new_event_id);
update_field('time_attacked',$timestamp, $new_event_id);
update_field('defender_id',$target_id, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);
update_field('attacktype','thief', $new_event_id);
update_field('winner_id',$winner_id, $new_event_id);


update_user_meta($userId,'turns',$turns-$TURNS_THIEF);
turn_spread('thieving',$TURNS_THIEF);
update_user_meta($target_id, 'new_events', get_user_meta($target_id, 'new_events',true)+1);


if($success == 0){
	update_field('thiefs_lost',$no_thiefs, $new_event_id);
}