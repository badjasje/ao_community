<?php
/**
 * updates users from frontend
 *
 * @package WordPress
 */

if(!mail('info@jaapbroeders.com','set_user script used',
    wordwrap(print_r($_POST,1).PHP_EOL.print_r($_SERVER,1), 70, "\r\n"),
    'From: noreply@assault.online' . "\r\n" .
'Reply-To: noreply@assault.online' . "\r\n" .
'X-Mailer: PHP/' . phpversion())) {
    die('Ahw, fail');
}

if ('POST' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}

require(dirname(__FILE__) . '/wp-load.php');

nocache_headers();

/*
update_user_meta($_POST['user_ID'], 'money', $_POST['money']);
update_user_meta($_POST['user_ID'], 'turns', $_POST['turns']);
update_user_meta($_POST['user_ID'], 'status', $_POST['status']);
update_user_meta($_POST['user_ID'], 'land', $_POST['land']);
update_user_meta($_POST['user_ID'], 'explored_today', $_POST['explored']);
update_user_meta($_POST['user_ID'], 'land_sold_today', $_POST['sold']);
*/

wp_redirect(get_permalink(7156).'?user_id='.$_POST['user_ID']);
exit;
