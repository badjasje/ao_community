<?php
/**
 * Handles reset province
 *
 * @package WordPress
 */


require( dirname(__FILE__) . '/wp-load.php' );


$user_ID = get_current_user_id(); 

if ( ! defined( 'ABSPATH' ) ) exit; 
if(empty($user_ID)){
	wp_redirect(get_permalink(3582)); exit;
}
if ( !is_user_logged_in() ) { 
	wp_redirect(get_permalink(3582)); exit;
	}
 
update_user_meta($user_ID, 'status', 'dead');
$_SESSION['status'] = 'Account has been reset';
wp_redirect(get_permalink(3486)); 
exit;

