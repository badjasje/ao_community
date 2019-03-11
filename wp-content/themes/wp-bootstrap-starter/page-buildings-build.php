<?php
 /*
 * Template Name: Buildings build
*/
get_header();
$activeTab = 'build';
global $userData;
global $userId;
$user_ID = $userId;

$PwrUsage = $userData['power'][0];

include 'building_array.php';
include 'units_array.php';

$land       = $userData['land'][0];
$builtland  = $userData['builtland'][0];
$totalMoney = $userData['money'][0];
$totalturns = $userData['turns'][0];

$airspace = $userData['airfield'][0] * 10;
$seaspace = $userData['shipyard'][0] * 5;
$vehspace = $userData['warfactory'][0] * 10;
$infspace = $userData['baracks'][0] * 20;

$EElevel = $userData['level_engineering_effectiveness'][0];

$startingbonus = $userData['starting_bonus'][0];
$extra_divide  = 0;
if ($startingbonus == 'defensive') {
	$extra_divide = 5;
}

$totalair = 0;
$totalsea = 0;
$totalveh = 0;
$totalinf = 0;
foreach ($units as $key => $order) {
	$units_owned   = $userData[$key.'_owned'][0];
	$units_ordered = $userData[$key.'_ordered'][0];
	$unittype      = $units[$key]['type'];
	$secondarytype = $units[$key]['sectype'];

	if($secondarytype == 'special'){
		$totalspecial += $units_ordered + $units_owned;
	}

	if ($unittype == 'air') {
		$totalair += $units_ordered + $units_owned;
	}

	if ($unittype == 'sea') {
		$totalsea += $units_ordered + $units_owned;
	}

	if ($unittype == 'inf') {
		$totalinf += $units_ordered + $units_owned;
	}

	if ($unittype == 'veh') {
		$totalveh += $units_ordered + $units_owned;
	}
}

if ($EElevel == 0 || empty($EElevel)) {
	$buildingsPerTurn = 5 + $extra_divide;

	if ($EElevel == 1) {
		$buildingsPerTurn = 10 + $extra_divide;

	}
	if ($EElevel >= 2) {
		$buildingsPerTurn = 15 + $extra_divide;

	}
}
?>

<div class="row pageRow">
	<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#build" href="?tab=build">Build</a>
			<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#demolish" href="?tab=demolish">Demolish</a>
		</nav>
	</div>

	<div class="fw-row">
        <div class="tab-content current tabbed-table">
            <?php include('pages/buildings/type.php'); ?>
        </div>
    </form>
	</div>
</div> <!-- // End pageRow -->

<?php
if($PwrUsage > 50) {
	helpText('Keep your power level around 20% to survive attacks longer', 'buildings', 'reminder');
}
if($userData['advancedpowerplant'][0] > $userData['powerplant'][0]) {
	helpText('Normal powerplants survive attacks longer', 'buildings', 'reminder');
}
?>

<script>
(function($) {

	var request;

	$("#buildbuildings").submit(function(event){
		$('.pageLoader, #page-cover').show();
		$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

		event.preventDefault();

		if (request) { request.abort(); }

		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var serializedData = $form.serialize();

		request = $.ajax({url: "/build.php",type: "POST",data: serializedData});
		request.done(function (response, textStatus, jqXHR){
			updateHeaderData();
			var array = JSON.parse(response);

			$.notify({message: array.status},{type:'info', delay:5000, allow_dismiss:true, newest_on_top:true});
			$.each( array.newmax, function( key, value ) {
				$('#button'+key).html(value);
			});
			$.each( array.newowned, function( key, value ) {
				$('#'+key+'_owned').html(value);
			});

			$('#order_total').html('0');
			$('#total').html('0');

			$('#networth_total').html('0');
			$('#turn_total').html('0');
			$('#landspace').html(array.landspace);

			$('#buildbuildings').trigger("reset");
		});
	});

	var demolish;

	$("#demobuildings").submit(function(demolishevent){
		$('.pageLoader, #page-cover').show();
		$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

		demolishevent.preventDefault();

		if (demolish) { demolish.abort(); }

		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var serializedData = $form.serialize();

		demolish = $.ajax({url: "/demolish.php", type: "POST", data: serializedData});
		demolish.done(function (response, textStatus, jqXHR){
			updateHeaderData();
			var array = JSON.parse(response);
			$.notify({message: array.status},{type:'info', delay:5000, allow_dismiss:true, newest_on_top:true});
			if(array.next == true){
				$.each( array.newmax, function( key, value ) {
					$('#demobutton'+key).html(value);
				});
				$.each( array.newowned, function( key, value ) {
					$('#'+key+'_demo_owned').html(value);
				});
				$('#demototal').html('0');
				$('#demoorder_total').html('0');
				$('#demonetworth_total').html('0');

				$('#demolandspace').html(array.landspace);
			}
			$('#demobuildings').trigger("reset");
		});
	});

	$(document).on("keyup paste blur change", ".buyInput", function() {
		var sum = 0;
		var orderval = 0;
		var addednw = 0;
		var turntot = 0;
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));

		$(".buyInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +($(this).attr( "data-nw" )/100)*orderval;
			}
		});

		$("#turn_total").html(Math.ceil(sum/<?php echo $buildingsPerTurn;?>));

		$("#total").html(sum);
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
		$("#networth_new").html(number_format(addednw+oldnw, 0, ',', ' '));
	});

	$(document).on("click", ".allbutton", function() {
		var sum = 0;
		var inputkey = $(this).attr( "data-key" );
		var inputamount = $(this).html();
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));

		$(".buy_"+inputkey).val(inputamount);

		var orderval = 0
		var addednw = 0;
		var turntot = 0;

		$(".buyInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +$(this).attr("data-nw")/100 * orderval;
			}
		});

		$("#total").html(sum);
		$("#turn_total").html(Math.ceil(sum/<?php echo $buildingsPerTurn;?>));
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
		$("#networth_new").html(number_format(addednw+oldnw, 0, ',', ' '));
	});

	// Demo bds total fields fuckery
	$(document).on("keyup paste blur change", ".sellInput", function() {

		var sum = 0;
		var orderval = 0;
		var lostnw = 0;
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));

		$(".sellInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				lostnw += +$(this).attr("data-nw")/100 * orderval;
			}
		});

		$("#demototal").html(sum);
		$("#demoorder_total").html(number_format(orderval, 0, ',', ' '));
		$("#demonetworth_total").html(number_format(lostnw, 0, ',', ' '));
		$("#networth_new_demo").html(number_format(oldnw-lostnw, 0, ',', ' '));

	});

	$(document).on('click', '.sellall', function() {
		var sum = 0;
		var inputkey = $(this).attr( "data-key" );
		var inputamount = $(this).html();
		$("#demo_"+inputkey).val(inputamount);

		var orderval = 0
		var addednw = 0;
		var oldnw = parseInt($('#masthead .networthheader').text().replace(/\s/g,''));

		$(".sellInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +$(this).attr( "data-nw" )/100*orderval;
			}
		});

		$("#demototal").html(sum);
		$("#demoorder_total").html(number_format(orderval, 0, ',', ' '));
		$("#demonetworth_total").html(number_format(addednw, 0, ',', ' '));
		$("#networth_new_demo").html(number_format(addednw+oldnw, 0, ',', ' '));
	});

})(jQuery);
</script>
<?php
get_footer();