<?php
global $wpdb;
function rcopy($src, $dst)
{

	if (is_dir($src))
	{
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
		if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
	}
	else if (file_exists($src)) copy($src, $dst);
}

$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );


$table_prefix = $wpdb->prefix;
//drop, create and insert data for commentmeta

$siteurl = home_url('/');
$upload_dir = wp_upload_dir();
$uploaddir = $upload_dir['baseurl'];


include_once('import_options.php');
include_once('import_postmeta.php');
include_once('import_posts.php');
include_once('import_terms.php');
include_once('import_term_relationships.php');
include_once('import_term_taxonomy.php');
include_once('import_layerslider.php');
include_once('import_comments.php');





rcopy("uploads/", "../../../uploads/");
echo "success";

?>