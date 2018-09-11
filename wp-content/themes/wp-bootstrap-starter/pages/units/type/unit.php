<?php
$unitsOwned = $userData[$unitKey.'_owned'][0];
$unitsOrdered = $userData[$unitKey.'_ordered'][0];
$networthPerUnit = $unit['price']*$unit['networth']/100;
$buyPrice =  ceil($unit['price']);
$canAttack = is_array($unit['attacks']) && !empty($unit['attacks']) ? implode(', ', $unit['attacks']) : 'N/A';
$count++;




?>
<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock <?php echo $unitTypeKey;?>_heading">
        <?php echo $unit['normalname'];?>

        <?php if(isset($unit['description'])):?>
            <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $unit['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
        <?php endif;?>
    </div>
    <div class="col-md-2 celBlock">
		<span class="columnDataLeft">Owned (ordered)</span>
		<span class="columnDataRight"><span id="<?php echo $unitKey;?>_owned"><?php echo $unitsOwned; ?></span> (<?php echo $unitsOrdered; ?>)</span>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Price</span>
	    <span class="columnDataRight">
	        <span class="hover-tip"
	            data-toggle="tooltip"
	            data-original-title="The <?php echo $unit['normalname'];?> adds <?php echo $unit['networth'];?>% networth. $ <?php echo $networthPerUnit; ?> per unit."
	            data-placement="bottom">
	            $ <?php echo $buyPrice;?>
	        </span>
	    </span>
    </div>
    <div class="col-md-1 celBlock">
	    <span class="columnDataLeft">Attack / Life</span>
		<span class="columnDataRight"><?php echo $unit['attack'];?>/<?php echo $unit['life'];?></span>
    </div>
    <div class="col-md-2 celBlock">
	    <span class="columnDataLeft">Targets</span>
		<span class="columnDataRight"><?php echo $canAttack; ?></span>
    </div>
    <div class="col-md-1 celBlock maxBlock">
	    <span class="columnDataLeft">Max</span>
	    <span class="columnDataRight">
		<?php 
            $maxMoney = floor($totalMoney / $buyPrice);
            $maxSpace = $space[$unitTypeKey] - $usedSpace[$unitTypeKey];
            $maxTurns = floor($totalturns*$unitsPerTurn[$unitTypeKey]);
       
            ?>

		<?php if(in_array($unitKey, $specialUnits)) : ?>
			<span type-key="<?php echo $unitTypeKey;?>" data-key="<?php echo $unitKey;?>" data-price="<?php echo $buyPrice;?>" data-nw="<?php echo $unit['networth'];?>" class="allbutton" id="button<?php echo $unitKey;?>"><?php echo (min($space['special'], $maxMoney, $maxSpace, $maxTurns));?></span>
		<?php else : ?>
                <span type-key="<?php echo $unitTypeKey;?>" data-key="<?php echo $unitKey;?>" data-price="<?php echo $buyPrice;?>" data-nw="<?php echo $unit['networth'];?>" class="allbutton" id="button<?php echo $unitKey;?>"><?php echo (min($maxMoney, $maxSpace, $maxTurns)); ?></span>
		<?php endif;?>
	    </span>
    </div>
    <div class="col-md-2 celBlock inputBlock">
        <input class="unitInput buyInput buy_<?php echo $unitKey;?>" min="0" type-key="<?php echo $unitTypeKey;?>" data-key="<?php echo $unitKey;?>" data-price="<?php echo $buyPrice;?>" data-nw="<?php echo $unit['networth'];?>" type="number" id="<?php echo $unitKey;?>" name="<?php echo $unitKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
        <input type="number" id="<?php echo $unitKey;?>_total" class="ordertotal" hidden />
        <input type="number" id="<?php echo $unitKey;?>_nw_total" class="nwtotal" hidden />
        <input type="number" id="<?php echo $unitKey;?>_turn_total" class="turntotal" hidden  />
    </div>
</div> <! // Close Unit row -->