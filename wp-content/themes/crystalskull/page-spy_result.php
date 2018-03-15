<?php
 /*
 * Template Name: Spy Result
 */
get_header();
$defender_ID = $_SESSION['target_id'];
$userId = get_current_user_id();

$success = (rand(30,100));

$userData = get_user_meta($userId);
$defenderData = get_user_meta($defender_ID);

$sat_status = $defenderData['stealth_sat_status'][0];

if($sat_status == 'active'){
	$success = 100;
}

$clan_defender_id = $defenderData['clan_id_user'][0];
$clan_ID = $userData['clan_id_user'][0];
$timestamp = current_time('timestamp');

$spytype = $_SESSION['attack_array']['sendspy'];
$turns = $userData['turns'][0];
$spies = $userData['spy_owned'][0];
$spyplanes = $userData['spyplane_owned'][0];



/* check if user has enough spies or spy planes */
if($spies < 1 && $_SESSION['attack_array']['sendspy'] == 'spy'){ 
	
	$_SESSION['status'] = 'Not enough spies';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
	}	
	
if($spyplanes < 1 && $_SESSION['attack_array']['sendspy'] == 'spyplane'){ 
	
	$_SESSION['status'] = 'Not enough spy planes';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
	}
	
	
	
/* check if user has enough turns */
if($turns < 1){ 
	
	$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(3360).'?id='.$defender_ID);
	exit;
	}	

	update_user_meta($userId,'turns',$turns-1);
	

$snipers = $defenderData['snipers_owned'][0];
$land_def = $defenderData['land'][0];
$networth_def = $defenderData['networth'][0];

$success = $success+$snipers*0.25;

if($sat_status == 'active'){
	$success = 100;
}

$members = get_post_meta($clan_ID,'clan_members',true);
			
			
			/* enhancing spy */
			$enhanceSpy = 0;
			
			$args = array(
			'posts_per_page'   => -1,
			'author__in'	=> $members,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'spied_id',
						'value'	  	=> $defender_ID,
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'spy_type',
						'value'	  	=> 'spy',
						'compare' 	=> '=',
						),
						
					
					),
			'post_type'        => 'spy_rep',
			);
			$reports = get_posts( $args ); 		
		
			foreach ($reports as $report) {
				
			
			$posttime = strtotime($report->post_date);
		
			if($posttime-$timestamp+900 > 0){
			
				$enhanceSpy+=1;
				}
			
			}
			if($enhanceSpy > 3){
				$enhanceSpy = 3;
			}
			
			
			
			
			/* enhancing plane */
			$enhancePlane = 0;
			
			$args = array(
			'posts_per_page'   => -1,
			'author__in'	=> $members,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'meta_query'	=> array(
				'relation'		=> 'AND',
					array(
						'key'	 	=> 'spied_id',
						'value'	  	=> $defender_ID,
						'compare' 	=> '=',
						),
					array(
						'key'	 	=> 'spy_type',
						'value'	  	=> 'spyplane',
						'compare' 	=> '=',
						),
						
					
					),
			'post_type'        => 'spy_rep',
			);
			$reports = get_posts( $args ); 		
		
			foreach ($reports as $report) {
				
			
			$posttime = strtotime($report->post_date);
			
			if($posttime-$timestamp+900 > 0){
			
				$enhancePlane+=1;
				}
			
			}
			if($enhancePlane > 3){
				$enhancePlane = 3;
			}
			




			
			
	
 ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
<?php if($_SESSION['attack_array']['sendspy'] == 'spy'):?>
	<?php if($success <= 90):
		include('units_array.php');?>
		<?php $winner_id = $userId;?>
			<center><h2>S U C C E S S</h2></center>
			<div class="notice_message">
			<span class="rdw-line">Your spy entered the base of 
				<a href="/users/profile/?id=<?php echo $defender_ID;?>">
				<strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>'; ?> </strong> 
				<?php if($enhanceSpy < 3):?>
					Spy report enhanced <?php echo $enhanceSpy;?> times.
				<?php else:?>
					Spy report fully enhanced.
				<?php endif;?>
			</span>
			<?php if($enhanceSpy < 3):?>
				<span class="rdw-line">Re-spy this target within 15 minutes to enhance spy reports</span>
			<?php endif;?>
			</div><br/>
			<center>
		

		<p><a class="btn btn-general" href="<?php echo get_the_permalink($clan_defender_id);?>">View clan</a> <a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">Spy report overview for clan</a></p></center>
			
			
				
		<?php 
			
			
			$amountArray = array();	
			$spy_array = array();
			foreach ($units as $key => $unit) {
			$owned_units = $defenderData[$key.'_owned'][0];
			$amountArray[$unit['normalname']] = $owned_units;}
			?>
			<table class="responsive-table">
				<thead>
				<th scope="col">Name</th>
				<th scope="col">Owned</th>
				</thead>
				<tbody>
			<?php 
				foreach ($amountArray as $unit => $amount) {
				
				
				if($amount>0){
					
				$rangeDamp = 1 - sqrt(($amount)*1.4)/100;
				if($rangeDamp < 0){
					$rangeDamp = 0.2;
				}
				
		
				$displayamount = max(round($amount/(1+(mt_rand(20*$rangeDamp, 30*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(36*$rangeDamp, 72*$rangeDamp)/100)))));
				
				if($enhanceSpy == 1){
					$displayamount = max(round($amount/(1+(mt_rand(10*$rangeDamp, 20*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(12*$rangeDamp, 36*$rangeDamp)/100)))));
				}
				if($enhanceSpy == 2){
					$displayamount = max(round($amount/(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)))));
				}
				if($enhanceSpy == 3){
					$displayamount = max(round($amount/(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)))));
				}
				$spy_array[$unit] = $displayamount;
				/*
				if($amount >= 50 && $amount < 100){$displayamount = '50-99';}
				if($amount >= 100 && $amount < 250){$displayamount = '100-249';}
				if($amount >= 250 && $amount < 500){$displayamount = '250-499';}
				if($amount >= 500 && $amount < 1000){$displayamount = '500-999';}
				if($amount >= 1000 && $amount < 2000){$displayamount = '1000-1999';}
				if($amount >= 2000 && $amount < 3000){$displayamount = '2000-2999';}
				if($amount >= 3000 && $amount < 5000){$displayamount = '3000-4999';}
				if($amount >= 5000 && $amount < 7500){$displayamount = '5000-7499';}
				if($amount >= 7500 && $amount < 10000){$displayamount = '7500-9999';}
				if($amount >= 10000 && $amount < 15000){$displayamount = '10000-14999';}
				if($amount >= 15000 && $amount < 20000){$displayamount = '15000-19999';}
				if($amount >= 20000 && $amount < 25000){$displayamount = '20000-24999';}
				if($amount >= 25000 && $amount < 30000){$displayamount = '25000-29999';}
				*/
			
			?>
		
			<tr>
				<td data-title="Name"><?php echo $unit;?></td>
				<td data-title="Owned"><?php echo $displayamount;?></td>	
			</tr>
			
			<?php 
				$spy_array['enhance'] = $enhanceSpy;
				
				}}?>
			
			</tbody>
			</table>
		
			
			<?php $args = array(	
				'post_title'    => 'Spy report by '.$userId.' Defender: '.$defender_ID.' '.$spytype,
				'post_status'   => 'publish',
				'post_type'		=> 'spy_rep',
				'post_author'   => $userId
				);
				
			
			$new_event_id = wp_insert_post( $args );
			
			update_field('spied_id', $defender_ID, $new_event_id);
			update_field('clan_id_report', $clan_ID, $new_event_id);
			update_field('spy_type', 'spy', $new_event_id);
			update_field('spy_array', $spy_array, $new_event_id);
			
			
			update_field('spied_land', $land_def, $new_event_id);
			update_field('spied_nw', $networth_def, $new_event_id);
			
			
			?>
			<?php else:?>
			<?php $winner_id = $defender_ID;?>
			<center><h2>F A I L U R E</h2></center>
			<div class="notice_message">Your spy was caught and killed by <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</div><br/>
			<?php /* update spies defender */
			$spies = $userData['spy_owned'][0];
			update_user_meta($userId, 'spy_owned', $spies-1);
			?>
				
			<?php endif;?><?php endif;?>
			
			
			
			
			
			
			
			<?php if($_SESSION['attack_array']['sendspy'] == 'spyplane'):?>
			<?php if($success <= 90): include('building_array.php');?>
			<?php $winner_id = $userId;?>
			<center><h2>S U C C E S S</h2>
			<div class="notice_message">
			Your spyplane flew over the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> </strong>
			<?php if($enhancePlane < 3):?>
					Spy report enhanced <?php echo $enhancePlane;?> times.
				<?php else:?>
					Spy report fully enhanced.
				<?php endif;?>
			</span>
			<?php if($enhancePlane < 3):?>
				<span class="rdw-line">Re-spy this target within 15 minutes to enhance spy reports</span>
			<?php endif;?>

			</div><br/>
			<center>
		

		<p><a class="btn btn-general" href="<?php echo get_the_permalink($clan_defender_id);?>">View clan</a> <a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">Spy report overview for clan</a></p></center>
		
		
		<?php 
			
			
			$amountArray = array();	
			$spy_array = array();
			foreach ($buildings as $key => $unit) {
			$owned_units = $defenderData[$key][0];
			$amountArray[$unit['normalname']] = $owned_units;}
			?>
			<table class="responsive-table">
				<thead>
				<th scope="col">Name</th>
				<th scope="col">Owned</th>
				</thead>
				<tbody>
			<?php foreach ($amountArray as $unit => $amount) {
				
				
				if($amount>0){
				
				$rangeDamp = 1 - sqrt(($amount)*2.3)/100;
				if($rangeDamp < 0){
					$rangeDamp = 0.2;
				}
				
				$displayamount = max(round($amount/(1+(mt_rand(36*$rangeDamp, 72*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(36, 72)/100)))));
				
				if($enhancePlane == 1){
					$displayamount = max(round($amount/(1+(mt_rand(12*$rangeDamp, 36*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(12, 36)/100)))));
				}
				if($enhancePlane == 2){
					$displayamount = max(round($amount/(1+(mt_rand(6*$rangeDamp, 12*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(6, 12)/100)))));
				}
				if($enhancePlane == 3){
					$displayamount = max(round($amount/(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)),-1),0) . ' - ' . (ceil(($amount*(1+(mt_rand(3*$rangeDamp, 6*$rangeDamp)/100)))));
				}
				$spy_array[$unit] = $displayamount;
				/*
				if($amount >= 50 && $amount < 100){$displayamount = '50-99';}
				if($amount >= 100 && $amount < 250){$displayamount = '100-249';}
				if($amount >= 250 && $amount < 500){$displayamount = '250-499';}
				if($amount >= 500 && $amount < 1000){$displayamount = '500-999';}
				if($amount >= 1000 && $amount < 2000){$displayamount = '1000-1999';}
				if($amount >= 2000 && $amount < 3000){$displayamount = '2000-2999';}
				if($amount >= 3000 && $amount < 5000){$displayamount = '3000-4999';}
				if($amount >= 5000 && $amount < 7500){$displayamount = '5000-7499';}
				if($amount >= 7500 && $amount < 10000){$displayamount = '7500-9999';}
				if($amount >= 10000 && $amount < 15000){$displayamount = '10000-14999';}
				if($amount >= 15000 && $amount < 20000){$displayamount = '15000-19999';}
				if($amount >= 20000 && $amount < 25000){$displayamount = '20000-24999';}
				if($amount >= 25000 && $amount < 30000){$displayamount = '25000-29999';}
				*/
			
			?>
		
			<tr>
				<td data-title="Name"><?php echo $unit;?></td>
				<td data-title="Owned"><?php echo $displayamount;?></td>	
			</tr>
			
			<?php 
				$spy_array['enhance'] = $enhancePlane;
				
				}}?>
			
			</tbody>
			</table>
		
		
		
		
		
		
		
		
		
		
			
		
<?php 
	
/* Create spy report */	
$args = array(	
	'post_title'    => 'Spy report by '.$userId.' Defender: '.$defender_ID.' '.$spytype,
	'post_status'   => 'publish',
	'post_type'		=> 'spy_rep',
	'post_author'   => $userId
);


$new_event_id = wp_insert_post( $args );

update_field('spied_id', $defender_ID, $new_event_id);
update_field('clan_id_report', $clan_ID, $new_event_id);
update_field('spy_type', 'spyplane', $new_event_id);
update_field('spy_array', $spy_array, $new_event_id);
update_field('spied_land', $land_def, $new_event_id);
update_field('spied_nw', $networth_def, $new_event_id);

?>
			
			
			<?php else:?>
			<?php $winner_id = $defender_ID;?>
			<center><h2>F A I L U R E</h2>
			<p class="battleMessage">Your spyplane was shot down by <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			<?php /* update spies defender */
			$spyplane = $userData['spyplane_owned'][0];
			update_user_meta($userId, 'spyplane_owned', $spyplane-1);
			?>
			<?php endif;?><?php endif;?>
       
<?php 
	
/* Create Spy event post */

$args = array(	
	'post_title'    => 'Spy attempt by '.$userId.' Defender: '.$defender_ID.' '.$spytype,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $userId
);
			
$new_event_id = wp_insert_post( $args );


update_field('time_attacked',$timestamp, $new_event_id);


update_field('defender_id',$defender_ID, $new_event_id);
update_field('attacker_id',$userId, $new_event_id);

update_field('event_spy_type',$spytype, $new_event_id);


update_field('att_total_units_lost',1, $new_event_id);

update_field('winner_id',$winner_id, $new_event_id);
update_field('attacktype','spy', $new_event_id);

$sender_show = (rand(1,100));
$show = 'no';
if($sender_show > 80){
	$show = 'yes';
}
update_field('show_spy_sender',$show, $new_event_id);

$event_count = $defenderData['new_events'][0];
update_user_meta($defender_ID, 'new_events', $event_count + 1);

$spied = $userData['spied_current_clan'][0];
update_user_meta($userId, 'spied_current_clan', $spied+1);


?>       
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>