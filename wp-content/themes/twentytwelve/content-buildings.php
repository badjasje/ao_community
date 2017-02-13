<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_id(); 
include 'building_array.php';
$land = get_user_meta($user_ID, 'land');
$builtland = get_user_meta($user_ID, 'builtland');
$totalmoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');

?> 

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		
		<div class="entry-content">
			<center><h1>Buildings</h1></center>
			<center><p>Your free land allows you to build <strong><?php echo floor(($land[0]-$builtland[0])/20);?></strong> buildings.<br/>You can currently build 5 buildings per turn.</p></center>
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 7):?>
				<div class="marketnotice"><?php echo $_SESSION['buildings']; 
					if($_SESSION['buildings']>1){ echo ' buidings';}else{echo ' building';}?> built using <?php echo $_SESSION['turns_used'];
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
			<?php elseif($_SESSION['status'] == 14):?>
				<div class="marketnotice">Buildings demolished</div>
			<?php endif;?><?php endif;?>
			
	
			<div class="container">

			<center><ul class="tabs">
			<li class="tab-link current" data-tab="tab-1">Build</li>
			<li class="tab-link" data-tab="tab-2">Demolish</li>
			</ul></center>
			
			<div id="tab-1" class="tab-content current">
			<form class="form" action="<?php echo home_url() ?>/build.php" name="" id="market" method="post">
				
				
			
				<table>
					<tr>
						<td>Name</td>
						<td><span class="markettitle">Owned</span><span class="shorttitle">Own</span></td>
						<td>Price</td>
						<td>Att/Life</td>
						<td>Power usage</td>
						<td><span class="markettitle">Targets</span><span class="shorttitle">Trgt</span></td>
						<td>Max</td>
						<td></td>
  					</tr>
				<?php // building TABLE
					$totalbuildings = 0;
					foreach($buildings as $key => $order){
					$units_owned = get_user_meta($user_ID, $key);
					
					?>
					
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $order['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $order['shortname'];?></label>
					</td>
					<td>
					<?php echo $units_owned[0]; $totalbuildings+=$units_owned[0];?>			
					</td>
					<td>
					<span class="markettitle">$ </span><?php echo $order['price'];?>
					</td>
					<td><?php echo $order['attack'];?>/<?php echo $order['life'];?></td>
					<td><?php if($order['power'] != 0){echo $order['power'];}?></td>
					<td>
					<span class="markettitle"><?php 
						$i = 0;
						$len = count($order['attacks']);
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					<span class="shorttitle"><?php 
						$i = 0;
						$len = count($order['shortatt']);
						foreach($order['shortatt'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?></span>
					</td>
					<td>
						<?php 	$max_money = floor($totalmoney[0]/$order['price']);
								$max_turns = floor($totalturns[0]*5);
								$max_land = floor(($land[0]-$builtland[0])/20);
						?>
						
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_land,$max_turns));?></span>
					</td>
					<td>
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_land, $max_turns, $max_money));?>");
					
						});
					
					</script>
					
					<?php }?>
				</table>
				
				
					
					<br/>
			
					<input type="submit" value="Build" class="">
				
					
		
		
    
									
			</form>
			</div><!-- tab 1 close -->
			<div id="tab-2" class="tab-content">
				<form class="form" action="<?php echo home_url() ?>/demolish.php" name="" id="demolish" method="post">
				
				
			
				<table>
					<tr>
						<td>Name</td>
						<td><span class="markettitle">Owned</span><span class="shorttitle">Own</span></td>
						<td>Price to demolish</td>
						<td>Max</td>
						<td></td>
  					</tr>
				<?php // DEMOLISHHHHH TABLE
					$totalbuildings = 0;
					foreach($buildings as $key => $order){
					$units_owned = get_user_meta($user_ID, $key);
					if($units_owned[0]>0){
					?>
					
					<tr>
					<td>
					<label class="markettitle"><strong><?php echo $order['normalname'];?></strong></label>
					<label class="shorttitle"><?php echo $order['shortname'];?></label>
					</td>
					<td>
					<?php echo $units_owned[0]; $totalbuildings+=$units_owned[0];?>			
					</td>
					<td>
					<span class="markettitle">$ </span><?php echo floor($order['price']*0.15);?>
					</td>
			
					
					<td>
						<?php $max_demo_money = floor($totalmoney[0]/($order['price']*0.15));?>
						<span class="allbutton" id="demobutton<?php echo $key;?>"><?php echo min($max_demo_money,$units_owned[0]);?></span>
					</td>
					<td>
					<input class="small_input" type="text" id="demo<?php echo $key;?>" name="<?php echo $key;?>"/>
					</td>
					</tr>
					
					<script type="text/javascript">
						jQuery("#demobutton<?php echo $key;?>").click(function() {
						jQuery("#demo<?php echo $key;?>").val("<?php echo min($max_demo_money,$units_owned[0]);?>");
						});
					</script>
					
					<?php }}?>
				</table>
				
				
					
					<br/>
			
					<input type="submit" value="DEMOLISH" class="">
				
					
		
		
    
									
			</form>
				
			</div><!-- tab 2 close -->
<?php session_unset(); ?>
			
			</div><!-- tab container -->
		</div><!-- .entry-content -->

	</article><!-- #post -->
