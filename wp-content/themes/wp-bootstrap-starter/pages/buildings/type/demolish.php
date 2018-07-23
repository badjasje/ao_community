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
<div class="row unitRow" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
    <div class="col-md-2 celBlock nameBlock demolish_heading">
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
	    <span class="columnDataRight">$ <?php echo floor($buyPrice*0.15);?></span>
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
            
            <span class="allbutton" id="demobutton<?php echo $buildingKey; ?>">
    			<?php echo min($maxMoney, $maxOwned); ?>
			</span>


	    </span>
    </div>
    <div class="col-md-2 celBlock" style="padding:0px;">
        <input class="unitInput demobds" min="0" type="number" id="demo<?php echo $buildingKey;?>" name="<?php echo $buildingKey;?>" style="border: solid rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
        <input type="number" id="demo_<?php echo $buildingKey;?>_total" class="demoordertotal" hidden />
		<input type="number" id="demo_<?php echo $buildingKey;?>_nw_total" class="demonwtotal" hidden />
    </div>
</div> <! // Close Unit row -->
<script type="text/javascript">
	
	demo_calculate_<?php echo $buildingKey;?> = function(){
	// Caculate order total in hidden field
    var no_units = document.getElementById('demo<?php echo $buildingKey;?>').value;
    var price = <?php echo ceil($building['price']*0.15);?>;
    document.getElementById('demo_<?php echo $buildingKey;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $building['price']*$building['networth']/100;?>;
    document.getElementById('demo_<?php echo $buildingKey;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
	}	


	
	// Set total order value
	jQuery('body').on('blur', '.demobds', function() {
	demo_calculate_<?php echo $buildingKey;?>();
	
    var arr = document.getElementsByClassName('demoordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demoorder_total').value = tot;
    
    var span = document.getElementById('demoorder_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
	// Do NW calculations
	jQuery('body').on('blur', '.demobds', function() {
	calculate_<?php echo $buildingKey;?>();
	
    var arr = document.getElementsByClassName('demonwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demonetworth_total').value = tot;
    
    var span = document.getElementById('demonetworth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
	
	
	jQuery("#demobutton<?php echo $buildingKey;?>").click(function () {
	jQuery("#demo<?php echo $buildingKey;?>").val("<?php echo min($maxMoney, $maxOwned);?>");

                    


	demo_calculate_<?php echo $buildingKey;?>();
     
   
	// Set total number of units value
    var arr = document.getElementsByClassName('demobds');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demototal').value = tot;
    
    var span = document.getElementById('demototal');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	// Set total value of order
    var arr = document.getElementsByClassName('demoordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demoorder_total').value = tot;
    
    var span = document.getElementById('demoorder_total');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	// Set NW of the order
	var arr = document.getElementsByClassName('demonwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('demonetworth_total').value = tot;
    
    var span = document.getElementById('demonetworth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	jQuery("#button").show();
	jQuery("#message").hide();
	});

</script>	
<?php endif;?>