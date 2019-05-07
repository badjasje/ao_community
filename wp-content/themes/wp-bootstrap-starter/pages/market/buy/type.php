<?php

foreach($unitTypes as $unitTypeKey => $unitType) :  ?>
    <div class="tab-pane smallTable marketBuyTable <?=($startingBonus=='shipping'?'withDelay ':'').($activeTab===$unitTypeKey?'active':'')?>"
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
            echo sprintf('Your empty %s allow you to build a maximum of <span id="'.$unitTypeKey.'spacecount">%d</span> %s units.', $housing, $space[$unitTypeKey] - $usedSpace[$unitTypeKey], strtolower($unitType));
            ?>
        </div>

        <div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
            <div class="col-md-2 celBlock nameBlock">
                Name
            </div>
            <div class="col-md-2 celBlock owned">
                Owned (ordered)
            </div>
            <div class="col-md-1 celBlock price">
                Price
            </div>
            <div class="col-md-1 celBlock attacklife">
                Att / Life
            </div>
            <div class="col-md-2 celBlock targets">
                Targets
            </div>
            <?php if($startingBonus == 'shipping'):?>
            <div class="col-md-1 celBlock delay">
                Delay
            </div>
            <?php endif;?>
            <div class="col-md-1 celBlock max">Max</div>
            <div class="col-md-2 celBlock"></div>
        </div> <!-- // Close Unit row -->
        <?php
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
<?php endforeach; ?>