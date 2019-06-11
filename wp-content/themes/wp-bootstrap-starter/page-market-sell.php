<?php
 /*
 * Template Name: Market Sell
*/
get_header();
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'air';
global $userData;
global $userId;
$marketSellMultiplier = (2.2 * 0.5);
include 'units_array.php';
include 'count_functions.php';

$specialSold = $userData['special_sold_today'][0];

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

	<?php if(get_field('game_status','option') == 'Live'):?>
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'air' ? 'active' : ''; ?>" data-toggle="tab" data-target="#air" href="?tab=air">Air units</a>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'sea' ? 'active' : ''; ?>" data-toggle="tab" data-target="#sea" href="?tab=sea">Sea units</a>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'veh' ? 'active' : ''; ?> " data-toggle="tab" data-target="#veh" href="?tab=veh">Vehicles</a>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'inf' ? 'active' : ''; ?>" data-toggle="tab" data-target="#inf" href="?tab=inf">Infantry</a>
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
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));

		$(".sellInput").each(function(){
			var inputkey = $(this).attr("data-key");
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0) {
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				nwlost += parseInt($(this).attr("data-baseprice")) * inputval * ($(this).attr("data-nw")/100);
			}
		});

		$("#totalsell").html(sum);
		$("#return_val").html(number_format(orderval, 0, ',', ' '));
		$("#nw_lost").html(number_format(nwlost, 0, ',', ' '));
		$("#networth_new").html(number_format(oldnw-nwlost, 0, ',', ' '));
	});

	$(document).on('click', '.sellall', function() {
		var sum = 0;
		var inputkey = $(this).attr("data-key");
		var inputamount = parseInt($(this).text());
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));
		$("#sell_"+inputkey).val(inputamount);

		var orderval = 0
		var nwlost = 0;
		$(".sellInput").each(function(){
			var inputkey = $(this).attr("data-key");
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				nwlost += parseInt($(this).attr("data-baseprice")) * inputval * ($(this).attr("data-nw")/100);
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

		event.preventDefault();
		if (request) { request.abort(); }

		var serializedData = $(this).serialize();
		request = $.ajax({url: "/sell_units.php",type: "post",data: serializedData});
		request.done(function (response, textStatus, jqXHR){
			$('.pageLoader, #page-cover').fadeOut( "fast");
			updateHeaderData();
			var array = JSON.parse(response);
			$.each(array.newmax, function( key, value ) {
				$('#maxsell_'+key).text(parseInt(value));
				if(parseInt(value) <= 0){
					$('#sell_'+key).remove();
				}
			});
			$.notify({message: array.status}, {type:'info', delay:5000, allow_dismiss:true, newest_on_top:true});
			$('#totalsell').html('0');
			$('#return_val').html('0');
			$('#nw_lost').html('0');
			$('#sellmarket').trigger("reset");
			$("#networth_new").html(number_format(array.networth, 0, ',', ' '));
		});
	});
})(jQuery);
</script>

<?php
get_footer();