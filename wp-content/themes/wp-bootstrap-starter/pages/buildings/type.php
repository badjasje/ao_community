<div class="tab-pane <?php echo $activeTab === 'build' ? 'active' : ''; ?>" id="build" role="tabpanel">
<form class="form" name="build" id="buildbuildings" method="post">

	<div class="blockHeader spaceNotice">
		Your free land allows you to build <span id="landspace"><strong><?php echo floor(($land - $builtland) / 20); ?></strong></span> buildings.
		<?php if ($EElevel == 0 || empty($EElevel)) {
		$buildingsPerTurn = 5 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 5 + $extra_divide;
	}

	if ($EElevel == 1) {
		$buildingsPerTurn = 10 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 10 + $extra_divide;
	}
	if ($EElevel >= 2) {
		$buildingsPerTurn = 15 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 15 + $extra_divide;
	}

	?>
	</div>



<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(45, 67, 81, 0.75);">
	<div class="col-md-2 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-2 celBlock">
		Owned
    </div>
    <div class="col-md-2 celBlock">
		Price
    </div>
    <div class="col-md-1 celBlock">
		Att / Life
    </div>
    <div class="col-md-2 celBlock">
		Targets
    </div>
    <div class="col-md-1 celBlock">Max</div>
    <div class="col-md-2 celBlock"></div>
</div> <! // Close Unit row -->


<?php $count = 0;
	foreach($buildings as $buildingKey => $building) {
		include('type/building.php');
	}?>
<div class="row statusBlockButtons">

	<div class="col-md-3 totalsField statCol-1">
		Number of buildings: <span id="total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-2">
		Total cost: $ <span id="order_total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-3">
		Turns required: <span id="turn_total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-4">
		Added networth : $ <span id="networth_total">0</span>
	</div>
</div>


<input type="submit" value="Build" class="mainSubmit hoverEffect">
 </form>
</div>
    
<div class="tab-pane <?php echo $activeTab === 'demolish' ? 'active' : ''; ?>" id="demolish" role="tabpanel">
<form class="form" id="demobuildings">
<input type="hidden" name="currentTab" id="currentTab" value="?tab=<?php echo $activeTab; ?>" />
	<div class="blockHeader spaceNotice">
		Your free land allows you to build <span id="demolandspace"><strong><?php echo floor(($land - $builtland) / 20); ?></strong></span> buildings.
		<?php if ($EElevel == 0 || empty($EElevel)) {
		$buildingsPerTurn = 5 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 5 + $extra_divide;
	}

	if ($EElevel == 1) {
		$buildingsPerTurn = 10 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 10 + $extra_divide;
	}
	if ($EElevel == 2) {
		$buildingsPerTurn = 15 + $extra_divide;
		echo 'You can currently build <strong>' . $buildingsPerTurn . '</strong> buildings per turn.';
		$turns_multiplier = 15 + $extra_divide;
	}

	?>

	</div>


<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(127, 82, 67, 0.75);">
	<div class="col-md-2 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-2 celBlock">
		Owned
    </div>
    <div class="col-md-2 celBlock">
		Cost to demolish
    </div>
    <div class="col-md-1 celBlock">
		Att / Life
    </div>
    <div class="col-md-2 celBlock">
		Targets
    </div>
    <div class="col-md-1 celBlock">Max</div>
    <div class="col-md-2 celBlock"></div>
</div> <! // Close Unit row -->
        <?php $count = 0;
        foreach($buildings as $buildingKey => $building) {
                include('type/demolish.php');

        }
        ?>
<div class="row statusBlockButtons">

	<div class="col-md-4 totalsField statCol-1">
		Number of buildings: <span id="demototal">0</span>
	</div>
	<div class="col-md-4 totalsField statCol-2">
		Total cost: $ <span id="demoorder_total">0</span>
	</div>
	<div class="col-md-4 totalsField statCol-3">
		Networth lost: $ <span id="demonetworth_total">0</span>
	</div>
</div>
<input type="submit" value="Demolish" class="mainSubmit hoverEffect">
</form>
</div>