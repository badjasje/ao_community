<?php 
$exploredToday = $userData['explored_today'][0];


$perturnm2 = 200-((ceil($ownedland*0.002)));
if (($perturnm2 < 50) && ($perturnm2 > 25)) {
	$perturnm2 = 50;
} elseif ($perturnm2 < 25) {
	$perturnm2 = 25;
}
$maxAmount = floor((20000-$exploredToday)/$perturnm2);
?>
<div class="blockHeader">
	Current exploration rate is <?php echo $perturnm2;?> m<sup>2</sup> per turn
</div>
<div class="blockHeader spaceNotice explNotice">
	<?php if(empty($exploredToday) || $exploredToday == 0):?>
		You haven't explored any land today. You can explore <strong><?php echo number_format(20000-$exploredToday, 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo $maxAmount;?> turns)</i>
	<?php else:?>
		You have explored <strong><?php echo number_format($exploredToday, 0, ',', ' '); ?> m<sup>2</sup></strong> today.
		You can explore an additional <strong><?php echo number_format(20000-$exploredToday, 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo floor((20000-$exploredToday)/(200-((ceil($ownedland*0.002)))));?> turns)</i>
	<?php endif;?>
	
</div>


<div class="fw-row">
<form id="exploreform">
	
<div class="row no-gutters">
	<div class="col-md-3 no-gutters">
		<div class="attackDropdown statCol-1 no-gutters" style="width:100%">
			Turns to explore
		</div>
	</div>
	
	<div class="col-md-9 no-gutters">
		<div class="row no-gutters">
			<div class="col-sm-6 bankCol">
		 		<input class="unitInput" min="0" max="<?php echo $maxAmount;?>" placeholder="Enter amount" type="number" id="turnsinput" name="turns" style="border: none;"/>
			</div>
			
			<div id="maxexp" data-max="<?php echo $maxAmount;?>" class="col-sm-6 bankCol mainSubmit" style="border-top:0px;background-color:rgba(70, 118, 94, 0.8);">
				ALL TURNS
			</div>
		</div>
	</div>
</div>
	
	
	<input type="submit" value="Explore" class="mainSubmit">
</form>	
	
	
</div>