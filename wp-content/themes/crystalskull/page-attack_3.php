<?php
 /*
 * Template Name: Attack step 3
 */
include 'DO_NOT_DELETE.php';
$units_attack = $_SESSION['attack_array'];

$attackUserId = $_SESSION['target_id'];

get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
		<?php if(get_field('game_status','option') != 'Live'):?>
<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
<?php else:?>

        <?php if (!empty($attackUserId)) : ?>
            <h3 class="centered">Attacking <?php echo LinkUtil::user_link($attackUserId); ?></h3>
        <?php endif; ?>
			
		<?php  //// UNIT ATTACK, A&S, REG & GROUND
			
			
			if($_SESSION['attacktype'] != 'missile' && $_SESSION['attacktype'] != 'thief' && $_SESSION['attacktype'] != 'satellite' && $_SESSION['attacktype'] != 'spy' && $_SESSION['attacktype'] != 'sniper'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/result/" name="" id="attack" method="post">	
		
		<table class="responsive-table">
			<thead>
				<tr>
					<th scope="col"><strong>Name</strong></th>
					<th scope="col"><strong>Sending to battle</strong></th>
					<th scope="col"><strong>Percentage</strong></th>
  				</tr>
			</thead>
			<tbody>
		<?php foreach($units_attack as $key => $order){
			
			$units_owned = get_user_meta($user_ID, $key.'_owned');
			
			if($order > 0){
			if($order >= $units_owned[0]){
				$percentage = 1;
			}else{
				$percentage = $order/$units_owned[0];
			}
			?><tr>
			<td data-title="Name">
				<?php echo $units[$key]['normalname'];?>
			</td>
			<td data-title="Sending to battle">
				<?php echo $units_owned[0]*$percentage;?>
			</td>
			<td data-title="Percentage">
				<input type="hidden" name="<?php echo $key;?>" value="<?php echo $percentage;?>">
				<?php echo round($percentage*100,0).'%';$_SESSION[$key]['percentage'] = $percentage;?>
			</td>
			
			
		
			
			
			
			</tr>
		<?php }} ?>
			</tbody>
		</table>
		<input style="width:100%" type="submit" value="ATTACK" class="">
		<div class="footer_continue">
			<input style="width:100%" type="submit" value="ATTACK" class="">
		</div>
		</form>
		<?php endif;?>
		
		
		<?php ///// LAUNCHING MISSILE
			
			
			if($_SESSION['attacktype'] == 'missile'): ?>
		<form class="form" action="<?php echo home_url() ?>/attack/missile-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Launching</strong></td>
				
  					</tr>
  				<?php
			$key = $_SESSION['attack_array']['missile'];
			$units_owned = get_user_meta($user_ID, $key.'_owned',true);
			
			
			
			?><tr>
			<td>
				<?php echo $missiles[$key]['normalname'];?>
			</td>
			<td>
				1
			</td>
			
			
		
			
			
			
			</tr>
		
		</table>
		<input style="width:100%" type="submit" value="ATTACK" class="">
		</form>
		<?php endif;?>
		
		
		
		
		
		<?php ///// FIRING SATELLITE
			
			
			if($_SESSION['attacktype'] == 'satellite'): 
			include 'satellite_array.php';
			$sat_owned = get_user_meta($user_ID, 'sat_owned',true);
			?>
			
			
			
		<form class="form" action="<?php echo home_url() ?>/attack/satellite-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Firing</strong></td>
				
  					</tr>
  			<tr>
				<td>
					<?php echo $satellites[$sat_owned]['name'];?>
				</td>
			<td>
				1
			</td>
			
			
		
			
			
			
			</tr>
		
		</table>
		<input style="width:100%" type="submit" value="FIRE" class="">
		</form>
		<?php endif;?>
		
		
		
		
		
		<?php ////// THIEFING
			
			
			if($_SESSION['attacktype'] == 'thief'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/thief-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Sending to battle</strong></td>
				
  					</tr>
		<?php foreach($units_attack as $key => $order){
			
			$units_owned = get_user_meta($user_ID, $key.'_owned');
			
			if($order > 0){
			if($order >= $units_owned[0]){
				$percentage = 1;
			}else{
				$percentage = $order/$units_owned[0];
			}
			?><tr>
			<td>
				<?php echo $units[$key]['normalname'];?>
			</td>
			<td>
				<?php echo $units_owned[0]*$percentage;?>
			</td>
			
			
		
			
			
			
			</tr>
		<?php }} ?>
		</table>
		<input style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		
		<?php ////// SNIPING
			
			
			if($_SESSION['attacktype'] == 'sniper'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/sniper-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Sending to battle</strong></td>
				
  					</tr>
		<?php foreach($units_attack as $key => $order){
			
			$units_owned = get_user_meta($user_ID, $key.'_owned');
			
			if($order > 0){
			if($order >= $units_owned[0]){
				$percentage = 1;
			}else{
				$percentage = $order/$units_owned[0];
			}
			?><tr>
			<td>
				<?php echo $units[$key]['normalname'];?>
			</td>
			<td>
				<?php echo $units_owned[0]*$percentage;?>
			</td>
			
			
		
			
			
			
			</tr>
		<?php }} ?>
		</table>
		<input style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		
		
		<?php ////// SPYING
			
			
			if($_SESSION['attacktype'] == 'spy'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/spy-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Send to enemy base</strong></td>
				
  					</tr>
		<?php foreach($units_attack as $key => $order){
			
			$units_owned = get_user_meta($user_ID, $order.'_owned');
		
			
			?><tr>
			<td>
				<?php echo $units[$order]['normalname'];?>
			</td>
			<td>
				1
			</td>
			
			
		
			
			
			
			</tr>
		<?php } ?>
		</table>
		<input style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		<?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>