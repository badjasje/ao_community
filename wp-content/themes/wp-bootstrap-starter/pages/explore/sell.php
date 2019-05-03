<?php
$soldLandToday = $userData['land_sold_today'][0];
$freeLand = $ownedland - $builtLand;
$maxSell = $freeLand < (20000 - $soldLandToday) ? $freeLand : (20000 - $soldLandToday);
?>
<div class="blockHeader spaceNotice sellNotice">
	1 m<sup>2</sup> has a value of $ 75. You have <?php echo $freeLand;?> m<sup>2</sup> of free land.
	You have sold <strong><?php echo $soldLandToday;?> m<sup>2</sup></strong> today.
	You can sell an additional <strong class="maxsell"><?php echo $maxSell;?> m<sup>2</sup></strong>
</div>

<div class="fw-row">
	<form id="sellform">
		<div class="row no-gutters">
			<div class="col-md-3 no-gutters">
				<div class="attackDropdown statCol-1 no-gutters" style="width:100%">
					Amount of land to sell
				</div>
			</div>

			<div class="col-md-9 no-gutters">
				<div class="row no-gutters">
					<div class="col-sm-6 bankCol">
						<input class="unitInput" min="0" max="<?php echo $maxSell;?>" placeholder="Enter amount" type="number" id="landinput" name="land" style="border: none;"/>
					</div>

					<div id="maxsell" class="col-sm-6 bankCol maxsell mainSubmit" style="border-top:0px;background-color:rgba(70, 118, 94, 0.8);">
						ALL LAND
					</div>
				</div>
			</div>
		</div>
		<input type="submit" value="Sell" class="mainSubmit">
	</form>
</div>
