<?php
/**
 * Template Name: Missiles
 */

get_header();

global $userData;
global $userId;

$activeTab = 'buy';
include 'count_functions.php';
include 'missiles_array.php';

$missilespace = $userData['silo'][0];
$totalMoney = $userData['money'][0];
$totalturns = $userData['turns'][0];
$totalmissiles = count_missilespace($userId);
$tomahawkspace = $userData['submarine_owned'][0]*2;
$missileAccLevel = $userData['level_missile_accuracy'][0];
$buyBackColor = "45, 67, 81";
$sellBackColor = "127, 82, 67"
?>

<div class="row pageRow">


<div class="fw-row">
	<nav id="allthetabs" class="nav nav-pills nav-fill flex-column flex-sm-row">
		<a class="nav-item nav-link navItem active" data-toggle="tab" data-target="#buy" href="?tab=buy">Buy</a>
		<a class="nav-item nav-link navItem" data-toggle="tab" data-target="#sell" href="?tab=sell">Sell</a>
	</nav>
</div>


<div class="tab-content current tabbed-table">
	<?php include 'pages/missiles/buy.php'; ?>
	<?php include 'pages/missiles/sell.php'; ?>
</div>

</div> <!-- end .pageRow -->

<script>
(function($) {

	$(document).on("keyup paste blur change", ".buyInput", function() {

		var sum = 0;
		var orderval = 0;
		var addednw = 0;
		var turntot = 0;
		$(".buyInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +$(this).attr( "data-nw" )/100*orderval;
				var inputkey = $(this).attr( "data-key" );
				if(inputkey == 'tomahawk'){
					turntot += Math.ceil(inputval/3);
				}else{
					turntot += Math.ceil(inputval*5);
				}
			}
		});

		$("#turn_total").html(turntot);

		$("#total").html(sum);
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
	});

	$(document).on("click", ".allbutton", function() {

		var sum = 0;
		var inputkey = $(this).attr( "data-key" );
		var inputamount = parseInt($(this).data('amount'));
		$(".buy_"+inputkey).val(inputamount);

		var orderval = 0
		var addednw = 0;
		var turntot = 0;
		$(".buyInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +$(this).attr( "data-nw" )/100 * orderval;
				var inputkey = $(this).attr( "data-key" );
				if(inputkey == 'tomahawk'){
					turntot += Math.ceil(inputval/3);
				}else{
					turntot += Math.ceil(inputval*5);
				}
			}
		});

		$("#turn_total").html(turntot);
		$("#total").html(sum);
		$("#order_total").html(number_format(orderval, 0, ',', ' '));
		$("#networth_total").html(number_format(addednw, 0, ',', ' '));
	});

	$(document).on("keyup paste blur change", ".sellInput", function() {
		var sum = 0;
		var orderval = 0;
		var addednw = 0;
		$(".sellInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +($(this).attr("data-nw")/100) * (parseInt($(this).attr("data-nwprice")) * inputval);
			}
		});

		$("#totalsell").html(sum);
		$("#return_val").html(number_format(orderval, 0, ',', ' '));
		$("#nw_lost").html(number_format(addednw, 0, ',', ' '));
	});

	$('body').on('click', '.sellall', function() {

		var sum = 0;
		var inputkey = $(this).attr( "data-key" );
		var inputamount = parseInt($(this).data('amount'));
		$("#sell_"+inputkey).val(inputamount);
		var orderval = 0
		var addednw = 0;
		$(".sellInput").each(function(){
			var inputval = Math.abs(parseInt($(this).val()));
			if(inputval > 0){
				sum += inputval;
				orderval += parseInt($(this).attr("data-price")) * inputval;
				addednw += +($(this).attr("data-nw")/100) * (parseInt($(this).attr("data-nwprice")) * inputval);
			}
		});

		$("#totalsell").html(sum);
		$("#return_val").html(number_format(orderval, 0, ',', ' '));
		$("#nw_lost").html(number_format(addednw, 0, ',', ' '));
	});

	// Post request for ordering missiles yay
	var request;
	$("#ordermissiles").submit(function(event){
		$('.pageLoader, #page-cover').show();
		event.preventDefault();
		if (request) { request.abort(); }

		var serializedData = $(this).serialize();
		request = $.ajax({url: "/missiles.php", type: "POST", data: serializedData});
		request.done(function (response, textStatus, jqXHR){
			$('.pageLoader, #page-cover').fadeOut( "fast");
			updateHeaderData();
			var array = JSON.parse(response);
			$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true,});
			if(array.next == true){
				$('#order_total').html('0');
				$('#total').html('0');
				$('#networth_total').html('0');
				$('#turn_total').html('0');
				$('#ordermissiles').trigger("reset");
				$.each( array.newmax, function( key, value ) {
					$('#'+key).html(value);
					$('#'+key).attr("data-amount",value); //setter
				});
				$.each( array.allordered, function( key, value ) {
					$('#'+key+'_ordered').html(value);
				});
			}
		});
	});

	// Post request for selling missiles yay
	var sellrequest;
	$("#sellmissiles").submit(function(event){
		$('.pageLoader, #page-cover').show();
		event.preventDefault();
		if (sellrequest) { sellrequest.abort(); }

		var serializedData = $(this).serialize();
		sellrequest = $.ajax({url: "/sell_missiles.php", type: "POST", data: serializedData});
		sellrequest.done(function (response, textStatus, jqXHR){
			$('.pageLoader, #page-cover').fadeOut( "fast");
			updateHeaderData();
			var array = JSON.parse(response);
			$.notify({message: array.status},{type: 'info',delay: 5000,allow_dismiss: true,newest_on_top: true});
			if(array.next == true){
				$('#totalsell').html('0');
				$('#return_val').html('0');
				$('#nw_lost').html('0');
				$.each( array.newmaxsell, function( key, value ) {
					$('#maxsell_'+key).html(value);
					$('#maxsell_'+key).attr("data-amount",value); //setter
					if(value <= 0){
						$(".removerow_"+key).empty();
					}
				});
				$('#sellmissiles').trigger("reset");
			}
		});
	});

	$('#allthetabs a').on('click', function (e) {
		e.preventDefault()
		$(this).tab('show')
	})

})(jQuery);
</script>
<?php
get_footer();