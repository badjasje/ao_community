<?php 
	foreach($unitTypes as $unitTypeKey => $unitType) :  ?>
    <div class="tab-pane <?php if($unitTypeKey == 'air') echo 'active';?>"
         id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <div class="blockHeader spaceNotice">
            <?php
            $housing = 'housing';
            if ($unitTypeKey == 'air') {
                $housing = "airfields";
                $backColor = "127, 82, 67";
            } elseif ($unitTypeKey == 'sea') {
                $housing = 'shipyards';
                $backColor = "45, 67, 81";
            } elseif ($unitTypeKey == 'veh') {
                $housing = 'war factories';
                $backColor = "86, 113, 61";
            } elseif ($unitTypeKey == 'inf') {
                $housing = 'barracks';
                $backColor = "126, 100, 68";
            }

            echo sprintf('Your empty %s allow you to build a maximum of <span id="'.$unitTypeKey.'spacecount">%d %s units.', $housing, $space[$unitTypeKey] - $usedSpace[$unitTypeKey], strtolower($unitType));
            ?>
        </div>


<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-2 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-2 celBlock">
		Owned (ordered)
    </div>
    <div class="col-md-1 celBlock">
		Price
    </div>
    <div class="col-md-1 celBlock">
		Att / Life
    </div>
    <div class="col-md-2 celBlock">
		Targets
    </div>
    <div class="col-md-1 celBlock">
	    <?php if($startingBonus == 'shipping'):?>
			Delay
         <?php endif;?>
    </div>
    <div class="col-md-1 celBlock"></div>
    <div class="col-md-2 celBlock"></div>
</div> <! // Close Unit row -->
        <?php $count = 0;
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
<?php endforeach; ?>