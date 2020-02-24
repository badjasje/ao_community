<?php
  
require_once("wp-load.php");
update_post_meta( 1605195, 'previous_members', '');
/*

$args = array(
	'meta_key'     	=> 'last_online',
	'orderby'      	=> 'meta_value_num',
	'meta_value'	=> $timestamp-1728000,
	'meta_compare'	=> '>',

);


    $args = array(
        'meta_query'=>

         array(

            array(

                'relation' => 'AND',

            array(
                'key' => 'last_online',
                'value' => $timestamp-1728000,
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

echo get_user_name($user->ID);
$turnSpread = maybe_unserialize(maybe_unserialize($userData['turn_spread'][0]));
echo '<pre>';
print_r($turnSpread);
echo '</pre>';
$totalTurnspread = array_sum($turnSpread);
echo 'Turns lost: '.$userData['turns'][0].'<br/>';
echo 'Turns on hand: '.$userData['turns_lost'][0].'<br/>';
echo 'Turns in turnspread: '.$totalTurnspread.'<br/><br/>';
echo 'All turns: '.$totalTurnspread+$userData['turns_lost'][0]+$userData['turns'][0].'<br/><br/>';
endforeach;

*/
/*

$userId = 1029;
$array_for_filter = array(


						'satellite',
						'regular',
						'air_sea',
						'ground',
						'missile',
	);
$args = array(
	'posts_per_page'   => -1,
	'orderby'          	=> 'date',
	'order'            	=> 'DESC',

	'post_type'        	=> 'event_local',
	'post_status'      	=> 'publish',
	'meta_query'	=> array(
					'relation' => 'AND',
					array(
						'key'	 	=> 'defender_id',
						'value'	  	=> $userId,
						'compare' 	=> '=',
						),
					array(
						'key' => 'attacktype',
						'value' => $array_for_filter,
						'compare' => 'IN'
						),


						)
);

$attacks = get_posts( $args );

$buildingslost = 0;
foreach ($attacks as $attack):
$eventData = get_post_meta($attack->ID);

$buildingslost += $eventData['total_buildings_lost'][0];


endforeach;
echo $buildingslost;





    $ip_array = maybe_unserialize(get_field('login_array_general',139664));

	foreach ($ip_array as $ip => $userdata):?>
	<h2><?php echo $ip;?></h2>
	<?php if(count($userdata) > 1){ echo '<span style="color:#ff0000"><strong>MULTI DETECTED</strong></span><br/><br/>';}?>
	<?php foreach ($userdata as $userId => $data):?>
	<?php echo get_user_name($userId);?>
	<?php echo '<pre>';
	print_r($userdata[$userId]);
	echo '</pre>';
	$geodata = json_decode($userdata[$userId][3]);

	?>
	<ul>
	<?
	foreach ($geodata->data->geo as $key => $item):?>

	<li><?php echo $key;?>: <?php echo $item;?></li>

	<?php endforeach;?>
	</ul>
	<br/>
	<?php endforeach;?>

	<?php endforeach;

