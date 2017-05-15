<?php
 /*
 * Template Name: Sniper result
 */
include('constants.php');
include 'units_array.php';
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
$defender_ID = 2; 
//$_SESSION['target_id'];
$user_ID     = get_current_user_ID();
$no_snipers   = 5;
 //$_SESSION['attack_array']['sniper'];


$tot_sniper_attackpower = $units['sniper']['attack']*$no_snipers;
echo $tot_sniper_attackpower.'<br/>';

$turns = get_user_meta($user_ID,'turns');

$defender_thiefs = 30;//get_user_meta($defender_ID, 'thief_owned',true);
$defender_spies = 20;//get_user_meta($defender_ID, 'spy_owned',true);

$thief_life = $units['thief']['life']*$defender_thiefs;
$spy_life = $units['spy']['life']*$defender_spies;

$tot_life = $thief_life+$spy_life;

$spy_damage = $tot_sniper_attackpower*($spy_life/$tot_life);
$thief_damage = $tot_sniper_attackpower*($thief_life/$tot_life);

echo $spy_damage.'<br/>';
echo $thief_damage.'<br/>';
$def_snipers = get_user_meta($defender_ID, 'sniper_owned',true);


/* Check if attacker has enough turns */
if($turns[0]<2){
	echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=1";
	
		</script>';
    ;
    exit;
	
}





$networth_att = get_user_meta($user_ID, 'networth');
$networth_def = get_user_meta($defender_ID, 'networth');


// NW Check between attacker & Defender

/*
if (($networth_def[0] > $networth_att[0] /1.4 && $networth_def[0] < $networth_att[0] * 1.4)) {
} else {
    echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=9";
	
		</script>';
    ;
    exit;
}*/
$oldmorale = get_user_meta($user_ID, 'morale');

if ($oldmorale[0] < 5) {
    echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=2";
	
		</script>';
    exit;
} else {
    update_user_meta($user_ID, 'morale', $oldmorale[0] - 5);
}



get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
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
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> and were <strong>killed</strong></div>


<?php } else {
	
	$winner_id = $user_ID;}
?>
		
<center><h2>S U C C E S S</h2>
<?php
	

	
	?>
<div class="notice_message">Your <?php
if ($no_thiefs == 1) {
    echo 'thief';
} else {
    echo 'thieves';
}
?> entered the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->nickname; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong> <?php if($money_stolen > 0){echo'and stole $ '.number_format($money_stolen, 0, ',', ' ');}else{ echo 'but there was no money to steal';} ?></strong>
</div>
			
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
<?php 
/*
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
			
		*/	
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>