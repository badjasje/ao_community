<?php
/**
 * Admin Pages
 *
 * @package     userid_changer\admin\doit
 * @since       1.2.0
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add menu item for User ID Change
 *
 * @since       1.0.0
 * @return      void
 */
function userid_changer_add_admin_menu() {
	// Only admin-level users with the add_users capability can change user ID
	add_submenu_page(
		'users.php',
		__( 'User ID Changer', 'userid-changer' ),
		__( 'User ID Changer', 'userid-changer' ),
		'edit_users',
		'userid_changer',
		'userid_changer_add_admin_page'
	);
}
add_action( 'admin_menu', 'userid_changer_add_admin_menu' );


/**
* display custom admin notice
*
* @since       1.1.0 - Check Changes In Code
* @global      object $pagenow The WordPress user.php page
*
*/
function backup_custom_admin_notice() { 
	global $pagenow;
    if ( $pagenow == 'users.php' ) {
		
		echo __( '<div id="message" class="notice-warning notice is-dismissible"><p><font color="#ff0000"><strong>
		ATTENTION! </font>ALWAYS BACKUP YOUR WORDPRESS DATABASE BEFORE MAKING CHANGES ... MAKE SURE YOUR IP ADDREES IS WHITELISTED ... BE SAFE</strong>', 'userid-changer') . '</p>
		</div>';
		 
	}
}
add_action('admin_notices', 'backup_custom_admin_notice');

/**
 * load css into the admin pages
 *
 * @since       1.1.0
 * @return      void
 */

function doit_enqueue_style() {
    wp_enqueue_style( 'doit', plugin_dir_url( __FILE__ ) . 'assets/css/doit.css' );
}
add_action( 'admin_enqueue_scripts', 'doit_enqueue_style' );

/**
* Check for Multisite WordPress
*
* @since       1.1.0
* @return      void
*
*/
if ( is_multisite() ) 
	{ echo __( '<div id="message" class="notice-warning notice is-dismissible"><font color="#ff0000"><p><strong>
	ATTENTION! </font>WORDPRESS MULTISITE DETECTED, THIS PLUGIN DOES NOT SUPPORT WORDPRESS MULTISITE ... (YET).</strong></p></div>', 'userid-changer' );
	return;
}
// --------------------------------- WORDPRESS CONTENT INFORMATION---------------------------------
// Count Pages
// Types: publish, future, draft, pending, private, trash, auto-draft, inherit
function countPages($type) {
	$count_posts = wp_count_posts('page');
	if ($count_posts->$type == NULL) {
		echo 0;
	}
	else {
		echo $count_posts->$type;
	}
}

// Count Posts
// Types: publish, future, draft, pending, private, trash, auto-draft, inherit
function countPosts($type) {
	$count_posts = wp_count_posts();
	if ($count_posts->$type == NULL) {
		echo 0;
	}
	else {
		echo $count_posts->$type;
	}
}

// Woo Orders
// Types: wc-pending, wc-processing, wc-on-hold, wc-completed, wc-cancelled, wc-refunded, wc-failed
function countWoo($type) {
	$count_woo = wp_count_posts('shop_order');
	if ($count_woo->$type == NULL) {
		echo 0;
	}
	else {
		echo $count_woo->$type;
	}
}

// Count Comments
// Types: moderated, trash, total_comments, approved, spam, post-trashed
function countComments($type) {
	$count_comments = wp_count_comments();
	if ($count_comments->$type == NULL) {
		echo 0;
	}
	else {
		echo $count_comments->$type;
	}
}

// Total Users
function totalUsers() {
	$count_users = count_users();
	echo $count_users['total_users'];
}

// Total Roles
function totalRoles() {
	$count_roles = count_users();
	foreach ( $count_roles['avail_roles'] as $role => $count ) {
		echo '<li><strong>'.ucfirst( $role ).':</strong> '.$count.'</li>';
	}
}
// --------------------------------- CLIENT INFORMATION ---------------------------------
// Get the User Browser type
function iwd_get_browser() {
	$user_agent    = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
	$browser       = esc_html__( 'Unknown Browser', 'dashboard-widgets-suite' );
	$browser_array = array(
		'/msie/i'      =>  'Internet Explorer',
		'/firefox/i'   =>  'Firefox',
		'/chrome/i'    =>  'Chrome',
		'/safari/i'    =>  'Safari',
		'/opera/i'     =>  'Opera',
		'/netscape/i'  =>  'Netscape',
		'/maxthon/i'   =>  'Maxthon',
		'/konqueror/i' =>  'Konqueror',
		'/mobile/i'    =>  'Handheld Browser'
	);
	foreach ( $browser_array as $regex => $value ) {
		if ( preg_match( $regex, $user_agent ) ) {
			$browser = $value;
			break;
		}
	}
	return $browser;
}

// Get The User Agent
function iwd_get_user_agent() {
	return isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : 'Unknown';
}

// Get the User port number
function iwd_get_client_port() {
	return isset( $_SERVER['REMOTE_PORT'] ) ? sanitize_text_field( $_SERVER['REMOTE_PORT'] ) : 'Unknown';
}

// Get the User OS platform
function iwd_get_platform() {
	$user_agent  = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';
	$os_platform = esc_html__( 'Unknown OS Platform', 'userid-changer' );
	$os_array    = array(
		'/windows nt 10.0/i'			=>  'Microsoft Windows 10',
		'/windows nt 6.2/i'				=>  'Microsoft Windows 8',
		'/windows nt 6.1/i'				=>  'Microsoft Windows 7',
		'/windows nt 6.0/i'				=>  'Microsoft Windows Vista',
		'/windows nt 5.2/i'				=>  'Microsoft Windows Server 2003/XP x64',
		'/windows nt 5.1/i'				=>  'Microsoft Windows XP',
		'/windows xp/i'					=>  'Microsoft Windows XP',
		'/windows nt 5.0/i'				=>  'Microsoft Windows 2000',
		'/windows me/i'					=>  'Microsoft Windows ME',
		'/win98/i'						=>  'Microsoft Windows 98',
		'/win95/i'						=>  'Microsoft Windows 95',
		'/win16/i'						=>  'Microsoft Windows 3.11',
		'/mac os x 10_12/i'				=>	'Mac OS X 10.12 (Sierra)',
		'/mac os x 10_11/i'				=>	'Mac OS X 10.11 (El Capitan)',
		'/mac os x 10_10/i'				=>	'Mac OS X 10.10 (Yosemite)',
		'/mac os x 10_9/i'				=>	'Mac OS X 10.9',
		'/mac os x/i' 					=>  'Mac OS X',
		'/mac_powerpc/i'				=>  'Mac OS 9',
		'/linux/i'						=>  'Linux',
		'/ubuntu/i'						=>  'Ubuntu',
		'/iphone/i'						=>  'iPhone',
		'/ipod/i'						=>  'iPod',
		'/ipad/i'						=>  'iPad',
		'/android/i'					=>  'Android',
		'/blackberry/i'					=>  'BlackBerry',
		'/webos/i'						=>  'Mobile'
	);
	
	foreach ( $os_array as $regex => $value ) {
		if ( preg_match( $regex, $user_agent ) ) {
			$os_platform = $value;
			break;
		}
	}
	return $os_platform;
}
//
// --------------------------------- WEBSITE - HOSTIOG - SSL - PROXY ---------------------------------
//
/**
* This is where we figure out all the IP addresses.
*
* Website 			IP address 	/	URL 					=	check_web_ip()
* Hosting Server 	IP Address 	/ 	Name 					= 	check_server_ip()			gethostname()
* Hosting Location 	Hosting									=	check_server_location()
* Proxy Server 		IP Address 	/	Name 					= 	check_for_proxy()
* SSL 				On 			/	Off 					= 	check_for_ssl()
* Your (Client) 	IP Address	/	ISP 	/	AS 			=	proxy_to_your_address() 	check_your_location()
* hostname 			IP Addrees 	/	Name 	/ 	Who			= 	iwd_get_host_name() 		check_hostname_location()
*
*/
// --------------------------------- WEBSITE - HOSTIOG - SSL - PROXY ---------------------------------

// Get Server IP
function check_server_ip() {
	global $lhost;
	
	if (gethostbyname( gethostname() ) == '127.0.0.1' ) {
		$lhost = 'localhost';
		return gethostbyname( getenv('HTTP_HOST') ) . '<br>' . gethostbyname( gethostname() ) . ' ' . $lhost;
	} 
	return gethostbyname( getenv('HTTP_HOST') ) . '<br>' . gethostbyname( gethostname() ) ;
}

// Get Website IP
function check_website_ip() {
	if ( isset( $_SERVER['SERVER_ADDR'] ) ) {
		return $_SERVER['SERVER_ADDR'];
	} elseif ( isset( $_SERVER['SERVER_NAME'] ) ) { 
		return gethostbyname( $_SERVER['SERVER_NAME'] );
	} else {
		return 'Unknown';
	}
	return 'Unkown';
}

// Get the Hostname (ISP)
function iwd_get_host_name() {
	return isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( gethostbyaddr( $_SERVER['REMOTE_ADDR'] ) ) : 'Unknown';
}

// Check for SSL
function check_for_SSL() {
	if ((isset( $_SERVER["HTTP_CF_VISITOR"] ) ) && ( strpos($_SERVER["HTTP_CF_VISITOR"], "https" ) ) ) {
		return sanitize_text_field( $ssl_chk = 'ON' ); 
	} elseif ( ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) )  && ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) ) {
		return sanitize_text_field( $ssl_chk = 'ON' );
	} elseif ( ( isset( $_SERVER['HTTPS'] ) ) && ( $_SERVER['HTTPS'] == 'on' ) ) {
		return sanitize_text_field( $ssl_chk = 'ON' );
	} else {
		return sanitize_text_field( $ssl_chk = 'OFF' );
	}
	return 'Unkown';
}

// Check for Proxy Server eg Cloudflare
function check_proxyip_realip() {
	global $proxy_ipaddress, $real_ipaddress;
	if (getenv('HTTP_X_FORWARDED_FOR')) {
        $proxy_ipaddress = getenv('HTTP_X_FORWARDED_FOR');
       	$real_ipaddress = getenv('REMOTE_ADDR');
		return ' (Your IP address: ' . $proxy_ipaddress . ' - Proxy IP address: ' . $real_ipaddress . ')';
    } else {
      	$real_ipaddress = getenv('REMOTE_ADDR');
        return ' (Your IP address: ' . $real_ipaddress . ')';
    }
    return 'Unkown';
}

// Check for Proxy Server
function check_for_proxy() {
	if(isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) {
		return 'Cloudflare ' . getenv('REMOTE_ADDR');
	} elseif (isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
		return getenv('REMOTE_ADDR');
	} else {
		return 'No Proxy' ;
	}
	return 'Unkown';
}
 //returns true, if domain is availible, false if not
 function isDomainAvailible($domain) {
 	//check, if a valid url is provided
	if(!filter_var($domain, FILTER_VALIDATE_URL))
	{
		return false;
	}
	//initialize curl
	$curlInit = curl_init($domain);
	curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
	curl_setopt($curlInit,CURLOPT_HEADER,true);
	curl_setopt($curlInit,CURLOPT_NOBODY,true);
	curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
	//get answer
	$response = curl_exec($curlInit);
	curl_close($curlInit);
	if ($response) return true;
		return false;
}

// Get Hosting/Website Location
function check_server_location() {
	if (isDomainAvailible('http://ip-api.com')){
		$srv_query = @unserialize( file_get_contents( 'http://ip-api.com/php/' ) );
		$server_location = wp_cache_get( 'server_location' );
		if( false === $server_location && $srv_query && $srv_query['status'] == 'success' ) {
			$server_location = $srv_query['isp'] . 
			'<br>' . $srv_query['city'] . ', ' . $srv_query['country'];
			wp_cache_set( 'server_location', $server_location );
			return $server_location;
		} else {
			return $srv_query['message'];
		}
		return 'Unkown';
	} else {
		echo 'No Data Found!';
	}
}

// Get Your/Client Location
function check_your_location() {
	if (isDomainAvailible('http://ip-api.com')){
		$your_ip = $_SERVER['REMOTE_ADDR'];
		$your_query = @unserialize(file_get_contents('http://ip-api.com/php/'.$your_ip));
		$your_location = wp_cache_get( 'your_location' );
		if( false === $your_location && $your_query && $your_query['status'] == 'success' ) {
			$your_location = $your_query['isp'] .
			'<br>' . $your_query['city'] . ', ' . $your_query['country'] . 
			'<br>' . $your_query['as'];
			wp_cache_set( 'your_location', $your_location );
			return $your_location;
		} else {
			return $your_query['message'];
		}
		return 'Unkown';
	} else {
		echo 'No Data Found!';
	}
}

// Get Hostname/ISP Location
function check_hostname_location() {
	if (isDomainAvailible('http://ip-api.co')){
		$hostname_ip = iwd_get_host_name();
		$hostname_query = @unserialize(file_get_contents('http://ip-api.com/php/'.$hostname_ip));
		$hostname_location = wp_cache_get( 'hostname_location' );
		if( false === $hostname_location && $hostname_query && $hostname_query['status'] == 'success' ) {
			$hostname_location = $hostname_query['isp'];
			wp_cache_set( 'hostname_location', $hostname_location );
			return $hostname_location;
		} else {
			return $hostname_query['message'];
		}
		return 'Unkown';
	} else {
		echo 'No Data Found!';
	}
}

// Check for real IP if Proxy IP is used
function proxy_to_your_address() {
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
		return $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR']; // echo 'XFF :' . $_SERVER['HTTP_X_FORWARDED_FOR'] . '<br>';
//}elseif(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP']; // echo 'CFIP: ' . $_SERVER['HTTP_CF_CONNECTING_IP'] . '<br>';
//}elseif(isset($_SERVER['HTTP_X_FORWARDED'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED']; // echo 'XF: ' . $_SERVER['HTTP_X_FORWARDED'] . '<br>';
//}elseif(isset($_SERVER['HTTP_FORWARDED_FOR'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_FORWARDED_FOR']; // echo 'FF: ' . $_SERVER['HTTP_FORWARDED_FOR'] . '<br>';
//}elseif(isset($_SERVER['HTTP_FORWARDED'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_FORWARDED']; // echo 'F: ' . $_SERVER['HTTP_FORWARDED'] . '<br>';
//}elseif(isset($_SERVER['HTTP_X_REAL_IP'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_REAL_IP']; // echo 'XRIP: ' . $_SERVER['HTTP_X_REAL_IP'] . '<br>';
//}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
//	$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CLIENT_IP']; // echo 'CIP: ' . $_SERVER['HTTP_CLIENT_IP'] . '<br>';
	} else {
		return getenv('REMOTE_ADDR');
	}
	return 'Unkown';
}
//
//
// --------------------------------- START ---------------------------------
//
//
/**
 * Add User ID Changer page
 *
 * @since       1.2.0
 * @global      object $wpdb The WordPress database object
 * @global      array $userdata The data for the current user
 * @global      array $current_ID The data for the current user
 * @return      void
 */

function userid_changer_add_admin_page() {
	global $wpdb, $userdata, $current_ID, $wp_version, $wp_db_version, $new_ID, $jp_connected;

	if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { 

		$masterid = Jetpack_Options::get_option( 'master_user' );
		
		echo '
		<div id="message" class="notice notice-warning notice is-dismissible">
		<p><strong>'
		. sprintf( __('
		<font color="#ff0000">ATTENTION! </font>
		JETPACK DETECTED ON LINKED PRIMARY USER ID <font color=#7b84fc>%1$s</font>. If you are changing the User ID of the linked Jetpack User. Please read instructions ' ), $masterid ) .
		'</strong>
		<a href="#raptors" style="text-decoration:none; font-size:9pt; color:#eaeaea; background-color:#474747; border:none; padding:2px 50px 2px 50px">Click Here</a>
		</div>
		';
	}

	if( current_user_can( 'edit_users' ) == false ) {
		echo '<div class="notice-error notice is-dismissible"><p><strong>' . __( 'You do not have permission to change a User ID!', 'userid-changer' ) . '</strong></p></div>';
		return;
	} else {

		// Get the current logged in user details
		$logged_in_user_id 		= get_current_user_id();
		$logged_in_user_info 	= get_userdata($logged_in_user_id );
	
		echo '
		<div class="wrap">
 		<h2>
 		<a id="pluginlogo32" class="headerlogo32" href="https://interwebdefence.com" target="_blank"></a>
 		User ID Changer
 		
 		<span style="font-size:small;">Logged In As: <font color=#7b84fc>
 		'
		. $logged_in_user_info->user_login . '</font>, ID: <font color=#7b84fc>'
		. $logged_in_user_info->ID . '</font>, Role: ';
			
		if ($logged_in_user_info->roles[0] == 'administrator' ){
			foreach ($logged_in_user_info->roles as $keyroles => $roles){
				if($keyroles !== 0 ) echo ', ';
				if( $roles == 'administrator' )  { 
					echo '<font color=#7b84fc>' . $roles . '</font>'; 
				} else {
					echo $roles;
				}
			}

		} else {
			implode(', ', $logged_in_user_info->roles);
		}
			
		// echo ' - Current Users From Database </strong>( <font color=#7b84fc>' . $wpdb->dbname . '</font> ) ';
			
		echo '. ' . check_proxyip_realip();
    	
		echo '</h2></span></div>
		<div style="clear:both"></div>
		';
//
//
// --------------------------------------------------USERS IN THE DATABASE AREA--------------------------------------------------------
//
//
		// WordPress get all user details in the database
		$args = array( 'orderby' => 'ID' );
		$wp_user_query = new WP_User_Query( $args );
		$row_count = 0;
		echo '
		<div class="wrap">
			<table style="width: 100%"  cellpadding="5" class="widefat">
				<thead>
					<tr>
						<th></th>
						<th title="User ID"><strong>ID</strong></th>
						<th title="Login Username"><strong>Username</strong></th>
						<th title="Display Name"><strong>Display Name</strong></th>
						<th title="Login Role"><strong>Role(s)</strong></th>
						<th title="What You Can & Can Not Do"><strong>Level</strong></th>
						<th title="User Email Address"><strong>Email</strong></th>
						<th title="Real Name (First Name + Last Name)"><strong>Name</strong></th>
						<th title="User Nickname"><strong>Nickname</strong></th>
						<th title="Total Posts For User"><strong>Posts</strong></th>
						<th title="Total / Approved / Unapproved"><strong>Comments</strong></th>
						';
						if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { 
							echo '<th>Jetpack</th>'; 
						} 
						echo '
					</tr>
				</thead>
				<tbody>
				';

		if (!empty ( $wp_user_query->results ) ) {
			foreach ( $wp_user_query->results as $akusers ) {

				if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { 
					$jp_connected = Jetpack::is_user_connected( $akusers->ID ); 
				}
				
				if ( $jp_connected == 1 ) {
					$jp_connected = "Linked";
				} else {
					$jp_connected = "-";
				}
				
				$comment_approved 	= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s", $akusers->ID ) );
				$comment_unapproved = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 0 AND user_id = %s", $akusers->ID ) );
				$comment_num 		= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE user_id = %s", $akusers->ID ) );

				$row_count++;
				if( $row_count & 1 )
				{
					echo '<tr class="alternate">';
				} else {
					echo '<tr>';
				}

				echo '<td style="vertical-align:middle;">' . get_avatar( $akusers->ID, 30 ) . '</td>';

				if ( $akusers->ID == "1" )
				{
					echo '<td style="vertical-align:middle;"><strong><font color=#7b84fc>' . $akusers->ID . '</font></strong></td>';
				} else {
					echo '<td style="vertical-align:middle;">' . $akusers->ID . '</td>';
				}

				if ( $akusers->user_login == "admin" )
				{
					echo '<td style="vertical-align:middle;"><strong><font color=#7b84fc>' . $akusers->user_login . '</font></strong></td>';
				} else {
					echo '<td style="vertical-align:middle;">' . $akusers->user_login . '</td>';
				}

				echo '<td style="vertical-align:middle;">' . $akusers->display_name . '</td>';

				if (isset( $akusers->roles[0]) )
				{
					echo '<td style="vertical-align:middle; width:10%;">';
						foreach ( $akusers->roles as $keyroles => $roles ){

							// echo '<pre>';print_r( $akusers->allcaps ); echo '</pre>'; // Role / Cap Debug

							if( $keyroles !== 0 ) echo ', ';
							if( $roles == 'administrator' )  { 
								echo "<strong><font color=#7b84fc>" . $roles . "</font></strong>"; 
							} elseif ( $roles == '' ) {
								echo "No Role";
							}
							else
							{
								echo $roles;
							}
						}
					echo '</td>';

				} else {
					echo '<td style="vertical-align:middle; width:10%;">' . implode( ', ', $akusers->roles ) . '</td>';
				}

				echo '
					<td style="vertical-align:middle;">' . $akusers->user_level . '</td>
					<td style="vertical-align:middle;">' . $akusers->user_email . '</td>
					<td style="vertical-align:middle;">' . $akusers->user_firstname . ' ' . $akusers->user_lastname . '</td>
					<td style="vertical-align:middle;">' . $akusers->nickname . '</td>
					<td style="vertical-align:middle;">' . count_user_posts( $akusers->ID ) . '</td>
					<td style="vertical-align:middle;">' . 'T:' . $comment_num . ' / A:' . $comment_approved . ' / U:' . $comment_unapproved .'</td>
					';

					if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { 
						echo '<td style="vertical-align:middle;">' . $jp_connected . '</td>'; 
					} 
				echo '
				</tr>
				';
			}
			echo '
				</tbody>
			</table>
		</div>
		';
//
//
// --------------------------------- FORM SECTION ---------------------------------
//
//
			if( isset( $_POST['actionID'] ) && ( $_POST['actionID'] == 'updateID' ) && !empty( $_POST['new_ID'] ) && !empty( $_POST['current_ID'] ) ) {
				// Sanitize the new user id
				$new_ID     = sanitize_user( $_POST['new_ID'] );
				$new_ID     = esc_sql( $new_ID );
				$current_ID = esc_sql( $_POST['current_ID'] );	

				if( username_exists( $current_ID ) ) {
			
					// Is the new id the same value as the selected id
					if( $new_ID == username_exists( $current_ID ) ) {
						// Make sure user id exists and user id != new user id - THIS WORKS -
						echo '<div id="message" class="notice notice-error is-dismissible"><p><strong>' 
						. sprintf( __( 'Selected User ID and New User ID cannot both be User ID %1$s!', 'userid-changer' ), $new_ID ) . '</strong></p></div>';
					// Check if id exists
					} elseif ( get_user_by( 'ID', $new_ID ) ) {
						// Make sure new userid doesn't exist - THIS WORKS -
						echo '<div id="message" class="notice notice-error is-dismissible"><p><strong>' 
						. sprintf( __( 'No changes made because User ID %1$s cannot be changed to User ID %2$s, as User ID %3$s already exists!', 'userid-changer' ), username_exists( $current_ID ), $new_ID, $new_ID ) . '</strong></p></div>';
					// Not the same vaule - Setup SQL Query
					} elseif ( $new_ID != username_exists( $current_ID ) ) {	

						$selected_ID = username_exists( $current_ID );
						// SQL Command Query Prep: users(ID) usermeta(user_id) & posts(post_author)!
						$q_ID 			= $wpdb->prepare( "UPDATE $wpdb->users SET ID = %s WHERE ID = %s", $new_ID, $selected_ID );
						$q_metaID 		= $wpdb->prepare( "UPDATE $wpdb->usermeta SET user_id = %s WHERE user_id = %s", $new_ID, $selected_ID );
						$q_posts 		= $wpdb->prepare( "UPDATE $wpdb->posts SET post_author = %s WHERE post_author = %s", $new_ID, $selected_ID );
						$q_comments		= $wpdb->prepare( "UPDATE $wpdb->comments SET user_id = %s WHERE user_id = %s", $new_ID, $selected_ID );

						if( false !== $wpdb->query( $q_ID ) ) {	
						//if( false !==  print 'DUMMY UPDATE <br />' ) {

							// This write the new User ID to the database
							$wpdb->query( $q_metaID );
							$wpdb->query( $q_posts );
							$wpdb->query( $q_comments );

							// Wordfence START Code
							if (file_exists( WP_PLUGIN_DIR . '/wordfence/wordfence.php' ) && is_plugin_active( 'wordfence/wordfence.php' ))
							{
								deactivate_plugins('wordfence/wordfence.php');
								activate_plugin('wordfence/wordfence.php');
							} 
							// Wordfence END Code

							// If changing own user ID, display link to re-login
							if( $logged_in_user_info->ID == $selected_ID ) 
							{
								// THIS WORKs
								echo '<div id="message" class="notice notice-success is-dismissible"><p><strong>' 
								. sprintf( __( 'Logged in User ID %1$s was changed to %2$s.&nbsp;&nbsp;Click <a href="%3$s">here</a> to log back in.', 'userid-changer' ), $selected_ID, $new_ID, wp_login_url() ) . '</strong></p></div>';
							} else {
								// THIS WORKS
								echo '
								<div id="message" class="notice notice-success is-dismissible">
								<p><strong>
								' 
								. sprintf( __( 'User ID %1$s was changed to User ID %2$s. ', 'ID-changer' ), $selected_ID, $new_ID ) . '</strong>';
								echo '
								<script>function jumpToAnchor() { window.location = window.location + "#raptors"; }</script>
								<a href="#raptors" style="text-decoration:none;font-size:9pt; color:#eaeaea; background-color:#474747; border:none; padding:2px 50px 2px 50px">
								Click To See Changed User Results</a>
								<span>&nbsp;&nbsp;</span>
								<a href="#" style="text-decoration:none; font-size:9pt; color:#eaeaea; background-color:#474747; border:none; padding:2px 50px 2px 50px" onclick="location.reload()">
								Refresh Main User Table</a>
								</div>
								';
							}
						} else {
							// If database error occurred, display it
							echo '<div id="message" class="notice notice-error is-dismissible"><p><strong>' 
							. sprintf( __( 'A database error occurred : %1$s', 'userid-changer' ), $wpdb->last_error ) . '</strong></p></div>';
						}
					}
				} else {
					// Warn if user doesn't exist (this should never happen!)
					echo '<div id="message" class="notice notice-error is-dismissible"><p><strong>' 
					. sprintf( __( 'ID "%1$s" doesn\'t exist!', 'userid-changer' ), $selected_ID ) . '</strong></p></div>';
				}
			} elseif ( ( isset( $_POST['actionID'] ) && $_POST['actionID'] == 'updateID' ) && ( empty( $_POST['new_ID'] ) || empty( $_POST['current_ID'] ) ) ) {
				// All fields are required
				echo '<div id="message" class="notice notice-error is-dismissible"><p><strong>' 
				. __( 'Both "Current User ID" and "New User ID" fields are required!', 'userid-changer' ) . '</strong></p></div>';
			} 
			?>
			<div class="wrap">
			<table style="width: 100%" cellpadding="5" class="widefat">
				<tr>
					<td width="600px">
						<form name="ID_changer" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=userid_changer">
							<input type='hidden' name='actionID' value='updateID' />
							<table cellpadding="5" class="widefat post">
								<thead>
									<tr>
										<?php
										if( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != '' ) {
											$usersql_ID    = $wpdb->prepare( "SELECT * from $wpdb->users where ID = %d", $_REQUEST['id'] );
											$userinfo_ID   = $wpdb->get_row( $usersql_ID );
											echo '<th><strong>' . __( 'Rename ID To What?', 'userid-changer' ) . '</strong></th>';
										} else {
											echo '<th><strong>' . __( 'Edit Which User ID?', 'userid-changer' ) . '</strong></th>';
										}
										?>
									</tr>
									<tr>
										<td>
											<label for="current_ID"></label>
											<strong><?php echo __( 'Select Username', 'userid-changer' ); ?></strong>
											<?php
											// Get the username & ID to creat selection list
											if( isset( $_REQUEST['id'] ) && $_REQUEST['id'] != '' ) {
												$usersql_ID    = $wpdb->prepare( "SELECT * from $wpdb->users where ID = %d", $_REQUEST['id'] );
												$userinfo_ID   = $wpdb->get_row( $usersql_ID );
												echo '<input name="current_ID" id="current_ID" type="text" value="' . esc_attr( $userinfo_ID->ID ) . '" readonly />';
											} else {
												echo '<select style="font-size: 16px; color: #ffffff; background-color: #7b84fc;" name="current_ID" id="current_ID">';

												echo '<option value=""></option>';	

												// SQL command to sort by ID 
												$usersql_ID    = "SELECT * from $wpdb->users order by ID asc";
									
												// Get list of users by ID
												$userinfo_ID   = $wpdb->get_results( $usersql_ID );

												if( $userinfo_ID ) {
													foreach( $userinfo_ID as $userinfoObj_ID ) {
														$user_info = get_userdata( $userinfoObj_ID->ID );
														echo '<option style="font-size: 16px; color: #ffffff; background-color: #7b84fc;" value="' 
														. esc_attr( $userinfoObj_ID->user_login ) . '">'
														. 'ID: ' . esc_html( $userinfoObj_ID->ID )
														. ' - ' . esc_html( $userinfoObj_ID->user_login ) 
														. ' as ' . esc_html( implode(', ', $user_info->roles) )
														. '</option>';
													}
												}
												echo '</select><br />';
											}
											?>

											<label for="new_ID"></label><br />
											<strong style="padding-right: 18px;"><?php echo __( 'New User ID (Range 1 - 9999999999)', 'ID-changer' ); ?></strong>
											<input style="font-size: 16px; color: #ffffff; background-color: #7b84fc; height:26px" name="new_ID" id="new_ID" type="text" value="" size="12" maxlength="10" 
pattern="^([1-9]|[1-9][0-9]|[1-9][0-9][0-9]|[1-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]|[1-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9])$"/>											<div style="float: right;">
												<input type="submit" name="submit" id="submit" class="button button-primary action" value="<?php echo __( 'Save Changes', 'ID-changer' ); ?>" />
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<div style="float: left;">
												<p>
												<font color="#7b84fc">
												<strong> NOTE:</strong></font> WordPress uses an auto increment value for the User ID's. It starts at 1 and increments every time a new user is added. 
												If you change one of the User ID's to a high value like 4007, the auto increment will start from 4008 and so on.</p>
												<p>Maximum number of User ID's WordPress supports: 9,223,372,036,854,775,807<br />
												(9 quintillion 223 quadrillion 372 trillion 36 billion 854 million 775 thousand and 807)</p>
											</div>
										</td>
									</tr>
								</thead>
							</table>
						</form>
					</td>
					<td>
						<table cellpadding="5" class="widefat post">
							<thead>
								<tr>
									<th>
										<div style="float: left;">
											<strong>User ID Changer - Information</strong>
										</div>
									<th>
								</tr>
								<tr>
									<td>
										<div style="float: left;">
											<p style="text-align: justify;">
											WordPress security has become a number one issue for businesses. One of the many ways hackers gain entry into your WordPress site is by Brute Force & User Name enumeration, 
											once the hacker knows the User Name, they can start a Brute Force attack. Brute Force attacks use a password dictionary to try and crack your password.
											</p>
											<p style="text-align: justify;">
											User ID Changer will help safe guard your WordPress site. By default WordPress can create an <strong>ADMIN</strong> user with an ID of <strong>1</strong>, this is know by every hacker on the planet.
											</p>
											<p style="text-align: justify;">
											<strong>If you have an ADMIN username or an ID of 1, then CHANGE IT NOW!!</strong>.
											</p>
											<p style="text-align: justify;">
											<strong><font color=#7b84fc>Database Backup:</font></strong> Use "WP-DBManager" plugin. <a href="https://wordpress.org/plugins/wp-dbmanager/" target="_blank">Get it here.</a>
											<br />
											<strong><font color=#7b84fc>Username Change:</font></strong> Use "Username Changer" plugin. <a href="https://wordpress.org/plugins/username-changer/" target="_blank">Get it here.</a>
											<br />
											<strong><font color=#7b84fc>Role Change:</font></strong> Use "User Role Editor" plugin. <a href="https://en-gb.wordpress.org/plugins/user-role-editor/" target="_blank">Get it here.</a>
										</div>
									</td>
								</tr>
							</thead>
						</table>
					</td>
				</tr>
			</table>
			</div>
			<?php
//
//
// --------------------------------- RESULTS SECTION ---------------------------------
//
//
			$args = array( 'orderby' => 'ID' );
			$wp_user_query_ID = new WP_User_Query( $args );

			if ( !empty( $wp_user_query_ID->results ) ) {
				echo '
				<a name="raptors"></a>
				<div class="wrap">
				<div id="icon-options-general" class="icon32"></div>
				<h2><font color=#7b84fc>Changed User ID Results</font></h2>
				<table style="width: 100%" cellpadding="5" class="widefat post">
					<thead>
						<tr>
							<th></th>
							<th title="Old User ID"><strong>Old ID</strong></th>
							<th title="New User ID"><strong>New ID</strong></th>
							<th title="Login Username"><strong>Username</strong></th>
							<th title="Display Name"><strong>Display Name</strong></th>
							<th title="Login Role"><strong>Role(s)</strong></th>
							<th title="What You Can & Can Not Do"><strong>Level</strong></th>
							<th title="User Email Address"><strong>Email</strong></th>
							<th title="Real Name"><strong>Name</strong></th>
							<th title="User Nickname"><strong>Nickname</strong></th>
							<th title="Total Posts For User"><strong>Posts</strong></th>
							<th title="Total / Approved / Unapproved"><strong>Comments</strong></th>
						</tr>
					</thead>
					<tbody>
				';

				foreach ( $wp_user_query_ID->results as $db_users_ID ) {

					$comment_approved 	= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 1 AND user_id = %s", $db_users_ID->ID ) );
					$comment_unapproved = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = 0 AND user_id = %s", $db_users_ID->ID ) );
					$comment_num 		= $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE user_id = %s", $db_users_ID->ID ) );

					echo '
						<tr class="alternate">
						';
					if ( $db_users_ID->ID == $new_ID ){

						if ( $selected_ID == "" ){
							echo '<td style="vertical-align:middle;" colspan="12"><p><strong>' . 
							sprintf( __( '<font color="#ff0000">NO CHANGES WHERE MADE!!' ), username_exists( $current_ID ), $new_ID, $new_ID ) . '</strong></p></font></td>';
					
						} else {

							echo '<td style="vertical-align:middle;">' . get_avatar( $db_users_ID->ID, 30 ) . '</td>';

							if ( $selected_ID == "1" ){
								echo '<td style="vertical-align:middle;"><strong><font color=#7b84fc>' . $selected_ID . '</font></strong></td>';
							} else {
								echo '<td style="vertical-align:middle;">' . $selected_ID . '</td>';
							}

							if ( $db_users_ID->ID == "1" ){
								echo '<td style="vertical-align:middle;"><strong><font color=#7b84fc>' . $db_users_ID->ID . '</font></strong></td>';
							} else {
								echo '<td style="vertical-align:middle;">' . $db_users_ID->ID . '</td>';
							}

							if ( $db_users_ID->user_login == "admin" ){
								echo '<td style="vertical-align:middle;"><strong><font color=#7b84fc>' . $db_users_ID->user_login . '</font></strong></td>';
							} else {
								echo '<td style="vertical-align:middle;">' . $db_users_ID->user_login . '</td>';
							}

							echo '<td style="vertical-align:middle;">' . $db_users_ID->display_name . '</td>';

							echo '<td style="vertical-align:middle; width:10%;">';
							foreach ( $db_users_ID->roles as $keyroles => $roles ){
								if( $keyroles !== 0 ) echo ', ';
								if( $roles == "administrator" )  { 
									echo "<strong><font color=#7b84fc>" . $roles . "</font></strong>"; 
								} else {
									echo $roles;
								}
							}
							echo '</td>';

							echo '
							<td style="vertical-align:middle;">' . $db_users_ID->user_level . '</td>
							<td style="vertical-align:middle;">' . $db_users_ID->user_email . '</td>
							<td style="vertical-align:middle;">' . $db_users_ID->user_firstname . ' ' . $db_users_ID->user_lastname . '</td>
							<td style="vertical-align:middle;">' . $db_users_ID->nickname . '</td>
							<td style="vertical-align:middle;">' . count_user_posts( $db_users_ID->ID ) . '</td>
							<td style="vertical-align:middle;">' . 'T:' . $comment_num . ' / A:' . $comment_approved . ' / U:' . $comment_unapproved .'</td>
						</tr>
						';
						}
					} 
				}
				echo '
					</tbody>
				</table>
				</div>
				';
			} else {
				echo '<p><font color=#ff0000>No Users Found</font></p>';
			}
		} else {
			echo '<p><font color=#ff0000>No Users Found</font></p>';
		}

		echo '
		<div class="wrap">
			<table cellpadding="5" class="widefat post fixed">
				<tbody>
					<tr>
						<td style="vertical-align: middle;">
							<center>
							<a href="https://interwebDEFENCE.com" target="_blank">
							<img style="text-decoration: none; border-style: none;" border="0" src="' . plugin_dir_url( __FILE__ ) . 'assets/images/iwd01.png' . '"></a>
							<br />WordPress Security & Monitoring Specialist.
							</center>
						</td>
							';
							if ( is_plugin_active( 'jetpack/jetpack.php' ) ) { 
							echo '
						<td>
							<p style="text-align:justify;"><h3><strong><font color=#7b84fc>Jetpack User Information</font></strong></h3>
							If you are changing the primary User ID linked to Jetpack, please DEACTIVATE the Jetpack plugin, then changed the User ID. Once you have changed it, ACTIVATE the 
							Jetpack plugin again and press <strong>"Connect to WordPress.com"</strong> (This is dsiplayed at the top of the admin screen). 
							This will then take you to WordPress.com where you press <strong>Approve</strong>. This will then assign the new User ID to Jetpack.</p>
						</td>
							'; 
							} 
							echo '
					</tr>
				</tbody>
			</table>
		</div>
		';
//
//
// --------------------------------- WordPress - POSTS - PAGES - COMMENTS - WOOCOMMERCE - VERSIONS - PLUGINS ---------------------------------
//
//
		?>
		<div class="wrap">
			<div class="wrap"><hr style="border: none; border-bottom: 5px solid #474747;"></div>
			<h2><font color=#7b84fc>WordPress Information</font></h2>
			<table style="width: 100%"  cellpadding="5" class="widefat post fixed">
				<tbody>
					<tr>
						<td>
							<h3>Roles / Users</h3>
								<ul>
									<li><strong>Total Users:</strong> <?php totalUsers(); ?></li>
									<?php totalRoles(); ?>
								</ul>
						</td>
						<td class="alternate">
							<h3>Posts</h3>
							<ul>
								<li><strong>Published:</strong> 		<?php countPosts('publish'); ?></li>
								<li><strong>Future:</strong> 			<?php countPosts('future'); ?></li>
								<li><strong>Drafts:</strong> 			<?php countPosts('draft'); ?></li>
								<li><strong>Pending:</strong> 			<?php countPosts('pending'); ?></li>
								<li><strong>Private:</strong> 			<?php countPosts('private'); ?></li>
								<li><strong>Trash:</strong> 			<?php countPosts('trash'); ?></li>
								<li><strong>Auto Draft:</strong> 		<?php countPosts('auto-draft'); ?></li>
								<li><strong>Inherit:</strong> 			<?php countPosts('inherit'); ?></li>
								<li><?php $categories = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->terms;");
									echo '<strong>Terms: </strong>' . $categories;?></li>
							</ul>
						</td>
						<td>
							<h3>Pages</h3>
							<ul>
								<li><strong>Published:</strong> 		<?php countPages('publish'); ?></li>
								<li><strong>Future:</strong> 			<?php countPages('future'); ?></li>
								<li><strong>Drafts:</strong> 			<?php countPages('draft'); ?></li>
								<li><strong>Pending:</strong> 			<?php countPages('pending'); ?></li>
								<li><strong>Private:</strong> 			<?php countPages('private'); ?></li>
								<li><strong>Trash:</strong> 			<?php countPages('trash'); ?></li>
								<li><strong>Auto Draft:</strong> 		<?php countPages('auto-draft'); ?></li>
								<li><strong>Inherit:</strong> 			<?php countPages('inherit'); ?></li>
								<li><?php $revisions = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'revision';");
									echo '<strong>Revisions: </strong>' . $revisions;?></li>
							</ul>
						</td>
						<td class="alternate">
							<h3>Comments</h>
							<ul>
								<li><strong>Total:</strong> 			<?php countComments('total_comments'); ?></li>
								<li><strong>Approved:</strong> 			<?php countComments('approved'); ?></li>
								<li><?php $comment_unapproved = $wpdb->get_var( "SELECT COUNT(*) AS total FROM $wpdb->comments WHERE comment_approved = '0'" ); ?>
								<strong>Unapproved:</strong> 			<?php echo $comment_unapproved; ?></li>
								<li><strong>Pending:</strong> 			<?php countComments('moderated'); ?></li>
								<li><strong>Spam:</strong> 				<?php countComments('spam'); ?></li>
								<li><strong>Trash:</strong> 			<?php countComments('trash'); ?></li>
							</ul>
						</td>
						<?php
						if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) { 
						?>
						<td>
							<h3>WooCommerce</h3>
							<ul>
								<li><strong>Pending:</strong> 			<?php countWoo('wc-pending'); ?></li>
								<li><strong>Processing:</strong> 		<?php countWoo('wc-processing'); ?></li>
								<li><strong>On Hold:</strong> 			<?php countWoo('wc-on-hold'); ?></li>
								<li><strong>Completed:</strong> 		<?php countWoo('wc-completed'); ?></li>
								<li><strong>Cancelled:</strong> 		<?php countWoo('wc-cancelled'); ?></li>
								<li><strong>Refunded:</strong> 			<?php countWoo('wc-refunded'); ?></li>
								<li><strong>Failed:</strong> 			<?php countWoo('wc-failed'); ?></li>
							</ul>
						</td>
						<?php
						} 
						?>
						
						<td style="background-color:#c0c0c0;">
							<h3>Versions</h>
							<ul>
								<li><strong>WordPress Version:</strong> <?php echo $wp_version; ?></li>
								<li><strong>PHP Version:</strong> 		<?php echo phpversion(); ?></li>
								<li><strong>mySQL Version:</strong> 	<?php echo $wpdb->db_version(); ?></li>
								<li><strong>WP DB Version:</strong> 	<?php echo $wp_db_version; ?></li>
								<li><strong>Theme: </strong>			<?php $my_theme = wp_get_theme(); echo $my_theme->get( 'Name' ) . " Version " . $my_theme->get( 'Version' ); ?></li>
								<li><strong>Encoding:</strong> 			<?php echo get_option('blog_charset'); ?></li>
							</ul>
							<h3>WordPress Database</h>
							<ul>
								<li><strong>DB Name:</strong>		<?php echo $wpdb->dbname ?></li>
								<li><strong>DB Prefix:</strong>	<?php echo $wpdb->base_prefix ?></li>
							</ul>
						</td>
						<td style="background-color:#e0e0e0;">
							<h3>Popular Plugins</h>
							<ul>
								<li><strong>WooCommerce:</strong>		
									<?php
									if (!file_exists(WP_PLUGIN_DIR . '/woocommerce/woocommerce.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else {
										if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										}
									}
									?>
								</li>
								<li><strong>Jetpack:</strong>
									<?php
									if (!file_exists(WP_PLUGIN_DIR . '/jetpack/jetpack.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else {
										if ( is_plugin_active( 'jetpack/jetpack.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										}
									}
									?>
								</li>
								<li><strong>Shield:</strong>
									<?php
									if (!file_exists(WP_PLUGIN_DIR . '/wp-simple-firewall/icwp-wpsf.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else {
										if ( is_plugin_active( 'wp-simple-firewall/icwp-wpsf.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										}
									}
									?>
								</li>
								<li><strong>iControlWP:</strong>
									<?php
									if (!file_exists(WP_PLUGIN_DIR . '/worpit-admin-dashboard-plugin/worpit.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else {
										if ( is_plugin_active( 'worpit-admin-dashboard-plugin/worpit.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										}
									}
									?>
								</li>
								<li><strong>Wordfence:</strong>
									<?php
									if (!file_exists(WP_PLUGIN_DIR . '/wordfence/wordfence.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else { 
										if ( is_plugin_active( 'wordfence/wordfence.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										} 
									}
									?>
								</li>
								<li><strong>Sucuri:</strong>
									<?php 

									if (!file_exists(WP_PLUGIN_DIR . '/sucuri-scanner/sucuri.php')){
										echo '<span style="color:#333333; background-color:#ffdd00; padding:2px 10px 2px 10px">Not Installed</span>';
									} else { 
										if ( is_plugin_active( 'sucuri-scanner/sucuri.php' ) ) 
										{ 
											echo '<span style="color:#333333; background-color:#33cc33; padding:2px 10px 2px 10px">Activated</span>'; 
										} else { 
											echo '<span style="color:#333333; background-color:#ff0000; padding:2px 10px 2px 10px">Deactivated</span>'; 
										} 
									}
									?>
								</li>
							</ul>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
//
// --------------------------------- SYSTEM INFORMATION SECTION ---------------------------------
//
//
		?>
		<style>.xsf {font-size: .9em!important;}</style>
		<div class="wrap"><hr style="border: none; border-bottom: 5px solid #474747;"></div>
		<div class="wrap">
			<table style="width: 100%" cellpadding="5" class="widefat post fixed">
				<tr>
					<td>
						<table style="width: 100%"  cellpadding="0" cellspacing="0" class="widefat post">
							<thead>
								<tr>
									<th width="100%" class="alternate" colspan="2"><strong>Website Details</strong></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="xsf" width="40%"><strong>Website URL:</strong></td>
									<td class="xsf" width="60%"><?php echo get_site_url(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%">
										<?PHP
										$website_ip_addr = check_website_ip();
										if (!filter_var($website_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
											echo '<strong>Website IPv6 Address:</strong>';
										} elseif (!filter_var($website_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
											echo '<strong>Website IPv4 Address:</strong>';
										} 
										?>
									</td>
									<td class="xsf" width="60%"><?php echo check_website_ip(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Hosted Server:</strong></td>
									<td class="xsf" width="60%"><?php echo gethostname(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Hosted IP Address(s):</strong></td>
									<td class="xsf" width="60%"><?php echo check_server_ip(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Hosting Provider / Affiliate:<br>Host Location:</strong></td>
									<td class="xsf" width="60%"><?php echo check_server_location(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Proxy Server:</strong></td>
									<td class="xsf" width="60%"><?php echo check_for_proxy(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>SSL Encryption:</strong></td>
									<td class="xsf" width="60%"><?php echo check_for_ssl(); ?></td>
								<tr>
									<td class="xsf" width="40%"><strong>Host Software:</strong></td>
									<td class="xsf" width="60%"><?php echo getenv('SERVER_SOFTWARE'); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Host OS:</strong></td>
									<td class="xsf" width="60%"><?php echo PHP_OS; ?></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td>
						<table style="width: 100%" cellpadding="0" cellspacing="0" class="widefat post">
							<thead>
								<tr>
									<th width="100%" class="alternate" colspan="2"><strong>Your Details</strong></th>
								<tr>
							</thead>
							<tbody>		
								<tr>
									<td class="xsf" width="30%">
										<?PHP
										$your_ip_addr = proxy_to_your_address();
										if (!filter_var($your_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
											echo '<strong>Your IPv6 Address:</strong>';
										} elseif (!filter_var($your_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false) {
											echo '<strong>Your IPv4 Address:</strong>';
										} 
										?>
									</td>
									<td class="xsf" width="60%"><?php echo proxy_to_your_address(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Internet Provider:<br>Location:<br>Autonomous Systems:</strong></td>
									<td class="xsf" width="60%"><?php echo check_your_location(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Your Hardware:</strong></td>
									<td class="xsf" width="60%"><?php echo iwd_get_platform(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>Your Browser:</strong></td>
									<td class="xsf" width="60%"><?php echo iwd_get_browser(); ?></td>
								</tr>
								<tr>
									<td class="xsf" width="40%">
										<?php 
										$hostname_ip_addr = iwd_get_host_name();
										if (!filter_var($hostname_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
											echo '<strong>IP Address Allocated By:</strong>';
										} else {
											echo '<strong>Internet Providers Host:</strong>';
										}
										?>
									</td>
									<td class="xsf" width="60%">
										<?php 
										$hostname_ip_addr = iwd_get_host_name();
										if (!filter_var($hostname_ip_addr, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
											echo  iwd_get_host_name()  . '<br>' . check_hostname_location();
										} else {
											echo iwd_get_host_name();
										} 
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
					<td>
						<table style="width: 100%" cellpadding="0" cellspacing="0" class="widefat post">
							<thead>
								<tr>
									<th width="100%"class="alternate" colspan="2"><strong>DNS Information</strong></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td class="xsf" width="40%"><strong>Name Servers:</strong></td>
									<td class="xsf" width="60%">	
										<?php // Name Server Lookup
										$NS_results = dns_get_record(getenv('HTTP_HOST'), DNS_NS);
										if (!empty( $NS_results ) ) {
											foreach ( $NS_results as $DNS_records ) { echo $DNS_records['target'] . '<br>'; }
										} else { echo 'No NS Records<br />'; }?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>MX Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // MX record Lookup
										$MX_results = dns_get_record(getenv('HTTP_HOST'), DNS_MX);
										if (!empty( $MX_results ) ) {
											foreach ( $MX_results as $MX_records ) { echo $MX_records['target'] . '<br>'; }
										} else { echo 'No MX Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>A Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // A Record Lookuo
										$Arec_results = dns_get_record(getenv('HTTP_HOST'), DNS_A);
										if (!empty( $Arec_results ) ) {
											foreach ( $Arec_results as $Arec_records ) { echo $Arec_records['ip'] . '<br>'; }
										} else { echo 'No A Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>CNAME Records:</strong></td>
									<td class="xsf" width="60%">
										<?php //CNAME Record Lookup
										$CNAME_results = dns_get_record(getenv('HTTP_HOST'), DNS_CNAME);
										if (!empty( $CNAME_results ) ) {
											foreach ( $CNAME_results as $CNAME_records ) { echo $CNAME_records['host'] . '<br>'; }
										} else { echo 'No CNAME Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>AAAA Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // AAAA Record Lookup
										$AAAA_results = dns_get_record(getenv('HTTP_HOST'), DNS_AAAA);
										if (!empty( $AAAA_results ) ) {
											foreach ( $AAAA_results as $AAAA_records ) {echo $AAAA_records['ipv6'] . '<br>'; }
										} else { echo 'No AAAA Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>PTR Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // PTR Record Lookup
										$PTR_results = dns_get_record(getenv('HTTP_HOST'), DNS_PTR);
										if (!empty( $PTR_results ) ) {
											foreach ( $PTR_results as $PTR_records ) { echo $PTR_records['rname'] . '<br>'; }
										} else { echo 'No PTR Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>SOA Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // SOA Record Lookip
										$SOA_results = dns_get_record(getenv('HTTP_HOST'), DNS_SOA);
										if (!empty( $SOA_results ) ) {
											foreach ( $SOA_results as $SOA_records ) { echo 'mname: ' 
												. $SOA_records['mname'] . '<br>rname: ' . $SOA_records['rname'] . '<br>'; }
										} else { echo 'No SOA Records<br />'; } ?>
									</td>
								</tr>
								<tr>
									<td class="xsf" width="40%"><strong>TXT Records:</strong></td>
									<td class="xsf" width="60%">
										<?php // TXT Record Lookup
										$TXT_results = dns_get_record(getenv('HTTP_HOST'), DNS_TXT);
										if (!empty( $TXT_results ) ) {
											foreach ( $TXT_results as $TXT_records ) { echo $TXT_records['txt'] . '<br>'; }
										} else { echo 'No TXT Records<br />'; } ?>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<div class="wrap"><hr style="border: none; border-bottom: 5px solid #474747;"></div>
		<?php
	}
}
?>