<?php
 /*
 * Template Name: Research Page
*/

get_header();
$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94";
include 'research_array.php';

global $userData;
global $userId;

$research_in_progress = $userData['research_in_progress'][0];
update_user_meta($userId, 'user_lock', 0);
$research_queued = $userData['queued_research'][0];
$timestamp = current_time('timestamp');
$startingbonus = $userData['starting_bonus'][0];

$args = array(
	'posts_per_page'   => -1,
	'author'        	=>  $userId,
	'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'research'
);
$researchCount = count(get_posts($args));

$research_reduce = 1;

if($startingbonus == 'defensive'){
	$research_reduce = 0.9;
}

$market_discount_bonus = 0;
if ($startingbonus === 'shipping') {
	$market_discount_bonus = 10;
}

$money_bonus = 0;
if ($startingbonus === 'finance') {
	$money_bonus = 10;
}

$researchURL = '/research.php';
$btnText = 'Research';
$selectText = 'Select';

if($researchCount >= 1){
	$researchURL = '/queue_research.php';
	$btnText = 'Queue research';
	$selectText = 'Queue select';
}
?>

<div class="row pageRow">
	<form class="fw-row" id="research">
		<div class="row unitRow headerRow fw-row" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
			<div class="col-md-3 celBlock nameBlock">Name</div>
			<div class="col-md-4 celBlock">Effect</div>
			<div class="col-md-2 celBlock">Time</div>
			<div class="col-md-3 celBlock"></div>
		</div> <!-- //Close Unit row -->
		<?php
		$count = 0; $hasResearch = false;
		foreach ($researches as $key => $research) {
			$count++;
			$inProgress = 0;
			$current = $userData['level_' . $key][0];
			if(!empty($current) && $current > 0) $hasResearch=true;
			if($key == $research_in_progress){
				$inProgress = 1;
			}
			$extraClass = '';
			if($inProgress == 1) {
				$extraClass = 'loader';
				$args = array(
					'posts_per_page'   => 1,
					'author'	   => $userId,
					'post_type'        => 'research',
				);
				$researches_in_progress = get_posts( $args );
				$completionTime = $researches_in_progress[0]->post_title;
				$researchID = $researches_in_progress[0]->ID;
				$timeLeft = $completionTime-$timestamp;
				$timeLeftStamp = $completionTime-$timestamp;
				$totaltime = $research['duration']*60*60*$research_reduce;
				$percentage = round((1-($timeLeft/$totaltime))*100);
				$timeLeft = date('H:i:s', $timeLeft);
			}
			?>
			<div id="research_<?php echo $key;?>" class="row unitRow fw-row <?php echo $extraClass;?>" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
			    <div class="col-md-3 celBlock nameBlock sea_heading">
       				<?php echo $research['name'];?>
					<sup>Current level: <?php echo $current;?> / <?php echo $research['maxlevel'];?></sup>
    			</div>
    			<div class="col-md-4 celBlock">
	    			<?php
					$level = 'level' . ($current + 1);
					if ($research['maxlevel'] != $current) {
						$hideButton = '';
						switch ($key) {
							case 'market_discount':
								$md_discount_research = $research[$level.'_value']+$market_discount_bonus;
								echo str_replace('{value}', $md_discount_research, $research[$level]);
							break;
							case 'money_production':
								$money_research = $research[$level.'_value'] * (1 + ($money_bonus / 100));
								echo str_replace('{value}', '$ '.number_format($money_research, 0, ',', ' '), $research[$level]);
							break;
							default:
								echo $research[$level];
							break;
						}
					} else {
						$hideButton = 'hidden';
						echo '<strong>Maximum level reached.</strong>';
					}
					?>
				</div>

				<div class="col-md-2 celBlock">
					<?php if($research['maxlevel'] != $current):?>
						<span class="hover-tip"  data-toggle="tooltip" data-original-title="$ <?php echo number_format($research['duration']*950, 0, ',', ' ');?> networth added when completing this research" data-placement="bottom">
							<span class="mobileSpan">Time: </span>
							<?php echo $research['duration']*$research_reduce;?> hours <i class="fa fa-info-circle" aria-hidden="true"></i>
						</span>
					<?php endif;?>
				</div>

    			<div class="col-md-3 celBlock" style="padding:0px;">
					<?php if($research_queued == '0' || $research_in_progress == '0'):?>
						<div class="researchselector">
							<input style="display:none;" type="radio" name="research" id="<?php echo $key;?>" value="<?php echo $key;?>">
								<label <?php echo $hideButton;?>  style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/20);?>);" class="researchlabel mainSubmit hoverEffect attackSelect <?php echo $key.'_button';?>" for="<?php echo $key;?>">
									<?php echo $selectText;?>
								</label>
						</div>
					<?php else:?>
						<div class="researchselector">
							<label style="background-color: rgba(221, 221, 221, <?php echo 1-($count/20);?>);" class="researchlabel mainSubmit hoverEffect attackSelect">
								No Selection Possible
							</label>
						</div>
					<?php endif;?>
				</div>

			</div> <!-- //Close Unit row -->

			<?php if($inProgress == 1):?>
				<div class="blockHeader fw-row">
					<i class="fa fa-circle-notch fa-spin"></i> Time left:&nbsp;
					<div class="timeLeft" id="countdown_time"></div>
					<script>
					var diff = <?php echo $timeLeftStamp*1000;?>;

					function updateETimeLeft() {
						days = Math.floor( diff / (1000*60*60*48) ),
						hours = Math.floor( diff / (1000*60*60) ),
						mins = Math.floor( diff / (1000*60) ),
						secs = Math.floor( diff / 1000 ),
						dd = days,
						hh = hours - days * 24,
						mm = mins - hours * 60,
						ss = secs - mins * 60;
						jQuery("#countdown_time").text(('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' + ('00'+ss).slice(-2));
						diff -= 1000;
					}
					setInterval(updateETimeLeft, 1000);
					</script>
				</div>
			<?php endif;?>

			<?php if($research_queued == $key):?>
			<div class="blockHeader fw-row">
				<i class="fa fa-clock"></i> Research Queued
			</div>
			<?php endif;?>
			<?php
		} ?>

		<?php if($research_queued == '0' || $research_in_progress == '0'):?>
		<input id="researchsubmit" type="submit" value="<?php echo $btnText;?>" class="mainSubmit hoverEffect">
		<?php endif;?>
	</form>

	<?php
	if(!empty($research_in_progress)) {
		helpText('Queueing research takes extra turns', 'research', 'warning');
	}
	else if(!$hasResearch) {
		helpText('Every hour of research adds to your networth', 'research', 'reminder');
	}
	?>

	<script>
	(function($) {
		var request;
		$('form').submit(function( event ) {

			if(!$("input[name='research']:checked").val()) {
				$.notify({message: 'Please select a research'},{type: 'info', delay: 5000, allow_dismiss: true, newest_on_top: true});
				return false;
			}

			$('.pageLoader, #page-cover').show();
			$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
			event.preventDefault();
			if (request) request.abort();

			var $form = $(this);
			var $inputs = $form.find("input, select, button, textarea");
			var serializedData = $form.serialize();

			request = $.ajax({url: "/research.php", type: "post", data: serializedData});
			request.done(function (response, textStatus, jqXHR){
				updateHeaderData();
				var array = JSON.parse(response);

				$.notify({message: array.status},{type: 'info', delay: 5000, allow_dismiss: true, newest_on_top: true});

				$("#researchsubmit").val('Queue research');
				$( ".researchlabel" ).html( "Queue select" );
				location.reload();
				$( array.hidebutton).hide();

				if(array.endtime != 'queued') {
					$( "<div class='blockHeader fw-row'><i class='fa fa-circle-notch fa-spin'></i> Time left: <div class='timeLeft' id='countdown_time'></div></div>").insertAfter( "#research_"+array.started );

					var diff = array.endtime*1000;
					function updateETime() {
						days = Math.floor( diff / (1000*60*60*48) ),
						hours = Math.floor( diff / (1000*60*60) ),
						mins = Math.floor( diff / (1000*60) ),
						secs = Math.floor( diff / 1000 ),
						dd = days,
						hh = hours - days * 24,
						mm = mins - hours * 60,
						ss = secs - mins * 60;
						$("#countdown_time").text(('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' + ('00'+ss).slice(-2));
						diff -= 1000;
					}
					setInterval(updateETime, 1000 );

					$( "#research_"+array.started ).addClass("loader");
				} else {
					$( "<div class='blockHeader fw-row'><i class='fa fa-clock'></i> Research queued</div>").insertAfter("#research_"+array.started);
					$( "#researchsubmit" ).remove();
					$( "#researchselector" ).empty();
					$( ".researchselector" ).html('<label style="background-color: rgba(221, 221, 221, 0.9);" class="researchlabel mainSubmit hoverEffect attackSelect">No Selection Possible</label>');
				}

				$('form').trigger("reset");
			});
		});
	})(jQuery);
	</script>

</div> <!-- End pageRow -->
<?php
get_footer();