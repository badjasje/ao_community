<?php $maxAmount = floor((20000-get_user_meta($userId, 'explored_today')[0])/(200-((ceil($ownedland[0]*0.002))))); ?>

<div class="spaceNotice">
	<?php if(empty(get_user_meta($userId, 'explored_today')[0]) || get_user_meta($userId, 'explored_today')[0] == 0):?>
		You haven't explored any land today. You can explore <strong><?php echo number_format(20000-get_user_meta($userId, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo $maxAmount;?> turns)</i>
	<?php else:?>
		You have explored <strong><?php echo number_format(get_user_meta($userId, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> today.
		You can explore an additional <strong><?php echo number_format(20000-get_user_meta($userId, 'explored_today')[0], 0, ',', ' '); ?> m<sup>2</sup></strong> <i>(<?php echo floor((20000-get_user_meta($userId, 'explored_today')[0])/(200-((ceil($ownedland[0]*0.002)))));?> turns)</i>
	<?php endif;?>
</div>

<form class="form" action="<?php echo home_url() ?>/explore.php" name="" id="explore" method="post">
	<div class="row market_block">	
	<div class="row">	
		<div class="row">
			<div class="col-md-3"></div>
			<div class="col-md-3 clan_column"><strong><center>Turns to explore</center></strong></div>
			<div class="col-md-3 clan_column">
				<div class="col-xs-10">
					<input pattern="[0-9]" min="0" type="number" class="marketInput" type="text" id="turns" name="turns" value=""/>
				</div>
				<div id="maxexp" class="col-xs-2 maxExplore">
					MAX
				</div>
			</div>
			<div class="col-md-3"></div>
		</div>
	</div>
	</div>
	<input type="submit" value="Explore" class="">
						
</form>

<script type="text/javascript">
	jQuery("#maxexp").click(function() {
	jQuery("#turns").val("<?php echo $maxAmount;?>");
	});

</script>
