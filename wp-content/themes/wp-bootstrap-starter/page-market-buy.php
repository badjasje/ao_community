<?php
 /*
 * Template Name: Market Buy
*/
get_header();
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'air';
global $userData;
global $userId;

$disableClass = '';
if(!Market::isOpen()) $disableClass = ' disabledDiv';

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
			<a class="nav-item nav-link navItem <?=($activeTab == 'air' ? 'active' : '').$disableClass?>" data-toggle="tab" data-target="#air" href="?tab=air">Air units</a>
			<a class="nav-item nav-link navItem <?=($activeTab == 'sea' ? 'active' : '').$disableClass?>" data-toggle="tab" data-target="#sea" href="?tab=sea">Sea units</a>
			<a class="nav-item nav-link navItem <?=($activeTab == 'veh' ? 'active' : '').$disableClass?>" data-toggle="tab" data-target="#veh" href="?tab=veh">Vehicles</a>
			<a class="nav-item nav-link navItem <?=($activeTab == 'inf' ? 'active' : '').$disableClass?>" data-toggle="tab" data-target="#inf" href="?tab=inf">Infantry</a>
			<a class="nav-item nav-link navItem" href="/sell" style="background-color: rgba(70, 118, 94, 0.8);">Sell</a>
		</nav>
	</div>

	<div class="fw-row<?=$disableClass?>">
    <form class="form" name="" id="market" method="post">
        <input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
        <div class="tab-content current build_content tabbed-table">

            <?php include('pages/market/buy/type.php'); ?>

            <div class="row statusBlockButtons">

				<div class="col-md-3 totalsField statCol-1">
					Number of units: <span id="total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-2">
					Total cost: $ <span id="order_total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-3">
					Added networth : $ <span id="networth_total">0</span>
				</div>
				<div class="col-md-3 totalsField statCol-4">
					New networth : $ <span id="networth_new"></span>
				</div>
			</div>

			<? if(Market::isOpen()) { ?>
            <input type="submit" value="Place order" class="mainSubmit hoverEffect">
			<? } ?>
        </div>
    </form>
	</div>

</div> <!-- // End pageRow -->

<?php
$args = [
    'posts_per_page'=> -1,
    'meta_key'		=> 'user_placed_id',
    'meta_value'	=> $userId,
    'post_type'     => 'market_order',
];
$orders = get_posts( $args );
if(count($orders) == 0) {
	helpText('Buying units in the market does not use turns', 'market', 'reminder');
}
?>

<script>
(function($) {
	$(document).on('shown.bs.tab', function (event) {
		var currentTab = $(event.target).attr('href');
		history.pushState(null, null, currentTab);
	});

	// Variable to hold request
	var request;
	// Bind to the submit event of our form
	$("#market").submit(function(event){
		$('.pageLoader, #page-cover').show();

		event.preventDefault();
		if (request) request.abort();

		var serializedData = $(this).serialize();
		request = $.ajax({url: "/market.php",type: "post",data: serializedData});
		request.done(function (response, textStatus, jqXHR){
			$('.pageLoader, #page-cover').fadeOut( "fast");

			updateHeaderData();
			var array = JSON.parse(response);

			$.notify({message: array.status},{type:'info', allow_dismiss:true, newest_on_top:true, delay:5000});
			$('#order_total').html('0');
			$('#total').html('0');
			$('#networth_total').html('0');

			if(array.next == true) {
				$.each( array.allordered, function( key, value ) {
					$('#'+key+'_ordered').html(value);
				});

				$.each( array.newmax, function( key, value ) {
					$('#button'+key).html(value);
				});

				$.each( array.usedspace, function( key, value ) {
					$('#'+key+'spacecount').html(number_format(value, 0, ',', ' '));
				});
			}
			$('#market').trigger("reset");
		});
	});

	$(document).on("keyup paste blur change", ".buyInput", function() {
		var sum = 0;
		var orderval = 0;
		var addednw = 0;
		var oldnw = provinceData.networth != 'undefined' ? provinceData.networth : 0;
		$(".buyInput").each(function(){
			var inputkey = $(this).attr("data-key");
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0) {
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += $(this).attr("nw-per-unit")*inputval;
			}
		});

		$("#total").html(sum);
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
		$("#networth_new").html(number_format(addednw+oldnw, 0, ',', ' '));
	});

	$(document).on("click", ".allbutton", function() {
		var sum = 0;
		var inputkey = $(this).attr("data-key");
		var inputamount = $(this).html();
		$(".buy_"+inputkey).val(inputamount);

		var orderval = 0;
		var addednw = 0;
		var oldnw = provinceData.networth != 'undefined' ? provinceData.networth : 0;

		$(".buyInput").each(function(){
			var inputkey = $(this).attr("data-key");
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += $(this).attr("nw-per-unit")*inputval;
			}
		});

		$("#total").html(sum);
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
		$("#networth_new").html(number_format(addednw+oldnw, 0, ',', ' '));
	});

})(jQuery);
</script>
<?php
get_footer();