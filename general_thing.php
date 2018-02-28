<?php
    
    require_once("wp-load.php");
	global $wpdb;
	//$emptyArray = maybe_serialize(array('asd','dsaaas','333aa'));
	//update_post_meta(4512, 'cooldown_list', $emptyArray);
	
	//$emptyArray = maybe_unserialize($emptyArray);
	//echo $emptyArray;
	/*
	$wpdb->query("
			UPDATE `${table_prefix}postmeta`
			SET meta_value = ''
			WHERE meta_key IN('24h_nw_list')
            ");
            
*/

 $prevmem = maybe_unserialize(get_post_meta(4512, 'open_invites', true));
 

 
  echo '<pre>';
 print_r($prevmem);
 echo '</pre>';

