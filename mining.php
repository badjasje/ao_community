<?php
    
    
require(dirname(__FILE__) . '/wp-load.php');


$userId = get_current_user_id();

if (! defined('ABSPATH')) {
    exit;
}
if (empty($userId)) {
    wp_redirect(get_permalink(3486));
    exit;
}
if (!is_user_logged_in()) {
    wp_redirect(get_permalink(3486));
    exit;
}
update_user_meta($userId, 'mining', $_GET['status']);
$_SESSION['status'] = 'Mining status switched';
wp_redirect(get_the_permalink(3596).'?tab=mining');
exit;
