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
			<span class="allbutton" id="button<?php echo $unitKey;?>"><?php echo (min($space['special'], $maxMoney, $maxSpace, $maxTurns));?></span>
		<?php else : ?>
                <span class="allbutton" id="button<?php echo $unitKey;?>"><?php echo (min($maxMoney, $maxSpace, $maxTurns)); ?></span>
		<?php endif;?>
	    </span>
    </div>
    <div class="col-md-2 celBlock inputBlock">
        <input class="unitInput" min="0" type="number" id="<?php echo $unitKey;?>" name="<?php echo $unitKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
        <input type="number" id="<?php echo $unitKey;?>_total" class="ordertotal" hidden />
        <input type="number" id="<?php echo $unitKey;?>_nw_total" class="nwtotal" hidden />
        <input type="number" id="<?php echo $unitKey;?>_turn_total" class="turntotal" hidden  />
    </div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	calculate_<?php echo $unitKey;?> = function()
	{
	// Caculate order total in hidden field
    var no_units = document.getElementById('<?php echo $unitKey;?>').value;
    var price = <?php echo ceil($buyPrice);?>;
    document.getElementById('<?php echo $unitKey;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $buyPrice*$unit['networth']/100;?>;
    document.getElementById('<?php echo $unitKey;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
    document.getElementById('<?php echo $unitKey;?>_turn_total').value = Math.ceil(no_units/<?php echo $unitsPerTurn[$unitTypeKey];?>);
	}	
	calculate_<?php echo $unitKey;?>();

	
	// Set total order value
	jQuery('body').on('blur', '.unitInput', function() {
	calculate_<?php echo $unitKey;?>();
	
    var arr = document.getElementsByClassName('ordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('order_total').value = tot;
    
    var span = document.getElementById('order_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
	// Do NW calculations
	jQuery('body').on('blur', '.unitInput', function() {
	calculate_<?php echo $unitKey;?>();
	
	// calculate NW total
    var arr = document.getElementsByClassName('nwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('networth_total').value = tot;
    
    var span = document.getElementById('networth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	// Calculate turn total
	
	var arr = document.getElementsByClassName('turntotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('turn_total').value = tot;
    
    var span = document.getElementById('turn_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	});
	
	
	
	jQuery("#button<?php echo $unitKey;?>").click(function() {
	
	jQuery("#<?php echo $unitKey;?>").val("<?php
		if(in_array($unitKey, $specialUnits)){
		echo (min($space['special'],$maxTurns ,$maxSpace));
		}
		else{
			echo (min($maxMoney,$maxSpace,$maxTurns));
		}
		?>");
		

	calculate_<?php echo $unitKey;?>();
     
   
	// Set total number of units value
    var arr = document.getElementsByClassName('unitInput');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('total').value = tot;
    
    var span = document.getElementById('total');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	// Calculate turn total
	
	var arr = document.getElementsByClassName('turntotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('turn_total').value = tot;
    
    var span = document.getElementById('turn_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	// Set total value of order
    var arr = document.getElementsByClassName('ordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('order_total').value = tot;
    
    var span = document.getElementById('order_total');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	// Set NW of the order
	var arr = document.getElementsByClassName('nwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('networth_total').value = tot;
    
    var span = document.getElementById('networth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	jQuery("#button").show();
	jQuery("#message").hide();
	});

</script>	
