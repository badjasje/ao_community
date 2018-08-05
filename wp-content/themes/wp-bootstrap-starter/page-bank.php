<?php
 /*
 * Template Name: Bank
*/
include 'interest_array.php';
get_header(); 
global $userData;
global $userId;

update_user_meta($userId, 'user_lock', 0);
$banklevel = $userData['level_bank_management'][0];
$money = $userData['money'][0];
$timestamp = current_time('timestamp');
$total_deposited = 0;
$total_final = 0;
$unlocked = 0;
$backColor = "45, 67, 81";
$buttonColor = "70, 118, 94";

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

$banklevel = $userData['level_bank_management'][0];
		$startingbonus = $userData['starting_bonus'][0];
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
}
$maxDepositAmount = floor(min($max_dep,$money));
?>

<div class="row pageRow">	
	


<div class="fw-row">
<form id="bank">
	
	<div class="blockHeader" style="border-bottom:0px;">
		Your current research allows you to deposit a total of $ <?php echo number_format($max_tot, 0, ',', ' '); ?>. $ <?php echo number_format($max_dep, 0, ',', ' '); ?> maximum per deposit.
	</div>
	
	<div class="blockHeader spaceNotice">
           The minimum required to deposit is $ 5 000. You currently have <span id="nrdeposits"><?php echo count_deposits($userId);?></span> deposits.
	</div>
	
	
<div class="row no-gutters">
	<div class="col-md-6 no-gutters">
		<div class="row no-gutters">
			<div class="attackDropdown statCol-1 no-gutters">
				Days to deposit
			</div>
			
			<div style="padding:0px;" class="attackDropdown statCol-2 no-gutters">
				
				
				<select <?php echo $daysleft;?> name="days" class="attackTypeInput">
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
		</div>
	</div>
	
	<div class="col-md-6 no-gutters">
		<div class="row no-gutters">
		<div class="col-sm-6 bankCol">
		 	<input class="unitInput" min="0" max="<?php echo $maxDepositAmount;?>" placeholder="Enter amount"type="number" id="amount" name="amount" style="border: none;"/>
		</div>
		<div id="maxdep" data-max="<?php echo $maxDepositAmount;?>" class="col-sm-6 bankCol mainSubmit" style="border-top:0px;background-color:rgba(70, 118, 94, 0.8);">
			MAX
		</div>
		</div>
	</div>
</div>
	
	
	<input type="submit" value="Deposit money" class="mainSubmit">
</form>	
	
	
</div>
	
	
	
	
	
<div class="pageSpacer"></div>



<div class="row unitRow headerRow fw-row bankHeader" style="border-bottom:1px solid #fff;background-color: rgba(<?php echo $backColor;?>, 0.75);">
	<div class="col-md-3 celBlock nameBlock">
		Deposited
    </div>
    <div class="col-md-3 celBlock">
		Including interest
    </div>
    <div class="col-md-3 celBlock">
		Release date
    </div>
    <div class="col-md-3 celBlock">
    </div>
</div> <! // Close Unit row -->



	
	
<?php 	
	
	$args = array(
		'posts_per_page'   => -1,
		'author'	=> $userId,
		'post_type'        => 'deposit',
		'meta_key' => 'release_date',
		'orderby'    => 'meta_value_num',
		);
	
	$deposits = get_posts( $args ); 
	$count = 0;
	foreach ($deposits as $deposit) :
		$depositId = $deposit->ID;
		$depositData = get_post_meta($depositId);
		
		$days = $depositData['days'][0];
		$deposited = $depositData['amount'][0];
		$total_deposited+=$deposited;
		$amount = $depositData['amount'][0];
		$incl_interest = $amount*pow($rates[$days]['interest']+($extra_interest/100),$days);
		$total_final+=$incl_interest;
		$release_stamp = $depositData['release_date'][0];
		$time_left = $release_stamp-$timestamp;
		$placedStamp = $depositData['deposit_placed'][0];
		$count++;
	?>	
<div class="row unitRow fw-row deposit_<?php echo $depositId;?>" style="background-color: rgba(<?php echo $backColor;?>, <?php echo 0.6-($count/25);?>);">
	<div class="col-md-3 celBlock">
	    <span class="columnDataLeft">Deposited</span>
	    <span class="columnDataRight depositedspan" inclinterest="<?php echo $incl_interest;?>" depositamount="<?php echo $deposited;?>">$ <?php echo number_format($deposited, 0, ',', ' '); ?></span>
	</div>
	
	<div class="col-md-3 celBlock">
	    <span class="columnDataLeft">Including interest</span>
	    <span class="columnDataRight">$ <?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?></span>
	</div>
	
	<div class="col-md-3 celBlock">
	    <span class="columnDataLeft">Release date</span>
	    <span class="columnDataRight"><?php echo date('H:i | d-m-Y', $release_stamp);?></span>
	</div>
	
	<div class="col-md-3 celBlock" style="padding:0px;">
		
	
	<?php if($time_left < 0):?>
		
		<?php $unlocked+=$incl_interest;?>
		
		<button id="withdraw" unlocked="<?php echo $unlocked;?>" data-dep-value="<?php echo number_format(ceil($incl_interest), 0, ',', ' '); ?>" data-deposit="<?php echo $depositId;?>" class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/220);?>);" type="submit" value="<?php echo $depositId;?>">Withdraw</button>
		 
	<?php endif;?>
			
<?php if($banklevel >= 2 && $time_left > 0):?>
				
	<?php if($placedStamp+43200 <= $timestamp && $time_left > 0){  $unlocked+=$incl_interest; ?>
		
		<button id="withdraw" unlocked="<?php echo $unlocked;?>" data-dep-value="<?php echo number_format(ceil($deposited*$extra_interest), 0, ',', ' '); ?>" data-deposit="<?php echo $depositId;?>" class="cancelButton hoverEffect" style="background-color: rgba(<?php echo $buttonColor;?>, <?php echo 1-($count/220);?>);" type="submit" >Cancel</button>
					
				<?php }?>
<?php endif;?>
			
	
	
	
	
	</div>
</div>

<?php endforeach;?>

<div class="pageSpacer"></div>
<div class="statCol-1 blockHeader bankFoot">Total deposited: $ <span id="depositedvalue"><?php echo number_format($total_deposited, 0, ',', ' '); ?></span> (<span class="totaldeposits"><?php echo count_deposits($userId);?></span> deposits)</div>
<div class="statCol-2 blockHeader bankFoot">Total final: $ <span id="inclinterest"><?php echo number_format($total_final, 0, ',', ' '); ?></span></div>
<div class="statCol-3 blockHeader bankFoot">Total Available (unlocked): $ <span id="unlockedvalue"><?php echo number_format($unlocked, 0, ',', ' '); ?></span></div>



	
<script>
(function($) {
	

$( ".cancelButton" ).click(function() {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
	var bankvalue = $(this).attr( "data-dep-value" );
	if(confirm("Are you sure? This deposit will return $ "+bankvalue)){

	
	var withdrawid = $(this).attr( "data-deposit" );
	var withdraw;
  	
  	withdraw = $.ajax({
        url: "/withdraw_money.php",
        type: "post",
        data: {deposit : withdrawid}
    });
    
	withdraw.done(function (response, textStatus, jqXHR){ 
		console.log(response);
		var array = JSON.parse(response);
		console.log(array);
		$.notify({
			message: array.status,
			},{
			type: 'info',
			delay: 5000,
			template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
						'<i class="fa fa-info-circle"></i> ' +
						'' +
						'<span data-notify="message">{2}</span>' +
						'</div>'
				});	
		$('#withdraw').trigger("reset");
		if(array.next == true){
			$('#money').html(number_format(array.money, 0, ',', ' '));
			$('#nrdeposits,.totaldeposits').html(array.deposits);
			$('.deposit_'+array.removeid).remove();
			$("#amount").attr({
				"max" : array.newmaxdep,
				"min" : 0
			});
			$("#maxdep").attr({
				"data-max" : array.newmaxdep,
				"min" : 0
			});
			var depval = 0;
			var inclinterest = 0;
			var unlocked = 0;
			$(".depositedspan").each(function() {
				depval += +$(this).attr( "depositamount" );
				inclinterest += +$(this).attr( "inclinterest" );
    		});
    		$(".cancelButton").each(function() {
				unlocked += +$(this).attr( "unlocked" );
    		});
    		
			$('#depositedvalue').html(number_format(depval, 0, ',', ' '));
			$('#inclinterest').html(number_format(inclinterest, 0, ',', ' '));
			$('#unlockedvalue').html(number_format(unlocked, 0, ',', ' '));
			
		}
		
	});
} // End cancel if statement
});

// Variable to hold request
var request;

// Bind to the submit event of our form
$('form').submit(function( event ) {
	$('.pageLoader, #page-cover').show();
	$('.pageLoader, #page-cover').delay(250).fadeOut( "fast");
    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    //$inputs.prop("disabled", true);

    // Fire off the request to /form.php
    request = $.ajax({
        url: "/bank_money.php",
        type: "post",
        data: serializedData
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        var array = JSON.parse(response);
        
        	
				$.notify({
					message: array.status,
					},{
					type: 'info',
					delay: 5000,
					template: 	'<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
								'<i class="fa fa-info-circle"></i> ' +
								'' +
								'<span data-notify="message">{2}</span>' +
								'</div>'
						});	
			
			
			if(array.next == true){
				$("#amount").attr({
					"max" : array.newmaxdep,
					"min" : 0
				});
				$("#maxdep").attr({
					"data-max" : array.newmaxdep,
					"min" : 0
				});
				$('#money').html(number_format(array.money, 0, ',', ' '));
				$('#nrdeposits,.totaldeposits').html(array.deposits);
				
				$(".bankHeader").after("<div class='row unitRow fw-row' style='background-color: rgba(45, 67, 81, 0.56);'><div class='col-md-3 celBlock'><span class='columnDataLeft'>Deposited</span><span class='columnDataRight depositedspan' inclinterest='"+array.inclinterest+"' depositamount='"+array.deposited+"'>$ "+number_format(array.deposited, 0, ',', ' ')+"</span></div><div class='col-md-3 celBlock'><span class='columnDataLeft'>Including interest</span><span class='columnDataRight'>$ "+number_format(array.inclinterest, 0, ',', ' ')+"</span></div><div class='col-md-3 celBlock'><span class='columnDataLeft'>Release date</span><span class='columnDataRight'>"+array.releasedate+"</span></div><div class='col-md-3 celBlock' style='padding:0px;'></div></div>");
				
			var depval = 0;
			var inclinterest = 0;
			var unlocked = 0;
			$(".depositedspan").each(function() {
				depval += +$(this).attr( "depositamount" );
				inclinterest += +$(this).attr( "inclinterest" );
    		});
    		$(".cancelButton").each(function() {
				unlocked += +$(this).attr( "unlocked" );
    		});
    		
			$('#depositedvalue').html(number_format(depval, 0, ',', ' '));
			$('#inclinterest').html(number_format(inclinterest, 0, ',', ' '));
			$('#unlockedvalue').html(number_format(unlocked, 0, ',', ' '));
				
				
				$('form').trigger("reset");
			}
});	});	
})(jQuery);
</script>
	
	
	
	
<?php if($daysleft >= 3):?>	
<script type="text/javascript">
	jQuery("#maxdep").click(function() {
	var maxdep = jQuery(this).attr( "data-max" );
		jQuery("#amount").val(maxdep);

	});

</script>
<?php endif;?>
	
	
	
	
	
	
</div> <!-- end pageRow -->
<?php
get_sidebar();
get_footer();