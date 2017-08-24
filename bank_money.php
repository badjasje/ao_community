<?php
/**
 * Handles market orders
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );
if(get_field('game_status','option') == 'Live'){
nocache_headers();


if(!is_numeric($_POST['amount'])){
	$_SESSION['status'] = 'Enter a valid number';
	wp_redirect(get_permalink(3953)); exit;
	}


if($_POST['amount'] <= 0){
	$_SESSION['status'] = 'Enter a valid number';
	wp_redirect(get_permalink(3953)); exit;
	}


/* Get some important variables */
$user_ID = get_current_user_id(); 

$userLock = get_user_meta($user_ID, 'user_lock', true);

if($userLock == 1){
	update_user_meta($user_ID, 'user_lock', 0);
	$_SESSION['status'] = 'Please try again.';
	wp_redirect(get_permalink(3582));
}
update_user_meta($user_ID, 'user_lock', 1);

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
	
$money = get_user_meta($user_ID,'money');


/* check if user actually has enough cash */
if($money[0] < $_POST['amount']){
	$_SESSION['status'] = 'Insufficient funds';
	wp_redirect(get_permalink(3953));exit;
	}


$deposits = get_user_meta($user_ID,'total_deposits');
if(empty($deposits)){
	$deposits = 0;
	
}

$args = array(
	'posts_per_page'   => -1,
	'author'	   => $user_ID,
	'post_type'        => 'deposit'
	);
	$_deposits = get_posts( $args ); 
	
	$tot_deposited = 0;
	
	/* Get total amount of deposited money */
	foreach ($_deposits as $_deposit) {
		$tot_deposited+=get_post_meta($_deposit->ID, 'amount')[0];
		
		}
		
/* Get banking level and max values */
$banklevel = get_user_meta($user_ID, 'level_bank_management')[0];
$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
	$finance_multi = 1;
	if($startingbonus == 'finance'){
		$finance_multi = 1.5;
	}

if($banklevel == 0){
	$max_dep = 250000*$finance_multi;
	$max_tot = 2500000*$finance_multi;
}
if($banklevel == 1){
	$max_dep = 350000*$finance_multi;
	$max_tot = 3500000;
}
if($banklevel == 2){
	$max_dep = 450000*$finance_multi;
	$max_tot = 4500000;
}
if($banklevel == 3){
	$max_dep = 500000*$finance_multi;
	$max_tot = 5000000*$finance_multi;
}



/* check for minimum value */
if($_POST['amount'] < 5000){
	$_SESSION['status'] = 'Deposit at least $ 5 000';
	wp_redirect(get_permalink(3953));exit;
	}
	

/* check amount of deposits made, max 10 */
if($deposits[0] >= 10){
	$_SESSION['status'] = 'You already made 10 deposits';
	wp_redirect(get_permalink(3953));exit;
	}


/* check if the sum of the amount + the amount already deposited doesn't exceed the max set by research */
if($tot_deposited+$_POST['amount'] > $max_tot){
	$_SESSION['status'] = 'The total sum exceeds the amount of deposited money you can have at this time';
	wp_redirect(get_permalink(3953));exit;
	}


/* check if deposit doesn't exceed the max deposit based on research */
if($_POST['amount'] > $max_dep){
	$_SESSION['status'] = "Your research doesn't allow you to deposit this much";
	wp_redirect(get_permalink(3953));exit;
	}else{
	


/* Create the actual deposit */	
$timestamp = current_time('timestamp');
$RELEASE_DATE = $timestamp+($_POST['days']*86400);	
	
		$args = array(
				'post_title'    => $RELEASE_DATE,
				'post_status'   => 'publish',
				'post_type'		=> 'deposit',
				'post_author'   => $user_ID
				);
			
			
			$new_order_id = wp_insert_post( $args );
			update_post_meta($new_order_id,'release_date',$RELEASE_DATE);
			update_post_meta($new_order_id,'deposit_placed',$timestamp);
			update_post_meta($new_order_id,'days',$_POST['days']);
			update_post_meta($new_order_id,'amount',$_POST['amount']);
			update_user_meta($user_ID,'money',$money[0]-$_POST['amount']);
			update_user_meta($user_ID,'total_deposits',$deposits[0]+1);
	
	/* return to banking page succesful */
	update_user_meta($user_ID, 'user_lock', 0);
	$_SESSION['status'] = 'Deposit placed';wp_redirect(get_permalink(3953));exit;
	
}
}