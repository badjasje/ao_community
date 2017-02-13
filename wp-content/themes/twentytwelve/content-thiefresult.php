<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
include('constants.php');

$defender_ID = $_SESSION['target_id'];
$user_ID     = get_current_user_ID();
$no_thiefs   = $_SESSION['attack_array']['thief'];

$defender_money = get_user_meta($defender_ID, 'money');
$attacker_money = get_user_meta($user_ID, 'money');
$turns = get_user_meta($user_ID,'turns');

$tot_thiefs = get_user_meta($user_ID, 'thief_owned');


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
$caught = rand(70*1+($no_thiefs/3), 100);
}
if($thief_level == 1){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(2, 3) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(60*1+($no_thiefs/3), 100);
}
if($thief_level == 2){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(4, 5) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(50*1+($no_thiefs/3), 100);
}
if($thief_level == 3){
$money_stolen = ceil($defender_money[0]*pow(1+((rand(5, 7) / 100)),$no_thiefs))-$defender_money[0];
$caught = rand(40*1+($no_thiefs/3), 100);
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

?>

	<article id="post-<?php the_ID();?>" <?php post_class(); ?>>
		<div class="entry-content">


<?php if($result == 'failure'){
	
	$winner_id = $defender_ID;
	$money_stolen = 0;
	update_user_meta($user_ID,'thief_owned',$tot_thiefs[0]-$no_thiefs);
?>

	<center><h2>F A I L U R E</h2>
			<p>Your <?php
if ($no_thiefs == 1) {
    echo 'thief';
} else {
    echo 'thieves';
}
?> failed to get into the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> and were <strong>killed</strong>
			</center>


<?php } else {
	
	$winner_id = $user_ID;
?>
		
<center><h2>S U C C E S S</h2>
			<p>Your <?php
if ($no_thiefs == 1) {
    echo 'thief';
} else {
    echo 'thieves';
}
?> entered the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> <?php if($money_stolen > 0){echo'and stole $ '.number_format($money_stolen, 0, ',', ' ');}else{ echo 'but there was no money to steal';} ?>
			</center>
			
			
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
				'post_title'    => 'Attack made by '.$user_ID.' Defender: '.$defender_ID,
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


