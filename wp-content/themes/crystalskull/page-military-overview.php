<?php
 /*
 * Template Name: Military overview
 */
 
$user__ID = $_GET['id'];
$viewerID = get_current_user_id();
if(empty($user__ID)){
	wp_redirect(get_permalink(3486));
}
$user = get_userdata($user__ID);
if ( $user === false ) {
	wp_redirect(get_permalink(3486));
}

$clan_id = get_user_meta($user__ID, 'clan_id_user',true);

$clanmembers = get_post_meta($clan_id,'clan_members',true);

if(!in_array($viewerID, $clanmembers)){
	wp_redirect(get_permalink(3486));
}

include 'units_array.php';
include 'building_array.php';
include 'missiles_array.php';
include 'interest_array.php';
include 'research_array.php';

// Get orders 

$args = array(
	'posts_per_page'   => -1,
	'meta_key'		=> 'user_placed_id',
	'meta_value'	=> $user__ID,
	'post_type'        => 'market_order',
	);
$orders = get_posts( $args ); 
	
$timestamp = strtotime(date('Y-m-d H:i:s'));



$banklevel = get_user_meta($user__ID, 'level_bank_management')[0];
$startingbonus = get_user_meta($user__ID, 'starting_bonus',true);
	$finance_multi = 1;
	if($startingbonus == 'finance'){
		$finance_multi = 1.5;
	}

if($banklevel == 0){
	$extra_interest = 0;
	$max_dep = 250000*$finance_multi;
	$max_tot = 2500000*$finance_multi;
}
if($banklevel == 1){
	$extra_interest = 0.5;
	$max_dep = 350000*$finance_multi;
	$max_tot = 3500000;
}
if($banklevel == 2){
	$extra_interest = 0.75;
	$max_dep = 450000*$finance_multi;
	$max_tot = 4500000;
}
if($banklevel == 3){
	$extra_interest = 1;
	$max_dep = 500000*$finance_multi;
	$max_tot = 5000000*$finance_multi;
}
$total_deposited = 0;
$total_final = 0;
$unlocked = 0;
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            

 <div class="col-lg-12 col-md-12">
<ul class="target_info media-list">
	<li class="media ">
		<div class="media-left">
			<div class="leftAvatar"><?php echo small_avatar($user__ID,'');?></div>
		</div>
	<div class="media-body">
	<h4 class="media-heading">Viewing <?php echo LinkUtil::user_link($user__ID); ?></h4>
	Current networth <?php echo networth_range($user__ID);?>
	</div>
	</li>
	</ul>
 </div>
		

<!-- owned/ordered unites block -->
<div class="row">
	<div class="col-md-6">
<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Units</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-6"><strong>Owned (ordered)</strong></div>
	</div> 
	
	
<?php foreach ($units as $key => $unit) {
		$owned = get_user_meta($user__ID, $key.'_owned', true);
		$ordered = get_user_meta($user__ID, $key.'_ordered', true);
		if($owned > 0 || $ordered > 0){
?>	
	<div class="row clan_profile_row">
		<div class="col-md-6">
			<span class="clan_data_left">Name</span>
			<span class="clan_data_right">
			<?php echo $unit['normalname'];?>
			</span>
		</div>
		<div class="col-md-6">
			<span class="clan_data_left">Owned (ordered)</span>
			<span class="clan_data_right">
			<?php echo $owned;?> (<?php echo $ordered;?>)
			</span>
		</div>
	</div>
<?php }}?>	
	
</div>
	</div>

<div class="col-md-6">
<!-- on order block -->

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Current orders</div>
	</div>
	<?php if(count($orders) == 0):?>
	<div class="row clan_header_row">
		<div class="col-md-12"><strong>No orders to display</strong></div>
	</div> 
	<?php else:?>
	<div class="row clan_header_row">
		<div class="col-md-4"><strong>Name</strong></div>
		<div class="col-md-4"><strong>Ordered</strong></div>
		<div class="col-md-4"><strong>Time left</strong></div>
	</div> 
	
	
<?php foreach ($orders as $key => $order) {
		$units_in_this_order = get_post_meta($order->ID,'amount_ordered',true);
		$order_type = get_post_meta($order->ID,'order_type',true);

		$user_ID = $order->post_author;
		$delivery_time = get_post_meta($order->ID,'delivery_time',true);
		
	
		$timeleft = $delivery_time-$timestamp;
		$timeleft = date('H:i:s', $timeleft);
?>	
	<div class="row clan_profile_row">
		<div class="col-md-4">
			<span class="clan_data_left">Name</span>
			<span class="clan_data_right">
			<?php echo get_the_title($order->ID);?>
			</span>
		</div>
		<div class="col-md-4">
			<span class="clan_data_left">Ordered</span>
			<span class="clan_data_right">
			<?php echo $units_in_this_order;?>
			</span>
		</div>
		<div class="col-md-4">
			<span class="clan_data_left">Time left</span>
			<span class="clan_data_right">
			<?php echo $timeleft;?>
			</span>
		</div>
	</div>
<?php }?>	
<?php endif;?>
</div>

<!-- owned/ordered missiles block -->

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Missiles</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-6"><strong>Owned (ordered)</strong></div>
	</div> 
	
	
<?php foreach ($missiles as $key => $missile) {
		$owned = get_user_meta($user__ID, $key.'_owned', true);
		$ordered = get_user_meta($user__ID, $key.'_ordered', true);
		if($owned > 0 || $ordered > 0){
?>	
	<div class="row clan_profile_row">
		<div class="col-md-6">
			<span class="clan_data_left">Name</span>
			<span class="clan_data_right">
			<?php echo $missile['normalname'];?>
			</span>
		</div>
		<div class="col-md-6">
			<span class="clan_data_left">Owned (ordered)</span>
			<span class="clan_data_right">
			<?php echo $owned;?> (<?php echo $ordered;?>)
			</span>
		</div>
	</div>
<?php }}?>	
	
</div>



</div>
</div>



<!-- owned buildings block -->

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Buildings</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-6"><strong>Owned</strong></div>
	</div> 
	
	
<?php foreach ($buildings as $key => $building) {
		$owned = get_user_meta($user__ID, $key, true);
		if($owned > 0){
?>	
	<div class="row clan_profile_row">
		<div class="col-md-6">
			<span class="clan_data_left">Name</span>
			<span class="clan_data_right">
			<?php echo $building['normalname'];?>
			</span>
		</div>
		<div class="col-md-6">
			<span class="clan_data_left">Owned</span>
			<span class="clan_data_right">
			<?php echo $owned;?>
			</span>
		</div>
	</div>
<?php }}?>	
	
</div>  
	
	
	
	            
<!-- Bank block -->


<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Deposits</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-3"><strong>Deposited</strong></div>
		<div class="col-md-3"><strong>Including interest</strong></div>
		<div class="col-md-3"><strong>Releasedate</strong></div>
	</div>
	
	
	
<?php 	
	
	$args = array(
		'posts_per_page'   => -1,
		'author'	=> $user__ID,
		'post_type'        => 'deposit',
		'meta_key' => 'release_date',
		'orderby'    => 'meta_value_num',
		);
	
	$deposits = get_posts( $args ); 
	
	foreach ($deposits as $deposit) {
		$days = get_post_meta($deposit->ID,'days',true);
		$deposited = get_post_meta($deposit->ID,'amount',true);
		$total_deposited+=$deposited;
		$amount = get_post_meta($deposit->ID,'amount')[0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = get_post_meta($deposit->ID,'release_date',true);
	?>
	
	<div class="row clan_profile_row">
		<div class="col-md-3">
			<span class="clan_data_left">Deposited</span>
			<span class="clan_data_right">
			$ <?php echo number_format($deposited, 0, ',', ' '); ?>
			</span>
		</div>
		<div class="col-md-3">
			<span class="clan_data_left">Including interest</span>
			<span class="clan_data_right">
			$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>
			</span>
		</div>
		<div class="col-md-3">
			<span class="clan_data_left">Release date</span>
			<span class="clan_data_right">
			<?php echo date('H:i | d-m-Y', $release_stamp);?>
			</span>
		</div>
	</div>
	
	<?php }?>
	<div class="row">
		<div class="depTotals">	
			<strong>Total deposited:</strong> $ <?php echo number_format($total_deposited, 0, ',', ' '); ?> (<?php echo count_deposits($user_ID);?> deposits)<br/>
			<strong>Total final:</strong> $ <?php echo number_format($total_final, 0, ',', ' '); ?><br/>
			<strong>Total Available (unlocked):</strong> $ <?php echo number_format($unlocked, 0, ',', ' '); ?>
		</div>
	</div>
</div>     
	          
	          
	          
	          
	          
<!-- Research block -->

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Research</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-6"><strong>Name</strong></div>
		<div class="col-md-6"><strong>Level</strong></div>
	</div> 
	
	
<?php foreach ($researches as $key => $research) {
		$level = get_user_meta($user__ID, 'level_'.$key,true);

?>	
	<div class="row clan_profile_row">
		<div class="col-md-6">
			<span class="clan_data_left">Name</span>
			<span class="clan_data_right">
			<?php echo $research['name'];?>
			</span>
		</div>
		<div class="col-md-6">
			<span class="clan_data_left">Level</span>
			<span class="clan_data_right">
			<?php echo $level;?>
			</span>
		</div>
	</div>
<?php }?>	
	
</div>  	          
<div class="row profile_block"> 
	<div class="col-md-6">	      
		<h2>Networth breakdown</h2>
		<canvas id="nwbreakdown" width="338" height="338"></canvas>
	</div>
	<div class="col-md-6">	      
	</div>
</div>
<script>
       
            // pie chart data
            var pieData = [
                {	label: 'Satellite networth',
                    value: <?php echo get_user_meta($user__ID, 'sat_nw', true);?>,
                    color:"#2D434E"
                },
                {	label: 'Research networth',
                    value : <?php echo get_user_meta($user__ID, 'research_nw', true);?>,
                    color : "#1B3642"
                },
                {	label: 'Building networth',
                    value : <?php echo get_user_meta($user__ID, 'building_nw', true);?>,
                    color : "#6C708E"
                },
                {	label: 'Unit networth',
                    value : <?php echo get_user_meta($user__ID, 'unit_nw', true);?>,
                    color : "#121636"
                },
                {	label: 'Land networth',
                    value : <?php echo get_user_meta($user__ID, 'land_nw', true);?>,
                    color : "#49775D"
                },
                {	label: 'Missile networth',
                    value : <?php echo get_user_meta($user__ID, 'missile_nw', true);?>,
                    color : "#7B6C44"
                }
            ];
            // pie chart options
            var pieOptions = {
                 segmentShowStroke : true,
                 animateScale : true
            }
            // get pie chart canvas
            var countries= document.getElementById("nwbreakdown").getContext("2d");
            // draw pie chart
            new Chart(countries).Pie(pieData, pieOptions);
         
        </script>
	          
	  
	            
       
            
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>