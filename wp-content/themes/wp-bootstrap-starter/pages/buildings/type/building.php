<?php
$buildingsOwned = $userData[$buildingKey][0];
$buildingsOrdered = $userData[$buildingKey][0];
$networthPerUnit = $building['price']*$building['networth']/100;
$buyPrice =  ceil($building['price']);
$canAttack = is_array($building['attacks']) && !empty($building['attacks']) ? implode(', ', $building['attacks']) : 'N/A';
$count++;
$backColor = "45, 67, 81"
?>

<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock buildings_heading">
        <?php echo $building['normalname'];?>

        <?php if(isset($building['description'])):?>
            <span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $building['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
        <?php endif;?>
    </div>
    <div class="col-md-2 celBlock">
		<span class="columnDataLeft">Owned</span>
		<span class="columnDataRight"><?php echo $buildingsOwned; ?></span>
    </div>
    <div class="col-md-2 celBlock">
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
    <div class="col-md-1 celBlock">
	    <span class="columnDataLeft">Attack / Life</span>
		<span class="columnDataRight"><?php echo $building['attack'];?>/<?php echo $building['life'];?></span>
    </div>
    <div class="col-md-2 celBlock">
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

                <span class="allbutton" id="button<?php echo $buildingKey;?>"><?php echo (min($maxMoney, $maxSpace, $maxTurns)); ?></span>

	    </span>
    </div>
    <div class="col-md-2 celBlock inputBlock">
        <input class="unitInput" min="0" type="number" id="<?php echo $buildingKey;?>" name="<?php echo $buildingKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
        <input type="number" id="<?php echo $buildingKey;?>_total" class="ordertotal" hidden />
        <input type="number" id="<?php echo $buildingKey;?>_nw_total" class="nwtotal" hidden />
        <input type="number" id="<?php echo $buildingKey;?>_turn_total" class="turntotal" hidden  />
    </div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	calculate_<?php echo $buildingKey;?> = function()
	{
	// Caculate order total in hidden field
    var no_units = document.getElementById('<?php echo $buildingKey;?>').value;
    var price = <?php echo ceil($buyPrice);?>;
    document.getElementById('<?php echo $buildingKey;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $buyPrice*$building['networth']/100;?>;
    document.getElementById('<?php echo $buildingKey;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
    document.getElementById('<?php echo $buildingKey;?>_turn_total').value = Math.ceil(no_units/<?php echo $buildingsPerTurn;?>);
	}	
	calculate_<?php echo $buildingKey;?>();

	
	// Set total order value
	jQuery('body').on('blur', '.unitInput', function() {
	calculate_<?php echo $buildingKey;?>();
	
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
	calculate_<?php echo $buildingKey;?>();
	
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
	
	
	
	jQuery("#button<?php echo $buildingKey;?>").click(function() {
	
	jQuery("#<?php echo $buildingKey;?>").val("<?php
	
		
			echo (min($maxMoney,$maxSpace,$maxTurns));
		
		?>");
		

	calculate_<?php echo $buildingKey;?>();
     
   
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