<?php foreach($unitTypes as $unitTypeKey => $unitType) : ?>
    <div class="tab-pane <?php echo $activeTab === $unitTypeKey ? 'active' : ''; ?>"
         id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <div class="spaceNotice">
            <?php
            $housing = 'housing';
            if ($unitTypeKey == 'air') {
                $housing = "airfields";
            } elseif ($unitTypeKey == 'sea') {
                $housing = 'shipyards';
            } elseif ($unitTypeKey == 'veh') {
                $housing = 'war factories';
            } elseif ($unitTypeKey == 'inf') {
                $housing = 'barracks';
            }

            echo sprintf('Your empty %s allow you to build a maximum of <strong>%d</strong> %s units.', $housing, $space[$unitTypeKey] - $usedSpace[$unitTypeKey], strtolower($unitType));
            ?>
        </div>
        <?php
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
<?php endforeach; ?>