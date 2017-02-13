<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
 
  include 'satellite_array.php';
  $user_ID = get_current_user_id();
  $sat_level = get_user_meta($user_ID, 'level_satellite_construction',true);
  $sat_owned = get_user_meta($user_ID, 'sat_owned',true);
  $sat_progress = get_user_meta($user_ID, 'sat_in_progress',true);
  $sat_endlife = get_user_meta($user_ID, 'sat_endlife',true);

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<div class="entry-content">
			<center><h1>Satellites</h1></center>
			
			<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Satellite ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Build more warfactories</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice insuffunds">Build more shipyards</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">Build more baracks</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">Satellite ordered</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php endif;?><?php endif;?>
			
			
			
			<?php if($sat_level == '0'):?>
			<center><a href="/research/"><h2>Research satellite construction</h2></a></center>
			<?php endif;?>
			
			
			<?php if(!empty($sat_progress) || $sat_progress != 0):?>
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
	'meta_query'	=> array(
                    'relation' => 'OR',
					array(
                             'key' => 'user_placed_id',
                             'value' => get_current_user_ID(),
                             'compare' => 'IN'
                           ),
                           array(
                             'key' => 'order_type',
                             'value' => 'satellite',
                             'compare' => 'IN'
                           ),
                         )
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
		<td>
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

			<?php endif;?>
			
			
			



			
			<?php if($sat_level == 1 and $sat_owned == 0 and $sat_progress != 0):?>
			<form class="form" action="<?php echo home_url() ?>/satellite.php" name="" id="satellite" method="post">
				
				
			<div class="container2">
				<table class="responsive-table">
					<thead>
						<tr>
							<th scope="col">Name</th>
							<th scope="col">Effect</th>
							<th scope="col">Price</th>
							<th scope="col">Pick satellite</th>
						</tr>
					</thead>
				<tbody>
			<?php foreach ($satellites as $key => $satellite) {
			?>
			<tr>
				<th scope="row">
					<?php echo $satellite['name'];?>
				</th>
				<td data-title="Effect">
					<?php echo $satellite['desc']; ?>
				</td>
				<td data-title="Time">
					$ <?php echo $satellite['price'];?>
				</td>
			
				<td data-title="Pick research">
					<input type='radio' name='satellite' value='<?php echo $key;?>'>
				</td>
			</tr>
			
			
			<?php }?>
				</tbody>
		</table>
		
		<input type="submit" value="Order" class="">
		</div><!-- end container div -->
		
		
		
		</form>

		<?php endif;?>
		
		<?php if($sat_owned == 'laser'):?>
		<center>You currently own a <?php echo $satellites[$sat_owned]['name'];?>
		<?php 
			$timestamp = strtotime(date('Y-m-d H:i:s'));
			$timeleft = $sat_endlife-$timestamp;
			if($timeleft >= 0){
	
			$timeleft2 = date('H:i:s', $timeleft);
			?>
		<p><strong><?php echo date('d', $timeleft);?> days</strong> and <strong><?php echo $timeleft2;}?></strong> before your satellite re-enters the atmosphere.
		</center>
		
		<?php endif;?>
			
			
			
		</div><!-- .entry-content -->
	</article><!-- #post -->
<?php session_unset();