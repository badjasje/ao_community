<?php
 /*
 * Template Name: Market Orders
*/
get_header(); 
global $userData;
global $userId;

update_user_meta($userId, 'user_lock', 0);
include 'units_array.php';
include 'missiles_array.php';
include 'satellite_array.php';
$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94"
?>

<div class="row pageRow">	
	
	


<div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-3 celBlock nameBlock">Name</div>
    <div class="col-md-2 celBlock">Ordered</div>
    <div class="col-md-2 celBlock">Order value</div>
    <div class="col-md-2 celBlock">Time left</div>
    <div class="col-md-3 celBlock"></div>
</div> <! // Close Unit row -->


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
    $unit_type = $orderData['unit_type'][0];
    $userId = $order->post_author;
    $delivery_time = $orderData['delivery_time'][0];

    $timeLeft = $delivery_time-$timestamp;

    if($timeLeft >= 0) {
        $orderValue = $orderData['order_value'][0];

        if($order_type == 'missile'){
            $totalNetworth += $orderValue*$missiles[$unit_type]['networth']/100;
        }
        
        if($order_type == 'satellite'){
            $totalNetworth += $orderValue*$satellites[$unit_type]['networth']/100;
        }

        if($order_type == 'units'){
            $totalNetworth += (($units[$unit_type]['price'] *$units[$unit_type]['networth']) / 100) * $units_in_this_order;
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
			<form name="cancel" id="cancel">
				<input style="display:none;"type="text" id="order" name="order" value="<?php echo $order->ID;?>"/>
				<button onclick="return confirm('Are you sure you want to cancel this order?')" class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/220);?>);"type="submit" >Cancel</button>
			</form>
		<?php endif;?>
    </div>
</div> <! // Close Unit row -->


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

<script>
(function($) {
	

// Variable to hold request
var request;

// Bind to the submit event of our form
$('form').submit(function( event ) {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    //$inputs.prop("disabled", true);

    // Fire off the request to /form.php
    request = $.ajax({
        url: "/cancel_order.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        	console.log(array);
        		$( "#order_"+array.remove ).empty();
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					allow_dismiss: true,
					newest_on_top: true,
						});	
			
			$('#money').html(number_format(array.money, 0, ',', ' '));
		

});	});	
})(jQuery);
</script>

</div> <!-- End pageRow -->
<?php
get_footer();