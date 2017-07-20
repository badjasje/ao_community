<?php
/**
 * Handles clan creation
 *
 * @package WordPress
 */

if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
	header('Allow: POST');
	header('HTTP/1.1 405 Method Not Allowed');
	header('Content-Type: text/plain');
	exit;
}

require( dirname(__FILE__) . '/wp-load.php' );
if(get_field('game_status','option') == 'Live'){
$user_ID = get_current_user_ID();


$slug = sanitize_title( $_POST['clanname']);


$args = array(	

				'post_type'		=> 'clan',
				'posts_per_page'   => -1,
				'name'    => $slug
				);

$posts = get_posts($args);
if(count($posts) != 0){$_SESSION['status'] = '1'; wp_redirect(get_permalink(3601)); exit;}


$args = array(
				'post_title'    => $_POST['clanname'],
				'post_content'	=> $_POST['clanmessage'],
				'post_status'   => 'publish',
				'post_type'		=> 'clan',
				'post_author'   => $user_ID
				);
				$timestamp = current_time('timestamp');
			
			$new_order_id = wp_insert_post( $args );
			update_field('clan_tag', $_POST['clantag'], $new_order_id);
			update_field('clan_leader', $user_ID, $new_order_id);
			update_user_meta($user_ID,'clan_id_user',$new_order_id);
			update_user_meta($user_ID,'clan_message',$new_order_id);
	
			$clan_membersnew = array();
			$clan_membersnew[] = $user_ID;
			update_field('clan_members', $clan_membersnew, $new_order_id);
			update_field('ct_1', 0, $new_order_id);
			update_field('ct_2', 0, $new_order_id);
			update_field('ct_3', 0, $new_order_id);
			update_field('ct_4', 0, $new_order_id);
			update_post_meta($new_order_id, 'bonus_level', 0);
			update_post_meta($new_order_id, 'clan_points', 0);
			
			/*create clan forum*/
			$cat_id = wp_insert_term($_POST['clanname'],'asgarosforum-category');
				$ID = $cat_id['term_id'];
		
			$wpdb->insert("23zx_forum_forums", array(
			"name" => 'General',
			"parent_id" => $ID
			));
			$wpdb->insert("23zx_forum_forums", array(
			"name" => 'Strategy',
			"parent_id" => $ID
			));
			$wpdb->insert("23zx_forum_forums", array(
			"name" => 'Off topic',
			"parent_id" => $ID
			));
			update_post_meta($new_order_id, 'clan_forum_id', $ID);
			
			update_user_meta($user_ID, 'clan_create_counter', 1);
			wp_redirect(get_permalink(3601));
  
			exit;


}