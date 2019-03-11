<?php
require_once("../../../../../wp-load.php");
include("../../../../../satellite_array.php");
global $userId;
$userData = get_user_meta($userId);

$sat_level = $userData['level_satellite_construction'][0];
$sat_owned = (string)$userData['sat_owned'][0];
$sat_progress = (string)$userData['sat_in_progress'][0];

$sat_endlife = $userData['sat_endlife'][0];
$sat_status = $userData['stealth_sat_status'][0];
$stealth_sat_time = $userData['stealth_sat_time'][0];
$backColor = "45, 67, 81";

$headerText = 'Building a satellite requires 25 turns';
$disableClass = '';
if($sat_level == 0){
	$headerText = '<i class="fas fa-exclamation-triangle"></i> Please research satellite construction';
	$disableClass = 'disabledDiv';
}

$buttonColor = "70, 118, 94"

?>
<?php if($sat_owned == '0' && $sat_progress == '0'):?>
<div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-2 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-6 celBlock">
		Effect
    </div>
    <div class="col-md-2 celBlock">
		Price
    </div>
    <div class="col-md-2 celBlock"></div>
</div> <!-- //Close Unit row -->

<form class="form" name="" id="satbuild" method="post">
<?php
	$count = 0;
	foreach ($satellites as $key => $satellite): $count++; ?>


<div class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock sea_heading">
        <?php echo $satellite['name'];?>
    </div>
    <div class="col-md-6 celBlock">
	    <span class="columnDataLeft">Effect</span>
		<span class="columnDataRight"><?php echo $satellite['desc'];?></span>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Price</span>
	    <span class="columnDataRight">
	            $ <?php echo $satellite['price'];?>
	    </span>
    </div>
    <div class="col-md-2 celBlock" style="padding:0px;">
	    <input style="display:none;" type="radio" name="satellite" id="<?php echo $key;?>" value="<?php echo $key;?>" checked>
			<label style="height:100%;background-color:rgba(70, 118, 94,<?php echo 0.95-($count/12);?>)" class="mainSubmit hoverEffect attackSelect" for="<?php echo $key;?>">
				Select
			</label>
    </div>
</div> <!-- //Close Unit row -->

<?php endforeach;?>



<?php if($sat_level > 0):?>
	<input type="submit" value="Order satellite" class="mainSubmit hoverEffect">

<?php endif;?>
</form>
<?php elseif($sat_progress != '0'):?>



<div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-3 celBlock nameBlock">Name</div>
    <div class="col-md-2 celBlock">Ordered</div>
    <div class="col-md-2 celBlock">Order value</div>
    <div class="col-md-2 celBlock">Time left</div>
    <div class="col-md-3 celBlock"></div>
</div> <!-- //Close Unit row -->


<?php

$args = [
    'posts_per_page'   => -1,
    'meta_key'		=> 'user_placed_id',
    'meta_value'	=> $userId,
    'post_type'        => 'market_order',
];

$orders = get_posts( $args );
$count = 0;
$timestamp = current_time('timestamp');
$totalOrder = 0;
$totalNetworth = 0;
$totalOrderValue = 0;

foreach ($orders as $order){
	$count++;
    $orderId = $order->ID;
    $orderData = get_post_meta($orderId);

    $units_in_this_order = $orderData['amount_ordered'][0];

    $order_type = $orderData['order_type'][0];
    if($order_type != 'satellite'){
	    continue;
    }
    $unit_type = $orderData['unit_type'][0];
    $userId = $order->post_author;
    $delivery_time = $orderData['delivery_time'][0];

    $timeLeft = $delivery_time-$timestamp;

    if($timeLeft >= 0) {
        $orderValue = $orderData['order_value'][0];



        if($order_type == 'satellite'){
            $totalNetworth += $orderValue*$satellites[$unit_type]['networth']/100;
        }

        $timeLeft = date('H:i:s', $timeLeft);
        $totalOrder += $units_in_this_order;
        $totalOrderValue += $orderValue;
    ?>

<div id="order_<?php echo $orderId;?>" class="row unitRow fw-row" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-3 celBlock nameBlock sea_heading">
        <?php echo get_the_title($order->ID);?>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Ordered</span>
	    <span class="columnDataRight"><?php echo $units_in_this_order;?></span>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Order value</span>
	    <span class="columnDataRight">$ <?php echo number_format($orderValue, 0, ',', ' ');?></span>
	    </span>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Time left</span>
		<span class="columnDataRight"><?php echo $timeLeft;?></span>
    </div>
    <div class="col-md-3 celBlock" style="padding:0px;">
	    <?php if($order_type != 'missile'):?>
			<form name="cancel" id="cancelsat">
				<input style="display:none;"type="text" id="order" name="order" value="<?php echo $order->ID;?>"/>
				<button onclick="return confirm('Are you sure you want to cancel this order?')" class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/220);?>);"type="submit" >Cancel</button>
			</form>
		<?php endif;?>
    </div>
</div> <!-- //Close Unit row -->


<?php }}?>

<div class="row statusBlockButtons">
	<div class="col-md-4 totalsField statCol-1">
		Ordered: <?php echo $totalOrder;?>
	</div>
	<div class="col-md-4 totalsField statCol-2">
		Total order value: $ <?php echo number_format($totalOrderValue, 0, ',', ' ');?>
	</div>
	<div class="col-md-4 totalsField statCol-3">
		Added networth : $ <?php echo number_format($totalNetworth, 0, ',', ' ');?>
	</div>
</div>






<?php elseif($sat_owned != '0'):?>
<div class="blockHeader">Satellite owned: <?php echo $satellites[$sat_owned]['name'];?></div>
<?php endif; //End check if no sat owned or in progress ?>
