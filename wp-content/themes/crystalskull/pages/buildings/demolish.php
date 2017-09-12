<div class="tab-pane <?php echo $activeTab === 'demolish' ? 'active': ''; ?>" id="demolish" role="tabpanel">

    <form class="form" action="<?php echo home_url() ?>/demolish.php" name="" id="demolish" method="post">
	    
	    
	    
<div class="tab-pane <?php echo $activeTab === 'build' ? 'active': ''; ?>" id="build" role="tabpanel">


<form class="form" action="<?php echo home_url() ?>/build.php" name="" id="market" method="post">
	    
	    
<div class="spaceNotice">
	Your free land allows you to build <strong><?php echo floor(($land[0] - $builtland[0]) / 20); ?></strong> buildings.
</div>

<div class="row market_block">	
	<div class="row clan_header_row storeDetails-heads">
		<div class="col-md-3"><strong>Name</strong></div>
		<div class="col-md-2"><strong>Owned</strong></div>
		<div class="col-md-2"><strong>Cost to demolish</strong></div>
		<div class="col-md-2"><strong>Max</strong></div>
		<div class="col-md-3"></div>
	</div>
<?php // Buildings TABLE
$totalbuildings = 0;
foreach ($buildings as $key => $order) {
$units_owned = get_user_meta($user_ID, $key);
if ($units_owned[0] > 0) {
?>
			
	<div class="row clan_profile_row2">
		
		<div class="col-md-3 center_clan_col market_column marketHeader">
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
			<?php echo $units_owned[0]; ?>
		</span>

	</div>
	<div class="col-md-2 clan_column border_bottom_mobile">
		<span class="clan_data_left">Cost to demolish</span>
		<span class="clan_data_right">
			$ <?php echo floor($order['price'] * 0.15); ?>
		</span>

	</div>
	
	<div class="col-md-2 clan_column">
		<span class="clan_data_left">Max</span>
		<span class="clan_data_right">
			<?php $max_demo_money = floor($totalmoney[0] / ($order['price'] * 0.15));
				$max_owned            = $units_owned[0];

				if ($order['normalname'] == 'Airfield') {

					$max_demo_money = floor($max_demo_money - ($totalair / 10));

					if ($max_demo_money < 0) {
						$max_demo_money = 0;
					}

					$max_owned = floor($max_owned - ($totalair / 10));

					if ($max_owned < 0) {
						$max_owned = 0;
					}
				}

				if ($order['normalname'] == 'Shipyard') {

					$max_demo_money = floor($max_demo_money - ($totalsea / 5));

					if ($max_demo_money < 0) {
						$max_demo_money = 0;
					}

					$max_owned = floor($max_owned - $totalsea / 5);

					if ($max_owned < 0) {
						$max_owned = 0;
					}
				}

				if ($order['normalname'] == 'Baracks') {

					$max_demo_money = floor($max_demo_money - ($totalinf / 20));

					if ($max_demo_money < 0) {
						$max_demo_money = 0;
					}

					$max_owned = floor($max_owned - ($totalinf / 20));

					if ($max_owned < 0) {
						$max_owned = 0;
					}
				}
				if ($order['normalname'] == 'Warfactory') {

					$max_demo_money = floor($max_demo_money - ($totalveh / 10));

					if ($max_demo_money < 0) {
						$max_demo_money = 0;
					}

					$max_owned = floor($max_owned - ($totalveh / 10));

					if ($max_owned < 0) {
						$max_owned = 0;
					}
				}


				?>
			<span class="allbutton" id="demobutton<?php echo $key; ?>">
    			<?php echo min($max_demo_money, $max_owned); ?>
			</span>
		</span>
	</div>
	<div class="col-md-3 clan_column border_bottom_mobile">
		<input class="marketInput demobds" min="0" type="number" id="demo<?php echo $key;?>" name="<?php echo $key;?>"/>
		<input type="number" id="demo_<?php echo $key;?>_total" class="demoordertotal" hidden />
		<input type="number" id="demo_<?php echo $key;?>_nw_total" class="demonwtotal" hidden />
	</div>
</div> <! // Close Unit row -->

<script type="text/javascript">
	
	demo_calculate_<?php echo $key;?> = function(){
	// Caculate order total in hidden field
    var no_units = document.getElementById('demo<?php echo $key;?>').value;
    var price = <?php echo ceil($order['price']*0.15);?>;
    document.getElementById('demo_<?php echo $key;?>_total').value = parseInt(no_units)*parseInt(price);
    
  
    var networth = <?php echo $order['price']*$order['networth']/100;?>;
    document.getElementById('demo_<?php echo $key;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
	}	


	
	// Set total order value
	jQuery('body').on('blur', '.demobds', function() {
	demo_calculate_<?php echo $key;?>();
	
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
	calculate_<?php echo $key;?>();
	
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
	
	
	
	jQuery("#demobutton<?php echo $key;?>").click(function () {
	jQuery("#demo<?php echo $key;?>").val("<?php echo min($max_demo_money, $max_owned);?>");

                    


	demo_calculate_<?php echo $key;?>();
     
   
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
<?php }}?>		
</div>
   
<div class="col-md-12 totalsField">
				
	<div class="col-md-4">
		Number of buildings: <span id="demototal">0</span>
	</div>
	<div class="col-md-4">
		Total cost: $ <span id="demoorder_total">0</span>
	</div>
	<div class="col-md-4">
		Networth lost : $ -<span id="demonetworth_total">0</span>
	</div>
	
</div>

        <input type="submit" value="Demolish" class="">
        <div class="footer_continue">
            <input type="submit" value="Demolish" class="">
        </div>


    </form>

</div></div>  

