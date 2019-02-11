<?php
$show = $eventData['show_spy_sender'][0];
$spy_type = $eventData['event_spy_type'][0];
$sender = '<a href="/users/profile/?id='.$attacker_id.'">'.$member_data->display_name.' (#'.$attacker_id.')</a>';

if($winner_id == $attacker_id){
	if($show == 'no'){
		$attacker_id = 0;
		$sender = '<strong>Someone</strong>';
	}
	if($spy_type == 'spy'){
		$message = $sender.' sent a spy';
	}
	if($spy_type == 'spyplane'){
		$message = 'A <strong>spyplane</strong> flew over your base';
	}
}
if($winner_id == $defender_id){
	if($spy_type == 'spy'){
		$message = 'You killed a spy that was sent by '.$sender;
	}
	if($spy_type == 'spyplane'){
		$message = 'You shot down a spyplane that was sent by '.$sender;
	}
}
?>

<div class="fw-row row row-no-padding">
	<div class="col-xs-2 col-no-padding eventImageCol">
		<?php echo small_avatar($attacker_id, 'eventAvatar'); ?>
	</div>

	<div class="col-xs-10 col-no-padding" style="flex: 100;">
		<div class="eventMainMessage">
			<?php echo $message;?>
		</div>

		<div class="row eventResultRow">
			<div class="col-md-12 col-no-padding">

			</div>
		</div>
	</div>

	<div class="row statusBlockButtons eventFooter">
		<div class="col-md-3 totalsField statCol-1">
			<?php echo human_time_diff( $timeattacked, $timestamp );?> ago
		</div>
		<div class="col-md-3 totalsField statCol-2">
			Defender NW lost: $ <?php echo number_format($defender_NW_lost, 0, ',', ' '); ?>
		</div>
		<div class="col-md-3 totalsField statCol-3">
			Attacker NW lost: $ <?php echo number_format($attacker_NW_lost, 0, ',', ' '); ?>
		</div>
		<div class="col-md-3 totalsField statCol-4">
			Land stolen: <?php echo number_format($landlost, 0, ',', ' '); ?>m<sup>2</sup>
		</div>
	</div>
</div><!-- end fw-row -->