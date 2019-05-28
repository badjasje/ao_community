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
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'explore' ? 'active' : ''; ?>" href="?tab=explore" data-toggle="tab" data-target="#explore">Explore</a><?/* */?>
			<a class="nav-item nav-link navItem <?php echo $activeTab === 'sell' ? 'active' : ''; ?>" href="?tab=sell" data-toggle="tab" data-target="#sell">Sell</a><?/**/?>
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

	<?php
	if($userData['turns'][0] > 150 && $userData['money'][0] < 70000) {
		helpText('Low on money? Use some turns to explore and sell', 'explore', 'reminder');
	}
	if($maxSell > 700) {
		helpText('You will lose unused land when attacked', 'explore', 'reminder');
	}
	?>

	<script>
	(function($) {
		$(document).on('click', ".maxexp", function() {
			var maxexp = $(this).attr("data-max");
			$("#turnsinput").val(maxexp);
		});
		$(document).on('click', ".maxsell", function() {
			var maxsell = $(this).attr("data-max");
			$("#landinput").val(maxsell);
		});

		var request;
		$('#exploreform').submit(function( event ) {
			$('.pageLoader, #page-cover').show();
			event.preventDefault();
			if (request) request.abort();

			request = $.ajax({url: "/explore.php",type: "post",data: $(this).serialize()});
			request.done(function (response, textStatus, jqXHR){
				$('.pageLoader, #page-cover').fadeOut( "fast");
				updateHeaderData();
				var array = JSON.parse(response);
				$.notify({message: array.status},{type: 'info', delay: 5000, allow_dismiss: true, newest_on_top: true});
				if(array.next == true){
					$(".explNotice").html(array.exploredtoday);
					$(".sellNotice").html(array.soldtoday);
					$('#exprate').html(array.newrate);
					$("#turnsinput").attr({"max" : array.maxturns, "min" : 0});
					$("#landinput").attr({"max" : array.maxsell, "min" : 0});
					$(".maxexp").attr({"data-max" : array.maxturns});
					$(".maxsell").attr({"data-max" : array.maxsell});
					$('form').trigger("reset");
				}
			});
		});

		$('#sellform').submit(function( event ) {
			$('.pageLoader, #page-cover').show();
			event.preventDefault();
			if (request) request.abort();

			request = $.ajax({url: "/sell_land.php", type: "post", data: $(this).serialize()});
			request.done(function (response, textStatus, jqXHR){
				$('.pageLoader, #page-cover').fadeOut( "fast");
				updateHeaderData();
				var array = JSON.parse(response);
				$.notify({message: array.status},{type: 'info', delay: 5000, allow_dismiss: true, newest_on_top: true});
				if(array.next == true){
					$(".sellNotice").html(array.soldtoday);
					$("#landinput").attr({"max" : array.maxsell, "min" : 0});
					$(".maxsell").attr({"data-max" : array.maxsell});
					$('form').trigger("reset");
				}
			});
		});
	})(jQuery);
	</script>

</div> <!-- end .pageRow -->
<?php
get_footer();
