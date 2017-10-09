<?php 	$sold_land_today = get_user_meta($user_ID, 'land_sold_today',true);
		$freeland = get_user_meta($user_ID, 'land', true)-get_user_meta($user_ID, 'builtland', true);
		$maxSell = 20000-$sold_land_today;
?>
<div class="spaceNotice">
	1 m<sup>2</sup> has a value of $ 75. You have <?php echo $freeland;?> m<sup>2</sup> of free land.
	
		You have sold <strong><?php echo $sold_land_today;?> m<sup>2</sup></strong> today. You can sell an additional <strong><?php echo $maxSell;?> m<sup>2</sup></strong>
	
</div>

<form class="form" action="<?php echo home_url() ?>/sell_land.php" name="" id="explore" method="post">
	
	
<div class="row market_block">	
	<div class="row">	
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-3 clan_column"><strong><center>Amount of land to sell</center></strong></div>
			<div class="col-md-3 clan_column">
				<div class="col-xs-10">
					<input pattern="[0-9]" type="number" min="0"  class="marketInput" type="text" id="land" name="land" value=""/>
				</div>
				<div id="maxsell" class="col-xs-2 maxExplore">
					MAX
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
</div>
						
<input type="submit" value="Sell land" class="">
					
</form>

<script type="text/javascript">
	jQuery("#maxsell").click(function() {
	jQuery("#land").val("<?php echo $maxSell;?>");
	});

</script>