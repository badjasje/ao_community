<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */


include 'DO_NOT_DELETE.php';
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
$networth_att = get_user_meta($user_ID, 'networth');
$turns = get_user_meta($user_ID, 'turns');
$networth_def = get_user_meta($defender_ID, 'networth');

$sat_morale = get_user_meta($user_ID, 'sat_morale',true);

/* check satellite morale */

if (100 > $sat_morale) {
	echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=20";
	
		</script>';
        ;exit;	
}


$defender_clan_ID = get_user_meta($defender_ID, 'clan_id_user',true);
$attacker_clan_ID = get_user_meta($user_ID, 'clan_id_user',true);
$calculate_points = 0;

/* check if target isn't dead, else redirect */
$target_status = get_user_meta($defender_ID,'status',true);
if($target_status == 'dead'){
	wp_redirect(get_permalink(3360).'?fail=8');
	exit;
}

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



$result = 'success';



if($mutual == 2){
	$one_sided = 0;
}




// NW Check between attacker & Defender

if($mutual != 2){
if (($networth_def[0] > $networth_att[0]/1.4 && $networth_def[0] < $networth_att[0]*1.4)){
	
	
}else{
echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=9";
	
		</script>';
        ;exit;	
}}





    

        $blddamage = 5000;
  
    
    // KILLING BUILDINGS OF DEFENDER //
    $_total_bld_def = 0;
    foreach ($buildings as $key => $building) {
        $def_bld_owned = get_user_meta($defender_ID, $key);
        $_total_bld_def += $def_bld_owned[0];
    }
    
    
    foreach ($buildings as $key => $building) {
        
        
        //bld		
        $def_bld_owned = get_user_meta($defender_ID, $key);
        $def_bld_owned = $def_bld_owned[0];
        
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
        echo '<script type="text/javascript">
			
		
		window.location.href = "/attack/step-1/?fail=8";
	
		</script>';
        exit;
    }

    
  
  


$def_unitslost = $defender_lost;
$att_unitslost = 0;

include('units_array.php');
include('building_array.php');
?>

	<article id="post-<?php
the_ID();
?>" <?php
post_class();
?>>
		

		<div class="entry-content">

		
				
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

 		$land_stolen  = 0;
        $money_stolen = 0;




$killed = false;
if ($def_lostbuildings_tot >= $_total_bld_def) {
    $killed = true;
    update_user_meta($defender_ID, 'status', 'dead');
    update_user_meta($defender_ID, 'networth', 0);
    update_user_meta($defender_ID, 'land', 0);
    after_death($defender_ID);
}else{
/// UDPDATE DEFENDER NETWORTH
$def_NW_lost += $land_stolen * 0.85;
$defender_Networth = get_user_meta($defender_ID, 'networth');
update_user_meta($defender_ID, 'networth', $defender_Networth[0] - ceil($def_NW_lost));}







////// CALCULATE CLAN POINTS //////
$clan_points = 0;
$unit_points = 0;

$att_clan_ID = get_user_meta($user_ID, 'clan_id_user');
$old_CP = get_post_meta($att_clan_ID[0],'clan_points');




if($calculate_points == 1 && $result = 'success'){
			
	$def_total_units = 0;
			
			
		if($def_total_units != 0 && $def_lostunits_tot != 0){
			$unit_points = $def_lostunits_tot/$def_total_units;
			}
			
		$defender_Networth = get_user_meta($defender_ID, 'networth');
	
			
			
				
			
			
			if ($killed != true) {
				$clan_points = ceil(9+($def_NW_lost/$defender_Networth[0]*170));
					if($clan_points > 25){
						$clan_points = 25;
						}
				
				
		
			if($one_sided == 1 ){
				$clan_points = ceil($clan_points/2);
			}
			
			}
			
			
			
		

			/* check if defender is killed */
			if ($killed == true) { 
				/* killed in mutual? */
				if($mutual == 2){
					$clan_points = 50;
				}
				if($one_sided == 1){
				/* one sided kill? */
					$clan_points = 25;	
				}
			}
			
			update_post_meta($att_clan_ID[0],'clan_points',$old_CP[0]+$clan_points);
	}
?>		
				
		
				
				
<?php if($result = 'success'){ ?>
	
	<center>
		<h2>S U C C E S S</h2>
			<p>Your satellite hit the base of <strong>
			<a href="/users/profile/?id=<?php echo $defender_ID;?>">
				<?php $playername = get_userdata($defender_ID);
					echo $playername->nickname;
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
					
					
					
					
<table>
	<tbody>
	<tr>
		<th colspan="3" class="report_header">
			<center>Battle Report</center>
		</th>
	</tr>
				
	<tr>
		<td><strong>No money stolen</strong></td>
		<td><strong>No land stolen</strong></td>
		<td>Clan Points Gained: <?php echo $clan_points;?></td>
	</tr>
				
				
	<tr>
		<th colspan="3"><center>Enemy networth decreased: 
			<strong>$ <?php echo number_format($def_NW_lost, 0, ',', ' '); ?></strong></center>
		</th>
	</tr>
				
	<tr>
		<td>
			<strong>No units lost</strong>
		</td>
					
		<td>
			<strong>No units killed</strong><br/>
		</td>
				
		<td>
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
					<h2>F A I L U R E</h2>
					<p>Your missile missed the base of <a href="/users/profile/?id=<?php
    echo $defender_ID;
	 $winner_ID = $defender_ID;
?>"><strong><?php
    $playername = get_userdata($defender_ID);
    echo $playername->nickname;
    echo ' (#' . $_SESSION['target_id'] . ')';
?></strong></a></p></center>
			<?php }?>	
			
				




			
			
			<?php 



////// CREATE EVENT POST ////////////
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
				'post_title'    => 'Attack made by '.$user_ID.' Defender: '.$defender_ID,
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
			
			update_user_meta($user_ID,'turns',$turns[0]-3);
			
			update_user_meta($user_ID,'sat_morale',0);
			
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events')[0]+1);
?>
			
			
		
		
		</div><!-- .entry-content -->
		<footer class="entry-meta">
			
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
