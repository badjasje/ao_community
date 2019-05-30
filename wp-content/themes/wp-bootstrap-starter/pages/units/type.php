<?php
$typeTotals = array();
foreach($unitTypes as $unitTypeKey => $unitType) :  ?>
    <div class="tab-pane smallTable unitBuildTable <?=($unitTypeKey=='air'?'active':'')?>" id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <div class="blockHeader spaceNotice">
            <?php
            $housing = 'housing';
            if ($unitTypeKey == 'air') {
                $housing = "airfields";
                $backColor = "127, 82, 67";
                $unitsPerTurn2 = 10;
                $unitsNameDisplay = 'air units';
            } elseif ($unitTypeKey == 'sea') {
                $housing = 'shipyards';
                $backColor = "45, 67, 81";
                $unitsPerTurn2 = 5;
                $unitsNameDisplay = 'sea units';
            } elseif ($unitTypeKey == 'veh') {
                $housing = 'war factories';
                $backColor = "86, 113, 61";
                $unitsPerTurn2 = 10;
                $unitsNameDisplay = 'vehicles';
            } elseif ($unitTypeKey == 'inf') {
                $housing = 'barracks';
                $backColor = "126, 100, 68";
                $unitsPerTurn2 = 20;
                $unitsNameDisplay = 'infantry';
            }
            echo sprintf('Your empty %s allow you to build a maximum of <span id="'.$unitTypeKey.'spacecount">%d %s units. ', $housing, $space[$unitTypeKey] - $usedSpace[$unitTypeKey], strtolower($unitType));
            echo sprintf('<strong>'.$unitsPerTurn2.' units </strong>built per turn for '.$unitsNameDisplay);
            ?>
        </div>

        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-2 celBlock nameBlock">Name</div>
            <div class="col-md-2 celBlock owned">Owned (ordered)</div>
            <div class="col-md-2 celBlock price">Price</div>
            <div class="col-md-1 celBlock attacklife">Att / Life</div>
            <div class="col-md-2 celBlock targets">Targets</div>
            <div class="col-md-1 celBlock max">Max</div>
            <div class="col-md-2 celBlock"></div>
        </div>

        <?php $count = 0; $typeTotals[$unitTypeKey] = 0;
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
    <?php
endforeach;

$c = 0;
foreach($typeTotals as $key => $num) {
    if($num > 100) $c++;
}
//echo 'total '.$c;
if($c>2) helpText('It\\\'s better to focus on one or two unit types', 'units', 'warning');
