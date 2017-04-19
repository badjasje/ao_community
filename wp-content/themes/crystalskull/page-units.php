<?php
 /*
 * Template Name: Units
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
$totalturns = get_user_meta($user_ID, 'turns');

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
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	       
	        
	        
	        
	        <?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
	        
             <div class="notice_message"><span class="rdw-line">You can build units using turns. Your units will arrive immediately.</span>
			<span class="rdw-line">Per turn you can build 10 air units, 10 vehicles, 5 sea units or 20 infantry</span>
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
			
			
			
			<form class="form" action="<?php echo home_url() ?>/turnbuild.php" name="" id="market" method="post">
				<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />

				<div class="tab-content current build_content tabbed-table">
				<div class="tab-pane <?php echo $activeTab === 'air' ? 'active' : ''; ?>"  id="air" role="tabpanel">
				<div class="container2">
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
						<?php if($order['description']):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="right"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif;?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalair+=$units_owned[0]+$units_ordered[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
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
						<?php 	$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*10);
								$max_space = ($airspace[0]*10)-count_airspace($user_ID);
								
							
						?>
						<?php if($key == 'spyplane'):?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_turns,$max_space));?></span>
						<?php else:?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
						<?php endif;?>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php 
							if($key == 'spyplane'){
							echo (min($ccspace,$max_money,$max_turns,$max_space));}
							else{
							echo (min($max_money,$max_turns,$max_space));	
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
					<div class="space_desc padded">Your empty airfields allow you to build a maximum of <strong><?php echo $max_space;?></strong> air units.
				</div></div>

				<div class="tab-pane <?php echo $activeTab === 'sea' ? 'active' : ''; ?>"  id="sea" role="tabpanel">
				<div class="container2">
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
						<?php if($order['description']):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="right"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif;?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalsea+=$units_owned[0]+$units_ordered[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
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
						<?php 	$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*5);
								$max_space = ($seaspace[0]*5)-count_seaspace($user_ID);
						
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_space,$max_money,$max_turns));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_space,$max_money,$max_turns));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
  					</tbody>
				</table>
				</div>
					<div class="space_desc padded">Your empty shipyards allow you to build a maximum of <strong><?php echo $max_space;?></strong> sea units.
				</div></div>


				<div class="tab-pane <?php echo $activeTab === 'vehicles' ? 'active' : ''; ?>"  id="vehicles" role="tabpanel">
				<div class="container2">
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
						<?php if($order['description']):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="right"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif;?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalair+=$units_owned[0]+$units_ordered[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
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
						<?php 	$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*10);
								$max_space = ($vehspace[0]*10)-count_vehspace($user_ID);
								
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_space,$max_money,$max_turns));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_space,$max_money,$max_turns));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
				</table>
				</div>
					<div class="space_desc padded">Your empty warfactories allow you to build a maximum of <strong><?php echo $max_space;?></strong> vehicles.</div>
				</div>

				<div class="tab-pane <?php echo $activeTab === 'infantry' ? 'active' : ''; ?>"  id="infantry" role="tabpanel">
				<div class="container2">
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
						<?php if($order['description']):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="right"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif;?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalinf+=$units_owned[0]+$units_ordered[0];?> 
					(<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
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
						<?php 	$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*20);
								$max_space = ($infspace[0]*20)-count_infspace($user_ID);
							
							
						?>
						<?php if($key == 'spy' || $key == 'thief' || $key == 'sniper'):?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_turns,$max_space));?></span>
						<?php else:?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
						<?php endif;?>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/> 
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php
							if($key == 'spy' || $key == 'thief' || $key == 'sniper'){
							echo (min($ccspace,$max_money,$max_turns,$max_space));}
							else{
							echo (min($max_money,$max_turns,$max_space));	
							}?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
					<?php endif;?><?php }?>
  					</tbody>
				</table>
				</div>
					<div class="space_desc padded">Your empty barracks allow you to build a maximum of <strong><?php echo $max_space;?></strong> infantry.</div>
				</div>
					<?php session_unset(); ?>

					<div class="padded" style="padding: 0 15px 15px 15px;">
						<input type="submit" value="Build" class="">
						<div class="footer_continue">
						<input type="submit" value="Build" class="">
						</div>
					</div>
				</div>
           </form>
            </div>
            <?php endif;?>
        </div>
    </div></div>
</div>

<script type="text/javascript">
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
        jQuery('#currentTab').val(currentTab);
    });
</script>
<?php get_footer(); ?>