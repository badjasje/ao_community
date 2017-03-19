<?php
 /*
 * Template Name: Spy Result
 */
$defender_ID = $_SESSION['target_id'];
$succes = (rand(80,100));
$sat_status = get_user_meta($defender_ID, 'stealth_sat_status',true);
if($sat_status == 'active'){
	$succes = 100;
}
$clan_defender_id = get_user_meta($defender_ID, 'clan_id_user', true);
$spytype = $_SESSION['attack_array']['sendspy'];
	$turns = get_user_meta($user_ID, 'turns');
	update_user_meta($user_ID,'turns',$turns[0]-1);
	
$sat_status = get_user_meta($defender_ID, 'stealth_sat_status',true);
if($sat_status == 'active'){
	$success = 100;
	
}
	
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
       
<?php if($_SESSION['attack_array']['sendspy'] == 'spy'):?>
	<?php if($succes != 100):
		include('units_array.php');?>
		<?php $winner_id = $user_ID;?>
			<center><h2>S U C C E S S</h2></center>
			<div class="notice_message">
			Your spy entered the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> </strong>
			</div><br/>
			<center>
		

		<p><a class="btn btn-general" href="<?php echo get_the_permalink($clan_defender_id);?>">View clan</a> <a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">Spy report overview for clan</a></p></center>
			
			
				
			<?php 
			$spy_array = array();	
			foreach ($units as $key => $unit) {
			$owned_units = get_user_meta($defender_ID, $key.'_owned');
			$spy_array[$unit['normalname']] = $owned_units[0];}
			?>
			<table class="responsive-table">
				<thead>
				<th scope="col">Name</th>
				<th scope="col">Owned</th>
				</thead>
				<tbody>
			<?php foreach ($spy_array as $unit => $amount) {
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
			?>
			<?php if($amount > 49):?>
			<tr>
				<td data-title="Name"><?php echo $unit;?></td>
				<td data-title="Owned"><?php echo $displayamount;?></td>	
			</tr>
			<?php endif;?>
			<?php }?>
			<tr>
				<td><strong>Other units</strong></td>
				<td>0-49</td>	
			</tr>
			</tbody>
			</table>
		
			
			<?php $args = array(	
				'post_title'    => 'Spy report by '.$user_ID.' Defender: '.$defender_ID.' '.$spytype,
				'post_status'   => 'publish',
				'post_type'		=> 'spy_rep',
				'post_author'   => $user_ID
				);
				
			
			$new_event_id = wp_insert_post( $args );
			$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
			update_field('spied_id', $defender_ID, $new_event_id);
			update_field('clan_id_report', $clan_ID, $new_event_id);
			update_field('spy_type', 'spy', $new_event_id);
			update_field('spy_array', $spy_array, $new_event_id);
			
			$land_def = get_user_meta($defender_ID, 'land', true);
			$networth_def = get_user_meta($defender_ID, 'networth', true);
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
			$spies = get_user_meta($user_ID, 'spy_owned', true);
			update_user_meta($user_ID, 'spy_owned', $spies-1);
			?>
				
			<?php endif;?><?php endif;?>
			
			
			
			
			
			
			
			<?php if($_SESSION['attack_array']['sendspy'] == 'spyplane'):?>
			<?php if($succes != 100): include('building_array.php');?>
			<?php $winner_id = $user_ID;?>
			<center><h2>S U C C E S S</h2>
			<div class="notice_message">
			Your spyplane flew over the base of <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> </strong>
			</div><br/>
			<center>
		

		<p><a class="btn btn-general" href="<?php echo get_the_permalink($clan_defender_id);?>">View clan</a> <a class="btn btn-general" href="/spy-report-overview/?id=<?php echo $clan_defender_id;?>">Spy report overview for clan</a></p></center>
			
			<?php 
			$spy_array = array();		
			foreach ($buildings as $key => $unit) {
			$owned_units = get_user_meta($defender_ID, $key);
			$spy_array[$unit['normalname']] = $owned_units[0];
			}
			?>
			<table class="responsive-table">
				<thead>
				<th scope="col">Name</th>
				<th scope="col">Owned</th>
				</thead>
				<tbody>
			<?php foreach ($spy_array as $building => $amount) {
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
			?>
			<?php if($amount > 49):?>
			<tr>
				<td data-title="Name"><?php echo $building;?></td>
				<td data-title="Owned"><?php echo $displayamount;?></td>	
			</tr>
			<?php endif;?>
			<?php }?>
			<tr>
				<td><strong>Other buildings</strong></td>
				<td>0-49</td>	
			</tr>
			</tbody>
			</table>
			
		
<?php 
	
/* Create spy report */	
$args = array(	
	'post_title'    => 'Spy report by '.$user_ID.' Defender: '.$defender_ID.' '.$spytype,
	'post_status'   => 'publish',
	'post_type'		=> 'spy_rep',
	'post_author'   => $user_ID
);


$new_event_id = wp_insert_post( $args );
$clan_ID = get_user_meta($user_ID, 'clan_id_user', true);
update_field('spied_id', $defender_ID, $new_event_id);
update_field('clan_id_report', $clan_ID, $new_event_id);
update_field('spy_type', 'spyplane', $new_event_id);
update_field('spy_array', $spy_array, $new_event_id);
$land_def = get_user_meta($defender_ID, 'land', true);
$networth_def = get_user_meta($defender_ID, 'networth', true);
update_field('spied_land', $land_def, $new_event_id);
update_field('spied_nw', $networth_def, $new_event_id);

?>
			
			
			<?php else:?>
			<?php $winner_id = $defender_ID;?>
			<center><h2>F A I L U R E</h2>
			<p>Your spyplane was shot down by <a href="/users/profile/?id=<?php
echo $defender_ID;
?>"><strong><?php $playername = get_userdata($defender_ID); echo $playername->display_name; echo ' (#' . $_SESSION['target_id'] . ')</a>';
?> <strong>
			</center><br/>
			<?php /* update spies defender */
			$spyplane = get_user_meta($user_ID, 'spyplane_owned', true);
			update_user_meta($user_ID, 'spyplane_owned', $spyplane-1);
			?>
			<?php endif;?><?php endif;?>
       
<?php 
	
/* Create Spy event post */
$timestamp = strtotime(date('Y-m-d H:i:s'));
$args = array(	
	'post_title'    => 'Spy attempt by '.$user_ID.' Defender: '.$defender_ID.' '.$spytype,
	'post_status'   => 'publish',
	'post_type'		=> 'event_local',
	'post_author'   => $user_ID
);
			
$new_event_id = wp_insert_post( $args );


update_field('time_attacked',$timestamp, $new_event_id);


update_field('defender_id',$defender_ID, $new_event_id);
update_field('attacker_id',$user_ID, $new_event_id);

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

$event_count = get_user_meta($defender_ID, 'new_events',true);
update_user_meta($defender_ID, 'new_events', $event_count + 1);

?>



       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
       
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>