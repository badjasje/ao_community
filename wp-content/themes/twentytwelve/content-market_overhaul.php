<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_id(); 
include 'units_array.php';
include 'count_functions.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$vehspace = get_user_meta($user_ID, 'warfactory');
$infspace = get_user_meta($user_ID, 'baracks');
$totalmoney = get_user_meta($user_ID, 'money');

$discount_level = get_user_meta($user_ID, 'level_market_discount')[0];

if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level == 2){
	$discount = 0.70;
}


?> 


	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			
			
			
			
			
			
			<center><h1>Market : Buy</h1>
			<p>The market enables you to buy units without using turns. <br/>
				<?php $MSlevel = get_user_meta($user_ID, 'level_shipping_time')[0];

					if($MSlevel == 0 || empty($MSlevel)){
						$hours = 12;	
						}
					
					if($MSlevel == 1){
						$hours = 6;	
						}
					if($MSlevel == 2){
						$hours = 3;	
						}?>
				
				There is a waiting time of <?php echo $hours;?> hours based on your completed research.</p></center>
			
			
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Build more airfields</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Build more warfactories</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php endif;?><?php endif;?>
			
			
			
		
			<center>
			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Air units</li>
			<li class="tab-link" data-tab="tab-2">Sea units</li>
			<li class="tab-link" data-tab="tab-3">Vehicles</li>
			<li class="tab-link" data-tab="tab-4">Infantry</li>
			</ul>
			</center>
			
			
			
			<form class="form" action="<?php echo home_url() ?>/market2.php" name="" id="market" method="post">
				
				
				<div id="tab-1" class="tab-content current">
				<div class="container2">
				<center><p>Your empty airfields allow you to build a maximum of <strong><?php echo ($airspace[0]*10)-count_airspace($user_ID);?></strong> air units.</p></center>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Price</th>
						<th scope="col">Att/Life</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  					<tbody>
				<?php // AIR TABLE
				
					$totalair = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					?>
					<?php if($unittype == 'air'):?>
					<tr>
					<th scope="row">
						<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					<?php echo ceil($order['price']*2.2*$discount);?>
					</td>
					
					<td data-title="Att/Life">
						<?php echo $order['attack'];?>/<?php echo $order['life'];?>
					</td>
					
					
					<td data-title="Targets">
					<?php 
						
						$i = 0;
						$len = count($order['attacks']);
						if(empty($order['attacks'])){echo 'n.a';}
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
					</td>
					
					
					
					<td data-title="Max">
						<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount)));
								$max_space = ($airspace[0]*10)-count_airspace($user_ID);
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_money,$max_space));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
				</tbody>
				</table>
				</div>
				
				</div>
				
				<div id="tab-2" class="tab-content">
				<div class="container2">
				<p><center>Your empty shipyards allow you to build a maximum of <strong><?php echo ($seaspace[0]*5)-count_seaspace($user_ID);?></strong> sea units.
				</center></p>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Price</th>
						<th scope="col">Att/Life</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  					<tbody>
				<?php // SEA TABLE
					$totalsea = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					?>
					<?php if($unittype == 'sea'):?>
					<tr>
					<th scope="row">
						<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; ?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					<?php echo ceil($order['price']*2.2*$discount);?>
					</td>
					
					<td data-title="Att/Life">
						<?php echo $order['attack'];?>/<?php echo $order['life'];?>
					</td>
					
					
					<td data-title="Targets">
					<?php 
						
						$i = 0;
						$len = count($order['attacks']);
						if(empty($order['attacks'])){echo 'n.a';}
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
					</td>
					
					
					
					<td data-title="Max">
						<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount)));
								$max_space = ($seaspace[0]*5)-count_seaspace($user_ID);
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_money,$max_space));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
  					</tbody>
				</table>
				</div>
				</div>
				
				
				<div id="tab-3" class="tab-content">
				<div class="container2">
				<p><center>Your empty warfactories allow you to build a maximum of <strong><?php echo ($vehspace[0]*10)-count_vehspace($user_ID);?></strong> vehicles.
				</center></p>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Price</th>
						<th scope="col">Att/Life</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  					<tbody>
				<?php // VEH TABLE
					$totalveh = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					?>
					<?php if($unittype == 'veh'):?>
					<tr>
					<th scope="row">
						<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					<?php echo ceil($order['price']*2.2*$discount);?>
					</td>
					
					<td data-title="Att/Life">
						<?php echo $order['attack'];?>/<?php echo $order['life'];?>
					</td>
					
					
					<td data-title="Targets">
					<?php 
						
						$i = 0;
						$len = count($order['attacks']);
						if(empty($order['attacks'])){echo 'n.a';}
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
					</td>
					
					
					
					<td data-title="Max">
						<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount)));
								$max_space = ($vehspace[0]*10)-count_vehspace($user_ID);
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_money,$max_space));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
				</table>
				</div>
				</div>
				
				<div id="tab-4" class="tab-content">
				<div class="container2">
				<p><center>Your empty baracks allow you to build a maximum of <strong><?php echo ($infspace[0]*20)-count_infspace($user_ID);?></strong> infantry.</center></p>
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Price</th>
						<th scope="col">Att/Life</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  					<tbody>
				<?php // INF TABLE
					$totalinf = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					?>
					<?php if($unittype == 'inf'):?>
					<tr>
					<th scope="row">
						<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					<?php echo ceil($order['price']*2.2*$discount);?>
					</td>
					
					<td data-title="Att/Life">
						<?php echo $order['attack'];?>/<?php echo $order['life'];?>
					</td>
					
					
					<td data-title="Targets">
					<?php 
						
						$i = 0;
						$len = count($order['attacks']);
						if(empty($order['attacks'])){echo 'n.a';}
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
					</td>
					
					
					
					<td data-title="Max">
						<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount)));
								$max_space = ($infspace[0]*20)-count_infspace($user_ID);
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_money,$max_space));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
  					</tbody>
				</table>
				</div>
				</div>
					
					
					
				
					<input type="submit" value="Place order" class="">
				
					
		
						
    
									
			</form>

			<?php session_unset(); ?>
		</div><!-- .entry-content -->

	</article><!-- #post -->
