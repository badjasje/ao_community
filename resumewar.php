<?php
    
    require_once("wp-load.php");
    
if ('GET' != $_SERVER['REQUEST_METHOD']) {
    header('Allow: POST');
    header('HTTP/1.1 405 Method Not Allowed');
    header('Content-Type: text/plain');
    exit;
}
    
    $declaredonID = $_GET['declaredon'];
    $declaredbyID = $_GET['declaredby'];
    
    $posts = get_posts(array(
    'numberposts'   => 1,
    'post_type'     => 'wars',
    'post_status'      => 'trash',
    'meta_query'    => array(
        'relation'      => 'AND',
        array(
            'key'       => 'declared_by',
            'value'     => $declaredbyID,
        ),
        array(
            'key'       => 'declared_on',
            'value'     => $declaredonID,
        ),
    ),
    ));


    $timestamp = current_time('timestamp');
    $my_post = array(
      'ID'           => $posts[0]->ID,
      'post_status'   => 'publish',
      'post_title'   => $timestamp,

    );

// Update the post into the database
    wp_update_post($my_post);

    $list = get_post_meta($declaredbyID, 'cooldown_list', true);
    unset($list[$declaredonID]);
    update_post_meta($declaredbyID, 'cooldown_list', $list);


    $_SESSION['status'] = 'War resumed against '.get_the_title($declaredonID).'(#'.$declaredonID.')';
    wp_redirect(get_permalink($declaredonID));
    exit;
