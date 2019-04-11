<?php
$statusMessage = '';
$extraStyle = '';
if($user_status =='nukeprotection'){
	$timestamp = current_time('timestamp');
	$timeleft = $nuke_protection_timestamp-$timestamp;
	$timer_left = $nuke_protection_timestamp-$timestamp;

	if($timeleft < 0){
		update_user_meta($userId, 'status', 'online');
	}
	$timeleft = date('H:i:s', $timeleft);

	$statusMessage .= 'Protection time left: <span id="countdown_time"></span>';

	if($timer_left < 86400){
		$extraStyle = 'style="padding-top:0px;padding-bottom:0px;"';
		$statusMessage .= "<a onclick='return confirm('Are you sure you want to remove protection?')' class='removeProtection hoverEffect' href='/remove_np.php/?user=$userId'><i class='fas fa-times'></i> Remove Protection</a>";
	}
}
elseif($user_status =='online'){
	$statusMessage = 'Status: Online';
}
elseif($user_status =='dead'){
	$statusMessage = 'Status: Dead';
}

$inProgress = $userData['research_in_progress'][0];
$researchTimeLeft ='';
if(!empty($inProgress)) {
	$timestamp = current_time('timestamp');
	$args = array('posts_per_page' => 1, 'author' => $userId, 'post_type' => 'research');
	$researches_in_progress = get_posts( $args );
	$completionTime = $researches_in_progress[0]->post_title;
	$researchTimeLeft = human_time_diff($completionTime, $timestamp);
}
include('research_array.php');

$telegram_key = $userData['telegram_key'][0];
if(empty($telegram_key)) {
	$telegram_key = uniqid();
	update_user_meta($userId, 'telegram_key', $telegram_key);
}
?>

<div class="blockHeader" <?php echo $extraStyle;?>><?php echo $statusMessage;?></div>

<div class="statusBlock">
	<div class="row statusTotalRow">

		<div class="col-md-6 col-lg-4 statusRow statCol-1">
			<div class="statusInsideCol">
				<strong>Points rank</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo number_format($PtsRank, 0, ',', ' ');?>
			</div>

			<div class="statusInsideCol">
				<strong>Satellite power</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo $sat_morale;?>%
			</div>

			<div class="statusInsideCol">
				<strong>Networth rank</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo number_format($NwRank, 0, ',', ' ');?>
			</div>

			<div class="statusInsideCol">
				<strong>AMS Coverage</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo number_format($shootdown_chance, 0, ',', ' ');?>%
			</div>
		</div>

		<div class="col-md-6 col-lg-4 statusRow statCol-2">
			<?/*<div class="statusInsideCol">
				<strong>Events</strong>
			</div>
			<div class="statusInsideCol">
				<a href="/events/incoming/">
					<?php echo $new_events;?> new event<?php echo plural_func($new_events);?>
				</a>
			</div>

			<div class="statusInsideCol">
				<strong>Power usage</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo number_format($PwrUsage, 0, ',', ' ');?>%
			</div>*/?>

			<div class="statusInsideCol">
				<strong>Conversations</strong>
			</div>
			<div class="statusInsideCol">
				<a href="/conversations/">
					<?php echo $new_messages;?> new message<?php echo plural_func($new_messages);?>
				</a>
			</div>

			<div class="statusInsideCol">
				<strong>Hourly income</strong>
			</div>
			<div class="statusInsideCol">
				$ <?php echo number_format($income, 0, ',', ' ');?>
			</div>

			<div class="statusInsideCol">
				<strong>Starting bonus</strong>
			</div>
			<div class="statusInsideCol">
				<?php if(in_array($startingbonus, $boni)):?>
					<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $bonuses[$startingbonus]['description'];?>" data-placement="left">
						<i class="fa <?php echo $bonuses[$startingbonus]['icon'];?>" aria-hidden="true"></i> <?php echo $bonuses[$startingbonus]['name'];?>
					</span>
				<?php else:?>
					<u><a href="#startingBonus">None</a></u>
				<?php endif;?>
			</div>

			<?php if(!empty($inProgress)) {?>
			<div class="statusInsideCol">
				<strong>Research in progress</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo $researches[$inProgress]['name'];?><br><?=$researchTimeLeft?> left
			</div>
			<?php } ?>

		</div>

		<div class="col-md-6 col-lg-4 statusRow statCol-3">
			<?/*<div class="statusInsideCol">
				<strong>Morale & pool</strong>
			</div>
			<div class="statusInsideCol">
				<?php echo $morale.'% <sup>('.$moralepool.'%)</sup>';?>
			</div>*/?>

			<div class="celBlock">
				<strong>Push notifications</strong>
				<ul style="padding-left: 16px;">
					<li>Install <a href="https://t.me/assaultonlinebot" style="text-decoration:underline;" target="_blank">Telegram</a> on your mobile
				device.</li>
					<li>Add <a href="https://t.me/assaultonlinebot" style="text-decoration:underline;" target="_blank">assaultonlinebot</a>.</li>
					<li>Use this code <strong><?php echo $telegram_key ?></strong> to get instant notifications.</a></li>
				</ul>
			</div>
		</div>

	</div> <!-- // End row -->
</div>

<div class="row fw-row no-gutters profileButtonRow">
	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 1);" href="/military-overview/?id=<?php echo $userId;?>">
		<i class="fa fa-bars"></i> Military overview
	</a>

	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.8);" href="/player-statistics/">
		<i class="fas fa-chart-line"></i> View statistics
	</a>

	<a class="col-md-4 profileButton" style="background-color: rgba(70, 118, 94, 0.9);" href="/users/profile/edit/">
		<i class="fa fa-wrench"></i> Edit profile
	</a>
</div>

<?php if($user_status =='nukeprotection'):?>
<script>
var diff = <?php echo $timer_left*1000;?>;

function updateETime() {
	days = Math.floor( diff / (1000*60*60*48) ),
	hours = Math.floor( diff / (1000*60*60) ),
	mins = Math.floor( diff / (1000*60) ),
	secs = Math.floor( diff / 1000 ),

	dd = days,
	hh = hours - days * 24,
	mm = mins - hours * 60,
	ss = secs - mins * 60;

	jQuery("#countdown_time").text( ('00'+hh).slice(-2) + ':' + ('00'+mm).slice(-2) + ':' +  ('00'+ss).slice(-2) );
	diff -= 1000;
}
setInterval(updateETime, 1000 );
</script>
<?php endif;?>