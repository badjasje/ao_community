<div class="tab-pane <?php echo $activeTab === 'buy' ? 'active' : ''; ?>" id="buy" role="tabpanel">

<form class="form" id="ordermissiles" method="post">
<div class="blockHeader spaceNotice">
	Your empty missile silo's allow you to build a maximum of <strong><?php echo $missilespace-$totalmissiles;?></strong> missiles
</div>



<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(45, 67, 81, 0.75);">
	<div class="col-md-2 celBlock nameBlock">Name</div>
    <div class="col-md-2 celBlock">Owned (ordered)</div>
    <div class="col-md-2 celBlock">Price</div>
    <div class="col-md-1 celBlock">Attack</div>
    <div class="col-md-2 celBlock">Targets</div>
    <div class="col-md-1 celBlock"></div>
    <div class="col-md-2 celBlock"></div>
</div> <! // Close Unit row -->


<?php 
$totalair = 0;
$count = 0;
foreach($missiles as $key => $order){
$units_owned = $userData[$key.'_owned'][0];
$units_ordered = $userData[$key.'_ordered'][0];
$unittype = $missiles[$key]['type'];
?>

			
<div class="row unitRow" style="background-color: rgba(<?php echo $buyBackColor;?>, <?php echo 0.6-($count/25);?>);">
	<div class="col-md-2 celBlock nameBlock sea_heading">
		<?php echo $order['normalname'];?>
			
		<?php if(isset($order['description'])):?>
			<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom">
			<i class="fa fa-info-circle" aria-hidden="true"></i>
			</span>
		<?php endif;?>
	</div>
	
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Owned (ordered)</span>
		<span class="columnDataRight">
			<?php echo $units_owned; ?>
			(<spam id="<?php echo $key;?>_ordered"><?php echo $units_ordered; ?></spam>)
		</span>
	</div>
	<div class="col-md-1 celBlock">
		<span class="columnDataLeft">Price</span>
		<span class="columnDataRight">
			<span 	class="hover-tip"  
					data-toggle="tooltip" 
					data-original-title="The <?php echo $order['normalname'];?> adds <?php echo $order['networth'];?>% networth. 
					$ <?php echo $order['price']*$order['networth']/100;?> per unit." 
					data-placement="bottom">
						$ <?php echo number_format(ceil($order['price']), 0, ',', ' '); ?>
			</span>	
		</span>
	</div>
	
	<div class="col-md-1 celBlock">
		<span class="columnDataLeft">Attack</span>
		<span class="columnDataRight">
		 	<?php echo $order['attack'];?>
		</span>
	</div>
	
	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Targets</span>
		<span class="columnDataRight">
			<?php if(!empty($order['attacks'])){ 
				echo implode (", ", $order['attacks']);
				}else{
				echo 'N.A';
				}
				?>
		</span>
	</div>

	<div class="col-md-1 celBlock">
		<span class="columnDataLeft">Max</span>
		<span class="columnDataRight">
			<?php 	
			if($key != 'tomahawk'){
			$max_money = floor($totalMoney/($order['price']));
			$max_turns = floor($totalturns*5);
			$max_space = $missilespace-$totalmissiles;
			}else{
			$max_money = floor($totalMoney/($order['price']));
			$max_turns = round($totalturns/3);
			$max_space = $tomahawkspace-$userData['tomahawk_owned'][0]-$userData['tomahawk_ordered'][0];
				
			}
							
						?>
		<span id="<?php echo $key;?>" class="allbutton" data-nw="<?php echo $order['networth'];?>" data-price="<?php echo $order['price'];?>" data-key="<?php echo $key;?>" data-amount="<?php echo (min($max_money,$max_turns,$max_space));?>"><?php echo (min($max_money,$max_turns,$max_space));?></span>
		</span>
	</div>
	<div class="col-md-3 celBlock inputBlock">
		<?php if($missileAccLevel == 0 && $key == 'tomahawk'):?>
			<div class="tomahawkSpan">Level 1 missile accuracy required</div>
		<?php else:?>
			<input class="unitInput buyInput  buy_<?php echo $key;?>" data-nw="<?php echo $order['networth'];?>" data-key="<?php echo $key;?>" data-price="<?php echo $order['price'];?>" type="number" id="<?php echo $key;?>" min="0" name="<?php echo $key;?>" style="border: solid rgba(<?php echo $buyBackColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
		<?php endif;?>
	</div>
</div> <! // Close Unit row -->
<?php $count++; }?>	




<div class="row statusBlockButtons">

	<div class="col-md-3 totalsField statCol-1">
		Number of missiles: <span id="total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-2">
		Total cost: $ <span id="order_total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-3">
		Turns required: <span id="turn_total">0</span>
	</div>
	<div class="col-md-3 totalsField statCol-4">
		Added networth : $ <span id="networth_total">0</span>
	</div>
</div>

<input type="submit" value="Place order" class="mainSubmit">
				
								
    
									
			</form>

</div>