<?php

/**
 * Class LinkUtil
 */
class LinkUtil {

	/**
	 * Render a link to the user profile page
	 * @param $userId
	 * @return string
	 */
	public static function user_link($userId) {
		if (empty($userId)) {
			return '';
		}

		$userData = get_userdata($userId);

		return sprintf('<a href="/users/profile/?id=%d">%s (#%d)</a>', $userId, $userData->display_name, $userId);
	}
}
