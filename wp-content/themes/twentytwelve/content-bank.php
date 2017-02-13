<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
include 'interest_array.php';
$user_ID = get_current_user_id();
$banklevel = get_user_meta($user_ID, 'level_bank_management')[0];

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<div class="entry-content">
				<?php if(!empty($_SESSION['status'])):?>
			<?php if($_SESSION['status'] == 0):?>
				<div class="marketnotice">Units ordered</div>
			<?php elseif($_SESSION['status'] == 1):?>
				<div class="marketnotice insuffunds">Insufficient funds</div>
			<?php elseif($_SESSION['status'] == 2):?>
				<div class="marketnotice insuffunds">Your research doesn't allow you to deposit this much</div>
			<?php elseif($_SESSION['status'] == 3):?>
				<div class="marketnotice">Deposit placed</div>
			<?php elseif($_SESSION['status'] == 4):?>
				<div class="marketnotice insuffunds">You already made 10 deposits</div>
			<?php elseif($_SESSION['status'] == 5):?>
				<div class="marketnotice">$ <?php echo number_format($_SESSION['withdrawn'], 0, ',', ' '); ?> withdrawn</div>
			<?php elseif($_SESSION['status'] == 6):?>
				<div class="marketnotice">The total sum exceeds the amount of deposited money you can have at this time.</div>
			<?php elseif($_SESSION['status'] == 7):?>
				<div class="marketnotice">You canceled your deposit. $ <?php echo number_format($_SESSION['withdrawn'], 0, ',', ' '); ?> withdrawn</div>
			<?php elseif($_SESSION['status'] == 12):?>
				<div class="marketnotice insuffunds">Enter a valid number</div>
			<?php elseif($_SESSION['status'] == 13):?>
				<div class="marketnotice insuffunds">Deposit at least $ 5 000</div>
			<?php endif;?><?php endif;?>
				
				<center><h1>Bank</h1></center>
				<center><p>
				<?php if($banklevel == 0):
					$extra_interest = 0;
				?>
				Your current research allows you to deposit a total of $ 2 500 000. $ 250 000 maximum per deposit.
				<?php elseif($banklevel == 1):
					$extra_interest = 0.5;
				?>
				Your current research allows you to deposit a total of $ 3 500 000. $ 350 000 maximum per deposit.
				<?php elseif($banklevel == 2):
					$extra_interest = 0.75;
				?>
				Your current research allows you to deposit a total of $ 4 500 000. $ 450 000 maximum per deposit.
				<?php elseif($banklevel == 3):
					$extra_interest = 1;
				?>
				Your current research allows you to deposit a total of $ 5 000 000. $ 500 000 maximum per deposit.
				<?php endif;?>
				<br/>The minimum required to deposit is $ 5 000
				</p></center>
				<form class="form" action="<?php echo home_url() ?>/bank_money.php" name="" id="bank" method="post">
				
			<table>
				<tr>
					<td>Days
					</td>
					<td>Amount
					</td>
				</tr>
				<tr>
					<td>
						<select name="days">
						<?php foreach ($rates as $key => $rate) { ?>
						
						<option name="days" value="<?php echo $key;?>"><?php echo $key;?> days (<?php echo ($rate['interest']-1)*100+$extra_interest;?>% daily interest</option>
						
						<?php } ?>
						
						
						</select>
					</td>
					<td><input class="small_input" type="text" id="amount" name="amount"/>
					</td>
				</tr>
			</table>
			<input type="submit" value="Deposit money" class="">
				</form>
				<br/>
				<center><h1>Your deposits</h1></center>
				<br/>
		
				<table>
					<tr>
						<td>Deposited
						</td>
						<td>Including interest
						</td>
						<td>Releasedate
						</td>
						<td>
						</td>
					</tr>
				<?php 	
	
		$args = array(
	'posts_per_page'   => -1,
	'author'	=> get_current_user_ID(),
	'post_type'        => 'deposit',
	);
	$deposits = get_posts( $args ); 

	$timestamp = strtotime(date('Y-m-d H:i:s'));
	
	foreach ($deposits as $deposit) {
		$days = get_post_meta($deposit->ID,'days')[0]
	?>
			<tr>
				<td>
					$ <?php echo number_format(get_post_meta($deposit->ID,'amount')[0], 0, ',', ' '); ?>
				</td>
				
				<td>
					$ <?php $amount = get_post_meta($deposit->ID,'amount')[0];
						echo number_format(ceil($amount*pow($rates[$days]['interest']+($extra_interest/100),$days)), 0, ',', ' ');
						?>
					<?php //echo get_post_meta($deposit->ID,'amount')[0]*(1+(0.02*5));?>
				</td>
				
				<td>
					<?php echo date('d-m-Y | H:i:s', get_post_meta($deposit->ID,'release_date')[0]);?>
				</td>
				
				<td>
					<?php 
						$time_left = get_post_meta($deposit->ID,'release_date')[0]-$timestamp;
						if($banklevel == 0 || $banklevel == 1):?>
					<?php 
					if($time_left < 0){ ?>
				<a href="/withdraw_money.php?id=<?php echo $deposit->ID;?>">Withdraw</a>
				<?php }?>
				<?php elseif($banklevel >= 2):?>
				
				<?php 
					$dep_placed = get_post_meta($deposit->ID,'deposit_placed')[0];
					if($time_left < 0){ ?>
				<a href="/withdraw_money.php?id=<?php echo $deposit->ID;?>">Withdraw</a>
					<?php }
					if($dep_placed+43200 <= $timestamp && $time_left > 0){ ?>
				<a href="/withdraw_money.php?id=<?php echo $deposit->ID;?>">Cancel</a>
				<?php }?>
				<?php endif;?>
				</td>
			</tr>
				
				
				
			<?php	}?>
				
				</table>
			
			
			
			<?php session_unset(); ?>
	
		</div><!-- .entry-content -->
		
	</article><!-- #post -->
