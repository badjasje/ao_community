<?php
$unitsOwned = get_user_meta($userId, $unitKey.'_owned', true);
$unitsOrdered = get_user_meta($userId, $unitKey.'_ordered');

$sellPrice =  ceil($unit['price'] * $marketSellMultiplier);
$networthPerUnit = $unit['price'] * $unit['networth'] / 100;
?>

<div class="row clan_profile_row2">
    <div class="col-md-3 center_clan_col market_column marketHeader">
        <?php echo $unit['normalname'];?>

        <?php if (isset($unit['description'])) : ?>
            <span class="hover-tip" data-toggle="tooltip" data-original-title="<?php echo $unit['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				</span>
        <?php endif;?>
    </div>

    <div class="col-md-3 clan_column border_bottom_mobile">
        <span class="clan_data_left">Price</span>
        <span class="clan_data_right">
			$ <?php echo $sellPrice;?>
		</span>
    </div>


    <div class="col-md-2 clan_column">
        <span class="clan_data_left">Max</span>
        <span class="clan_data_right">
			<?php if (in_array($unitKey, $specialUnitsArray)) : ?>
                <span class="allbutton" id="button<?php echo $unitKey;?>"><?php echo min($unitsOwned[0],$specialSold);?></span>
            <?php else:?>
                <span class="allbutton" id="button<?php echo $unitKey;?>"><?php echo $unitsOwned[0];?></span>
            <?php endif;?>
        </span>
    </div>
    <div class="col-md-4 clan_column border_bottom_mobile">
        <input class="marketInput sellunits" min="0" type="number" id="<?php echo $unitKey;?>" name="<?php echo $unitKey;?>"/>
        <input type="number" id="<?php echo $unitKey;?>_total" class="ordertotal" hidden />
        <input type="number" id="<?php echo $unitKey;?>_nw_total" class="nwtotal" hidden />
    </div>
</div>

<script type="text/javascript">
    calculate_<?php echo $unitKey;?> = function()
    {
        // Caculate order total in hidden field
        var no_units = document.getElementById('<?php echo $unitKey;?>').value;
        var price = <?php echo $sellPrice;?>;
        document.getElementById('<?php echo $unitKey;?>_total').value = parseInt(no_units)*parseInt(price);


        var networth = <?php echo $networthPerUnit;?>;
        document.getElementById('<?php echo $unitKey;?>_nw_total').value = parseInt(no_units)*parseInt(networth);
    }
    calculate_<?php echo $unitKey;?>();


    // Set total order value
    jQuery('body').on('blur', '.sellunits', function() {
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
    jQuery('body').on('blur', '.sellunits', function() {
        calculate_<?php echo $unitKey;?>();

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



    jQuery("#button<?php echo $unitKey;?>").click(function() {
        <?php if($unitKey == 'spyplane'):?>
        jQuery("#<?php echo $unitKey;?>").val("<?php echo min($unitsOwned[0],$specialSold);?>");
        <?php else:?>
        jQuery("#<?php echo $unitKey;?>").val("<?php echo $unitsOwned[0];?>");
        <?php endif;?>

        calculate_<?php echo $unitKey;?>();


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
