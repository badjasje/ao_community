<?php
 /*
 * Template Name: Satellite result
 */
include 'DO_NOT_DELETE.php';
include('attack_functions.php');
include 'units_array.php';
include 'constants.php';

$attacking_units = $_POST;
$defender_ID     = $_SESSION['target_id'];

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
$winner_ID = $user_ID;

$turns = get_user_meta($user_ID, 'turns',true);

/* check if user has enough turns */
if($turns < 3{ 
	
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
	}




/* check satellite morale */

$sat_morale = get_user_meta($user_ID, 'sat_morale',true);
if (100 > $sat_morale) {
	
	$_SESSION['status'] = 'Insufficient satellite power';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;

}

/* check if target is alive */

$target_status = get_user_meta($defender_ID,'status',true);
if($target_status == 'dead'){
	$_SESSION['status'] = 'This player is dead';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}






$defender_clan_ID = get_user_meta($defender_ID, 'clan_id_user',true);
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user',true);



$war_type = get_war_type($attacker_clan_ID, $defender_clan_ID);
$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);



/* check if target in range */

$attack_type = 'satellite';
$in_range = target_in_range($attack_type, $networth_att, $networth_def, $war_type);

/* validate target in range */
if (!$in_range) {
	w$_SESSION['status'] = 'Out of networth range';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
}


$blddamage = rand (6500,8000);
update_user_meta($user_ID,'sat_morale',$sat_morale-100);
$result = 'success';

$sat_status = get_user_meta($defender_ID, 'stealth_sat_status',true);

if($sat_status == 'active'){
	$result = 'failure';
	$blddamage = 0;
}



    // KILLING BUILDINGS OF DEFENDER //
    $_total_bld_def = 0;
    foreach ($buildings as $key => $building) {
        $def_bld_owned = get_user_meta($defender_ID, $key,true);
        $_total_bld_def += $def_bld_owned;
    }
    
    
foreach ($buildings as $key => $building) {
        
    
	//bld		
	$def_bld_owned = get_user_meta($defender_ID, $key,true);

        
	if ($def_bld_owned > 0) {
		
		$percentage = $def_bld_owned / $_total_bld_def;
            
        $damage = $blddamage * $percentage;
        
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
    // WRAPPING MISSILE UP //
    $land_stolen = 0;
    $money_stolen = 0;
    $attacker_lost = 0;
   
   

    
    // CHECK IF PLAYER IS DEAD
    if ($_total_bld_def <= 0) {
        update_user_meta($defender_ID, 'status', 'dead');
        update_user_meta($defender_ID, 'networth', 0);
        
        $_SESSION['status'] = 'This player is dead';
		wp_redirect(get_permalink(3360).'?id='.$defender_ID);
		exit;

    }

    
  
  


$def_unitslost = $defender_lost;
$att_unitslost = 0;

include('units_array.php');
include('building_array.php');
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
		
				
<?php
$def_NW_lost           = 0;
$att_NW_lost           = 0;
$def_lostunits_tot     = 0;
$def_lostbuildings_tot = 0;



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

 		


$killed = false;
if ($def_lostbuildings_tot >= $_total_bld_def) {
    $killed = true;
    update_user_meta($defender_ID, 'status', 'dead');
    update_user_meta($defender_ID, 'networth', 0);
    update_user_meta($defender_ID, 'land', 0);
    after_death($defender_ID);
}




////// CALCULATE CLAN POINTS //////


$clan_points_old_att = get_post_meta($attacker_clan_ID,'clan_points',true);

/* calculate clan points */
$clan_points = 0;
$unit_points = 0;

if($war_type != 'none' && $result == 'success') {
	

	if ($killed != true) {

		$clan_points = 7.8 * log($def_NW_lost/1.4 / 400); 
		
		if($clan_points < 1){
			$clan_points = 1;
		}
		$clan_points = ceil($clan_points);
		/* points cap */
		if($clan_points > $POINTS_CAP) {
			$clan_points = $POINTS_CAP;
		}
		
		if($war_type == 'incoming') {
			$clan_points = ceil($clan_points/2);
			
		}
		
	}
	
	/* determine points multiplier due to war */
	

	if ($killed == true) {
		/* add stats */
		// attacker
		
		$kills_made = get_user_meta($user_ID, 'kills_made', true);
		update_user_meta($user_ID, 'kills_made', $kills_made+1);
		
		// defender
		
		$times_killed = get_user_meta($defender_ID, 'times_killed', true);
		update_user_meta($defender_ID, 'times_killed', $times_killed+1);
		
		if($war_type == 'mutual') {
			$clan_points = 50;
		}
		elseif($war_type == 'incoming') {
			$clan_points = 25;
		}
		elseif($war_type == 'outgoing') {
			$clan_points = 25;
		}
	}
}



	/* add points */
	$starting_points = get_post_meta($attacker_clan_ID,'clan_points',true);
	update_post_meta($attacker_clan_ID,'clan_points',$starting_points+$clan_points);
	/* add attacks for UA */
	$starting_attacks = get_post_meta($attacker_clan_ID,'ua_total',true);
	update_post_meta($attacker_clan_ID,'ua_total',$starting_attacks+1);
	
	/* 24H pts update */
	$_pts = get_post_meta($attacker_clan_ID, '24h_pts', true);
	update_post_meta($attacker_clan_ID,'24h_pts',$_pts+$clan_points);

?>		
				
		

<?php 
	
	if($result == 'success'){ ?>

<?php $winner_ID = $user_ID;?>
	
<center>
	<h2>S U C C E S S</h2>
		
		<p>Your satellite hit the base of 
		<strong>
		<a href="/users/profile/?id=<?php echo $defender_ID;?>"><?php $playername = get_userdata($defender_ID);
			echo $playername->display_name;
			echo ' (#' . $_SESSION['target_id'] . ')';
				?>
		</a>
		</strong>
		
		
		<?php if ($killed == true) {echo ' and killed this player';} echo '</p>';

		$builtland = 0;
		$winner_ID = $user_ID;
			
			foreach ($buildings as $key => $building) {
				$ownedbuildings = get_user_meta($defender_ID, $key,true);
				if ($ownedbuildings > 0) {
				$builtland += $ownedbuildings * 20;
    			}
			}

			update_user_meta($defender_ID, 'builtland', ceil($builtland));?>
					
					
					
					
<table class="responsive-table">
	<tbody>
	<tr>
		<th colspan="3" class="report_header">
			<center>Battle Report</center>
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
			<strong>No units killed</strong><br/>
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
					
					
<?php if($result == 'failure'){ ?>
<center>
					<h2>F A I L U R E</h2>
					<p>Your satellite missed the base of <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->display_name;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	
			
				




			
			
			<?php 

$old_CP = get_user_meta($user_ID, 'user_clan_points', true);
update_user_meta($user_ID, 'user_clan_points', $old_CP+$clan_points);

////// CREATE EVENT POST ////////////
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
				'post_title'    => 'Satellite attack made by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );
			update_field('defender_lost', $def_unitslost, $new_event_id);
			update_field('attacker_lost', $att_unitslost, $new_event_id);
			update_field('land_lost', $land_stolen, $new_event_id);
			update_field('money_lost', $money_stolen, $new_event_id);
			update_field('time_attacked',$timestamp, $new_event_id);
			update_field('total_buildings_lost',$def_lostbuildings_tot, $new_event_id);
			
			update_field('nw_damage_defender',$def_NW_lost, $new_event_id);
	
			
			update_field('clan_points', $clan_points, $new_event_id);
			
			
			
			update_field('defender_id',$defender_ID, $new_event_id);
			update_field('winner_id',$winner_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype',$_SESSION['attacktype'], $new_event_id);
			update_field('outcome',$result, $new_event_id);
			if($killed == true){
			update_field('status_defender','death', $new_event_id);
			update_field('attacktype','death', $new_event_id);
			}
			
			update_field('defender_clan_id',$defender_clan_ID, $new_event_id);
			update_field('attacker_clan_id',$attacker_clan_ID, $new_event_id);
			
			update_user_meta($user_ID,'turns',$turns-3);
			
			
			
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events',true)+1);
			/* Add globals to defender */

$clan = get_user_meta($defender_ID, 'clan_id_user', true);
$clan_members = get_post_meta($clan,'clan_members');

if(!empty($clan) || $clan != 0){
foreach ($clan_members[0] as $member) {
	$globals = get_user_meta($member, 'new_global_events', true);
	update_user_meta($member, 'new_global_events', $globals+1);
}}
count_all_stats($defender_ID);
count_all_stats($user_ID);
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>