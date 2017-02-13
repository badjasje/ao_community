<?php
 /*
 * Template Name: Buildings NEW DESIGN
 */
 $user_ID = get_current_user_id(); 
include 'building_array.php';
include 'units_array.php';
$newToken = generateFormToken('form1'); 
  
$land = get_user_meta($user_ID, 'land');
$builtland = get_user_meta($user_ID, 'builtland');
$totalmoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');

$airspace = get_user_meta($user_ID, 'airfield')[0]*10;
$seaspace = get_user_meta($user_ID, 'shipyard')[0]*5;
$vehspace = get_user_meta($user_ID, 'warfactory')[0]*10;
$infspace = get_user_meta($user_ID, 'baracks')[0]*20;

$EElevel = get_user_meta($user_ID, 'level_engineering_effectiveness')[0];

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$extra_divide = 0;
if($startingbonus == 'defensive'){
	$extra_divide = 5;
}



	$totalair = 0;
	$totalsea = 0;
	$totalveh = 0;
	$totalinf = 0;
		foreach($units as $key => $order){
		$units_owned = get_user_meta($user_ID, $key.'_owned')[0];
		$units_ordered = get_user_meta($user_ID, $key.'_ordered')[0];
		$unittype = $units[$key]['type'];
			if($unittype == 'air'){
				$totalair+=$units_ordered+$units_owned;
			}	
			
			if($unittype == 'sea'){
				$totalsea+=$units_ordered+$units_owned;
			}
			
			if($unittype == 'inf'){
				$totalinf+=$units_ordered+$units_owned;
			}
			
			if($unittype == 'veh'){
				$totalveh+=$units_ordered+$units_owned;
			}}
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 7):?>
				<div class="marketnotice"><?php echo $_SESSION['buildings']; 
					if($_SESSION['buildings']>1){ echo ' buildings';}else{echo ' building';}?> built using <?php echo $_SESSION['turns_used'];
					if($_SESSION['turns_used']>1){echo ' turns';}else{echo' turn';}
						
						
					?></div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Not enough turns</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">You cannot enter negative amounts</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Not enough free land</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php elseif($_SESSION['status'] == 1322):?>
				<div class="marketnotice insuffunds">Cannot demolish all your buildings</div>
			<?php elseif($_SESSION['status'] == 14):?>
				<div class="marketnotice">Buildings demolished</div>
			<?php elseif($_SESSION['status'] == 17):?>
				<div class="marketnotice insuffunds">You must sell units occupying the buildings before you can demolish them</div>
			<?php endif;?><?php endif;?>
			
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
			
	
				<div class="notice_message"><span class="rdw-line">Your free land allows you to build <strong><?php echo floor(($land[0]-$builtland[0])/20);?></strong> buildings.</span><span class="rdw-line">
			
			<?php 	if($EElevel == 0 || empty($EElevel)){
						$buildings_per_turn = 5+$extra_divide;
						echo 'You can currently build <strong>'.$buildings_per_turn.'</strong> buildings per turn.';	
						$turns_multiplier = 5+$extra_divide;	
						}
					
					if($EElevel == 1){
						$buildings_per_turn = 10+$extra_divide;
						echo 'You can currently build <strong>'.$buildings_per_turn.'</strong> buildings per turn.';
						$turns_multiplier = 10+$extra_divide;	
						}
					if($EElevel == 2){
						$buildings_per_turn = 15+$extra_divide;
						echo 'You can currently build <strong>'.$buildings_per_turn.'</strong> buildings per turn.';
						$turns_multiplier = 15+$extra_divide;	
						}
			
			?></span></div>
			

<style>
	.tab-content{
		display:block;
	}
	table{
		border:none;
	}
	.responsive-table tbody tr{
		border:0px;
	}
	</style>
<br/>
<!-- Nav tabs -->
<ul id="myTab" class="nav nav-tabs nav-justified" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#build" role="tab">Build</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#demolish" role="tab">Demolish</a>
  </li>
</ul>

<script>
jQuery(function(){
  var hash = window.location.hash;
  hash && jQuery('ul.nav a[href="' + hash + '"]').tab('show');

  jQuery('.nav-tabs a').click(function (e) {
    jQuery(this).tab('show');
    var scrollmem = jQuery('body').scrollTop() || jQuery('html').scrollTop(100);
    window.location.hash = this.hash;
    jQuery('html,body').scrollTop(scrollmem);
  });
});

</script>


<!-- Tab panes -->
<div class="tab-content build_content">
	<div class="tab-pane active" id="build" role="tabpanel">
	
	
	<form class="form" action="<?php echo home_url() ?>/build.php" name="" id="market" method="post">
			<input type="hidden" name="token" value="<?php echo $newToken; ?>">
				
			
		
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned</th>
						<th scope="col">Price</th>
						<th scope="col">Att/Life</th>
						<th scope="col">Power usage</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  				<tbody>
				<?php // building TABLE
					$totalbuildings = 0;
					foreach($buildings as $key => $order){
					$units_owned = get_user_meta($user_ID, $key);
					
					?>
					
					<tr>
					<th scope="row">
						<?php echo $order['normalname'];?> 
						<?php if($order['description']):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="right"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
						<?php endif;?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalbuildings+=$units_owned[0];?>
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
					</td>
					
					<td data-title="Att/Life">
						<?php echo $order['attack'];?>/<?php echo $order['life'];?>
					</td>
					
					<td data-title="Power usage">
						<?php if($order['power'] != 0){echo $order['power'];}else{echo 'n.a';}?>
					</td>
					
					<td data-title="Targets">
					<?php 
						
						$i = 0;
						$len = count($order['attacks']);
						if(empty($order['attacks'])){echo '&nbsp;';}
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
					</td>
					
					<td data-title="Max">
						<?php 	$max_money = floor($totalmoney[0]/$order['price']);
								$max_turns = floor($totalturns[0]*$turns_multiplier);
								$max_land = floor(($land[0]-$builtland[0])/20);
						?>
						
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_land,$max_turns));?></span>
					</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					
					</tr>
					
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo min($max_land, $max_turns, $max_money);?>");
					
						});
					
					</script>
					
					<?php }?>
  					</tbody>
				</table>
			
				
					<div class="button_padding"><input type="submit" value="Build" class=""></div>
					<div class="footer_continue">
					<input type="submit" value="Build" class="">
					</div>
					
		
		
    
									
			</form>
		
		
		
		
	</div>


	<div class="tab-pane" id="demolish" role="tabpanel">
		
		
		<form class="form" action="<?php echo home_url() ?>/demolish.php" name="" id="demolish" method="post">
				
				
			
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned</th>
						<th scope="col">Price to demolish</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
					</thead>
					<tbody>
				<?php // DEMOLISHHHHH TABLE
					$totalbuildings = 0;
					foreach($buildings as $key => $order){
					$units_owned = get_user_meta($user_ID, $key);
					if($units_owned[0]>0){
					?>
					
					<tr>
					
					<th scope="row">
						<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; $totalbuildings+=$units_owned[0];?>		
					</td>
					
					<td data-title="Price to demolish">
					$ <?php echo floor($order['price']*0.15);?>
					</td>
			
					
					<td data-title="Max">
						<?php	$max_demo_money = floor($totalmoney[0]/($order['price']*0.15));
								$max_owned = $units_owned[0];
								
								if($order['normalname'] == 'Airfield'){
									
									$max_demo_money = floor($max_demo_money - ($totalair/10));
								
									if($max_demo_money < 0){
										$max_demo_money = 0;
										}
								
									$max_owned = floor($max_owned - ($totalair/10));
								
									if($max_owned < 0){
										$max_owned = 0;}
								}
								
								if($order['normalname'] == 'Shipyard'){
									
									$max_demo_money = floor($max_demo_money - ($totalsea/5));
								
									if($max_demo_money < 0){
										$max_demo_money = 0;
										}
								
									$max_owned = floor($max_owned - $totalsea/5);
								
									if($max_owned < 0){
										$max_owned = 0;}
								}
								
								if($order['normalname'] == 'Baracks'){
									
									$max_demo_money = floor($max_demo_money - ($totalinf/20));
								
									if($max_demo_money < 0){
										$max_demo_money = 0;
										}
								
									$max_owned = floor($max_owned - ($totalinf/20));
								
									if($max_owned < 0){
										$max_owned = 0;}
								}
								if($order['normalname'] == 'Warfactory'){
									
									$max_demo_money = floor($max_demo_money - ($totalveh/10));
								
									if($max_demo_money < 0){
										$max_demo_money = 0;
										}
								
									$max_owned = floor($max_owned - ($totalveh/10));
								
									if($max_owned < 0){
										$max_owned = 0;}
								}
							
							
						?>
						<span class="allbutton" id="demobutton<?php echo $key;?>"><?php echo min($max_demo_money,$max_owned);?></span>
					</td>
					
					<th colspan="2">
					<input class="small_input" type="text" id="demo<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					
					</tr>
					
					<script type="text/javascript">
						jQuery("#demobutton<?php echo $key;?>").click(function() {
						jQuery("#demo<?php echo $key;?>").val("<?php echo min($max_demo_money,$max_owned);?>");
						});
					</script>
					
					<?php }}?>
				</table>
				</tbody>
				</div>
				
					
					<br/>
			
					<div class="button_padding"><input type="submit" value="DEMOLISH" class=""></div>
					<div class="footer_continue">
					<input type="submit" value="DEMOLISH" class="">
					</div>
					
		
		
    
									
			</form>

		
		
		
		
	</div>

</div>




	
			
		
			
			
		
								
		
			<?php endif;?>
<?php session_unset(); ?>
			
	
         
        </div>
    </div>
</div>
<?php get_footer(); ?>