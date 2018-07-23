<?php
/**
 * Plugin Name:     User ID Changer
 * Description:     User ID Changer allows the administrator to change the User ID of any Username. This plugin is for standalone WordPress ONLY. The following changes are made in the WordPress Database - Tables: _users (ID), _usermeta (user_id), _posts (post_author), _comments (user_id).
 * Version:         1.1.7
 * Author:          interwebDEFENCE
 * Author URI:      https://Defence.ws
 * Text Domain:     userid-changer
 *
 *
 * Copyright (c) 2016-2017 interwebDEFENCE <assist@interwebDEFENCE.com>
 * All rights reserved.
 *
 * "User ID Changer" plugin is distributed under the GNU General Public License, Version 2,
 * June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
 * St, Fifth Floor, Boston, MA 02110, USA
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 *
 *
 * @package         UserIDChanger
 * @author          interwebDEFENCE <assist@interwebDEFENCE.com>
 * @copyright       Copyright (c) 2016-2017, interwebDEFENCE
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'UserID_Changer' ) ) {

	class UserID_Changer {

		/**
		 * @var         UserID_Changer $instance The one true UserID_Changer
		 * @since       1.0.0
		 */
		private static $instance;

		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      object self::$instance The one true UserID_Changer
		 */
		public static function instance() {
			if( ! self::$instance ) {
				self::$instance = new UserID_Changer();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin path
			define( 'USERID_CHANGER_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'USERID_CHANGER_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {
			if( is_admin() ) {
				//require_once USERID_CHANGER_DIR . 'includes/functions.php';
				require_once USERID_CHANGER_DIR . 'includes/admin/doit.php';
				//require_once USERID_CHANGER_DIR . 'includes/admin/users/actions.php';
			}
		}

		/**
		 * Load plugin language files
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'userID_changer_lang_dir', $lang_dir );

			// WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'userID-changer' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'userID-changer', $locale );

			// Setup paths to current locale file
			$mofile_local = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/userID-changer/' . $mofile;

			if( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/userID-changer folder
				load_textdomain( 'userID-changer', $mofile_global );
			} elseif( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/userID-changer/languages/ filder
				load_textdomain( 'userID-changer', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'userID-changer', false, $lang_dir );
			}
		}
	}
}


/**
 * The main function responsible for returning the one true UserID_Changer
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      UserID_Changer The one true UserID_Changer
 */
function UserID_Changer_load() {
	return UserID_Changer::instance();
}
add_action( 'plugins_loaded', 'UserID_Changer_load' );
