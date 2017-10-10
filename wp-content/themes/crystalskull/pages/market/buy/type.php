<?php foreach($unitTypes as $unitTypeKey => $unitType) : ?>
    <div class="tab-pane <?php echo $activeTab === $unitTypeKey ? 'active' : ''; ?>"
         id="<?php echo $unitTypeKey; ?>" role="tabpanel">
        <?php
        foreach($units as $unitKey => $unit) {
            if ($unit['type'] == $unitTypeKey) {
                include('type/unit.php');
            }
        }
        ?>
    </div>
<?php endforeach; ?>