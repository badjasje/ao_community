<?php 

/*
 * Template Name: Thief result
 * V1.0 kevin sometime
 * V2.0 haywardd/mega 20170302
 * V2.1 haywardd/mega 20170322 fixed single thief money being too high
 */
include('constants.php');

$defender_ID = $_SESSION['target_id'];
$user_ID     = get_current_user_ID();
$no_thiefs   = $_SESSION['attack_array']['thief'];

$defender_money = get_user_meta($defender_ID, 'money',true);
$attacker_money = get_user_meta($user_ID, 'money',true);

$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);

$turns = get_user_meta($user_ID,'turns',true);
$tot_thiefs = get_user_meta($user_ID, 'thief_owned',true);
$tot_snipers = get_user_meta($defender_ID, 'sniper_owned',true);
$level = get_user_meta($user_ID, 'level_thieving_effectiveness',true);


//$no_thiefs = $_GET['thiefs'];
//$tot_snipers = $_GET['snipers'];
//$level = $_GET['level'];
//$defender_money = $_GET['defender_money'];

$cashMultiplier = 0;

// Various validations
/* Check if attacker has enough thiefs */
if($no_thiefs > $tot_thiefs){
	
	wp_redirect(get_permalink(3360).'?fail=16');
    exit;
	
}

/* Check if attacker has enough turns */
if($turns < 2){
	
	wp_redirect(get_permalink(3360).'?fail=1');
    exit;
	
}


$thief_level = get_user_meta($user_ID, 'level_thieving_effectiveness',true);

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


if($sat_status == 'active'){
	$result = 'failure';
}

$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);



/* Check if in range */

if (($networth_def > $networth_att /1.4 && $networth_def < $networth_att * 1.4)) {
} else {
    wp_redirect(get_permalink(3360).'?fail=9');
    exit;
}

/* Check if attacker has sufficient morale */

$oldmorale = get_user_meta($user_ID, 'morale',true);

if ($oldmorale < 5) {
	
	wp_redirect(get_permalink(3360).'?fail=2');
    exit;

} 



$success = do_thief($level, $no_thiefs, $tot_snipers, $defender_money);

$sat_status = get_user_meta($defender_ID, 'stealth_sat_status',true);
if($sat_status == 'active'){
	$success = 0;
	
}


get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
<?php

if ($success > 0) {
	//Nick money
	$money_stolen = floor($defender_money*$success);
	update_user_meta($user_ID, 'morale', $oldmorale - 5);
	
	/* FOLLOWS THE PAGE ITSELF */
	$winner_id = $user_ID;
	echo "<center><h2>S U C C E S S</h2>";
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
	if ($money_stolen > $defender_money) {
		update_user_meta($defender_ID, 'money', $defender_money);
	} else {
		update_user_meta($defender_ID, 'money', $defender_money - $money_stolen);
	}
	update_user_meta($user_ID, 'money', $attacker_money + $money_stolen);

	
	
}
else {
	//Failure 
	echo "FAILURE!";
	$money_stolen = 0;
	update_user_meta($user_ID, 'morale', $oldmorale - 5);

	
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


	<?php 	
	
}

//These happen regardless of success or failure
$thieving_attempts = get_user_meta($user_ID, 'thieving_attempts', true);
update_user_meta($user_ID, 'thieving_attempts', $thieving_attempts+1);

$attempts_received = get_user_meta($defender_ID, 'attempts_received', true);
update_user_meta($defender_ID, 'attempts_received', $attempts_received+1);

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


update_user_meta($user_ID,'turns',$turns-$TURNS_THIEF);
update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events',true)+1);


if($success == 0){
	update_field('thiefs_lost',$no_thiefs, $new_event_id);
}
	
	?>	
	 </div>
        </div>
    </div>
</div>
<?php get_footer();


//Actual calculation function follows	

function do_thief($level, $thieves, $snipers, $defender_money) {
	
	//Set thief_multiplier based on number sent. The higher the multiplier, the lower the success chance but higher the money stolen
	
	//Sets some variables based on research level 
	switch ($level) {
		case 1:
		  if (!(isset($thief_multiplier))) {
			  $thief_multiplier=9;
		  }
		  //Set the number that must be higher than caughtChance in order for thieving to work
		  $randMax = mt_rand(0,100);
		  //Sets the damage an individual sniper does to the thieves
<<<<<<< HEAD
		  $snipersHit = ($snipers*0.59)*(mt_rand(70,130)/100);
		  $cashMultiplier = ((mt_rand(1,5)*($thieves/$thief_multiplier))/100);
=======
		  $snipersHit = ($snipers*0.39)*(mt_rand(70,130)/100);
		  $cashMultiplier = ((mt_rand(1,10)*($thieves/$thief_multiplier))/100);
>>>>>>> 33b99ddcba13cf74abd86ef352743ed8a0521d4f
		  
		break;
		case 2:
		  if (!(isset($thief_multiplier))) {
			  $thief_multiplier=7;
		  }
		  //Set the number that must be higher than caughtChance in order for thieving to work
		  $randMax = mt_rand(10,100);		
		  //Sets the damage an individual sniper does to the thieves
<<<<<<< HEAD
		  $snipersHit = ($snipers*0.59)*(mt_rand(70,130)/100);		
		  $cashMultiplier = ((mt_rand(2,7)*($thieves/$thief_multiplier))/100);  
=======
		  $snipersHit = ($snipers*0.39)*(mt_rand(70,130)/100);		
		  $cashMultiplier = ((mt_rand(2,13)*($thieves/$thief_multiplier))/100);  
>>>>>>> 33b99ddcba13cf74abd86ef352743ed8a0521d4f
		break;		
		case 3:
		  if (!(isset($thief_multiplier))) {
			  $thief_multiplier=5;

		  }
		  //Set the number that must be higher than caughtChance in order for thieving to work
		  $randMax = mt_rand(20,100);	
		  //Sets the damage an individual sniper does to the thieves
<<<<<<< HEAD
		  $snipersHit = ($snipers*0.59)*(mt_rand(70,130)/100);		 
		  $cashMultiplier = ((mt_rand(4,9)*($thieves/$thief_multiplier))/100);
=======
		  $snipersHit = ($snipers*0.39)*(mt_rand(70,130)/100);		 
		  $cashMultiplier = ((mt_rand(3,13)*($thieves/$thief_multiplier))/100);
>>>>>>> 33b99ddcba13cf74abd86ef352743ed8a0521d4f
		break;
		default:
		  if (!(isset($thief_multiplier))) {
			  $thief_multiplier=3;
		  }
		  //Set the number that must be higher than caughtChance in order for thieving to work
		  $randMax = mt_rand(40,100);		
		  //Sets the damage an individual sniper does to the thieves
<<<<<<< HEAD
		  $snipersHit = ($snipers*0.59)*(mt_rand(70,130)/100);	
		  $cashMultiplier = ((mt_rand(5,9)*($thieves/$thief_multiplier))/100);	  
=======
		  $snipersHit = ($snipers*0.39)*(mt_rand(70,130)/100);	
		  $cashMultiplier = ((mt_rand(5,13)*($thieves/$thief_multiplier))/100);	  
>>>>>>> 33b99ddcba13cf74abd86ef352743ed8a0521d4f
		break;
	}
	
	if ($thieves == 1) {
		$dice = 1+$snipersHit;
	}
	else {
		$dice = ($thieves * $thief_multiplier) + $snipersHit;
	}
	/* Debug stuff.. uncomment to enable 
	
	echo "successNo:".$randMax."<br/>";
	echo "thieves:".$thieves."<br/>";
	echo "snipers:".$snipers."<br/>";
	echo "thief_multipler:".$thief_multiplier."<br/>";
	echo "snipersHit:".$snipersHit."<br/>";
	echo "dice:".$dice."<br/>";
	echo "thiefChance:".(100-$dice)."<br/>";
	echo "cashMultiplier:".$cashMultiplier."<br/>";
	*/
	
	
	if ($randMax > $dice) {
		//Winner
		return $cashMultiplier;
	}
	else {
		//LOSER
		return 0;
	}
}


?>