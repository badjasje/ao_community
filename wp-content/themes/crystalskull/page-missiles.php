<?php
 /*
 * Template Name: Missiles
 */
 $user_ID = get_current_user_id(); 
include 'DO_NOT_DELETE.php';
include 'count_functions.php';
$missilespace = get_user_meta($user_ID, 'silo');
$totalmoney = get_user_meta($user_ID, 'money');
$totalturns = get_user_meta($user_ID, 'turns');
$totalmissiles = count_missilespace($user_ID);
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
			
			<div class="notice_message"><span class="rdw-line">
				Building one missile costs 5 turns.</span></div><br/>
			
			
			
			
			<form class="form" action="<?php echo home_url() ?>/missiles.php" name="" id="market" method="post">
				
				
				
				<div id="tab-1" class="tab-content current">
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
					<div class="footer_continue">
					<input type="submit" value="Place order" class="">
					</div>
								
    
									
			</form>
			<?php endif;?>
			<?php session_unset(); ?>
           
         
        </div>
    </div> </div></div>
</div>
<?php get_footer(); ?>