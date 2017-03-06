<?php

class GameUtil {

	/**
	 * Format the given networth
	 * @param $networth
	 * @return string
	 */
	public static function format_networth($networth) {
		return sprintf('$ %s', number_format(ceil($networth), 0, ',', ' '));
	}
}