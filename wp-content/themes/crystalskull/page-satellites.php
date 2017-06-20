<?php
 /*
 * Template Name: Satellites
 */
include 'satellite_array.php';
$user_ID = get_current_user_id();
$sat_level = get_user_meta($user_ID, 'level_satellite_construction',true);
$sat_owned = get_user_meta($user_ID, 'sat_owned',true);
$sat_progress = get_user_meta($user_ID, 'sat_in_progress',true);
$sat_endlife = get_user_meta($user_ID, 'sat_endlife',true);
$sat_status = get_user_meta($user_ID, 'stealth_sat_status',true);
$stealth_sat_time = get_user_meta($user_ID, 'stealth_sat_time',true);





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
			



<?php if($sat_level == '0'):?>
	<div class="notice_message"><span class="rdw-line"><a style="color:#fff;"href="/research/">Research satellite construction</a></span></div>
<?php endif;?>
			
			
<?php if($sat_progress != '0'):?>
			
	<table class="responsive-table">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Amount</th>
				<th scope="col">Time left</th>
  			</tr>
		</thead>
		
		<tbody>
			
			
<?php 	
	
$args = array(
	'posts_per_page'   => -1,
	'meta_key'		=> 'user_placed_id',
	'meta_value'	=> get_current_user_ID(),
	'post_type'        => 'market_order',
	'meta_query'	=> array(
		'relation' => 'AND',
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
		<td data-title="Name" >
			<label><strong><?php echo get_the_title($order->ID);?></strong></label>
		</td>
		<td data-title="Amount">
			<label><?php echo $units_in_this_order;?></label>
		</td>
		<td data-title="Time left">
			<label><?php echo $timeleft;?></label>
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
		
		?></tbody>
		</table>

			<?php endif;?>
			
			

			
			<?php if($sat_level != '0' && $sat_owned == '0' && $sat_progress == '0'):?>
			<div class="notice_message"><span class="rdw-line">Building a satellite requires 25 turns</span></div><br/>
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
				<td data-title="Price">
					$ <?php echo $satellite['price'];?>
				</td>
			
				<td data-title="Pick satellite">
					<input type='radio' name='satellite' value='<?php echo $key;?>' checked>
				</td>
			</tr>
			
			
			<?php }?>
				</tbody>
		</table>
		
		<input type="submit" value="Order" class="">
		</div><!-- end container div -->
		
		
		
		</form>

		<?php endif;?>
		
		<?php if($sat_owned != '0'):?>
		<div class="notice_message"><span class="rdw-line">You currently own a <?php echo $satellites[$sat_owned]['name'];?></span>
		<?php 
			$timestamp = strtotime(date('Y-m-d H:i:s'));
			
			$timeleft = $sat_endlife-$timestamp;
			if($timeleft >= 0){
	
			$timeleft2 = date('H:i:s', $timeleft);
			?>
		<span class="rdw-line"><?php echo date('d', $timeleft);?> days</strong> and <strong><?php echo $timeleft2;}?></strong> before your satellite re-enters the atmosphere.</span></div>
		<?php if($sat_owned == 'stealths'):?>
			<?php if($sat_status == 'active'):?><br/>
				<div class="notice_message"><span class="rdw-line">Stealth satellite active.</span><span class="rdw-line"><?php echo human_time_diff( $stealth_sat_time,$timestamp);?> before you need to reactivate. </span></div>
			<?php else:?>
			<br/>
			<center>
				<a class="btn btn-general" href="/activate_stealthsat.php" onclick="return confirm('Are you sure you want to activate your stealth satellite?')">
				<i class="fa fa-power-off" aria-hidden="true"></i> Activate stealth satellite</a>
			</center>
				
			<?php endif;?>
			
			
		
		<?php endif;?>
		<br/><center>
				<a class="btn btn-general" href="/crashsat.php" onclick="return confirm('Are you sure you want to demolish your satellite? This will cost 20% of the original price.')">
				<i class="fa fa-trash-o" aria-hidden="true"></i> Demolish satellite</a>
			</center>
		<?php endif;?>
		<?php endif;?>
            
            </div>
        </div>
    </div>
</div>
<?php session_unset();?>
<?php get_footer(); ?>