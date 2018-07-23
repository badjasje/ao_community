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
  
    request = $.ajax({
        url: "/build.php",
        type: "POST",
        data: serializedData
    });

    request.done(function (response, textStatus, jqXHR){
	    console.log(response);
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
			$.each( array.newmax, function( key, value ) {
					$('#button'+key).html(value);
				});
			$('#order_total').html('0');
			$('#total').html('0');
			$('#turns').html(number_format(array.turns, 0, ',', ' '));
			$('#networth_total').html('0');
			$('#turn_total').html('0');
			$('#power').html(number_format(array.newpower, 0, ',', ' '));
			$('#networth').html(number_format(array.networth, 0, ',', ' '));
			$('#money').html(number_format(array.money, 0, ',', ' '));
			$('#buildbuildings').trigger("reset");
});	});	



var demolish;

$("#demobuildings").submit(function(demolishevent){
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    demolishevent.preventDefault();

    if (demolish) { demolish.abort(); }

    var $form = $(this);
    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();
  
    demolish = $.ajax({
        url: "/demolish.php",
        type: "POST",
        data: serializedData
    });

    demolish.done(function (response, textStatus, jqXHR){
	    console.log(response);
        var array = JSON.parse(response);
		console.log(array.newmax);
				
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
			$.each( array.newmax, function( key, value ) {
					$('#demobutton'+key).html(value);
				});
			$('#demototal').html('0');
			$('#demoorder_total').html('0');
			$('#demonetworth_total').html('0');

			$('#money').html(number_format(array.money, 0, ',', ' '));
			$('#power').html(number_format(array.newpower, 0, ',', ' '));
			
			$('#demobuildings').trigger("reset");
});	});	






})(jQuery);
</script>

<script type="text/javascript">
	
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
	
	
	jQuery('body').on('change', '.demobds', function() {
		
    var arr = document.getElementsByClassName('demobds');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demototal').value = tot;
    
    var span = document.getElementById('demototal');

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