<?php
 /*
 * Template Name: Market orders
 */
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
			
			
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
			
			
			<div class="notice_message"><span class="rdw-line">Your current orders. Canceled unit orders return 75% of the initial order value.</span>
			<span class="rdw-line">Missile orders cannot be canceled.</span></div><br/>
			
			<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Ordered</th>
						<th scope="col">Time left</th>
						<th scope="col"></th>
  					</tr>
  					</thead>
  					<tbody>
			<?php 	
	
		$args = array(
	'posts_per_page'   => -1,
	'meta_key'		=> 'user_placed_id',
	'meta_value'	=> get_current_user_ID(),
	'post_type'        => 'market_order',
	);
	$units = get_posts( $args ); 

	$timestamp = strtotime(date('Y-m-d H:i:s'));
	
	foreach ($units as $order) {
		$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		$order_type = get_post_meta($order->ID,'order_type',true);

		$user_ID = $order->post_author;
		$delivery_time = get_post_meta($order->ID,'delivery_time',true);
		
	
		$timeleft = $delivery_time-$timestamp;
		
		if($timeleft >= 0){
	
		$timeleft = date('H:i:s', $timeleft);
		
		?>
		<tr>
		<td data-title="Name">
			<label><strong><?php echo get_the_title($order->ID);?></strong></label>
		</td>
		<td data-title="Units in order">
			<label><?php echo $units_in_this_order;?></label>
		</td>
		<td data-title="Time left">
			<label><?php echo $timeleft;?></label>
		</td>
		<td data-title="Cancel"><?php if($order_type != 'missile' || $order_type != 'satellite'):?>
			<form class="form" action="<?php echo home_url() ?>/cancel_order.php" name="" id="cancel" method="post">
			<input style="display:none;"type="text" id="order" name="order" value="<?php echo $order->ID;?>"/>
			<input onclick="return confirm('Are you sure you want to cancel this order?')" class="btn btn-general"type="submit" value="Cancel" class="">
			</form>
			<?php endif;?>
		</td>
		</tr>
		
		
		
		<?php }}
		
		?>
	</tbody>
	</table>
	<?php endif;?><?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset(); ?>
<?php get_footer(); ?>