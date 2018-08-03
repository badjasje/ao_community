<?php
 /*
 * Template Name: Satellite
*/
get_header(); 
include 'satellite_array.php';
global $userId;
global $userData;

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
if($sat_owned != '0'){
	$headerText = 'Satellite owned';
}

$buttonColor = "70, 118, 94"
?>

<div class="row pageRow">	
<div class="blockHeader"><?php echo $headerText;?></div>
<div class="fw-row <?php echo $disableClass;?> satblock">
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
</div> <! // Close Unit row -->
	
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
</div> <! // Close Unit row -->

<?php endforeach;?>

	
</div>
<?php if($sat_level > 0):?>
	<input type="submit" value="Order satellite" class="mainSubmit hoverEffect">

<?php endif;?>
</form>
<?php elseif($sat_progress != '0'):?>
<div class="blockHeader">Satellite in progress: <?php echo $satellites[$sat_progress]['name'];?></div>


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






<?php elseif($sat_owned != '0'):
$timestamp = current_time('timestamp');
			
$timeleft = $sat_endlife-$timestamp;
$timeleft2 = date('H:i:s', $timeleft);
	
	
?>
<div class="blockHeader spaceNotice"><?php echo date('d', $timeleft);?> days</strong> and <strong><?php echo $timeleft2;?></strong> before your satellite re-enters the atmosphere.</div>
<div class="fw-row satblock">
<div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-4 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-4 celBlock">
		Effect
    </div>
    <div class="col-md-4 celBlock"></div>
</div> <! // Close Unit row -->
	

<?php 
	$count = 0;
	foreach ($satellites as $key => $satellite): $count++; 
	if($sat_owned != $key){ continue;}
	?>


<div class="satrow row unitRow fw-row" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-4 celBlock nameBlock sea_heading">
        <?php echo $satellite['name'];?>
    </div>
    <div class="col-md-4 celBlock">
	    <span class="columnDataLeft">Effect</span>
		<span class="columnDataRight"><?php echo $satellite['desc'];?></span>
    </div>
     <div class="col-md-4 celBlock" style="padding:0px;">
	    
    </div>
</div> <! // Close Unit row -->
<?php if($sat_owned == 'stealths'):?>
	<?php if($sat_status == 'active'):?>
		<div class="blockHeader">Stealth satellite active. <?php echo human_time_diff( $stealth_sat_time,$timestamp);?> before you need to reactivate.</div>
	<?php else:?>
		<a class="mainSubmit profileButton activateSatellite" href="#">
			<i class="fa fa-power-off" aria-hidden="true"></i> Activate stealth satellite
		</a>
				
			<?php endif;?>

<?php endif;?>



<?php endforeach;?>
<?php endif; //End check if no sat owned or in progress ?>


<script>
(function($) {
	


$( ".activateSatellite" ).click(function() {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
	
	if(confirm("Are you sure you want to activate your stealth satellite?")){

  	activate = $.ajax({
        url: '/activate_stealthsat.php',
        type: 'get'
    });
    
    activate.done(function (response, textStatus, jqXHR){ 
		
		var array = JSON.parse(response);

		$.notify({
			message: array.status,
			},{
			type: 'info',
			delay: 5000,
			template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
						'<i class="fa fa-info-circle"></i> ' +
						'' +
						'<span data-notify="message">{2}</span>' +
						'</div>'
				});	
		if(array.next == true){
			
			
			$('.activateSatellite').remove();
			$(".satrow").append('<div class="blockHeader">Stealth satellite active. 3.5 hours before you need to reactivate.</div>');
			$('.titleBackWrapper').addClass('stealthsatactive');
		}
	
	});
	
	
}
});
	
var cancel;
$( "body" ).on('submit','#cancelsat',function(cancelevent) {
	jQuery('.pageTitle').html('<i class="fa fa-circle-o-notch fa-spin"></i>');

    cancelevent.preventDefault();

    if (cancel) { cancel.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedcancelData = $form.serialize();

    cancel = $.ajax({
        url: "/cancel_order.php",
        type: "post",
        data: serializedcancelData
    });

    cancel.done(function (response, textStatus, jqXHR){

        var cancelarray = JSON.parse(response);
        	console.log(cancelarray);
        		$( "#order_"+cancelarray.remove ).empty();
				$.notify({
					message: cancelarray.status,
					},{
					type: 'info',
					delay: 5000,
					template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
								'<i class="fa fa-info-circle"></i> ' +
								'' +
								'<span data-notify="message">{2}</span>' +
								'</div>'
						});
			$.get( "<?php echo get_stylesheet_directory_uri();?>/pages/satellite/satOrderBlock.php", function( canceldata ) {
					$( ".satblock" ).empty().append( canceldata );
				
				});
			$('.pageTitle').html('<?php echo get_the_title();?>');
			$('#money').html(number_format(cancelarray.money, 0, ',', ' '));
		

});	});	
})(jQuery);
</script>

<script>
(function($) {
	
var request;
$(document).on('submit','#satbuild',function(event){

	jQuery('.pageTitle').html('<i class="fas fa-circle-notch fa-spin"></i>');
    event.preventDefault();

    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/satellite.php",
        type: "post",
        data: serializedData
    });


    request.done(function (orderresponse, textStatus, jqXHR){

        var array = JSON.parse(orderresponse);
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
								'<i class="fa fa-info-circle"></i> ' +
								'' +
								'<span data-notify="message">{2}</span>' +
								'</div>'
						});	
			if(array.next == true){
				$.get( "<?php echo get_stylesheet_directory_uri();?>/pages/satellite/satOrderBlock.php", function( data ) {
					$( ".satblock" ).empty().append( data );
					$( ".mainSubmit" ).remove();
				});
			}
			$('.pageTitle').html('<?php echo get_the_title();?>');
			$('#order_total').html('0');
			$('#total').html('0');
			$('#networth_total').html('0');
			$('#turn_total').html('0');
			
			$('#money').html(number_format(array.money, 0, ',', ' '));
			$('#turns').html(number_format(array.turns, 0, ',', ' '));
			
			$('#satbuild').trigger("reset");
});	});	
})(jQuery);
</script>

	
	
</div> <!-- end .pageRow -->
<?php
get_footer();