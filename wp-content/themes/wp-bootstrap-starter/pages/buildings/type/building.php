<?php
$buildingsOwned = $userData[$buildingKey][0];
$buildingsOrdered = $userData[$buildingKey][0];
$networthPerUnit = $building['price']*$building['networth']/100;
$buyPrice =  ceil($building['price']);
$canAttack = is_array($building['attacks']) && !empty($building['attacks']) ? implode(', ', $building['attacks']) : 'N/A';
$count++;
$backColor = "45, 67, 81"
?>

<div class="row unitRow bodyRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock buildings_heading">
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
		<span id="<?php echo $buildingKey;?>_owned" class="columnDataRight"><?php echo $buildingsOwned; ?></span>
    </div>
    <div class="col-md-2 celBlock price">
	    <span class="columnDataLeft">Price</span>
	    <span class="columnDataRight">
	        <span class="hover-tip"
	            data-toggle="tooltip"
	            data-original-title="The <?php echo $building['normalname'];?> adds <?php echo $building['networth'];?>% networth. $ <?php echo $networthPerUnit; ?> per unit."
	            data-placement="bottom">
	            $ <?php echo $buyPrice;?>
	        </span>
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


            $maxMoney = floor($totalMoney / $building['price']);
			$maxTurns = floor($totalturns * $turns_multiplier);
			$maxSpace = floor(($land - $builtland) / 20);

            ?>

			<span class="allbutton" data-amount="<?php echo (min($maxMoney, $maxSpace, $maxTurns)); ?>" data-nw="<?php echo $building['networth'];?>" data-price="<?php echo $order['price'];?>" data-key="<?php echo $buildingKey;?>" id="button<?php echo $buildingKey;?>"><?php echo (min($maxMoney, $maxSpace, $maxTurns)); ?></span>

	    </span>
    </div>
    <div class="col-md-2 celBlock inputBlock">
        <input class="unitInput buyInput buy_<?php echo $buildingKey;?>" data-nw="<?php echo $building['networth'];?>" data-price="<?php echo $building['price'];?>" data-key="<?php echo $buildingKey;?>" min="0" type="number" id="<?php echo $buildingKey;?>" name="<?php echo $buildingKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>

    </div>
</div> <!-- //Close Unit row -->