<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<center><h1>Orders</h1>
			<p>Your current orders. Canceled unit orders return 75% of the initial order value.<br/>
				Missile orders cannot be canceled.
			</p>
			</center>
			<table>
					<tr>
						<td>Name</td>
						<td><span class="markettitle">Ordered</span><span class="shorttitle">Ordered</td>
						<td>Time left</td>
						<td></td>
  					</tr>
			
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
		<td>
			<label><strong><?php echo get_the_title($order->ID);?></strong></label>
		</td>
		<td>
			<label><?php echo $units_in_this_order;?></label>
		</td>
		<td>
			<label><?php echo $timeleft;?></label>
		</td>
		<td><?php if($order_type != 'missile' || $order_type != 'satellite'):?>
			<button value="<?php echo $order->ID;?>" id="cancel<?php echo $order->ID;?>">Cancel</button>
			<?php endif;?>
		</td>
		</tr>
		
		
		
		<script type="text/javascript">
			
		jQuery(document).ready(function(){
		jQuery('#cancel<?php echo $order->ID;?>').click(function(){
        jQuery.post("<?php get_site_url(); ?>/cancel_order.php?id=<?php echo $order->ID;?>&user=<?php echo $user_ID;?>",{ajax: true},
        function(data, status){
           location.reload();
         });
    });
});
</script>
		<?php }}
		
		?></table>
			
			
			
			
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
