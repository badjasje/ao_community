<?php
/**
 * Admin Pages
 *
 * @package     UserID_Changer\Admin\User\Actions
 * @since       1.0.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add link to user page
 *
 * @since       1.0.0
 * @param       array $actions The current user actions
 * @param       object $user The user we are editing
 * @return      array $actions The modified user actions
 */
function userID_changer_action_link( $actions, $user ) {
	if( current_user_can( 'edit_users' ) ) {
		if( ! is_multisite() || ( is_multisite() && ! is_network_admin() && ! user_can( $user->ID, 'manage_network' ) ) || ( is_multisite() && is_network_admin() ) ) {
			$actions[] = '<a href="' . add_query_arg( array( 'page' => 'userID_changer', 'id' => $user->ID ) ) . '">' . __( 'Change User ID', 'userID-changer' ) . '</a>';
		}
	}

	return $actions;
}
add_filter( 'user_row_actions', 'userID_changer_action_link', 10, 2 );

