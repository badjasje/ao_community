<?php
 /*
 * Template Name: Sniper result
 */
include('constants.php');
include 'units_array.php';

$defender_ID = $_SESSION['target_id'];

$target_id = $defender_ID;
$user_ID     = get_current_user_ID();

$attSnipers = get_user_meta($user_ID, 'sniper_owned', true);

$no_snipers = $attSnipers*$_SESSION['sniper']['percentage'];

if($no_snipers > $attSnipers){
	$no_snipers = $attSnipers;
}





/* Check if attacker has enough turns */
$turns = get_user_meta($user_ID,'turns',true);
if($turns < 2){
	
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(3360).'?id='.$target_id);
	exit;
}


/* Check if attacker has enough morale */
$oldmorale = get_user_meta($user_ID, 'morale',true);
if ($oldmorale < 10) {
    
    $_SESSION['status'] = 'Insufficient morale';
	wp_redirect(get_permalink(3360).'?id='.$target_id);
	exit;
} 


/* Check if attacker has enough snipers */
$ownedSnipers = get_user_meta($user_ID, 'sniper_owned', true);
if($no_snipers > $ownedSnipers){
	
	$_SESSION['status'] = 'Not enough snipers';
	wp_redirect(get_permalink(3360).'?id='.$target_id);
	exit;
}

/* Check if target is in range */
$networth_att = get_user_meta($user_ID, 'networth',true);
$networth_def = get_user_meta($defender_ID, 'networth',true);

if (($networth_def > $networth_att /1.4 && $networth_def < $networth_att * 1.4)) {} else {
  	$_SESSION['status'] = 'Out of networth range';
	wp_redirect(get_permalink(3360).'?id='.$target_id);
	exit;
}





$defender_thiefs = get_user_meta($defender_ID, 'thief_owned',true);
$defender_spies = get_user_meta($defender_ID, 'spy_owned',true);
$defender_snipers = get_user_meta($defender_ID, 'sniper_owned',true);








//update_user_meta($user_ID,'thief_owned',$tot_thiefs-$no_thiefs);


$result = 'failure';
$winner_id = $defender_ID;
$attackerLost = round($no_snipers*mt_rand(65,85)/100);
$attackResult = 'F A I L U R E';
$tot_sniper_attackpower = $units['sniper']['attack']*$no_snipers/mt_rand(2,3);


$loseRatio = $no_snipers*(1+(mt_rand(95,140)/100))+$defender_snipers/(mt_rand(27,49)/10);

if($loseRatio < 64){
	$result = 'success';
}



if($result == 'success'){
	$winner_id = $user_ID;
	$tot_sniper_attackpower = $units['sniper']['attack']*$no_snipers*(mt_rand(300,900)/100);
	$attackResult = 'S U C C E S S ';
	$attackerLost = round($no_snipers*mt_rand(1,20)/100);
}


$thief_life = $units['thief']['life']*$defender_thiefs;
$spy_life = $units['spy']['life']*$defender_spies;
$sniper_life = ($units['sniper']['life']-50)*$defender_snipers;

$tot_life = $thief_life+$spy_life+$sniper_life;

$spy_damage = $tot_sniper_attackpower*($spy_life/$tot_life);
$thief_damage = $tot_sniper_attackpower*($thief_life/$tot_life);
$sniper_damage = $tot_sniper_attackpower*($sniper_life/$tot_life);

$snipers_lost = min(round($sniper_damage/$units['sniper']['life']),$defender_snipers); 
$thiefs_lost = min(round($thief_damage/$units['thief']['life']),$defender_thiefs);
$spy_lost = min(round($spy_damage/$units['spy']['life']),$defender_spies);

$totalDefLost = $snipers_lost+$thiefs_lost+$spy_lost;


/* determine NW lost */
$attNWlost = $attackerLost*$units['sniper']['price']*0.06;
$defNWlost = ($snipers_lost*$units['sniper']['price']*0.06)+($thiefs_lost*$units['thief']['price']*0.06)+($spy_lost*$units['spy']['price']*0.06);


get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
   
<center>
	<h2><?php echo $attackResult;?></h2>
	<p class="battleMessage">Your sniper<?php if($no_snipers != 1 || 0){echo 's';}?> entered the base of 
		<strong>
			<a href="/users/profile/?id=<?php echo $defender_ID; ?>">
			<?php $playername = get_userdata($defender_ID); 
			echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';?>
		</strong>
		<?php if($totalDefLost == 0){ echo ' but there were no units to kill.';}?>
	</p>

<center>
<table class="responsive-table">
	<tbody>
	<tr>
		<th colspan="3" class="report_header"><center>Sniper Report</center></th>
	</tr>
	<tr>
		<td class="report_content">Money Stolen: <strong>$ 0</strong></td>
		<td class="report_content">Land Stolen: <strong>0 m<sup>2</sup></strong></td>
		<td class="report_content">No clan points gained</td>
	</tr>
	<tr>
		<th class="report_content" colspan="3"><center>Your networth decreased: <strong>$ <?php echo number_format($attNWlost, 0, ',', ' ');?></strong></center>
		
	</th></tr>
	<tr>
		<th class="report_content" colspan="3"><center>Enemy networth decreased: <strong>$ <?php echo number_format($defNWlost, 0, ',', ' ');?></strong></center>
		
	</th></tr>
	<tr>
		<td class="report_content"><strong>Units Lost: <?php echo $attackerLost;?></strong><br/>
			<?php if($attackerLost > 0){ echo 'Snipers: '.$attackerLost; }?>
		</td>		
		<td class="report_content">
			<strong>Special units killed: <?php echo $totalDefLost;?></strong>
			<?php if($totalDefLost>0){ echo '<br/>'; }?>
				<?php if($snipers_lost > 0){ echo 'Snipers: '.$snipers_lost.'<br/>'; }?>
				<?php if($thiefs_lost > 0){ echo 'Thiefs: '.$thiefs_lost.'<br/>'; }?>
				<?php if($spy_lost > 0){ echo 'Spies: '.$spy_lost.'<br/>'; }?>
		</td>
			
		<td class="report_content"><strong>Buildings destroyed: 0</strong></td>
	</tr>
	</tbody>
</table>

<a class="btn btn-general" href="/attack/result/"><i class="fa fa-refresh" aria-hidden="true"></i> STRIKE AGAIN</a>

<script> 
  jQuery("a").click(function (event) {
    if (jQuery(this).hasClass("disabled")) {
        event.preventDefault();
    }
    jQuery(this).addClass("disabled");
});
</script>

            
            </center>
			
		</div><!-- .entry-content -->
	
	</article><!-- #post -->
<?php 
	$def_unitslost = array();
	$att_unitslost = array();
	
	/* create defender units lost array */
	if($snipers_lost > 0){
		$def_unitslost[] = array(
			'type' => 'unit',
			'sniper' => $snipers_lost
		);
	}
	
	if($thiefs_lost > 0){
		$def_unitslost[] = array(
			'type' => 'unit',
			'sniper' => $thiefs_lost
		);
	}
	
	if($spy_lost > 0){
		$def_unitslost[] = array(
			'type' => 'unit',
			'spy' => $spy_lost
		);
	}
	
	/* create attacker units lost array */
	if($attackerLost > 0){
	$att_unitslost[] = array(
			'type' => 'unit',
			'sniper' => $attackerLost
		);
		}
	
/* update defender units */
update_user_meta($defender_ID, 'thief_owned', $defender_thiefs-$thiefs_lost);
update_user_meta($defender_ID, 'sniper_owned', $defender_snipers-$snipers_lost);
update_user_meta($defender_ID, 'spy_owned', $defender_spies-$spy_lost);

/* update attacker units */
update_user_meta($user_ID, 'sniper_owned', $attSnipers-$attackerLost);
	

////// CREATE EVENT POST ////////////
$timestamp = current_time('timestamp');
$args = array(	
				'post_title'    => 'Snipers sent by '.$user_ID.' Defender: '.$defender_ID,
				'post_status'   => 'publish',
				'post_type'		=> 'event_local',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );

			update_field('defender_lost', $def_unitslost, $new_event_id);
			update_field('attacker_lost', $att_unitslost, $new_event_id);
			
			update_field('time_attacked',$timestamp, $new_event_id);
			update_field('defender_id',$defender_ID, $new_event_id);
			update_field('attacker_id',$user_ID, $new_event_id);
			update_field('attacktype','sniper', $new_event_id);
			update_field('winner_id',$winner_id, $new_event_id);
			
			update_field('def_total_units_lost', $totalDefLost , $new_event_id);
			update_field('att_total_units_lost',$attackerLost, $new_event_id);
			
			update_field('nw_damage_defender', $defNWlost , $new_event_id);
			update_field('nw_damage_attacker', $attNWlost , $new_event_id);
			
			
			update_user_meta($user_ID,'turns',$turns-2);
			update_user_meta($user_ID, 'morale', $oldmorale - 10);
			update_user_meta($defender_ID, 'new_events', get_user_meta($defender_ID, 'new_events',true)+1);
			
			/* update defender land and trigger event */
			$event_count = get_user_meta($target_id, 'new_events',true);
			update_user_meta($target_id, 'new_events', $event_count + 1);
			
			
			count_all_stats($target_id);
			count_all_stats($user_ID);
			
	
?>

            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>