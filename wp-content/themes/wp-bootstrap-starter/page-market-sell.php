<?php
 /*
 * Template Name: Market Sell
*/
get_header(); 

global $userData;
global $userId;
$marketSellMultiplier = (2.2 * 0.5);
include 'units_array.php';
include 'count_functions.php';

$totalMoney = $userData['money'][0];

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

	<?php if(get_field('game_status','option') == 'Live'):?>
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#air" href="?tab=air">Air units</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#sea" href="?tab=sea">Sea units</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#veh" href="?tab=veh">Vehicles</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#inf" href="?tab=inf">Infantry</a>
			<a class="nav-item nav-link navItem" href="/buy" style="background-color: rgba(70, 118, 94, 0.8);">Buy</a>
		</nav>
	</div>
	
	<div class="fw-row">
    <form class="form" name="" id="sellmarket" method="post">
        <input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
        <div class="tab-content current build_content tabbed-table">

            <?php include('pages/market/sell/type.php'); ?>
            
<div class="row statusBlockButtons">

	<div class="col-md-3 totalsField statCol-1">
		Number of units: <span id="totalsell">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-2">
		Return value: $ <span id="return_val">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-3">
		Networth lost: $ <span id="nw_lost">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-4">
		New networth: $ <span id="networth_new"></span>
	</div>
</div>
            
            

            <input type="submit" value="Sell units" class="mainSubmit hoverEffect">
            
        </div>
    </form>
	</div>
    <?php endif;?>


	
</div> <!-- // End pageRow -->
<script>
(function($) {
	
$(document).on("keyup paste blur change", ".sellInput", function() {

	
    var sum = 0;
    var orderval = 0;
    var nwlost = 0;
    var oldnw = <?php echo $userData['networth'][0];?>;

    $(".sellInput").each(function(){
	    var inputval = $(this).val();
	    console.log(inputval);
        if(inputval > 0){
	        sum += +$(this).val();
        	orderval += +$(this).attr( "data-price" )*inputval;
        	nwlost += +$(this).attr( "data-price" )*inputval*($(this).attr( "data-nw" )/100);
        	
        }
    });
	
    $("#totalsell").html(sum);
    $("#return_val").html(number_format(orderval, 0, ',', ' '));
    $("#nw_lost").html(number_format(nwlost, 0, ',', ' '));
    $("#networth_new").html(number_format(oldnw-nwlost, 0, ',', ' '));
    
});

$(document).on('click', '.sellall', function() {
	var sum = 0;
	var inputkey = $(this).attr( "data-key" );
	var inputamount = $(this).html();
	var oldnw = <?php echo $userData['networth'][0];?>;

	$("#sell_"+inputkey).val(inputamount);
	
	var orderval = 0
	var nwlost = 0;

	$(".sellInput").each(function(){
        var inputval = $(this).val();
        
        if(inputval > 0){
	        sum += +$(this).val();
        	orderval += +$(this).attr( "data-price" )*inputval;
        	nwlost += +$(this).attr( "data-price" )*inputval*($(this).attr( "data-nw" )/100);
		}
    });

   
    $("#totalsell").html(sum);
    $("#return_val").html(number_format(orderval, 0, ',', ' '));
    $("#nw_lost").html(number_format(nwlost, 0, ',', ' '));
    $("#networth_new").html(number_format(oldnw-nwlost, 0, ',', ' '));
});


var request;
$("#sellmarket").submit(function(event){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();

    if (request) { request.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/sell_units.php",
        type: "post",
        data: serializedData
    });


    request.done(function (response, textStatus, jqXHR){
		updateHeaderData();
        var array = JSON.parse(response);
        	console.log(array);
        		$.each( array.allowned, function( key, value ) {
					$('#maxsell_'+key).html(value);
					if(value <= 0){
						$('#sell_'+key).remove();
					}
				});

				
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
			$('#totalsell').html('0');
			$('#return_val').html('0');
			$('#nw_lost').html('0');
			$('#sellmarket').trigger("reset");
});	});	
})(jQuery);
</script>
 
<?php
get_footer();