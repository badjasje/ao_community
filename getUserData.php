<?php
	require_once("wp-load.php");
	
	$data = get_user_meta( 2 );
	
	echo '<pre>';
	print_r(maybe_serialize($data));
	echo '</pre>';