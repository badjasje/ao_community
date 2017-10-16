<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active': ''; ?>" id="sell" role="tabpanel">
	
<form class="form" action="<?php echo home_url() ?>/sell_missiles.php" name="" id="market" method="post">
<div class="spaceNotice">
	Selling a missile returns 75% of the original price.
</div>

<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-3"><strong>Name</strong></div>
		<div class="col-md-3"><strong>Sell price</strong></div>
		<div class="col-md-2"><strong>You can sell</strong></div>
		<div class="col-md-4"></div>
	</div>
<?php // AIR TABLE
$totalair = 0;
foreach($missiles as $key => $order){
$missiles_owned = get_user_meta($userId, $key.'_owned',true);
$units_ordered = get_user_meta($userId, $key.'_ordered');
$unittype = $units[$key]['type'];
if($missiles_owned > 0){
?>
			
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
			$ <?php echo ceil($order['price']*0.75);?>
		</span>

	</div>
	

	<div class="col-md-2 clan_column">
		<span class="clan_data_left">Max</span>
		<span class="clan_data_right">
			<span class="allbutton" id="sellbutton<?php echo $key;?>"><?php echo $missiles_owned;?></span>
		</span>
	</div>
	<div class="col-md-4 clan_column border_bottom_mobile">
		<input class="marketInput sellunits" min="0" type="number" id="sell<?php echo $key;?>" name="<?php echo $key;?>"/>
		<input type="number" id="sell<?php echo $key;?>_total" class="sellordertotal" hidden />
		<input type="number" id="sell<?php echo $key;?>_nw_total" class="sellnwtotal" hidden />
	</div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	sell_calculate_<?php echo $key;?> = function()
	{
	// Caculate order total in hidden field
    var no_units = document.getElementById('sell<?php echo $key;?>').value;
    var price = <?php echo ceil($order['price']*0.75);?>;
    document.getElementById('sell<?php echo $key;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $order['price']*$order['networth']/100;?>;
    document.getElementById('sell<?php echo $key;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
	}	
	sell_calculate_<?php echo $key;?>();

	
	// Set total order value
	jQuery('body').on('blur', '.sellunits', function() {
	sell_calculate_<?php echo $key;?>();
	
    var arr = document.getElementsByClassName('sellordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('sellorder_total').value = tot;
    
    var span = document.getElementById('sellorder_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
	// Do NW calculations
	jQuery('body').on('blur', '.sellunits', function() {
	sell_calculate_<?php echo $key;?>();
	
    var arr = document.getElementsByClassName('sellnwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('sellnetworth_total').value = tot;
    
    var span = document.getElementById('sellnetworth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	});
	
	
	
	jQuery("#sellbutton<?php echo $key;?>").click(function() {
	jQuery("#sell<?php echo $key;?>").val("<?php echo $missiles_owned;?>");
		

	sell_calculate_<?php echo $key;?>();
     
   
	// Set total number of units value
    var arr = document.getElementsByClassName('sellunits');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('selltotal').value = tot;
    
    var span = document.getElementById('selltotal');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	// Set total value of order
    var arr = document.getElementsByClassName('sellordertotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('sellorder_total').value = tot;
    
    var span = document.getElementById('sellorder_total');

	while( span.firstChild ) {
   		span.removeChild( span.firstChild );
		}
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	// Set NW of the order
	var arr = document.getElementsByClassName('sellnwtotal');
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseInt(arr[i].value))
            tot += parseInt(arr[i].value);
    }
    document.getElementById('sellnetworth_total').value = tot;
    
    var span = document.getElementById('sellnetworth_total');

	while( span.firstChild ) {
    	span.removeChild( span.firstChild );
	}	
	span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );
	
	
	jQuery("#button").show();
	jQuery("#message").hide();
	});

</script>	


<?php }}?>		
</div>	


				
				


				
<script>
// Set total number of units value
jQuery('body').on('change', '.sellunits', function() {

var arr = document.getElementsByClassName('sellunits');
var tot=0;
for(var i=0;i<arr.length;i++){
if(parseInt(arr[i].value))
tot += parseInt(arr[i].value);
}
document.getElementById('selltotal').value = tot;

var span = document.getElementById('selltotal');

while( span.firstChild ) {
span.removeChild( span.firstChild );
}

span.appendChild( document.createTextNode(number_format(tot, 0, ',', ' ')) );

});
</script>		


<div class="col-md-12 totalsField">
				
		<div class="col-md-4">
			Number of missiles: <span id="selltotal">0</span>
		</div>
		<div class="col-md-4">
			Total: $ <span id="sellorder_total">0</span>
		</div>
		<div class="col-md-4">
			Networth lost: $ -<span id="sellnetworth_total">0</span>
		</div>

</div>		
				
				
					<input type="submit" value="Sell missiles" class="">
					<div class="footer_continue">
					<input type="submit" value="Sell missiles" class="">
					</div>
								
    
									
			</form>

</div>