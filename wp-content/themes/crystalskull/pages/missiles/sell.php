<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active': ''; ?>" id="sell" role="tabpanel">
<form class="form" action="<?php echo home_url() ?>/sell_missiles.php" name="" id="market" method="post">
				
				
				
				<div id="tab-1" class="tab-content current">
				<table class="responsive-table">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Owned (ordered)</th>
						<th scope="col">Sell price</td>
						<th scope="col">Attack</th>
						<th scope="col">Targets</th>
						<th scope="col">Max</th>
						<th scope="col"></th>
  					</tr>
  					</thead
  					<tbody>
				<?php // MISSILE TABLE
				
					foreach($missiles as $key => $order){
					$missiles_owned = get_user_meta($user_ID, $key.'_owned',true);
					if($missiles_owned > 0){
					$units_ordered = get_user_meta($user_ID, $key.'_ordered');
					$unittype = $missiles[$key]['type'];
					?>
					<?php if($missileAccLevel == 0 && $key == 'tomahawk'){?>
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $missiles_owned; ?> (<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Price">
					$ <?php echo round($order['price']*0.75);?>
					</td>
					
					<td data-title="Attackpower">
						<?php echo $order['attack'];?>
					</td>
					
					<td data-title="Targets">
					<?php 
						$i = 0;
						$len = count($order['attacks']);
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
			
					</td>
					
					<td data-title="Max">
						<?php 	
								if($key != 'tomahawk'){
								$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*5);
								$max_space = $missilespace[0]-$totalmissiles;
								}else{
								$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = round($totalturns[0]/3);
								$max_space = $tomahawkspace-get_user_meta($user_ID, 'tomahawk_owned', true)-get_user_meta($user_ID, 'tomahawk_ordered', true);
									
								}
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
					</td>
					
					<th colspan='2'data-title="">
					<div class="tomahawkSpan">Level 1 missile accuracy required</div>
					</th>
					</tr>
					
					<?php } else {?> 
					<tr>
					<th scope="row">
					<?php echo $order['normalname'];?>
					</th>
					
					<td data-title="Owned">
					<?php echo $missiles_owned; ?> (<?php echo $units_ordered[0]; ?>)				
					</td>
					
					<td data-title="Sell price">
					$ <?php echo round($order['price']*0.75);?>
					</td>
					
					<td data-title="Attackpower">
						<?php echo $order['attack'];?>
					</td>
					
					<td data-title="Targets">
					<?php 
						$i = 0;
						$len = count($order['attacks']);
						foreach($order['attacks'] as $attack){
						if ($i == $len - 1) {
						echo $attack;	
    					}else{echo $attack.', ';}
								
						$i++;
						;
						
						
						}?>
			
					</td>
					
					<td data-title="Max">
						<?php 	
								if($key != 'tomahawk'){
								$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = floor($totalturns[0]*5);
								$max_space = $missilespace[0]-$totalmissiles;
								}else{
								$max_money = floor($totalmoney[0]/($order['price']));
								$max_turns = round($totalturns[0]/3);
								$max_space = $tomahawkspace-get_user_meta($user_ID, 'tomahawk_owned', true)-get_user_meta($user_ID, 'tomahawk_ordered', true);
									
								}
							
						?>
						<span class="allbutton" id="button<?php echo $key;?>"><?php echo $missiles_owned;?></span>
					</td>
					
					<th colspan='2'data-title="">
					<input class="small_input" type="text" id="<?php echo $key;?>" name="<?php echo $key;?>"/>
					</th>
					</tr>
					<script type="text/javascript">
						jQuery("#button<?php echo $key;?>").click(function() {
						jQuery("#<?php echo $key;?>").val("<?php echo $missiles_owned;?>");
						jQuery("#button").show();
						jQuery("#message").hide();
						});
					
					</script>
  					
					<?php }}}?>
					<tbody>
				</table>
				
				
				
				
				
					<input type="submit" value="Sell missiles" class="">
					<div class="footer_continue">
					<input type="submit" value="Sell missiles" class="">
					</div>
								
    
									
			</form>

</div>