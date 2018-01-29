<div class="tab-pane <?php echo $activeTab === 'build' ? 'active': ''; ?>" id="build" role="tabpanel">


<form class="form" action="<?php echo home_url() ?>/build.php" name="" id="market" method="post">
	    
	    
<div class="spaceNotice">
	Your free land allows you to build <strong><?php echo floor(($land - $builtland) / 20); ?></strong> buildings.
</div>

<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-2"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Owned</strong></div>
		<div class="col-md-1"><strong>Price</strong></div>
		<div class="col-md-1"><strong>Att/Life</strong></div>
		<div class="col-md-1"><strong>Targets</strong></div>
		<div class="col-md-2"><strong>Power usage</strong></div>
		<div class="col-md-1"><strong>Max</strong></div>
		<div class="col-md-2"></div>
	</div>
<?php // Buildings TABLE


$totalbuildings = 0;

foreach ($buildings as $key => $order) {
$units_owned = $userData[$key][0];
?>
			
	<div class="row clan_profile_row2">
		
		<div class="col-md-2 center_clan_col market_column marketHeader">
			<?php echo $order['normalname']; ?>
				<?php if ($order['description']): ?>
					<span class="hover-tip" data-toggle="tooltip"
                          data-original-title="<?php echo $order['description']; ?>" data-placement="right"><i
						  class="fa fa-info-circle" aria-hidden="true"></i></span>
				<?php endif; ?>
		</div>
	
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Owned</span>
		<span class="clan_data_right">
			<?php echo $units_owned; ?>
		</span>

	</div>
	<div class="col-md-1 clan_column border_bottom_mobile">
		<span class="clan_data_left">Price</span>
		<span class="clan_data_right">
			<span 	class="hover-tip"  
					data-toggle="tooltip" 
					data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. 
					$ <?php echo $order['price']*$order['networth']/100;?> per unit." 
					data-placement="bottom">
						$ <?php echo ceil($order['price']);?>
			</span>	
		</span>

	</div>
	<div class="col-md-1 clan_column border_bottom_mobile">
		<span class="clan_data_left">Att/Life</span>
		<span class="clan_data_right land">
		 	<?php echo $order['attack'];?>/<?php echo $order['life'];?>
		</span>
	</div>
	
	<div class="col-md-1 clan_column border_bottom_mobile">
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
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Power usage</span>
		<span class="clan_data_right">
			<?php if ($order['power'] != 0) {
					echo $order['power'];
				} else {
					echo 'n.a';
				} ?>
		</span>

	</div>
	<div class="col-md-1 clan_column">
		<span class="clan_data_left">Max</span>
		<span class="clan_data_right">
			<?php 	$max_money = floor($totalMoney / $order['price']);
					$max_turns       = floor($totalturns * $turns_multiplier);
					$max_land        = floor(($land - $builtland) / 20);
				?>

				<span class="allbutton" id="button<?php echo $key; ?>">
					<?php echo(min($max_money, $max_land, $max_turns)); ?>
				</span>
		</span>
	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<input class="marketInput buyunits" min="0" type="number" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
		<input type="number" id="<?php echo $key;?>_total" class="ordertotal" hidden />
		<input type="number" id="<?php echo $key;?>_nw_total" class="nwtotal" hidden />
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
	});
	
	
	
	jQuery("#button<?php echo $key;?>").click(function () {
      jQuery("#<?php echo $key;?>").val("<?php echo min($max_land, $max_turns, $max_money);?>");

                    


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
<?php }?>		
</div>
   
<div class="col-md-12 totalsField">
				
	<div class="col-md-4">
		Number of buildings: <span id="total">0</span>
	</div>
	<div class="col-md-4">
		Total cost: $ <span id="order_total">0</span>
	</div>
	<div class="col-md-4">
		Added networth : $ <span id="networth_total">0</span>
	</div>
	
</div>


        <input type="submit" value="Build" class="">
        <div class="footer_continue">
            <input type="submit" value="Build" class="">
        </div>


    </form>


</div>
