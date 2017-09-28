<?php

/**
 * Class GameUtil
 */
class GameUtil {

	/**
	 * Format the given networth
	 * @param $networth
	 * @return string
	 */
	public static function format_networth($networth) {
		return GameUtil::format_money(ceil($networth));
	}

	public static function format_money($money) {
		return sprintf('$ %s', number_format($money, 0, ',', ' '));
	}
}
