<?php
 /*
 * Template Name: Unit turn build
*/
get_header(); 

global $userData;
global $userId;

include 'units_array.php';
include 'count_functions.php';

$totalMoney = $userData['money'][0];
$totalturns = $userData['turns'][0];
// Calculate space for special units.
$spies = $userData['spy_owned'][0];
$spiesOrdered = $userData['spy_ordered'][0];
$thieves = $userData['thief_owned'][0];
$thievesOrdered = $userData['thief_ordered'][0];
$planes = $userData['spyplane_owned'][0];
$planesOrdered = $userData['spyplane_ordered'][0];
$sniper = $userData['sniper_owned'][0];
$snipersOrdered = $userData['sniper_ordered'][0];


$commandCenters = $userData['command_centre'][0];
$space = [
    'air' => $userData['airfield'][0] * 10,
    'sea' => $userData['shipyard'][0] * 5,
    'veh' => $userData['warfactory'][0] * 10,
    'inf' => $userData['baracks'][0] * 20,
    'special' => ($commandCenters * 5) - $spies - $thieves - $planes - $spiesOrdered - $thievesOrdered - $planesOrdered - $sniper - $snipersOrdered
];

$usedSpace = [
    'air' => count_airspace($userId),
    'sea' => count_seaspace($userId),
    'veh' => count_vehspace($userId),
    'inf' => count_infspace($userId),
];
$unitsPerTurn = [
    'air' => 10,
    'sea' => 5,
    'veh' => 10,
    'inf' => 20,
];

$discountLevel = $userData['level_market_discount'][0];

$discount = 1.0;

if($discountLevel == 1){
	$discount = $discount - 0.15;
} elseif($discountLevel >= 2){
	$discount = $discount - 0.3;
}
$startingBonus = $userData['starting_bonus'][0];
if($startingBonus == 'shipping'){
    $discount = $discount - 0.1;
}

$endDate = get_field('end_date','option');
$endStamp = strtotime($endDate);
$timestamp = current_time('timestamp');
$timeLeft = $endStamp-$timestamp;
$marketClose = $timeLeft;

$specialUnits = [
    'spy',
    'thief',
    'sniper',
    'saboteur',
    'spyplane'
];

$unitTypes = [
    'air' => 'Air',
    'sea' => 'Sea',
    'veh' => 'Vehicles',
    'inf' => 'Infantry'
];

$marketShippingLevel = $userData['level_shipping_time'][0];
	if($marketShippingLevel == 1){
		$hours = 9;
	} elseif($marketShippingLevel == 2){
		$hours = 6;
	} else {
		$hours = 12;
}
?>

<div class="row pageRow">	

	
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#air" href="?tab=air">Air units</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#sea" href="?tab=sea">Sea units</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#veh" href="?tab=veh">Vehicles</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#inf" href="?tab=inf">Infantry</a>
		</nav>
	</div>
	
	<div class="fw-row">
    <form class="form" id="turnbuild">
        <div class="tab-content current build_content tabbed-table">

            <?php include('pages/units/type.php'); ?>
            
            <div class="row statusBlockButtons">

				<div class="col-md-3 totalsField statCol-1">
					Number of units: <span id="total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-2">
					Total cost: $ <span id="order_total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-3">
					Turns required: <span id="turn_total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-4">
					Added networth : $ <span id="networth_total">0</span>
				</div>
			</div>
            
            

            <input type="submit" value="Turn build" class="mainSubmit hoverEffect">
            
        </div>
    </form>
	</div>
	
</div> <!-- // End pageRow -->

<script>
	
	
(function($) {
	
var request;

$("#turnbuild").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();

    if (request) { request.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/turnbuild.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        	console.log(array);
        		
				
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
			$('#order_total').html('0');
			$('#total').html('0');
			$('#networth_total').html('0');
			$('#turn_total').html('0');
			if(array.next == true){
				$.each( array.allowned, function( key, value ) {
					$('#'+key+'_owned').html(value);
				});
				
				$.each( array.newmax, function( key, value ) {
					$('#button'+key).html(value);
				});
				
				$.each( array.usedspace, function( key, value ) {
					$('#'+key+'spacecount').html(number_format(value, 0, ',', ' '));
				});
				$('#money').html(number_format(array.money, 0, ',', ' '));
				$('#turns').html(number_format(array.turns, 0, ',', ' '));
				$('#networth').html(number_format(array.networth, 0, ',', ' '));
			}
			$('#turnbuild').trigger("reset");
});	});	
})(jQuery);
	
	// Set total number of units value
	jQuery('body').on('change', '.unitInput', function() {
		
        var arr = document.getElementsByClassName('unitInput');
        var tot=0;
        for(var i=0;i<arr.length;i++){
            if(parseInt(arr[i].value))
                tot += parseInt(arr[i].value);
        }
        document.getElementById('total').value = tot;
    
        var span = document.getElementById('total');

        while( span.firstChild ) {
            span.removeChild( span.firstChild );
        }

        span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
    jQuery(document).on('shown.bs.tab', function (event) {
        var currentTab = jQuery(event.target).attr('href');
        history.pushState(null, null, currentTab);
        jQuery('#currentTab').val(currentTab);
    });
</script>	 
<?php
get_footer();