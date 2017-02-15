<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// Load assault online specific classes
require_once __DIR__ . '/ao-loader.php';

$params = [
	'db_name'     => 'assauu_db1',
	'db_user'     => 'assauu_1',
	'db_password' => 'ATL4DW19Xc98SNTG',
	'db_host'     => 'localhost',
];

// Include params file and merge with default params if it exists.
$paramsFile = __DIR__ . '/params.php';
if (file_exists($paramsFile)) {
	$params = array_merge($params, require_once $paramsFile);
}

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', $params['db_name']);

/** MySQL database username */
define('DB_USER', $params['db_user']);

/** MySQL database password */
define('DB_PASSWORD', $params['db_password']);

/** MySQL hostname */
define('DB_HOST', $params['db_host']);

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'q7{MN20K<Ti>.0.$?SbA*j*?S:##uFTW$jXVf]*3KnDRvZE4YOGzV[6^`$m~_.`5');
define('SECURE_AUTH_KEY',  'VY6M82U>_[#~2|&c[@| l|ukjA/A>0+|!=MJ1oR ;G4GDu{t)--cP&2Z|=7j0s_+');
define('LOGGED_IN_KEY',    '*$mV6sSStZ%fTK23EL?W@4XD&<nrqm9[X0RLLN&4_|&{mw,3L1={&,UyaHs36:#T');
define('NONCE_KEY',        '5GkAGb6<oXrHO3CcIc%8K|yf0LZ#|1I({1npim-J*twh[Ab]_^{z4@1(ImynJ bp');
define('AUTH_SALT',        'moP~:S9({)C=Y!$g1)>|Fz1WR) ?M[Og6]eYbMQ2~j{je7,itu1=J<-4[ps3UjGT');
define('SECURE_AUTH_SALT', 'Tr!m+E-6;z;!q/oGz|~+ls^`;U3!`RK3L|7E|WS@+_cA?(vH3[;M>|2prmd+y6]!');
define('LOGGED_IN_SALT',   '`V3fEV9D)} 30!Sz=Ae7 $H+SQrU`JiJY{%Qb_}H20H}(qt-({259m:a+rhsk$VM');
define('NONCE_SALT',       'h,4/uv -qn(%)kMQDW@5f-p`+4e~@/wr7#@7GA^1>V6>UFa0?ukKN2dQKCs,nW{w');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '23zx_';
define('WP_MEMORY_LIMIT', '1024M');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
