<?php
 /*
 * Template Name: Missile Result
 */
get_header();
include 'DO_NOT_DELETE.php';

$attacking_units 	= 		$_POST;
$defender_ID     	= 		$_SESSION['target_id'];
$target_id 			= 		$_SESSION['target_id'];
$user_ID = get_current_user_id();

$silos = get_user_meta($user_ID, 'silo', true);

if($silos <= 0){
	$_SESSION['status'] = 'Not enough missile silos.';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}




$userLock = get_user_meta($user_ID, 'user_lock', true);

if($userLock == 1){
	wp_redirect(get_permalink(3360).'?id='.$target_id);
}
update_user_meta($user_ID, 'user_lock', 1);

$shotdown	= 	false;
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

/* AMS-Satellite */

$defSat = get_user_meta($defender_ID, 'sat_owned', true);
$satMorale = get_user_meta($defender_ID, 'sat_morale', true);

if($satMorale >= 20 && $power < 100){
	if($defSat == 'amssat'){
		$shotdown = true;
		update_user_meta($defender_ID, 'sat_morale', $satMorale-20);
	}
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


$networth_att = get_user_meta($user_ID, 'networth',true);
$turns = get_user_meta($user_ID, 'turns',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);


$defender_clan_ID = get_user_meta($defender_ID, 'clan_id_user',true);
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$missile_research = get_user_meta($user_ID, 'level_missile_accuracy', true);

$calculate_points = 0;


 ?>
<div class="page normal-page">
     <div class="container containerNZ">
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

if($missile_research >= 2){
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

$silo1Status = get_user_meta($user_ID, 'silo_disable_1', true);
$silo2Status = get_user_meta($user_ID, 'silo_disable_2', true);
$disabled = false;

if($silo1Status == 'active' || $silo2Status == 'active'){
	$shotdown = false;
	$result = 'failure';
	$disabled = true;
}

/* calculate attack power and divide power */
$attackpower   = $missiles[$key]['attack']*0.87;
$divided_power = $attackpower / count($missiles[$key]['attacks']);
        
        
        $attacks = $missiles[$key]['attacks'];
        foreach ($attacks as $attack) {
            if ($attack == 'sea') {
                $SEA_ATT_power += $divided_power * (rand(9, 11) / 10);
            }
            if ($attack == 'air') {
                $AIR_ATT_power += $divided_power * (rand(9, 11) / 10);
            }
            if ($attack == 'inf') {
                $INF_ATT_power += $divided_power * (rand(9, 11) / 10);
            }
            if ($attack == 'veh') {
                $VEH_ATT_power += $divided_power * (rand(9, 11) / 10);
            }
            if ($attack == 'bld') {
                $BLD_ATT_power += $divided_power * (rand(9, 11) / 10);
            }
        }
        
    
    
    
    $airdamage = $AIR_ATT_power;
    $infdamage = $INF_ATT_power;
    $vehdamage = $VEH_ATT_power;
    $seadamage = $SEA_ATT_power;
    $blddamage = $BLD_ATT_power;
    // DEFENDING //
    $_total_air_units_def = 0;
    $_total_inf_units_def = 0;
    $_total_veh_units_def = 0;
    $_total_sea_units_def = 0;
    
   
	
    if($result == 'success'){
    foreach ($units as $key => $order) {
        
        $units_defending = get_user_meta($defender_ID, $key . '_owned',true);
    
 
        
        $unittype = $units[$key]['type'];
        if ($unittype == 'sea') {
            
            $_total_sea_units_def += $units_defending;
        }
        if ($unittype == 'air') {
            
            $_total_air_units_def += $units_defending;
        }
        if ($unittype == 'inf') {
            
            $_total_inf_units_def += $units_defending;
        }
        if ($unittype == 'veh') {
            
            $_total_veh_units_def += $units_defending;
        }}

    /// MISSILES KILLING DEFENDER UNITS ///
    $TOTAL_ATT_DAMAGE = 0;
    $defender_lost    = array();
    foreach ($units as $key => $order) {
        
        
        $unittype = $units[$key]['type'];
        
        //AIR
        if ($unittype == 'air') {
            $def_units_owned = get_user_meta($defender_ID, $key . '_owned',true);
 
            if ($def_units_owned > 0) {
                $percentage = $def_units_owned / $_total_air_units_def;
                $damage     = $airdamage * $percentage;
                $TOTAL_ATT_DAMAGE += $damage;
                $units_lost = round($damage / $units[$key]['life']);
                if ($units_lost > 0) {
                    if ($def_units_owned < $units_lost) {
                        update_user_meta($defender_ID, $key . '_owned', 0);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $def_units_owned
                        );
                    } else {
                        update_user_meta($defender_ID, $key . '_owned', $def_units_owned - $units_lost);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $units_lost
                        );
                    }
                }
            }
        }
        
        //INF
        if ($unittype == 'inf') {
            $def_units_owned = get_user_meta($defender_ID, $key . '_owned',true);
        
            if ($def_units_owned > 0) {
                $percentage = $def_units_owned / $_total_inf_units_def;
                $damage     = $infdamage * $percentage;
                $TOTAL_ATT_DAMAGE += $damage;
                $units_lost = round($damage / $units[$key]['life']);
                if ($units_lost > 0) {
                    if ($def_units_owned < $units_lost) {
                        update_user_meta($defender_ID, $key . '_owned', 0);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $def_units_owned
                        );
                    } else {
                        update_user_meta($defender_ID, $key . '_owned', $def_units_owned - $units_lost);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $units_lost
                        );
                    }
                }
            }
        }
        //VEH
        if ($unittype == 'veh') {
            $def_units_owned = get_user_meta($defender_ID, $key . '_owned',true);
           
            if ($def_units_owned > 0) {
                $percentage = $def_units_owned / $_total_veh_units_def;
                $damage     = $vehdamage * $percentage;
                $TOTAL_ATT_DAMAGE += $damage;
                $units_lost = round($damage / $units[$key]['life']);
                
                if ($units_lost > 0) {
                    if ($def_units_owned < $units_lost) {
                        update_user_meta($defender_ID, $key . '_owned', 0);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $def_units_owned
                        );
                    } else {
                        update_user_meta($defender_ID, $key . '_owned', $def_units_owned - $units_lost);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $units_lost
                        );
                    }
                }
            }
        }
        //SEA
        if ($unittype == 'sea') {
            $def_units_owned = get_user_meta($defender_ID, $key . '_owned',true);
           
            if ($def_units_owned > 0) {
                $percentage = $def_units_owned / $_total_sea_units_def;
                $damage     = $seadamage * $percentage;
                $TOTAL_ATT_DAMAGE += $damage;
                $units_lost = round($damage / $units[$key]['life']);
                
                if ($units_lost > 0) {
                    if ($def_units_owned < $units_lost) {
                        update_user_meta($defender_ID, $key . '_owned', 0);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $def_units_owned
                        );
                    } else {
                        update_user_meta($defender_ID, $key . '_owned', $def_units_owned - $units_lost);
                        $defender_lost[] = array(
                            'type' => 'unit',
                            $key => $units_lost
                        );
                    }
                }
            }
        }
    }
    
    
// KILLING BUILDINGS OF DEFENDER //
    
/* calculate total number of buildings by defender */
$_total_bld_def = 0;

foreach ($buildings as $key => $building) {

	$def_bld_owned = get_user_meta($defender_ID, $key,true);
	$_total_bld_def += $def_bld_owned;

}
    
    
foreach ($buildings as $key => $building) {
	
	/* get building by type */
	$def_bld_owned = get_user_meta($defender_ID, $key,true);
     
    /* check if defender owns building */  
    if ($def_bld_owned > 0) {
    	
    	$percentage = $def_bld_owned / $_total_bld_def;
            
        $damage = $blddamage * $percentage;
        $TOTAL_ATT_DAMAGE += $damage;
        
        $buildings_lost = round($damage / $building['life']);
            
            if ($buildings_lost > 0) {
                if ($def_bld_owned < $buildings_lost) {
                    update_user_meta($defender_ID, $key, 0);
                    $defender_lost[] = array(
                        'type' => 'bld',
                        $key => $def_bld_owned
                    );
                } else {
                    update_user_meta($defender_ID, $key, $def_bld_owned - $buildings_lost);
                    $defender_lost[] = array(
                        'type' => 'bld',
                        $key => $buildings_lost
                    );
                }
            }
        }
        
        
        }
        
    }
    // WRAPPING MISSILE UP //
    $land_stolen = 0;
    $money_stolen = 0;
    $attacker_lost = 0;
   
   

    
    // CHECK IF PLAYER IS DEAD
    /*
    if($result == 'success'){
    if ($_total_bld_def <= 0) {
        update_user_meta($defender_ID, 'status', 'dead');
        update_user_meta($defender_ID, 'networth', 0);
        echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=8";
	
		</script>';
        exit;
    }}
	*/
    
  
  
    

   









$def_unitslost = $defender_lost;
$att_unitslost = 0;

include('units_array.php');
include('building_array.php');
?>

<article id="post-<?php the_ID();?>" <?php post_class(); ?>>
	<div class="entry-content">

		
<?php
	
$def_NW_lost           = 0;
$att_NW_lost           = 0;
$def_lostunits_tot     = 0;
$def_lostbuildings_tot = 0;

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
	
	
foreach ($units as $unitkey => $order) {
    foreach ($def_unitslost as $key => $def_unitlost) {
        if (isset($def_unitlost[$unitkey])) {
            if ($def_unitlost['type'] == 'unit') {
                
                
                $def_unitlost = array_values($def_unitlost);
                $def_unitlost = $def_unitlost[1];
                
                $def_lostunits_tot += $def_unitlost;
                $def_NW_lost += $def_unitlost * $units[$unitkey]['price'] * ($units[$unitkey]['networth'] / 100);
                
            }
        }
        
    }
}

}


if($result == 'success'){

foreach ($buildings as $buildingkey => $order) {
    foreach ($def_unitslost as $key => $def_bld_lost) {
        if (isset($def_bld_lost[$buildingkey])) {
            if ($def_bld_lost['type'] == 'bld') {
                
                
                $def_bld_lost = array_values($def_bld_lost);
                $def_bld_lost = $def_bld_lost[1];
                
                
                $def_lostbuildings_tot += $def_bld_lost;
                $def_NW_lost += $def_bld_lost * $buildings[$buildingkey]['price'] * ($buildings[$buildingkey]['networth'] / 100);
            }
        }
        
    }
}
}
 		
 		
 		
 		$land_stolen  = 0;
        $money_stolen = 0;




$killed = false;
if($result == 'success'){
if ($def_lostbuildings_tot >= $_total_bld_def) {
    $killed = true;
    update_user_meta($defender_ID, 'status', 'dead');
    update_user_meta($defender_ID, 'networth', 0);
    update_user_meta($defender_ID, 'land', 0);
    after_death($defender_ID);
	}
}







////// CALCULATE CLAN POINTS //////
$clan_points = 0;
$unit_points = 0;

$att_clan_ID = get_user_meta($user_ID, 'clan_id_user');
$old_CP = get_post_meta($att_clan_ID[0],'clan_points');




if($calculate_points == 1 && $result == 'success'){
			
	$def_total_units = $_total_air_units_def+$_total_inf_units_def+$_total_veh_units_def+$_total_sea_units_def;
			
			
		if($def_total_units != 0 && $def_lostunits_tot != 0){
			$unit_points = $def_lostunits_tot/$def_total_units;
			}
			
		$defender_Networth = get_user_meta($defender_ID, 'networth');
	
			
			
				
if ($killed != true) {


    /* MEGA logic to make nuke NW account also for province NW, reducing it's reward at very low Networth.
        The division on NW lost will increase the difference between low and high nw done in terms of pts. HIGHER division = more range
        The division on the defender NW will decrease the overall points which nukes offer. HIGHER division = less pts */

    $clan_points = ceil(25*(((log(sqrt($def_NW_lost)/1.8)) * (sqrt($defender_Networth[0])/3.1))/1024));


    if($clan_points > 25){
		$clan_points = 25;
	}
}

//MEGA changed block to stop 1-sided also awarding 50p 20180215 -->

if ($killed == true) { 

    if ($one_sided == 1) {
        $clan_points = 25;
    }
    else {
        $clan_points = 50;
    }

}	
// End MEGA 20180215
			
if($clan_points < 1){
	$clan_points = 1;
}		

			/* check if defender is killed */
			if ($killed == true) { 
				/* add stats */
					// attacker
		
					$kills_made = get_user_meta($user_ID, 'kills_made', true);
					update_user_meta($user_ID, 'kills_made', $kills_made+1);
		
					// defender
					
					$times_killed = get_user_meta($target_id, 'times_killed', true);
					update_user_meta($target_id, 'times_killed', $times_killed+1);
				
				
				
				/* killed in mutual? */
				if($mutual == 2) {
					$clan_points = 50;
				}
				if($one_sided == 1){
				/* one sided kill? */
				echo "DAVE";
					$clan_points = 25;	
				}
			}
			if($def_NW_lost == 0){ $clan_points == 0;}
			update_post_meta($att_clan_ID[0],'clan_points',$old_CP[0]+$clan_points);
		
			/* 24H pts update */
			$_pts = get_post_meta($att_clan_ID[0], '24h_pts', true);
			update_post_meta($att_clan_ID[0],'24h_pts',$_pts+$clan_points);
	}
?>		
				

				
				
<?php if($result == 'success'){ ?>

<?php
	/* add stats */
	// attacker
	$nw_damage_missiles = get_user_meta($user_ID, 'nw_damage_missiles', true);
	update_user_meta($user_ID, 'nw_damage_missiles', $nw_damage_missiles+$def_NW_lost);
	
	
	//defender
	$nw_damage_missiles_rec = get_user_meta($target_id, 'nw_damage_missiles_rec', true);
	update_user_meta($target_id, 'nw_damage_missiles_rec', $nw_damage_missiles_rec+$def_NW_lost);
	
	?>
	
	<center>
		<h2>S U C C E S S</h2>
			<p class="battleMessage">Your missile hit the base of <strong>
			<a href="/users/profile/?id=<?php echo $defender_ID;?>">
				<?php $playername = get_userdata($defender_ID);
					echo $playername->display_name;
					echo ' (#' . $_SESSION['target_id'] . ')';
				?></a></strong>
		<?php if ($killed == true) {echo ' and killed this player';} echo '</p>';

		$builtland = 0;
		$winner_ID = $user_ID;
			
			foreach ($buildings as $key => $building) {
				$ownedbuildings = get_user_meta($defender_ID, $key);
				if ($ownedbuildings[0] > 0) {
				$builtland += $ownedbuildings[0] * 20;
    			}
			}

			update_user_meta($defender_ID, 'builtland', ceil($builtland));?>
					
					
					
					
<table class="responsive-table">
	<tbody>
	<tr>
		<th colspan="3" class="report_header">
			<center>Missile Report</center>
		</th>
	</tr>
				
	<tr>
		<td class="report_content"><strong>No money stolen</strong></td>
		<td class="report_content"><strong>No land stolen</strong></td>
		<td class="report_content">Clan Points Gained: <?php echo $clan_points;?></td>
	</tr>
				
				
	<tr>
		<th class="report_content" colspan="3"><center>Enemy networth decreased: 
			<strong>$ <?php echo number_format($def_NW_lost, 0, ',', ' '); ?></strong></center>
		</th>
	</tr>
				
	<tr>
		<td class="report_content">
			<strong>No units lost</strong>
		</td>
					
		<td class="report_content">
			<strong>Units Killed: 
			<?php echo $def_lostunits_tot; ?></strong><br/>
				<?php foreach ($units as $key => $order) {
					
					foreach ($def_unitslost as $def_unitlost) {
						if (isset($def_unitlost[$key])) {
							echo $order['normalname'] . ': ' . $def_unitlost[$key] . '<br/>';
        				}
					}
				}?>
		</td>
				
		<td class="report_content">
			<strong>Buildings destroyed: 
			<?php echo $def_lostbuildings_tot;?></strong><br/>
				<?php foreach ($buildings as $key => $order) {
					
					foreach ($def_unitslost as $def_unitlost) {
						if (isset($def_unitlost[$key])) {
						if ($def_unitlost['type'] == 'bld') {
							echo $order['normalname'] . ': ' . $def_unitlost[$key] . '<br/>';
            			}
					}
    			}}?>
		</td>
	</tr>
	</tbody>
</table>				
					
					
					
					
					
					
					
	<?php }?>		
					
					
<?php if($result == 'failure' && $shotdown != true && $disabled != true){ ?>
					<center><h2>F A I L U R E</h2>
					<p class="battleMessage">Your missile missed the base of <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->display_name;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	
			
<?php if($result == 'failure' && $shotdown == true && $disabled != true){ ?>
					<center><h2>F A I L U R E</h2>
					<p class="battleMessage">Your missile was shot down by <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->display_name;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	

<?php if($result == 'failure' && $shotdown == false && $disabled == true){ ?>
	<center>
		<h2>F A I L U R E</h2>
		<p class="battleMessage">Your missile silo was sabotaged. You lost your missile and your missile silo.</p>
	</center>
<?php 
	
	update_user_meta($user_ID, 'silo', $silos-1);
	
	if($silo1Status == 'active'){
		update_user_meta($user_ID, 'silo_disable_1', 'inactive');
	}else{
		update_user_meta($user_ID, 'silo_disable_2', 'inactive');
	}
	
	}?>	
				




			
			
			<?php 



////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
				'post_title'    => 'Missile launched by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );
			




			update_field('time_attacked',$timestamp, $new_event_id);
			
			update_field('nw_damage_defender',$def_NW_lost, $new_event_id);
			update_field('missile_type',$missile_type, $new_event_id);
			
			if($result == 'success'){
			update_field('defender_lost', $def_unitslost, $new_event_id);
			update_field('total_buildings_lost',$def_lostbuildings_tot, $new_event_id);
			update_field('def_total_units_lost',$def_lostunits_tot, $new_event_id);
			update_field('clan_points', $clan_points, $new_event_id);
			}
			if($disabled == false){
				update_field('defender_id',$defender_ID, $new_event_id);
			}
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype',$_SESSION['attacktype'], $new_event_id);
			update_field('outcome',$result, $new_event_id);

			if($shotdown == true){
			update_field('shotdown','shotdown', $new_event_id);
			}
			
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			if($killed == true){
			kill_event($user_ID,$defender_ID,$result,$defender_clan_ID,$attacker_clan_ID);
			update_field('status_defender','death', $new_event_id);
			update_field('attacktype','missile', $new_event_id);
			
			
			}
			
			update_user_meta($user_ID,'turns',$turns-3);
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events')[0]+1);
			
			$user_pts = get_user_meta($user_ID, 'user_clan_points',true);
			update_user_meta($user_ID,'user_clan_points',$user_pts+$clan_points);
			
			// Update attacker points for current clan
			$userAttPts = get_user_meta($user_ID, 'current_clan_points',true);
			update_user_meta($user_ID, 'current_clan_points', $userAttPts+$clan_points);
			
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


$warcheck = get_posts(
	array(
		'numberposts'	=> -1,
		'post_type'		=> 'wars',
		'meta_query'	=> array(
			'relation'		=> 'AND',
			array(
				'key'	 	=> 'declared_on',
				'value'	  	=> array($clan_att,$defender_clan_ID),
				'compare' 	=> 'IN',
			),
			array(
				'key'	 	=> 'declared_by',
				'value'	  	=> array($clan_att,$defender_clan_ID),
				'compare' 	=> 'IN',
			),
		),
	)
);

$warstatID = get_post_meta($warcheck[0]->ID, 'war_array_id', true);


// Update war stats array for defender clan
$war_array_def = maybe_unserialize(get_post_meta($defender_clan_ID, 'war_array', true));

if(!is_array($war_array_def)){
	$war_array_def = array();
}

$war_array_def[$warstatID]['nw_dmg_rec'] += $def_NW_lost;
$war_array_def[$warstatID]['missiles_received'] += 1;
if($result == 'success'){
	$war_array_def[$warstatID]['missiles_hit_def'] += 1;
}

if($killed == true){
	$war_array_def[$warstatID]['deaths'] += 1;
}

$war_array_def[$warstatID]['bds_lost'] += $def_lostbuildings_tot;
$war_array_def[$warstatID]['units_lost'] += $def_lostunits_tot;

update_post_meta($defender_clan_ID, 'war_array', maybe_serialize($war_array_def));



// Update war stats array for attacker clan
$war_array_att = maybe_unserialize(get_post_meta($attacker_clan_ID, 'war_array', true));

if(!is_array($war_array_att)){
	$war_array_att = array();
}

$war_array_att[$warstatID]['nw_dmg_done'] += $def_NW_lost;
$war_array_att[$warstatID]['clan_points'] += $clan_points;
$war_array_att[$warstatID]['missiles_sent'] += 1;
if($result == 'success'){
	$war_array_att[$warstatID]['missiles_hit_att'] += 1;
}
$war_array_att[$warstatID]['bds_killed'] += $def_lostbuildings_tot;
$war_array_att[$warstatID]['units_killed'] += $def_lostunits_tot;

if($killed == true){
	$war_array_def[$warstatID]['kills'] += 1;
}

update_post_meta($attacker_clan_ID, 'war_array', maybe_serialize($war_array_att));



count_all_stats($target_id);
count_all_stats($user_ID);
update_user_meta($user_ID, 'user_lock', 0);
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>