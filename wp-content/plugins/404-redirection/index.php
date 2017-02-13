<?php

/*
Plugin Name: 404 Redirection
Plugin URI: http://www.flippingwp.com/
Description: Permanently redirect all 404's to the main blog URL. The primary purpose is to salvage Google PageRank (TM) from missing pages.
Author: flippingwp.com
Author URI: http://flippingwp.com/
Text Domain: 404-redirection
Version: 1.8
Stable tag: 1.8
License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

License:
 ==============================================================================
 Copyright 2011-2015 Don J  (email : admin@flippingwp.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function redirect_all_404s() {
	global $wp_query;
  	if ($wp_query->is_404) {
       wp_redirect(get_bloginfo('wpurl'),301);exit;
	}
}
add_action('wp', 'redirect_all_404s', 1);

?>
