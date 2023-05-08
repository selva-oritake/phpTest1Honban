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
define( 'DB_NAME', 'mydb' );

/** Database username */
define( 'DB_USER', 'vpsuser' );

/** Database password */
define( 'DB_PASSWORD', 'oIieG6GfXG' );

/** Database hostname */
define( 'DB_HOST', 'ik1-219-79869.vs.sakura.ne.jp' );

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
define( 'AUTH_KEY',         'oAFwk3(>FbJY2,7%x=%B!kptQ]RsizS2#gC&x+FZXGd<gG#EzPGHR5Ts- A2y6U)' );
define( 'SECURE_AUTH_KEY',  'Fs&dwS;qFVq]AA[?-,I].jd5863~o)ILApDkJjh5LQzQAHo|s_>];&RZC(!Snz|j' );
define( 'LOGGED_IN_KEY',    '[56:s7-hmg&W8WI@x)DEx[j6/kP<upi5.KO0Nc=C<!4gga+X->()G7G(w8&RO+@N' );
define( 'NONCE_KEY',        'HqHj^,IvJUrmb,HM`f9>?woJkAB(?; rcU(MzIS_:+fv@zr[Y(kGZmT4}oU3:`M2' );
define( 'AUTH_SALT',        '_-(%/kfC7qA)n.KO-6dtUJI(5{GgE #d;b6H]PqHDuMBw|4<Y`C/vO0ccCIr^V;R' );
define( 'SECURE_AUTH_SALT', 's G5OFPUTC %]`wsDO/>X$Q.m@raFOj@[FZlY]mK?~:]Q,44il3]sk1X!#&~(t0?' );
define( 'LOGGED_IN_SALT',   'U,jz|iF+UH^.-yi{bykNW0=VpnQu5u97|T#pi$/Yr#C8{RW3G|+%-({C3jiW&SMZ' );
define( 'NONCE_SALT',       'e17MwdWy)&<l/]yyBP@-O.3Rx[8Z_GQ.g1Fojic6r:6&S[lYX;ac# fFJ9=Ep<WT' );

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
