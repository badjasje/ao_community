<?php
    
    
require(dirname(__FILE__) . '/wp-load.php');


$user_ID = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($user_ID)) {
    wp_redirect(get_permalink(3486));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3486));
    exit;
}


$nightmode = get_user_meta($user_ID, 'nightmode', true);


update_user_meta($user_ID, 'nightmode', $_POST['mode']);
$_SESSION['status'] = 'Color scheme switched';
wp_redirect(get_the_permalink(3486));
exit;
