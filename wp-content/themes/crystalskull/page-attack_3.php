<?php
 /*
 * Template Name: Attack step 3
 */
include 'DO_NOT_DELETE.php';
$units_attack = $_SESSION['attack_array'];

$attackUserId = $_SESSION['target_id'];

$attack_type = $_SESSION['attacktype'];
/* Determine attack name for header */
if($attack_type == 'ground'){ $attack_name = 'Ground attack'; }
if($attack_type == 'air_sea'){ $attack_name = 'Air & Sea attack'; }
if($attack_type == 'regular'){ $attack_name = 'Regular attack'; }
if($attack_type == 'missile'){ $attack_name = 'Launching missile'; }
if($attack_type == 'spy'){ $attack_name = 'Spying'; }
if($attack_type == 'thief'){ $attack_name = 'Thieving'; }
if($attack_type == 'satellite'){ $attack_name = 'Using satellite'; }
if($attack_type == 'sniper'){ $attack_name = 'Sending sniper'; }

get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
		<?php if(get_field('game_status','option') != 'Live'):?>
<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
<?php else:?>

        <?php if ( ! empty($attackUserId)) : ?>
		

		
		
		<ul class="target_info media-list">
		<li class="media ">
		<div class="media-left">
		
        <div class="leftAvatar"><?php echo small_avatar($attackUserId,'');?></div>
      
	    </div>
		<div class="media-body">
		<h4 class="media-heading">Attacking <?php echo LinkUtil::user_link($attackUserId); ?></h4>
		<?php echo $attack_name;?>
    	</div>
		</li>
		</ul>
           
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
			
	if($key != 'tomahawk'){
			
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
		<?php }}} ?>
		<?php 
			$tomahawk_owned = get_user_meta($user_ID, 'tomahawk_owned',true);
		
			$order = $_SESSION['attack_array']['tomahawk'];
			
			if($order > 0){
			if($order >= $tomahawk_owned){
				$percentage = 1;
			}else{
				$percentage = $order/$tomahawk_owned;
			}
			?><tr>
			<td data-title="Name">
				Tomahawk
			</td>
			<td data-title="Sending to battle">
				<?php echo $tomahawk_owned*$percentage;?>
			</td>
			<td data-title="Percentage">
				<input type="hidden" name="tomahawk" value="<?php echo $percentage;?>">
				<?php echo round($percentage*100,0).'%';$_SESSION[$key]['percentage'] = $percentage;?>
			</td>
			
			
		<?php } ?>
			
			
			
			</tr>
		
			
			
		
			</tbody>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="ATTACK" class="">
		<div class="footer_continue">
			<input class="submitBtn" style="width:100%" type="submit" value="ATTACK" class="">
		</div>
		</form>
		<?php endif;?>
		
		
		<?php ///// LAUNCHING MISSILE
			
			
			if($_SESSION['attacktype'] == 'missile'): ?>
			<?php
			$key = $_SESSION['attack_array']['missile'];
			$units_owned = get_user_meta($user_ID, $key.'_owned',true);
			
				$url = '/attack/missile-result/';
			if($key == 'empmis'){
				$url = '/attack/emp-missile-result/';
			}
			
			?>
	
		<form class="form" action="<?php echo home_url().$url ?>" name="" id="attack" method="post">	
		
		<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Launching</strong></th>
				
  					</tr>
			</thead>
			<tbody>
  				<tr>
			<td  data-title="Name">
				<?php echo $missiles[$key]['normalname'];?>
			</td>
			<td  data-title="Launching">
				1
			</td>
			
			
		
			
			
			
			</tr>
			</tbody>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="ATTACK" class="">
		</form>
		<?php endif;?>
		
		
		
		
		
		<?php ///// FIRING SATELLITE
			
			
			if($_SESSION['attacktype'] == 'satellite'): 
			include 'satellite_array.php';
			$sat_owned = get_user_meta($user_ID, 'sat_owned',true);
			if($sat_owned == 'laser'){
			$resultURL = home_url().'/attack/satellite-result/';
			}
			if($sat_owned == 'empsat'){
			$resultURL = home_url().'/attack/emp-result/';
			}
			?>
			
			
			
		<form class="form" action="<?php echo $resultURL;?>" name="" id="attack" method="post">	
		
		<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Firing</strong></th>
  					</tr>
			</thead>
			<tbody>
  			<tr>
				<td>
					<?php echo $satellites[$sat_owned]['name'];?>
				</td>
			<td>
				1
			</td>
			
			
		
			
			
			
			</tr>
			</tbody>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="FIRE" class="">
		</form>
		<?php endif;?>
		
		
		
		
		
		<?php ////// THIEFING
			
			
			if($_SESSION['attacktype'] == 'thief'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/thief-result/" name="" id="attack" method="post">	
		
		<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Sending to battle</strong></th>
				
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
			<td  data-title="Name">
				<?php echo $units[$key]['normalname'];?>
			</td>
			<td  data-title="Sending">
				<?php echo $units_owned[0]*$percentage;?>
			</td>
			
			
		
			
			
			
			</tr>
		<?php }} ?>
			</tbody>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		
		<?php ////// SNIPING
			
			
			if($_SESSION['attacktype'] == 'sniper'):?>
		<form  class="form" action="<?php echo home_url() ?>/attack/sniper-result/" name="" id="attack" method="post">	
		
		<table>
					<tr>
						<td><strong>Name</strong></td>
						<td><strong>Sending to battle</strong></td>
						<td><strong>Percentage</strong></td>
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
			
			<td data-title="Percentage">
				<input type="hidden" name="<?php echo $key;?>" value="<?php echo $percentage;?>">
				<?php echo round($percentage*100,0).'%';$_SESSION[$key]['percentage'] = $percentage;?>
			</td>
		
			
			
			
			</tr>
		<?php }} ?>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		
		
		<?php ////// SPYING
			
			
			if($_SESSION['attacktype'] == 'spy'):?>
		<form class="form" action="<?php echo home_url() ?>/attack/spy-result/" name="" id="attack" method="post">	
		
		<table class="responsive-table">
			<thead>
					<tr>
						<th scope="col"><strong>Name</strong></th>
						<th scope="col"><strong>Send to enemy base</strong></th>
				
  					</tr>
			</thead>
			<tbody>
		<?php foreach($units_attack as $key => $order){
			
			$units_owned = get_user_meta($user_ID, $order.'_owned');
		
			
			?><tr>
			<td data-title="Name">
				<?php echo $units[$order]['normalname'];?>
			</td>
			<td data-title="Sending">
				1
			</td>
			
			
		
			
			
			
			</tr>
		<?php } ?>
			</tbody>
		</table>
		<input class="submitBtn" style="width:100%" type="submit" value="SEND" class="">
		</form>
		<?php endif;?>
		<?php endif;?>

		<script>
			jQuery(document).ready(function () {
			jQuery("#attack").submit(function () {
	        jQuery(".submitBtn").attr("disabled", true);
	        return true;
	    	});
		});
		</script>



            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>