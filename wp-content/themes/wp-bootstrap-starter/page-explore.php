<?php
 /*
 * Template Name: Explore
*/

get_header(); 
global $userData;
global $userId;
$ownedland = $userData['land'][0];
$builtLand = $userData['builtland'][0];
$freeland = $ownedland-$builtLand;
$activeTab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'explore';
?>

<div class="row pageRow">	
	
	
<div class="fw-row">
		<nav class="nav nav-pills nav-fill flex-column flex-sm-row">
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'explore' ? 'active' : ''; ?>" data-toggle="tab" data-target="#explore" href="?tab=explore">Explore</a>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'sell' ? 'active' : ''; ?>" data-toggle="tab" data-target="#sell" href="?tab=sell">Sell</a>
		</nav>
	</div>


<div class="fw-row">
	<div class="tab-content current build_content tabbed-table">
				<div class="tab-pane <?php echo $activeTab === 'explore' ? 'active' : ''; ?>"  id="explore" role="tabpanel">

					<?php include 'pages/explore/explore.php'; ?>
					
				</div>


				<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active' : ''; ?>"  id="sell" role="tabpanel">

					<?php include 'pages/explore/sell.php'; ?>
					
				</div>
			</div>
</div>
	
	
	
<script>
(function($) {
	
	$("#maxexp").click(function() {
		var maxexp = $(this).attr( "data-max" );
	$("#turnsinput").val(maxexp);
});
	$("#maxsell").click(function() {
	$("#landinput").val("<?php echo $maxSell;?>");
});

var request;


$('#exploreform').submit(function( event ) {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();

    if (request) {
        request.abort();
    }

    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");


    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/explore.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        updateHeaderData();
        var array = JSON.parse(response);

        	
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					allow_dismiss: true,
					newest_on_top: true,
						});	
			
			
			if(array.next == true){
				
				$( ".explNotice" ).empty();
				$( ".explNotice" ).append(array.exploredtoday);
				$('#exprate').html(array.newrate);

				$("#turnsinput").attr({
					"max" : array.maxturns,
					"min" : 0
				});
				$("#maxexp").attr({
					"data-max" : array.maxturns,
					"min" : 0
				});
				
				
				
				$('form').trigger("reset");
				location.reload();
			}
});	});	


// jQuery Sell

var request;


$('#sellform').submit(function( event ) {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");

    event.preventDefault();

    if (request) {
        request.abort();
    }

    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");


    var serializedData = $form.serialize();

    request = $.ajax({
        url: "/sell_land.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        updateHeaderData();
        var array = JSON.parse(response);
        
        	
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					allow_dismiss: true,
					newest_on_top: true,
						});	
			
			
			if(array.next == true){
		
				$( ".sellNotice" ).empty();
				$( ".sellNotice" ).append(array.soldtoday);
				
				
				
				
				
				$('form').trigger("reset");
				location.reload();
			}
});	});	





})(jQuery);
</script>
	
	
</div> <!-- end .pageRow -->
<?php
get_footer();