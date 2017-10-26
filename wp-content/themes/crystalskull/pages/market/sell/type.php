<?php foreach($unitTypes as $unitTypeKey => $unitType) : ?>
    <div class="tab-pane <?php echo $activeTab === $unitTypeKey ? 'active' : ''; ?>"
         id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <div class="spaceNotice">
            <?php echo sprintf('%d special units sold today. You can sell a maximum of 50 special units per day.', $specialSold); ?>
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