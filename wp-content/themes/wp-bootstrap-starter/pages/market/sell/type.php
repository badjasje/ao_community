<?php foreach($unitTypes as $unitTypeKey => $unitType) : 
	$count = 0;
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
	
	
?>
    <div class="tab-pane <?php echo 'air' === $unitTypeKey ? 'active' : ''; ?>"
         id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <div class="blockHeader spaceNotice">
            <?php echo sprintf('%d special units sold today. You can sell a maximum of 50 special units per day.', $specialSold); ?>
        </div>
        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-3 celBlock nameBlock">
		Name
    </div>
    <div class="col-md-3 celBlock">
		Price
    </div>
    <div class="col-md-2 celBlock">
		Max
    </div>
    <div class="col-md-4 celBlock">
    </div>
</div> <! // Close Unit row -->
        
        
        <?php
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
<?php endforeach; ?>