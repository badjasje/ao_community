<?php
    require_once("wp-load.php");
if (get_field('game_status', 'option') == 'Live') {
    
function update_medal($medalType,$toQuery){
	global $wpdb;
	$users = $wpdb->get_results("
		SELECT * FROM `23zx_usermeta` WHERE `meta_key` = '$toQuery' ORDER BY `23zx_usermeta`.`meta_value` DESC
	");
	
    $position = 0;
    foreach ($users as $key => $user) {
        $position += 1;
        $user_ID = $user->user_id;     
        
        $userValue = $user->meta_value;
        $valueAbove = $users[$key-1]->meta_value;
        $valueBelow = $users[$key+1]->meta_value;
        
        $medalPos = $medalType.'_position';
        $next = $medalType.'_next';
        $prev = $medalType.'_prev';
        
        $wpdb->query("
	        UPDATE `23zx_usermeta` 
	        SET `meta_value` = $position 
	        WHERE `23zx_usermeta`.`meta_key` = '$medalPos'
	        AND `23zx_usermeta`.`user_id` = $user_ID;
        ");
        
        $prevValue = round($userValue-$valueBelow);
        $wpdb->query("
	        UPDATE `23zx_usermeta` 
	        SET `meta_value` = $prevValue 
	        WHERE `23zx_usermeta`.`meta_key` = '$prev'
	        AND `23zx_usermeta`.`user_id` = $user_ID;
        ");
       
        
        if ($position == 1) {
             $wpdb->query("
		        UPDATE `23zx_usermeta` 
		        SET `meta_value` = 0 
		        WHERE `23zx_usermeta`.`meta_key` = '$next'
		        AND `23zx_usermeta`.`user_id` = $user_ID;
			");
        } else {
	        $nextValue = round($valueAbove-$userValue);
	        $wpdb->query("
		        UPDATE `23zx_usermeta` 
		        SET `meta_value` = $nextValue
		        WHERE `23zx_usermeta`.`meta_key` = '$next'
		        AND `23zx_usermeta`.`user_id` = $user_ID;
			");
        }
    }
	
	
}        
        
        
        
update_medal('moe','land');   
update_medal('moh','user_clan_points');   
update_medal('mog','networth');         
update_medal('moc','in_war_attacks');
update_medal('mod','kills_made');
update_medal('mot','money_gained_thieving');  
update_medal('modes','nw_damage_missiles');    
      
  
        $args = array(
    'meta_key'     => 'nw_damage_defender',
    'posts_per_page'    => 300,
    'post_type'     => 'event_local',
    'orderby'      => 'meta_value_num',
    'order'        => 'DESC',
    'meta_query'    => array(
        'relation'      => 'OR',
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'incoming',
        ),
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'mutual',
        ),
        array(
            'key'       => 'war_status',
            'compare'   => '=',
            'value'     => 'outgoing',
        )),
    
        );
    
        $attacks = get_posts($args);
        $position = 0;
        $users = array();
        $count = 0;
        foreach ($attacks as $attack) {
            $user_ID = $attack->post_author;
            if (!in_array($user_ID, $users)) {
                $count++;
                $position += 1;
                $member_data = get_userdata($user_ID);
                $damage = get_post_meta($attack->ID, 'nw_damage_defender', true);
                update_user_meta($user_ID, 'modev_position', $position);
                update_user_meta($user_ID, 'modev_damage', $damage);
        
                $users[] = $user_ID;
                if ($count == 200) {
                    die;
                }
            }
        }
	}