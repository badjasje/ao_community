<?php
 /*
 * Template Name: Market Sell
 */
$user_ID = get_current_user_id(); 
include 'units_array.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$vehspace = get_user_meta($user_ID, 'warfactory');
$infspace = get_user_meta($user_ID, 'baracks');
$discount_level = get_user_meta($user_ID, 'level_market_discount',true);

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_discount = 1;
if($startingbonus == 'shipping'){
	$shipping_discount = 0.9;
}

if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level == 2){
	$discount = 0.70;
}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<div class="container">
				
				
				
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
			<?php elseif($_SESSION['status'] == 50):?>
				<div class="marketnotice insuffunds">Can't sell more than 50 special units per day</div>
			<?php elseif($_SESSION['status'] == 22):?>
				<div class="marketnotice">Units sold</div>
			<?php endif;?><?php endif;?>	
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
			<?php else:?>
				
			
			<div class="notice_message"><span class="rdw-line">Selling units returns 50% of the original market price</span></div>
			<ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Air units</li>
			<li class="tab-link" data-tab="tab-2">Sea units</li>
			<li class="tab-link" data-tab="tab-3">Vehicles</li>
			<li class="tab-link" data-tab="tab-4">Infantry</li>
			</ul>
			
			
			
			<form class="form" action="<?php echo home_url() ?>/sell_units.php" name="" id="market" method="post">
				
				
				<div id="tab-1" class="tab-content current">
				<div class="container2">
				<table class="responsive-table">
				<thead>
			
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Price</th>
						<th scope="col">You can sell</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // AIR TABLE
					$totalair = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					if($units_owned[0] != 0){
					?>
					<?php if($unittype == 'air'):?>
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Price">
					$ <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>
					</td>
					
					<td data-title="You can sell">
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];?></span>						</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }}?>
  					</tbody>
				</table>
				</div>
				</div>
				
				<div id="tab-2" class="tab-content">
				<div class="container2">
				<table class="responsive-table">
				<thead>
			
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Price</th>
						<th scope="col">You can sell</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // SEA TABLE
					$totalair = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					if($units_owned[0] != 0){
					?>
					<?php if($unittype == 'sea'):?>
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Price">
					$ <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>
					</td>
					
					<td data-title="You can sell">
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];?></span>						</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }}?>
  					</tbody>
				</table>
				</div>
				</div>
				
				
				<div id="tab-3" class="tab-content">
				<div class="container2">
				<table class="responsive-table">
				<thead>
			
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Price</th>
						<th scope="col">You can sell</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // AIR TABLE
					$totalair = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					if($units_owned[0] != 0){
					?>
					<?php if($unittype == 'veh'):?>
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Price">
					$ <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>
					</td>
					
					<td data-title="You can sell">
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];?></span>						</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }}?>
  					</tbody>
				</table>
				</div>
				</div>
				
				<div id="tab-4" class="tab-content">
				<div class="container2">
				<table class="responsive-table">
				<thead>
			
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Price</th>
						<th scope="col">You can sell</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // INF TABLE
					$totalair = 0;
					foreach($units as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $units[$key]['type'];
					if($units_owned[0] != 0){
					?>
					<?php if($unittype == 'inf'):?>
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Price">
					$ <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>
					</td>
					
					<td data-title="You can sell">
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];?></span>						</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }}?>
  					</tbody>
				</table>
				</div>
				</div>
					
					
					
					
					<input type="submit" value="Sell Units" class="">
					<div class="footer_continue">
					<input type="submit" value="Sell Units" class="">
					</div>
					
					
		
				</div>			
    
									
			</form></div>
			<?php endif;?>
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>