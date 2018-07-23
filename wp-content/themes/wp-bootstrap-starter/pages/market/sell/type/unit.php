<?php
$unitsOwned = get_user_meta($userId, $unitKey.'_owned', true);
$unitsOrdered = get_user_meta($userId, $unitKey.'_ordered');
$count++;
$sellPrice =  ceil($unit['price'] * $marketSellMultiplier);
$networthPerUnit = $unit['price'] * $unit['networth'] / 100;
?>

<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-3 celBlock nameBlock <?php echo $unitTypeKey;?>_heading">
        <?php echo $unit['normalname'];?>

        <?php if (isset($unit['description'])) : ?>
            <span class="hover-tip" data-toggle="tooltip" data-original-title="<?php echo $unit['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
        <?php endif;?>
    </div>

    <div class="col-md-3 celBlock">
        <span class="columnDataLeft">Price</span>
        <span class="columnDataRight">
			$ <?php echo $sellPrice;?>
		</span>
    </div>


    <div class="col-md-2 celBlock">
        <span class="columnDataLeft">Max</span>
        <span class="columnDataRight">
			<?php if (in_array($unitKey, $specialUnitsArray)) : ?>
				<span id="maxsell_<?php echo $unitKey;?>" class="sellall" data-nw="<?php echo $unit['networth'];?>" data-price="<?php echo $sellPrice;?>" data-key="<?php echo $unitKey;?>" data-amount="<?php echo min($unitsOwned,$specialSold);?>"><?php echo min($unitsOwned,$specialSold);?></span>
            <?php else:?>
                <span id="maxsell_<?php echo $unitKey;?>" class="sellall" data-nw="<?php echo $unit['networth'];?>" data-price="<?php echo $sellPrice;?>" data-key="<?php echo $unitKey;?>" data-amount="<?php echo $unitsOwned;?>"><?php echo $unitsOwned;?></span>
            <?php endif;?>
        </span>
    </div>
    <div class="col-md-4 celBlock" style="padding:0px;">
	    <?php if($unitsOwned > 0):?>
        <input class="unitInput sellInput" data-nw="<?php echo $unit['networth'];?>" data-key="<?php echo $unitKey;?>" data-price="<?php echo $sellPrice;?>" max="<?php echo $unitsOwned;?>" type="number" id="sell_<?php echo $unitKey;?>" min="0" name="<?php echo $unitKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
        <?php endif;?>
    </div>
</div>
