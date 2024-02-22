<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'success' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '@26%mByf_p)/T3Pz?+Z~c0[.?r^vU)%pHY<fltqcdM@,t[U#zW0C#/y/`_g9hRo?' );
define( 'SECURE_AUTH_KEY',  'BwtynR=^%>VJ9l sydxlYBuAxs2f[H|~}hkDf]onZ.ydbOtABta=%12`7/++98uY' );
define( 'LOGGED_IN_KEY',    'X?Dcc!M/Tv@JOOjcH IN1y7 )HgX.povrL7Jh`5E~L11/t6I#ESv`3vQZu[0T<r#' );
define( 'NONCE_KEY',        '&3}KI;3gFDtrCQ>i.SN3r?D]WxR=!9jNDG4i2C3.$v+<p& 7w%:[YW~];+2#To/:' );
define( 'AUTH_SALT',        '6a4P?XrYYBD0{$%YTZ<GpJa$ y&4fgvyyVV2B9lk3Km?ZivGTuqvLH?Q/EE6I,XI' );
define( 'SECURE_AUTH_SALT', '8Zk36OG!oSC2iO7.DEDV9C7Xr%6,m|3G7Y<M r9k*&9k9C0XRF<r,garj;RX; ,O' );
define( 'LOGGED_IN_SALT',   'Oaxjj?hf^#%!IepiPP`)*Jn$o&l0nX0D}*p2{?Jh8*F+<P@:(%B&8= 8,PpvCyum' );
define( 'NONCE_SALT',       '-1g7w*,u%J7,en7G_P4=NGupbj6{FOCZGLU{HdI9+xHQ&H_Va:x+ g3LJ(2QnPUh' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
