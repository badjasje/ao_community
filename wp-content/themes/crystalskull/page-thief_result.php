<?php
 /*
 * Template Name: Thief result
 */
include('constants.php');

$defender_ID = $_SESSION['target_id'];
$user_ID     = get_current_user_ID();
$no_thiefs   = $_SESSION['attack_array']['thief'];

$defender_money = get_user_meta($defender_ID, 'money');
$attacker_money = get_user_meta($user_ID, 'money');
$turns = get_user_meta($user_ID,'turns');

$tot_thiefs = get_user_meta($user_ID, 'thief_owned',true);
$tot_snipers = get_user_meta($defender_ID, 'sniper_owned',true);

/* Check if attacker has enough thiefs */
if($no_thiefs[0]>$tot_thiefs){
	echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=16";
	
		</script>';
    ;
    exit;
	
}

/* Check if attacker has enough turns */
if($turns[0]<2){
	echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=1";
	
		</script>';
    ;
    exit;
	
}

$thief_level = get_user_meta($user_ID, 'level_thieving_effectiveness')[0];

if($thief_level == 0){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(1, 2) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(75*1+($no_thiefs/2), 100)+$no_thiefs*1.5*($tot_snipers*0.39);
}
if($thief_level == 1){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(2, 3) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(70*1+($no_thiefs/2), 100)+$no_thiefs*1.5*($tot_snipers*0.39);
}
if($thief_level == 2){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(3, 4) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(65*1+($no_thiefs/2), 100)+$no_thiefs*1.5*($tot_snipers*0.39);
}
if($thief_level == 3){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(4, 5) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(60*1+($no_thiefs/2), 100)+$no_thiefs*1.5*($tot_snipers*0.39);
}

if($defender_money[0] <= $money_stolen){
	$money_stolen = $defender_money[0];
}

$result = 'success';
if($caught > 90){
	$result = 'failure';
}

if ($defender_money == 0) {
    $money_stolen = 0;
}



$networth_att = get_user_meta($user_ID, 'networth');
$networth_def = get_user_meta($defender_ID, 'networth');


// NW Check between attacker & Defender


if (($networth_def[0] > $networth_att[0] /1.4 && $networth_def[0] < $networth_att[0] * 1.4)) {
} else {
    echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=9";
	
		</script>';
    ;
    exit;
}
$oldmorale = get_user_meta($user_ID, 'morale');

if ($oldmorale[0] < 5) {
    echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=2";
	
		</script>';
    exit;
} else {
    update_user_meta($user_ID, 'morale', $oldmorale[0] - 5);
}

/* add stats */
	// attacker
	$thieving_attempts = get_user_meta($user_ID, 'thieving_attempts', true);
	update_user_meta($user_ID, 'thieving_attempts', $thieving_attempts+1);
	
	
	//defender
	$attempts_received = get_user_meta($defender_ID, 'attempts_received', true);
	update_user_meta($defender_ID, 'attempts_received', $attempts_received+1);

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       <?php if($result == 'failure'){
	
	$winner_id = $defender_ID;
	$money_stolen = 0;
	update_user_meta($user_ID,'thief_owned',$tot_thiefs-$no_thiefs);
?>

	<center><h2>F A I L U R E</h2>
<div class="notice_message">Your <?php
if ($no_thiefs == 1) {
    echo 'thief';
} else {
    echo 'thieves';
}
?> failed to get into the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> and were <strong>killed</strong></div>


<?php } else {
	
	$winner_id = $user_ID;
?>
		
<center><h2>S U C C E S S</h2>
<?php
	
	/* add stats */
	// attacker
	$money_gained_thieving = get_user_meta($user_ID, 'money_gained_thieving', true);
	update_user_meta($user_ID, 'money_gained_thieving', $money_gained_thieving+$money_stolen);
	
	
	//defender
	$money_lost_thieving = get_user_meta($defender_ID, 'money_lost_thieving', true);
	update_user_meta($defender_ID, 'money_lost_thieving', $money_lost_thieving+$money_stolen);
	
	?>
<div class="notice_message">Your <?php
if ($no_thiefs == 1) {
    echo 'thief';
} else {
    echo 'thieves';
}
?> entered the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> <?php if($money_stolen > 0){echo'and stole $ '.number_format($money_stolen, 0, ',', ' ');}else{ echo 'but there was no money to steal';} ?></strong>
</div>
			
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
<?php //// UPDATE MONEY
if ($money_stolen > $defender_money[0]) {
    update_user_meta($defender_ID, 'money', $defender_money[0]);
} else {
    update_user_meta($defender_ID, 'money', $defender_money[0] - $money_stolen);
}
update_user_meta($user_ID, 'money', $attacker_money[0] + $money_stolen);


}

////// CREATE EVENT POST ////////////
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
				'post_title'    => 'Thieving done by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );


			update_field('money_lost', $money_stolen, $new_event_id);
			update_field('time_attacked',$timestamp, $new_event_id);
			update_field('defender_id',$defender_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype','thief', $new_event_id);
			update_field('winner_id',$winner_id, $new_event_id);
			
			
			update_user_meta($user_ID,'turns',$turns[0]-$TURNS_THIEF);
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events')[0]+1);
			
			
			if($result == 'failure'){
			update_field('thiefs_lost',$no_thiefs, $new_event_id);
			}
			
			
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>