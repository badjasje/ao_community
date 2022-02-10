<?php
  
require_once("wp-load.php");
/*
$orders = get_posts(array(
	'numberposts'	=> -1,
	'post_type'		=> 'market_order',
	'meta_key'		=> 'order_type',
	'meta_value'	=> 'units'
));

foreach ($orders as $order) {
	
	$meta = get_post_meta( $order->ID );

	$userId = $meta['user_placed_id'][0];
	$unitType = $meta['unit_type'][0];
	
	$amount = get_user_meta( $userId, $unitType.'_ordered', true );
	
	if(empty($amount)){
		echo 'FFS';
			echo '<pre>';
	print_r($unitType.' - '.$amount);
	echo '</pre>';
	echo  $meta['amount_ordered'][0];
	update_user_meta( $userId, $unitType.'_ordered', $meta['amount_ordered'][0]);
	}
	

	
}
*/

/*
    $args = array(
        'meta_query'=>

         array(

            array(

                'relation' => 'AND',

            array(
                'key' => 'last_online',
                'value' => $timestamp-3728000,
                'compare' => ">",
                'type' => 'numeric'
            ),

            array(
                'key' => 'networth',
                'value' =>  10,
                'compare' => ">",
                'type' => 'numeric'
            ),



          )
       )
    );

    $users = get_users( $args );


foreach ($users as $user):
$userData = get_user_meta($user->ID);
$userId = $user->ID;

$unit_1 = get_user_meta( $userId, 'unit_1_ordered', true );
if(empty($unit_1)){
	
	update_user_meta( $userId, 'unit_1_ordered', 0 );
}



$unit_2 = get_user_meta( $userId, 'unit_2_ordered', true );
if(empty($unit_2)){
	
	update_user_meta( $userId, 'unit_2_ordered', 0 );
}






$unit_3 = get_user_meta( $userId, 'unit_3_ordered', true );
if(empty($unit_3)){
	
	update_user_meta( $userId, 'unit_3_ordered', 0 );
}





$unit_4 = get_user_meta( $userId, 'unit_4_ordered', true );
if(empty($unit_4)){
	
	update_user_meta( $userId, 'unit_4_ordered', 0 );
}





$unit_5 = get_user_meta( $userId, 'unit_5_ordered', true );
if(empty($unit_5)){
	
	update_user_meta( $userId, 'unit_5_ordered', 0 );
}






$unit_6 = get_user_meta( $userId, 'unit_6_ordered', true );
if(empty($unit_6)){
	
	update_user_meta( $userId, 'unit_6_ordered', 0 );
}





$unit_7 = get_user_meta( $userId, 'unit_7_ordered', true );
if(empty($unit_7)){
	
	update_user_meta( $userId, 'unit_7_ordered', 0 );
}




$unit_8 = get_user_meta( $userId, 'unit_8_ordered', true );
if(empty($unit_8)){
	
	update_user_meta( $userId, 'unit_8_ordered', 0 );
}





$unit_9 = get_user_meta( $userId, 'unit_9_ordered', true );
if(empty($unit_9)){
	
	update_user_meta( $userId, 'unit_9_ordered', 0 );
}



endforeach;


