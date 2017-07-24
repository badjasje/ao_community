<?php
/**
 * Handles queueing researches
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

/* Get research input by user */
$research = $_POST['research'];
$totalturns = get_user_meta($user_ID, 'turns');

if($totalturns[0] < 30){$_SESSION['status'] = 'Not enough turns';wp_redirect(get_permalink(4837));exit;}



/* set up arguments for creating research post */

			update_user_meta($user_ID, 'queued_research', $research);
			update_user_meta( $user_ID, 'turns',$totalturns[0]-30);
			
			$_SESSION['status'] = $researches[$research]['name'].' research queued';wp_redirect(get_permalink(4837));exit;