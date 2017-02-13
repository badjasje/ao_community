<?php 
if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );

nocache_headers();


$user_ID = get_current_user_id(); 

$_POST['countrycode'];


update_user_meta($user_ID,'user_country',$_POST['countrycode']);

wp_redirect(get_permalink(6570)); exit;