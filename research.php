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

nocache_headers();

include 'research_array.php';

/* Get necessary vars */
$user_ID = get_current_user_id(); 
$current_research = get_user_meta($user_ID, 'research_in_progress', true);
if($current_research != 0){
	wp_redirect(get_permalink(4837));exit;
}
/* Get research input by user */
$research = $_POST['research'];
$totalturns = get_user_meta($user_ID, 'turns');

if($totalturns[0] < 25){$_SESSION['status'] = 'Not enough turns';
	wp_redirect(get_permalink(4837));exit;
	}

$timestamp = strtotime(date('Y-m-d H:i:s'));

$startingbonus = get_user_meta($user_ID, 'starting_bonus',true);
$research_reduce = 1;
if($startingbonus == 'defensive'){
	$research_reduce = 0.9;
}

/* Get duration of research */
$time = $researches[$research]['duration'];

/* set up arguments for creating research post */
$args = array(
				'post_title'    => $timestamp+($time*60*60*$research_reduce),  /* Receive research timestamp */
				'post_status'   => 'publish',
				'post_content'	=> $research, 
				'post_type'		=> 'research',
				'post_author'   => $user_ID
				);
				
			
			$new_research_id = wp_insert_post( $args );
			
			update_user_meta($user_ID, 'research_in_progress', $research);
			update_user_meta( $user_ID, 'turns',$totalturns[0]-25);
			
			$_SESSION['status'] = $researches[$research]['name'].' research started';wp_redirect(get_permalink(4837));exit;