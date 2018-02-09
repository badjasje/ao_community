<?php
 /*
 * Template Name: Satellites
 */
get_header();
include 'satellite_array.php';
$userId = get_current_user_id();
$userData = get_user_meta($userId);
$sat_level = $userData['level_satellite_construction'][0];
$sat_owned = $userData['sat_owned'][0];
$sat_progress = $userData['sat_in_progress'][0];
$sat_endlife = $userData['sat_endlife'][0];
$sat_status = $userData['stealth_sat_status'][0];
$stealth_sat_time = $userData['stealth_sat_time'][0];

?>
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

	<div class="spaceNotice">
			Building a satellite requires 25 turns
		</div>
		
		<div class="row market_block">	
			<div class="row clan_header_row storeDetails-heads">
				<div class="col-md-3"><strong>Name</strong></div>
				<div class="col-md-6"><strong>Effect</strong></div>
				<div class="col-md-3"><strong>Price</strong></div>
			</div>
				
			
	<?php foreach ($satellites as $key => $satellite) {?>
			
			<div class="row clan_profile_row2">
		
				<div class="col-md-3 center_clan_col market_column marketHeader">
					<?php echo $satellite['name'];?>
				</div>
			
				<div class="col-md-6 clan_column border_bottom_mobile">
						<div class="satDesc"><?php echo $satellite['desc']; ?></div>
				</div>
			
				<div class="col-md-3 clan_column">
					<span class="clan_data_left">Price</span>
					<span class="clan_data_right">
						<span 	class="hover-tip"  
								data-toggle="tooltip" 
								data-original-title="The <?php echo $satellite['name'];?> adds <?php echo $satellite['networth'];?>% networth. 
								$ <?php echo $satellite['price']*$satellite['networth']/100;?> per satellite." 
								data-placement="bottom">
									$ <?php echo number_format($satellite['price'], 0, ',', ' '); ?>
						</span>	
					</span>
			
				</div>
				
				<div class="col-md-2 clan_column">				
				</div>

			
			</div>
		
			
			<?php }?>
			
		</div>



	<div class="notice_message"><span class="rdw-line"><a style="color:#fff;"href="/research/">Research satellite construction</a></span></div>
<?php endif;?>
			
			
<?php if($sat_progress != '0'):?>
			
	<div class="row market_block">	
			<div class="row clan_header_row storeDetails-heads">
				<div class="col-md-4"><strong>Name</strong></div>
				<div class="col-md-4"><strong>Time left</strong></div>
				<div class="col-md-4"></div>
			</div>
			
			
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

$timestamp = current_time('timestamp');
	
	foreach ($units as $order) {
		$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		$order_type = get_post_meta($order->ID,'order_type',true);

		$userId = $order->post_author;
		$delivery_time = get_post_meta($order->ID,'delivery_time',true);
		
	
		$timeleft = $delivery_time-$timestamp;
		
		if($timeleft >= 0){
	
		$timeleft = date('H:i:s', $timeleft);
		
		?>
		<div class="row clan_profile_row2">
		
				<div class="col-md-4 center_clan_col market_column marketHeader">
					<?php echo get_the_title($order->ID);?>
				</div>
			
				<div class="col-md-4 clan_column border_bottom_mobile">
					<span class="clan_data_left">Time left</span>
					<span class="clan_data_right">
						<?php echo $timeleft;?>
					</span>
				</div>
			
				<div class="col-md-4 clan_column">
					<form class="form" action="<?php echo home_url() ?>/cancel_order.php" name="" id="cancel" method="post">
			<input style="display:none;"type="text" id="order" name="order" value="<?php echo $order->ID;?>"/>
			<input onclick="return confirm('Are you sure you want to cancel this order?')" class="btn btn-general submitBtn" type="submit" value="Cancel" class="">
			</form>			
				</div>
		</div>

	
		
		
		
		
		<?php }} ?>
		
	</div>

			<?php endif;?>
			
			

			
			<?php if($sat_level != '0' && $sat_owned == '0' && $sat_progress == '0'):?>
		
			<form class="form" action="<?php echo home_url() ?>/satellite.php" name="satellite" id="satellite" method="post">
				
			
		<div class="spaceNotice">
			Building a satellite requires 25 turns
		</div>
		
		<div class="row market_block">	
			<div class="row clan_header_row storeDetails-heads">
				<div class="col-md-3"><strong>Name</strong></div>
				<div class="col-md-5"><strong>Effect</strong></div>
				<div class="col-md-2"><strong>Price</strong></div>
				<div class="col-md-2"></div>
			</div>
				
			
	<?php foreach ($satellites as $key => $satellite) {?>
			
			<div class="row clan_profile_row2">
		
				<div class="col-md-3 center_clan_col market_column marketHeader">
					<?php echo $satellite['name'];?>
				</div>
			
				<div class="col-md-5 clan_column border_bottom_mobile">
						<div class="satDesc"><?php echo $satellite['desc']; ?></div>
				</div>
			
				<div class="col-md-2 clan_column">
					<span class="clan_data_left">Price</span>
					<span class="clan_data_right">
						<span 	class="hover-tip"  
								data-toggle="tooltip" 
								data-original-title="The <?php echo $satellite['name'];?> adds <?php echo $satellite['networth'];?>% networth. 
								$ <?php echo $satellite['price']*$satellite['networth']/100;?> per satellite." 
								data-placement="bottom">
									$ <?php echo number_format($satellite['price'], 0, ',', ' '); ?>
						</span>	
					</span>
			
				</div>
				
				<div class="col-md-2 clan_column">
				<input style="display:none;" type='radio' name='satellite' id="<?php echo $key;?>_satellite_construct" required value="<?php echo $key;?>" >
				<label class="satbutton btn btn-general" for="<?php echo $key;?>_satellite_construct">Select</label>
					
				</div>

			
			</div>
		
			
			<?php }?>
			
		</div>
		<div class="orderbutton">
			<input type="submit" value="Order satellite" class="">
		</div>
	
		
		
		
		</form>

		<?php endif;?>
		
		<?php if($sat_owned != '0'):?>
		<div class="notice_message"><span class="rdw-line">You currently own a <?php echo $satellites[$sat_owned]['name'];?></span>
		<?php 
			$timestamp = current_time('timestamp');
			
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