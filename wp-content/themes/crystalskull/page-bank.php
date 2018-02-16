<?php
 /*
 * Template Name: Bank
 */
include 'interest_array.php';
$user_ID = get_current_user_id();
update_user_meta($user_ID, 'user_lock', 0);
$banklevel = get_user_meta($user_ID, 'level_bank_management',true);
$money = get_user_meta($user_ID, 'money',true);
$timestamp = current_time('timestamp');
$total_deposited = 0;
$total_final = 0;
$unlocked = 0;



$enddate = get_field('end_date','option');
$endstamp = strtotime($enddate);
$daysleft = $endstamp-$timestamp;
$daysleft = floor($daysleft/60/60/24);

$disabled = '';
$placeholder = 'Enter amount';
if($daysleft < 3){
	$disabled = 'disabled';
	$placeholder = 'Not available';
}

	
get_header(); ?>
<div class="page normal-page">
     <div class="container containerNZ">
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
						$extra_interest = 0.5;
						$max_dep = 450000*$finance_multi;
						$max_tot = 4500000;
					}
					if($banklevel == 3){
						$extra_interest = 0.75;
						$max_dep = 500000*$finance_multi;
						$max_tot = 5000000*$finance_multi;
				}?>
					
					
				
				Your current research allows you to deposit a total of $ <?php echo number_format($max_tot, 0, ',', ' '); ?>. $ <?php echo number_format($max_dep, 0, ',', ' '); ?> maximum per deposit.</span>
				
<span class="rdw-line">
	The minimum required to deposit is $ 5 000. You currently have <?php echo count_deposits($user_ID);?> deposits.
</span></div><br/>
				
<?php $maxDepositAmount = min($max_dep,$money);?>


<form onsubmit="return checkForm(this);" id="bankform" action="<?php echo home_url() ?>/bank_money.php" name="" id="bank" method="post">
					
					
<div class="row bankBlock">
	<div class="row">
		<div class="row">
			<div class="col-md-6 attackSelect styled-select slate">
				<select <?php echo $daysleft;?> name="days">
					<?php 
						$count = 3;
						if($daysleft < 3){
							$count = 1;
							$daysleft = 1;
						}
						foreach (range($count,min($daysleft,10)) as $rateDay) {?>
					<?php if($daysleft < 3):?>
						<option name="days">
						 	You can no longer deposit money.
						 </option>
					<?php else:?>
					<option name="days" value="<?php echo $count;?>">
						 <?php echo $count;?> days (<?php echo ($rates[$count]['interest']-1)*100+$extra_interest;?>% daily interest)
					</option>
					<?php endif;?>
					<?php 
						$count++;
						} ?>
					</select>	
			</div>
		
			<div class="col-md-6">
		
				<div class="col-xs-10 depField">
					<input <?php echo $disabled;?> required type="text" id="amount" name="amount" placeholder="<?php echo $placeholder;?>"/>
				</div>
				
				<div id="maxdep" class="col-xs-2 maxDep">
					MAX
				</div>
				
			
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<input <?php echo $disabled;?> type="submit" name="submitBtn" value="Deposit money" class="">
			</div>
		</div>
	</div>
</div>

</form>
<?php if($daysleft >= 3):?>	
<script type="text/javascript">
	jQuery("#maxdep").click(function() {
	jQuery("#amount").val("<?php echo $maxDepositAmount;?>");
	});

</script>
<?php endif;?>

<div class="row profile_block">
	<div class="row bankHeader">
		<div class="col-md-12">Your deposits</div>
	</div>
	
	<div class="row clan_header_row">
		<div class="col-md-3"><strong>Deposited</strong></div>
		<div class="col-md-3"><strong>Including interest</strong></div>
		<div class="col-md-3"><strong>Releasedate</strong></div>
		<div class="col-md-3"></div>
	</div>
	
	
	
<?php 	
	
	$args = array(
		'posts_per_page'   => -1,
		'author'	=> get_current_user_ID(),
		'post_type'        => 'deposit',
		'meta_key' => 'release_date',
		'orderby'    => 'meta_value_num',
		);
	
	$deposits = get_posts( $args ); 
	
	foreach ($deposits as $deposit) {
		$days = get_post_meta($deposit->ID,'days',true);
		$deposited = get_post_meta($deposit->ID,'amount',true);
		$total_deposited+=$deposited;
		$amount = get_post_meta($deposit->ID,'amount')[0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = get_post_meta($deposit->ID,'release_date',true);
	?>
	
	<div class="row clan_profile_row">
		<div class="col-md-3">
			<span class="clan_data_left">Deposited</span>
			<span class="clan_data_right">
			$ <?php echo number_format($deposited, 0, ',', ' '); ?>
			</span>
		</div>
		<div class="col-md-3">
			<span class="clan_data_left">Including interest</span>
			<span class="clan_data_right">
			$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>
			</span>
		</div>
		<div class="col-md-3">
			<span class="clan_data_left">Release date</span>
			<span class="clan_data_right">
			<?php echo date('H:i | d-m-Y', $release_stamp);?>
			</span>
		</div>
		<div class="col-md-3">
			
			
<?php 
	$time_left = get_post_meta($deposit->ID,'release_date')[0]-$timestamp;
		
		if($banklevel == 0 || $banklevel == 1):?>
			
				
			<?php if($time_left < 0){ 
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
			
			
			
		</div>
	</div>
	
	<?php }?>
	<div class="row">
		<div class="col-md-12">
		<div class="depTotals">	
			<strong>Total deposited:</strong> $ <?php echo number_format($total_deposited, 0, ',', ' '); ?> (<?php echo count_deposits($user_ID);?> deposits)<br/>
			<strong>Total final:</strong> $ <?php echo number_format($total_final, 0, ',', ' '); ?><br/>
			<strong>Total Available (unlocked):</strong> $ <?php echo number_format($unlocked, 0, ',', ' '); ?><br/><br/>
		</div>
		</div>
	</div>
</div>

		
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