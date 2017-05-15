<?php
 /*
 * Template Name: Market Sell
 */

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'air';

$user_ID = get_current_user_id(); 
include 'units_array.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$specialSold = get_user_meta($user_ID, 'special_sold_today',true);
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

/** @TODO: This page contains a lot of duplication, while only a few things are different per tab. Should be refactored */
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<div class="container">
				
				
				<?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
				
				
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div><br/>
			<?php else:?>
				
			
			<div class="notice_message">
				<span class="rdw-line">Selling units returns 50% of the original market price</span>
				<span class="rdw-line"><?php echo $specialSold;?> special units sold today. You can sell a maximum of 50 special units per day.</span>
				
			</div>

			<ul id="explore-tab" class="nav nav-tabs nav-justified" role="tablist">
				<li class="nav-item <?php echo $activeTab === 'air' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#air" href="?tab=air" role="tab">Air units</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'sea' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#sea" href="?tab=sea" role="tab">Sea units</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#vehicles" href="?tab=vehicles" role="tab">Vehicles</a>
				</li>
				<li class="nav-item <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>">
					<a class="nav-link" data-toggle="tab" data-target="#infantry" href="?tab=infantry" role="tab">Infantry</a>
				</li>
			</ul>
			
			
			
			<form class="form" action="<?php echo home_url() ?>/sell_units.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
				<div class="tab-content current build_content tabbed-table">
					<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">
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

				<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">
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


				<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">
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
				<?php // VEHICLES TABLE
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

				<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">
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
					
					
					

				<div class="padded">
					<input type="submit" value="Sell Units" class="">
					<div class="footer_continue">
					<input type="submit" value="Sell Units" class="">
					</div>
				</div>
					
					
		
				</div>			
    
				</div>
			</form></div>
			<?php endif;?>
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
        jQuery('#currentTab').val(currentTab);
    });
</script>

<?php get_footer(); ?>