<?php
$buildingsOwned = $userData[$buildingKey][0];
$buildingsOrdered = $userData[$buildingKey][0];
$networthPerUnit = $building['price']*$building['networth']/100;
$buyPrice =  ceil($building['price']);
$canAttack = is_array($building['attacks']) && !empty($building['attacks']) ? implode(', ', $building['attacks']) : 'N/A';
$count++;
$backColor = "127, 82, 67"
?>

<?php if($buildingsOwned > 0):?>
<div id="<?php echo $buildingKey;?>_row" class="row unitRow bodyRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock demolish_heading">
        <?php echo $building['normalname'];?>

        <?php if(isset($building['description'])):?>
            <span class="hover-tip"  data-toggle="tooltip" data-html="true" data-original-title="<?php echo $building['description'];?><br>
            Attack: <?php echo $building['attack'];?><br>
            Life: <?php echo $building['life'];?><br>
            Targets: <?php echo $canAttack; ?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
            </span>
        <?php endif;?>
    </div>
    <div class="col-md-2 celBlock owned">
		<span class="columnDataLeft">Owned</span>
		<span id="<?php echo $buildingKey;?>_demo_owned" class="columnDataRight"><?php echo $buildingsOwned; ?></span>
    </div>
    <div class="col-md-2 celBlock price">
	    <span class="columnDataLeft">Price</span>
	    <span class="columnDataRight">$ <?php echo floor($buyPrice*0.15);?></span>
	    </span>
    </div>
    <div class="col-md-1 celBlock attacklife">
	    <span class="columnDataLeft">Attack / Life</span>
		<span class="columnDataRight"><?php echo $building['attack'];?>/<?php echo $building['life'];?></span>
    </div>
    <div class="col-md-2 celBlock targets">
	    <span class="columnDataLeft">Targets</span>
		<span class="columnDataRight"><?php echo $canAttack; ?></span>
    </div>
    <div class="col-md-1 celBlock maxBlock">
	    <span class="columnDataLeft">Max</span>
	    <span class="columnDataRight">
		<?php
            $maxMoney = floor($totalMoney / ($building['price'] * 0.15));
			$maxOwned = $buildingsOwned;

				if ($buildingKey == 'airfield') {

					$maxMoney = floor($maxMoney - ($totalair / 10));

					if ($maxMoney < 0) {
						$maxMoney = 0;
					}

					$maxOwned = floor($maxOwned - ($totalair / 10));

					if ($maxOwned < 0) {
						$maxOwned = 0;
					}
				}

				if ($buildingKey == 'command_centre') {

					$maxMoney = floor($maxMoney - ($totalspecial / 5));

					if ($maxMoney < 0) {
						$maxMoney = 0;
					}

					$maxOwned = floor($maxOwned - ($totalspecial / 5));

					if ($maxOwned < 0) {
						$maxOwned = 0;
					}
				}



				if ($buildingKey == 'shipyard') {

					$maxMoney = floor($maxMoney - ($totalsea / 5));

					if ($maxMoney < 0) {
						$maxMoney = 0;
					}

					$maxOwned = floor($maxOwned - $totalsea / 5);

					if ($maxOwned < 0) {
						$maxOwned = 0;
					}
				}

				if ($buildingKey == 'baracks') {

					$maxMoney = floor($maxMoney - ($totalinf / 20));

					if ($maxMoney < 0) {
						$maxMoney = 0;
					}

					$maxOwned = floor($maxOwned - ($totalinf / 20));

					if ($maxOwned < 0) {
						$maxOwned = 0;
					}
				}
				if ($buildingKey == 'warfactory') {

					$maxMoney = floor($maxMoney - ($totalveh / 10));

					if ($maxMoney < 0) {
						$maxMoney = 0;
					}

					$maxOwned = floor($maxOwned - ($totalveh / 10));

					if ($maxOwned < 0) {
						$maxOwned = 0;
					}
				}

            ?>

            <span class="sellall" id="demobutton_<?php echo $buildingKey; ?>" data-key="<?php echo $buildingKey;?>" data-amount="<?php echo min($maxMoney, $maxOwned); ?>">
    			<?php echo min($maxMoney, $maxOwned); ?>
			</span>


	    </span>
    </div>
    <div class="col-md-2 celBlock inputBlock">
        <input class="unitInput demobds sellInput" data-nw="<?php echo $building['networth'];?>" data-key="<?php echo $buildingKey;?>" data-price="<?php echo $building['price']*0.15;?>" min="0" type="number" id="demo_<?php echo $buildingKey;?>" name="<?php echo $buildingKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
    </div>
</div> <!-- //Close Unit row -->
<?php endif;?>