<div class="spaceNotice">
	<?php echo $specialSold;?> special units sold today. You can sell a maximum of 50 special units per day.
</div>

<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-3"><strong>Name</strong></div>
		<div class="col-md-3"><strong>Price</strong></div>
		<div class="col-md-2"><strong>You can sell</strong></div>
		<div class="col-md-4"></div>
	</div>
<?php // inf TABLE
$totalinf = 0;
foreach($units as $key => $order){
$units_owned = get_user_meta($userID, $key.'_owned');
$units_ordered = get_user_meta($userID, $key.'_ordered');
$unittype = $units[$key]['type'];
if($units_owned[0] != 0){
?>
<?php if($unittype == 'inf'):?>
			
	<div class="row clan_profile_row2">
		
		<div class="col-md-3 center_clan_col market_column marketHeader">
			<?php echo $order['normalname'];?>
				
			<?php if($order['description']):?>
				<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
			<?php endif;?>
		</div>
	
	<div class="col-md-3 clan_column border_bottom_mobile">
		<span class="clan_data_left">Price</span>
		<span class="clan_data_right">
			$ <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>
		</span>

	</div>
	

	<div class="col-md-2 clan_column">
		<span class="clan_data_left">Max</span>
		<span class="clan_data_right">
			<?php if($key == 'sniper' || $key == 'thief' || $key == 'spy'):?>
				<span class="allbutton" id="button<?php echo $key;?>"><?php echo min($units_owned[0],$specialSold);?></span>
			<?php else:?>
				<span class="allbutton" id="button<?php echo $key;?>"><?php echo $units_owned[0];?></span>
			<?php endif;?>
	</div>
	<div class="col-md-4 clan_column border_bottom_mobile">
		<input class="marketInput sellunits" min="0" type="number" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
		<input type="number" id="<?php echo $key;?>_total" class="ordertotal" hidden />
		<input type="number" id="<?php echo $key;?>_nw_total" class="nwtotal" hidden />
	</div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	calculate_<?php echo $key;?> = function()
	{
	// Caculate order total in hidden field
    var no_units = document.getElementById('<?php echo $key;?>').value;
    var price = <?php echo ceil($order['price']*2.2*0.65*$discount*$shipping_discount);?>;
    document.getElementById('<?php echo $key;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $order['price']*$order['networth']/100;?>;
    document.getElementById('<?php echo $key;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
	}	
	calculate_<?php echo $key;?>();

	
	// Set total order value
	jQuery('body').on('blur', '.sellunits', function() {
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
	jQuery('body').on('blur', '.sellunits', function() {
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
	
	
	jQuery("#button<?php echo $key;?>").click(function() {
	<?php if($key == 'sniper' || $key == 'thief' || $key == 'spy'):?>
		jQuery("#<?php echo $key;?>").val("<?php echo min($units_owned[0],$specialSold);?>");
	<?php else:?>
		jQuery("#<?php echo $key;?>").val("<?php echo $units_owned[0];?>");
	<?php endif;?>
		

	calculate_<?php echo $key;?>();
     
   
	// Set total number of units value
    var arr = document.getElementsByClassName('sellunits');
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

<?php endif;?>
<?php }}?>		
</div>