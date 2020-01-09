<?php
/**
 * Template Name: Satellite
 */
get_header();

$user = CurrentUser::make();
$province = $user->getProvince();

$sc = $province->getResearches('satellite_construction');
$satellites = $province->getSatellites();
$has_satellite = ($province->getSatelliteNum() > 0);
$has_ordered_satellites = (count($province->getOrderedSatellites()) > 0);
?>
<div class="row pageRow">
	<div class="blockHeader spaceNotice">
		<?
		if($sc['level']==0) echo '<i class="fas fa-exclamation-triangle"></i> Research satellite construction to build satellites. ';
		else echo 'Building a satellite requires ' . Settings::get('sat_turn_cost') . ' turns and ' . (Settings::get('sat_delivery_time')/3600) . ' hours. ';
		if($sc['level']>2 && !$has_satellite && !$has_ordered_satellites) echo 'Satellite costs reduced by 20% because of your research level. ';
		if($has_satellite) echo 'Demolishing a satellite costs 20% of it\'s price.';
		if($has_ordered_satellites) echo 'Cancelling a satellite order returns 75% of it\'s price. ';
		?>
	</div>
	<div id="satellites" class="aoTable">
		<div class="row unitRow headerRow">
			<div class="col-md-2 celBlock nameBlock">Name</div>
			<div class="col-md-6 celBlock">Effect</div>
			<div class="col-md-2 celBlock">Price</div>
			<div class="col-md-2 celBlock"></div>
		</div>

		<? foreach ($satellites as $key => $satellite) { ?>
			<form id="satellite_<?=$key?>" class="itemRow satelliteForm">
				<div class="row unitRow">
					<div class="col-md-2 celBlock nameBlock"><?=$satellite['name']?></div>
					<div class="col-md-6 celBlock">
						<? if($satellite['num']>0) { ?>
						<strong><span data-countdown="<?=$satellite['timeleft']?>"></span> before your satellite re-enters the atmosphere.</strong><br>
						<? } ?>
						<? if($satellite['in_progress']) { ?>
							<strong>Estimated arrival: <span data-countdown="<?=$satellite['timeleft']?>"></span></strong><br>
						<? } ?>
						<? if($satellite['active']) { ?>
							<strong>Stealth satellite active. <span data-countdown="<?=$satellite['stealthtime']?>"></span> before you need to reactivate.</strong><br>
						<? } ?>
						<?=$satellite['desc']?>
					</div>
					<div class="col-md-2 celBlock"><?=Format::money($satellite['price'])?></div>
					<div class="col-md-2 celBlock nopadding">
						<? if($sc['level']>0) { ?>

							<input type="hidden" name="nonce" value="<?=Request::getNonce()?>" class="nonce">
							<input type="hidden" name="sat" value="<?=$key?>">
							<? if($satellite['in_progress']) { ?>
								<input type="hidden" name="action" value="cancel">
								<input type="submit" value="Cancel order" class="mainSubmit hoverEffect">
							<? } elseif($has_ordered_satellites) { // other sat in progress?>
								<button class="mainSubmit hoverEffect disabled" disabled>Disabled</button>
							<? } elseif($has_satellite && $satellite['num']==0) { // other sat owned?>
								<button class="mainSubmit hoverEffect disabled" disabled>Disabled</button>
							<? } elseif($satellite['num']>0) { ?>
								<input type="hidden" name="action" value="demolish">
								<? if($key=='stealths' && !$satellite['active']) { ?>
								<button class="mainSubmit profileButton activateSatellite">
									<i class="fa fa-power-off" aria-hidden="true"></i> Activate
								</button>
								<? } ?>
								<input type="submit" value="Demolish" class="mainSubmit hoverEffect redBg">
							<? } else { ?>
								<input type="hidden" name="action" value="order">
								<input type="submit" value="Order" class="mainSubmit hoverEffect orderSubmit">
							<? } ?>

						<? } ?>
					</div>
				</div>
			</form>
		<? } ?>

	</div>
</div>
<?
get_footer();
