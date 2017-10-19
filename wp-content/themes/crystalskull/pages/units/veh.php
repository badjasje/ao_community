<div class="spaceNotice">
	Your empty warfactories allow you to build a maximum of <strong><?php echo ($vehspace[0]*10)-count_vehspace($userID);?></strong> vehicles.
</div>


<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-2"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Owned (ordered)</strong></div>
		<div class="col-md-1"><strong>Price</strong></div>
		<div class="col-md-1"><strong>Att/Life</strong></div>
		<div class="col-md-2"><strong>Targets</strong></div>
		<div class="col-md-1"><strong>Max</strong></div>
		<div class="col-md-3"></div>
	</div>
<?php // veh TABLE
$totalveh = 0;
foreach($units as $key => $order){
$units_owned = get_user_meta($userID, $key.'_owned');
$units_ordered = get_user_meta($userID, $key.'_ordered');
$unittype = $units[$key]['type'];
?>
<?php if($unittype == 'veh'):?>
			
	<div class="row clan_profile_row2">
		
		<div class="col-md-2 center_clan_col market_column  marketHeader">
			<?php echo $order['normalname'];?>
				
			<?php if($order['description']):?>
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			<?php endif;?>
		</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Owned (ordered)</span>
		<span class="clan_data_right">
			<?php echo $units_owned[0]; ?>
			(<?php echo $units_ordered[0]; ?>)
		</span>

	</div>
	<div class="col-md-1 clan_column border_bottom_mobile">
		<span class="clan_data_left">Price</span>
		<span class="clan_data_right">
			<span 	class="hover-tip"  
					data-toggle="tooltip" 
					data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. $ <?php echo $order['price']*$order['networth']/100;?> per unit." data-placement="bottom">
					$ <?php echo $order['price'];?>
			</span>
		</span>

	</div>
	<div class="col-md-1 clan_column border_bottom_mobile">
		<span class="clan_data_left">Att/Life</span>
		<span class="clan_data_right land">
		 	<?php echo $order['attack'];?>/<?php echo $order['life'];?>
		</span>
	</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Targets</span>
		<span class="clan_data_right">
			<?php

				$i = 0;
				$len = count($order['attacks']);
				if(empty($order['attacks'])){echo 'n.a';}
				foreach($order['attacks'] as $attack){
				if ($i == $len - 1) {
				echo $attack;
				}else{echo $attack.', ';}
				
				$i++;
				}?>
		</span>
	</div>

	<div class="col-md-1 clan_column">
		<span class="clan_data_left">Max</span>
		<span class="clan_data_right">
				<?php 	$max_money = floor($totalmoney[0]/($order['price']));
						$max_turns = floor($totalturns[0]*10);
						$max_space = ($vehspace[0]*10)-count_vehspace($userID);
								
							
						?>
						<?php if($key == 'spyplane' || $key == 'spy' || $key == 'thief' || $key == 'sniper'):?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($ccspace,$max_money,$max_turns,$max_space));?></span>
						<?php else:?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
						<?php endif;?>
		</span>
	</div>
	<div class="col-md-3 clan_column border_bottom_mobile">
		<input onblur="calculate_<?php echo $key;?>()" min="0" class="marketInput buyunits" type="number" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
		<input type="number" id="<?php echo $key;?>_total" class="ordertotal" hidden />
		<input type="number" id="<?php echo $key;?>_nw_total" class="nwtotal" hidden />
		<input type="number" id="<?php echo $key;?>_turn_total" class="turntotal" hidden  />
	</div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	calculate_<?php echo $key;?> = function()
	{
	// Caculate order total in hidden field
    var no_units = document.getElementById('<?php echo $key;?>').value;
    var price = <?php echo ceil($order['price']);?>;
    document.getElementById('<?php echo $key;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $order['price']*$order['networth']/100;?>;
    document.getElementById('<?php echo $key;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
    document.getElementById('<?php echo $key;?>_turn_total').value = Math.ceil(no_units/10);
	}	
	calculate_<?php echo $key;?>();

	
	// Set total order value
	jQuery('body').on('blur', '.buyunits', function() {
	calculate_<?php echo $key;?>();
	
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
	jQuery('body').on('blur', '.buyunits', function() {
	calculate_<?php echo $key;?>();
	
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
	
	
	
	jQuery("#button<?php echo $key;?>").click(function() {
	
	jQuery("#<?php echo $key;?>").val("<?php
		if($key == 'spyplane' || $key == 'thief' || $key == 'sniper'){
		echo (min($ccspace,$max_money,$max_space));}
		else{
		echo (min($max_money,$max_space));
		}
		?>");
		

	calculate_<?php echo $key;?>();
     
   
	// Set total number of units value
    var arr = document.getElementsByClassName('buyunits');
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

<?php endif;?>
<?php }?>		
</div>