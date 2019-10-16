<?php
require_once("../../../wp-load.php");

if(!function_exists('wp_handle_upload')) {
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
}

$uploadedfile = $_FILES['file'];

$upload_overrides = array('test_form' => false);

$movefile = wp_handle_upload($uploadedfile, $upload_overrides);

if ($movefile && !isset($movefile['error'])) {
    echo basename($movefile['file']);
} else {
    echo $movefile['error'];
}
