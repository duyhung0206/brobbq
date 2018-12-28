<?php
define('WP_CACHE', true); // Added by WP Rocket
 // Added by WP Rocket
 // Added by WP Rocket
 // Added by WP Rocket
 // Added by WP Rocket
 // Added by WP Rocket

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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'tung' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'h122122777' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '^pwb Z>BB6;*ClNZ{dp ~qwyg65H1!(X0&:A+9|L2cY>a$$W<UHvFo$=v?:f[6dK');
define('SECURE_AUTH_KEY',  '2-Xp/?4F3Z3~z>@QfI:r9522NDo0R`3Lwu8h9DVe^?*}c[oU*j}vdpwC5|<#1a.Y');
define('LOGGED_IN_KEY',    '6Ye_w=YI<7)iyK.nt:&U`8[pC|DUOR9]Krl3H*{Lv4tO&}mNKpN73MPp?ZJT<+~N');
define('NONCE_KEY',        '/FH|nAok0iaZ}|#cB.<1IArChQ(bo$6l_%-=FlL-`r_Ys.jl0s0,xn4ZY`CWV,vZ');
define('AUTH_SALT',        '=U9W/]An3Bs+bJZ9$+{fSc{]|.XY_jnrIVNb_+iy1D9TF^:ZEX])=$X.NDbMW1//');
define('SECURE_AUTH_SALT', 'NoeWKn<RD[yqw-ahmj.)V #Q`j-cD0Q</1hyYd>vm;ZkIEeut8SA^:slw=GC o|q');
define('LOGGED_IN_SALT',   'J+`5!WEOaY=o_;s;%-3$!|_3F+-H`vVsp|V>wr4nZ8[`_4Tp3-Qo`.2J!_JV;80K');
define('NONCE_SALT',       '%R-cG.y-32g1!|loujC58VkSZ/)S:0)-3`LmrXSFB2pe]@Uq%,KnRl`j?s}fQw#~');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
