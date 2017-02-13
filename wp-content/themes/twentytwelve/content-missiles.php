<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$user_ID = get_current_user_id(); 
include 'DO_NOT_DELETE.php';
include 'count_functions.php';
$missilespace = get_user_meta($user_ID, 'silo');
$totalmoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');
$totalmissiles = count_missilespace($user_ID);
?> 

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
		<center><h1>Missiles</h1>
		<p>Building one missile costs 5 turns.</p>
		</center>	
			
			
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 1):?>
				<div class="marketnotice">Missiles ordered</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Not enough turns</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more missile silo's</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php endif;?><?php endif;?>
			
			
			
			
			
			
			
			<form class="form" action="<?php echo home_url() ?>/missiles.php" name="" id="market" method="post">
				
				
				
				<div id="tab-1" class="tab-content current">
				<div class="container2">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Price</td>
						<th scope="col">Attack</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // MISSILE TABLE
				
					foreach($missiles as $key => $order){
					$units_owned = get_user_meta($user_ID, $key.'_owned');
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $missiles[$key]['type'];
					?>
					
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $units_owned[0]; ?> (<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo $order['price'];?>
					</td>
					
					<td data-title="Attackpower">
						<?php echo $order['attack'];?>
					</td>
					
					<td data-title="Targets">
					<?php 
						$i = 0;
						$len = count($order['attacks']);
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
								$max_space = $missilespace[0]-$totalmissiles;
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo (min($max_money,$max_turns,$max_space));?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
  					</tr>
					<?php }?>
					<tbody>
				</table>
				
					Your empty missile silo's allow you to build a maximum of <strong><?php echo $missilespace[0]-$totalmissiles;?></strong> missiles
				
				
				
					
					<br/><br/>
					<input type="submit" value="Place order" class="">
								
    
									
			</form>

			<?php session_unset(); ?>
		</div><!-- .entry-content -->

	</article><!-- #post -->
