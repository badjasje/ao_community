<?php
 /*
 * Template Name: Bank
 */
include 'interest_array.php';
$user_ID = get_current_user_id();
$banklevel = get_user_meta($user_ID, 'level_bank_management')[0];
get_header(); ?>
<div class="page normal-page">
     <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12">
	            
	            <?php if(!empty($_SESSION['status'])):?>
					<?php echo alert_notification($_SESSION['status']);?>
				<?php endif; // End empty status check ?>
	            
								
			<?php if(get_field('game_status','option') != 'Live'):?>
			<div class="notice_message"><span class="rdw-line">The round has ended!</span></div>
			<?php else:?>
			
			
				<div class="notice_message"><span class="rdw-line">
					
				<?php
					
					$banklevel = get_user_meta($user_ID, 'level_bank_management')[0];
					$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
						$finance_multi = 1;
						if($startingbonus == 'finance'){
							$finance_multi = 1.5;
						}
					
					if($banklevel == 0){
						$extra_interest = 0;
						$max_dep = 250000*$finance_multi;
						$max_tot = 2500000*$finance_multi;
					}
					if($banklevel == 1){
						$extra_interest = 0.5;
						$max_dep = 350000*$finance_multi;
						$max_tot = 3500000;
					}
					if($banklevel == 2){
						$extra_interest = 0.75;
						$max_dep = 450000*$finance_multi;
						$max_tot = 4500000;
					}
					if($banklevel == 3){
						$extra_interest = 1;
						$max_dep = 500000*$finance_multi;
						$max_tot = 5000000*$finance_multi;
				}?>
					
					
				
				Your current research allows you to deposit a total of $ <?php echo number_format($max_tot, 0, ',', ' '); ?>. $ <?php echo number_format($max_dep, 0, ',', ' '); ?> maximum per deposit.</span>
				
				<span class="rdw-line">The minimum required to deposit is $ 5 000. You currently have <?php echo count_deposits($user_ID);?> deposits.</span></div><br/>
				
				<form onsubmit="return checkForm(this);" id="bankform" action="<?php echo home_url() ?>/bank_money.php" name="" id="bank" method="post">
				
			<table class="responsive-table">
				<thead>
				<tr>
					<th scope="col">Days</th>
					<th scope="col">Amount</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						<select name="days">
						<?php foreach ($rates as $key => $rate) { ?>
						
						<option name="days" value="<?php echo $key;?>"><?php echo $key;?> days (<?php echo ($rate['interest']-1)*100+$extra_interest;?>% daily interest</option>
						
						<?php } ?>
						
						
						</select>
					</td>
					<td><input required type="text" id="amount" name="amount" placeholder="Enter amount"/>
					</td>
				</tr>
				</tbody>
			</table>
			<input type="submit" name="submitBtn" value="Deposit money" class="">
				</form>

				<br/>
				<center><h1>Your deposits</h1></center>
				<br/>
		
				<div class="clan_sorter">
				<center>Sort by
				<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('depsort'), [])">Deposited</a>
				<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('intsort'), [])">Incl. interest</a>
				<a class="sort-buttons"onclick="sorttable.innerSortFunction.apply(document.getElementById('datesort'), [])">Date</a>
				</center>
				<br/>
				</div>
				
				
				<table class="responsive-table sortable">
					<thead>
					<tr>
						<th id="depsort" scope="col">Deposited</th>
						<th id="intsort" scope="col">Including interest</th>
						<th id="datesort" scope="col">Releasedate</th>
						<th scope="col">Release</th>
					</tr>
					</thead>
					<tbody>
				<?php 	
	
		$args = array(
	'posts_per_page'   => -1,
	'author'	=> get_current_user_ID(),
	'post_type'        => 'deposit',
	'meta_key' => 'release_date',
	'orderby'    => 'meta_value_num',
	);
	$deposits = get_posts( $args ); 
	$timestamp = strtotime(date('Y-m-d H:i:s'));
	$total_deposited = 0;
	$total_final = 0;
	$unlocked = 0;
	foreach ($deposits as $deposit) {
		$days = get_post_meta($deposit->ID,'days',true);
		$deposited = get_post_meta($deposit->ID,'amount',true);
		$total_deposited+=$deposited;
		$amount = get_post_meta($deposit->ID,'amount')[0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = get_post_meta($deposit->ID,'release_date',true);
	?>
			<tr>
				<td sorttable_customkey="<?php echo $deposited;?>" data-title="Deposited">
					$ <?php echo number_format($deposited, 0, ',', ' '); ?>
				</td>
				
				<td sorttable_customkey="<?php echo $incl_interest;?>" data-title="Including interest">
					$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>
					<?php //echo get_post_meta($deposit->ID,'amount')[0]*(1+(0.02*5));?>
				</td>
				
				<td sorttable_customkey="<?php echo $release_stamp;?>" data-title="Release date">
					<?php echo date('H:i | d-m-Y', $release_stamp);?>
				</td>
			
				<td>
					<?php 
						$time_left = get_post_meta($deposit->ID,'release_date')[0]-$timestamp;
						if($banklevel == 0 || $banklevel == 1):?>
			
				
					<?php 
					if($time_left < 0){ 
						$unlocked+=$incl_interest;
						
					?>
				<form onsubmit="return checkForm(this);" class="form" action="<?php echo home_url() ?>/withdraw_money.php" name="" id="cancel" method="post">
					<input style="display:none;"type="text" id="deposit" name="deposit" value="<?php echo $deposit->ID;?>"/>
					<input name="submitBtn" onclick="return confirm('Are you sure you want to withdraw $ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>?')" class="btn btn-general"type="submit" value="Withdraw" class="">
					</form>
				<?php }?>
				<?php elseif($banklevel >= 2):?>
				
				<?php 
					$dep_placed = get_post_meta($deposit->ID,'deposit_placed')[0];
					if($time_left < 0){
						$unlocked+=$incl_interest;
						 ?>
					<form onsubmit="return checkForm(this);" class="form" action="<?php echo home_url() ?>/withdraw_money.php" name="" id="cancel" method="post">
					<input style="display:none;"type="text" id="deposit" name="deposit" value="<?php echo $deposit->ID;?>"/>
					<input name="submitBtn" onclick="return confirm('Are you sure you want to withdraw $ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>?')" class="btn btn-general"type="submit" value="Withdraw" class="">
					</form>
					<?php }
					if($dep_placed+43200 <= $timestamp && $time_left > 0){ 
						$unlocked+=$incl_interest;
					?>
				<form onsubmit="return checkForm(this);" class="form" action="<?php echo home_url() ?>/withdraw_money.php" name="" id="cancel" method="post">
					<input style="display:none;"type="text" id="deposit" name="deposit" value="<?php echo $deposit->ID;?>"/>
					<input name="submitBtn" onclick="return confirm('Cancelling this deposit returns $ <?php echo number_format(ceil($deposited*$extra_interest), 0, ',', ' '); ?>. Are you sure?')"class="btn btn-general"type="submit" value="Cancel" class="">
					</form>
				<?php }?>
				<?php endif;?>
				</td>
			</tr>
				
				
				
			<?php	}?>
					</tbody>
				</table>
			
			<strong>Total deposited:</strong> $ <?php echo number_format($total_deposited, 0, ',', ' '); ?> (<?php echo count_deposits($user_ID);?> deposits)<br/>
			<strong>Total final:</strong> $ <?php echo number_format($total_final, 0, ',', ' '); ?><br/>
			<strong>Total Available (unlocked):</strong> $ <?php echo number_format($unlocked, 0, ',', ' '); ?>
			<br/><br/>
			<?php endif;?>
			<?php session_unset(); ?>
            
            </div>
        </div>
    </div>
</div>

	<script type="text/javascript">

  function checkForm(form) // Submit button clicked
  {
    //
    // check form input values
    //

    form.submitBtn.disabled = true;
    form.submitBtn.value = "Please wait...";
    return true;
  }

 

</script>
<?php get_footer(); ?>