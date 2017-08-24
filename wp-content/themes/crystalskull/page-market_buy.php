<?php
 /*
 * Template Name: Market Buy
 */

$activeTab = $_GET['tab'] ? sanitize_text_field($_GET['tab']) : 'air';

$user_ID = get_current_user_id(); 
include 'units_array.php';
include 'count_functions.php';
$airspace = get_user_meta($user_ID, 'airfield');
$seaspace = get_user_meta($user_ID, 'shipyard');
$vehspace = get_user_meta($user_ID, 'warfactory');
$infspace = get_user_meta($user_ID, 'baracks');
$totalmoney = get_user_meta($user_ID, 'money');

$spies = get_user_meta($user_ID, 'spy_owned',true);
$spies_ordered = get_user_meta($user_ID, 'spy_ordered',true);
$thiefs = get_user_meta($user_ID, 'thief_owned',true);
$thiefs_ordered = get_user_meta($user_ID, 'thief_ordered',true);
$planes = get_user_meta($user_ID, 'spyplane_owned',true);
$planes_ordered = get_user_meta($user_ID, 'spyplane_ordered',true);
$sniper = get_user_meta($user_ID, 'sniper_owned',true);
$sniper_ordered = get_user_meta($user_ID, 'sniper_ordered',true);

$commandcenter = get_user_meta($user_ID, 'command_centre',true);
$ccspace = ($commandcenter*5)-$spies-$thiefs-$planes-$spies_ordered-$thiefs_ordered-$planes_ordered-$sniper-$sniper_ordered;



$discount_level = get_user_meta($user_ID, 'level_market_discount')[0];

if($discount_level == 0){
	$discount = 1;
}
if($discount_level == 1){
	$discount = 0.85;
}
if($discount_level >= 2){
	$discount = 0.70;
}

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$shipping_discount = 0;
if($startingbonus == 'shipping'){
	$shipping_discount = 0.1;
}

$discount_value = $discount-$shipping_discount;

$enddate = get_field('end_date','option');
$endstamp = strtotime($enddate);
$timestamp = current_time('timestamp');
$timeleft = $endstamp-$timestamp;

get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
			<?php if(!empty($_SESSION['status'])):?>
				<?php echo alert_notification($_SESSION['status']);?>
			<?php endif; // End empty status check ?>
	            
	           
	        <?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
	        
            <div class="notice_message">
	            <span class="rdw-line">The market enables you to buy units without using turns.</span>
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
				
<?php if($timeleft<86400):?>
<span class="rdw-line">You cannot order units during the last 24 hours of the round.</span</div></div>	
<?php else:?>
<span class="rdw-line">There is a waiting time of <?php echo $hours;?> hours based on your completed research.</span</div></div>
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

			<form class="form" action="<?php echo home_url() ?>/market2.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
				<div class="tab-content current build_content tabbed-table">

					<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">

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
							<?php if($startingbonus == 'shipping'):?>
							<th scope="colr">Delay <span class="hover-tip"  data-toggle="tooltip" data-original-title="Input the delay in minutes. You can delay market orders up to 6 hours. 360 minutes." data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span></th>
							<?php endif;?>
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
							<?php if($order['description']):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
							<?php endif;?>
						</th>

						<td data-title="Owned">
						<?php echo $units_owned[0];?>
						(<?php echo $units_ordered[0]; ?>)
						</td>

						<td data-title="Price">
						
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per unit." data-placement="bottom">
							$ <?php echo ceil($order['price']*2.2*$discount_value);?>
						</span>
						
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
						<?php if($startingbonus == 'shipping'):?>

						<?php endif;?>


						<td data-title="Max">
							<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount_value)));
									$max_space = ($airspace[0]*10)-count_airspace($user_ID);

							?>
							<?php if($key == 'spyplane'):?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_space));?></span>
							<?php else:?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
							<?php endif;?>
						</td>
						<?php if($startingbonus == 'shipping'):?>
						<td data-title="Delay">
						<input style="width:50%"type="number" id="delay<?php echo $key;?>" min="0" name="delay<?php echo $key;?>" placeholder="Delay in min."/>
						</td>
						<?php endif;?>
						<th colspan='2'data-title="">
						<input type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
						</th>
						</tr>
						<script type="text/javascript">
							jQuery("#button<?php echo $key;?>").click(function() {
							jQuery("#<?php echo $key;?>").val("<?php
								if($key == 'spyplane'){
								echo (min($ccspace,$max_money,$max_space));}
								else{
								echo (min($max_money,$max_space));
								}
								?>");
							jQuery("#button").show();
							jQuery("#message").hide();
							});

						</script>
						<?php endif;?><?php }?>
					</tbody>
					</table>

					</div>

					<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">

					<center><p>Your empty shipyards allow you to build a maximum of <strong><?php echo ($seaspace[0]*5)-count_seaspace($user_ID);?></strong> sea units.</p></center>
					<table class="responsive-table">
						<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Owned (ordered)</th>
							<th scope="col">Price</th>
							<th scope="col">Att/Life</th>
							<th scope="col">Targets</th>
							<th scope="col">Max</th>
							<?php if($startingbonus == 'shipping'):?>
							<th scope="colr">Delay <span class="hover-tip"  data-toggle="tooltip" data-original-title="Input the delay in minutes. You can delay market orders up to 6 hours. 360 minutes." data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span></th>
							<?php endif;?>
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
							<?php if($order['description']):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
							<?php endif;?>
						</th>

						<td data-title="Owned">
						<?php echo $units_owned[0]; ?>
						(<?php echo $units_ordered[0]; ?>)
						</td>

						<td data-title="Price">
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per unit." data-placement="bottom">
							$ <?php echo ceil($order['price']*2.2*$discount_value);?>
						</span>
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
							<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount_value)));
									$max_space = ($seaspace[0]*5)-count_seaspace($user_ID);

							?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
						</td>
						<?php if($startingbonus == 'shipping'):?>
						<td data-title="Delay">
						<input style="width:50%" type="number" min="0" id="delay<?php echo $key;?>" name="delay<?php echo $key;?>" placeholder="Delay in min."/>
						</td>
						<?php endif;?>
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


					<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">

					<center><p>Your empty warfactories allow you to build a maximum of <strong><?php echo ($vehspace[0]*10)-count_vehspace($user_ID);?></strong> vehicles.</p>
					</center>
					<table class="responsive-table">
						<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Owned (ordered)</th>
							<th scope="col">Price</th>
							<th scope="col">Att/Life</th>
							<th scope="col">Targets</th>
							<th scope="col">Max</th>
							<?php if($startingbonus == 'shipping'):?>
							<th scope="colr">Delay <span class="hover-tip"  data-toggle="tooltip" data-original-title="Input the delay in minutes. You can delay market orders up to 6 hours. 360 minutes." data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span></th>
							<?php endif;?>
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
							<?php if($order['description']):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
							<?php endif;?>
						</th>

						<td data-title="Owned">
						<?php echo $units_owned[0];?>
						(<?php echo $units_ordered[0]; ?>)
						</td>

						<td data-title="Price">
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per unit." data-placement="bottom">
							$ <?php echo ceil($order['price']*2.2*$discount_value);?>
						</span>
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
							<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount_value)));
									$max_space = ($vehspace[0]*10)-count_vehspace($user_ID);

							?>
							<?php if($key == 'spy' || $key == 'thief' || $key == 'sniper'):?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_space));?></span>
							<?php else:?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
							<?php endif;?>
						</td>
						<?php if($startingbonus == 'shipping'):?>
						<td data-title="Delay">
						<input style="width:50%" type="number" min="0" id="delay<?php echo $key;?>" name="delay<?php echo $key;?>" placeholder="Delay in min."/>
						</td>
						<?php endif;?>
						<th colspan='2'data-title="">
						<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
						</th>
						</tr>
						<script type="text/javascript">
							jQuery("#button<?php echo $key;?>").click(function() {
							jQuery("#<?php echo $key;?>").val("<?php
								if($key == 'spy' || $key == 'thief' || $key == 'sniper'){
								echo (min($ccspace,$max_money,$max_space));}
								else{
								echo (min($max_money,$max_space));
								}?>");
							jQuery("#button").show();
							jQuery("#message").hide();
							});

						</script>
						<?php endif;?><?php }?>
					</table>

					</div>

					<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">

					<center><p>Your empty baracks allow you to build a maximum of <strong><?php echo ($infspace[0]*20)-count_infspace($user_ID);?></strong> infantry.</p></center>
					<table class="responsive-table">
						<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Owned (ordered)</th>
							<th scope="col">Price</th>
							<th scope="col">Att/Life</th>
							<th scope="col">Targets</th>
							<th scope="col">Max</th>
							<?php if($startingbonus == 'shipping'):?>
							<th scope="colr">Delay <span class="hover-tip"  data-toggle="tooltip" data-original-title="Input the delay in minutes. You can delay market orders up to 6 hours. 360 minutes." data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span></th>
							<?php endif;?>
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
							<?php if($order['description']):?>
							<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
							<?php endif;?>
						</th>

						<td data-title="Owned">
						<?php echo $units_owned[0];?>
						(<?php echo $units_ordered[0]; ?>)
						</td>

						<td data-title="Price">
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per unit." data-placement="bottom">
							$ <?php echo ceil($order['price']*2.2*$discount_value);?>
						</span>
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
							<?php 	$max_money = floor($totalmoney[0]/ceil(($order['price']*2.2*$discount_value)));
									$max_space = ($infspace[0]*20)-count_infspace($user_ID);

							?>
							<?php if($key == 'spy' || $key == 'thief' || $key == 'sniper'):?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_space));?></span>
							<?php else:?>
							<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_space));?></span>
							<?php endif;?>
						</td>
						<?php if($startingbonus == 'shipping'):?>
						<td data-title="Delay">
						<input style="width:50%"type="number" id="delay<?php echo $key;?>" min="0" name="delay<?php echo $key;?>" placeholder="Delay in min."/>
						</td>
						<?php endif;?>
						<th colspan='2'data-title="">
						<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
						</th>
						</tr>
						<script type="text/javascript">
							jQuery("#button<?php echo $key;?>").click(function() {
							jQuery("#<?php echo $key;?>").val("<?php
								if($key == 'spy' || $key == 'thief' || $key == 'sniper'){
								echo (min($ccspace,$max_money,$max_space));}
								else{
								echo (min($max_money,$max_space));
								}?>");
							jQuery("#button").show();
							jQuery("#message").hide();
							});

						</script>
						<?php endif;?><?php }?>
	                    </tbody>
					</table>
					</div>





					<div class="padded">
						<input type="submit" value="Place order" class="">
						<div class="footer_continue">
							<input type="submit" value="Place order" class="">
						</div>
					</div>



				</div>
			</form>

<?php endif;?><?php endif;?>
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