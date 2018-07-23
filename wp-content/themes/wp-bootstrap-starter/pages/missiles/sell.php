<div class="tab-pane <?php echo $activeTab === 'sell' ? 'active' : ''; ?>" id="sell" role="tabpanel">
	
<form class="form" name="" id="sellmissiles" method="post">
<div class="blockHeader spaceNotice">
	Selling a missile returns 75% of the original price.
</div>

<div class="row unitRow headerRow" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $sellBackColor;?>, 0.75);">
	<div class="col-md-3 celBlock nameBlock">Name</div>
    <div class="col-md-3 celBlock">Sell price</div>
    <div class="col-md-2 celBlock">You can sell</div>
    <div class="col-md-4 celBlock"></div>
</div> <! // Close Unit row -->
	
<?php
$count = 0;
foreach($missiles as $key => $order){
$missiles_owned = $userData[$key.'_owned'][0];
$units_ordered = $userData[$key.'_ordered'][0];
$unittype = $missiles[$key]['type'];
if($missiles_owned > 0){
?>
			
<div class="row unitRow removerow_<?php echo $key;?>" style="background-color: rgba(<?php echo $sellBackColor;?>, <?php echo 0.6-($count/25);?>);">
		
	<div class="col-md-3 celBlock nameBlock air_heading">
		<?php echo $order['normalname'];?>
		<?php if(isset($order['description'])):?>
			<span class="hover-tip"  data-toggle="tooltip" data-original-title="<?php echo $order['description'];?>" data-placement="bottom">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
			</span>
		<?php endif;?>
	</div>
	
	<div class="col-md-3 celBlock">
		<span class="columnDataLeft">Price</span>
		<span class="columnDataRight">
			$ <?php echo number_format(ceil($order['price']*0.75), 0, ',', ' '); ?>
		</span>

	</div>
	

	<div class="col-md-2 celBlock">
		<span class="columnDataLeft">Max</span>
		<span class="columnDataRight">
			<span id="maxsell_<?php echo $key;?>" class="sellall" data-nw="<?php echo $order['networth'];?>" data-price="<?php echo $order['price']*0.75;?>" data-key="<?php echo $key;?>" data-amount="<?php echo $missiles_owned;?>"><?php echo $missiles_owned;?></span>
		</span>
	</div>
	<div class="col-md-4 celBlock" style="padding:0px;">
		<input class="unitInput sellInput" data-nw="<?php echo $order['networth'];?>" data-key="<?php echo $key;?>" data-price="<?php echo $order['price']*0.75;?>" max="<?php echo $missiles_owned;?>" type="number" id="sell_<?php echo $key;?>" min="0" name="<?php echo $key;?>" style="border: solid rgba(<?php echo $sellBackColor;?>, <?php echo 0.6-($count/25);?>);border-width:5px 13px 5px 13px;"/>
	</div>
</div> <! // Close Unit row -->

<?php $count++; }}?>		


<div class="row statusBlockButtons">

	<div class="col-md-4 totalsField statCol-1">
		Number of missiles: <span id="totalsell">0</span>
	</div>
	<div class="col-md-4 totalsField statCol-2">
		Return value: $ <span id="return_val">0</span>
	</div>
	<div class="col-md-4 totalsField statCol-3">
		Networth lost: $ <span id="nw_lost">0</span>
	</div>
</div>

<input type="submit" value="Sell missiles" class="mainSubmit">
    
									
			</form>

</div>
