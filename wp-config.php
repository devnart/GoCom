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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gocom' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '7!fHATRt]bQyD9:7AL;tQ<J`rr=Jad3t[RF73wv6<:rJ`^<|epQ4?![R!*LzUIH8' );
define( 'SECURE_AUTH_KEY',  '+$|JV j!E;bi}l|MD@<_]J,7Su9g)02;;lDS-td)~<lf=tiF.OL*5GqiEW<Uz4(:' );
define( 'LOGGED_IN_KEY',    '~W%)a;)h]~.T;:$9^tm!(w/bbBO1H8LX$Lj4xlcY&)9Biw0?MH-+}&3LjNqy)IG7' );
define( 'NONCE_KEY',        'Q-vs3UL%6t@alODnr@6#^<r*!eFOe+j450ag7yJ[EC9lf_E6skm2sVt3Q8s$+i`2' );
define( 'AUTH_SALT',        'Im#BE-]w&2oC$6cH2XC0W+@Icy2-i<-:Gd=~/zcJ:@OVG*]<i5x^wz]Yc9dn+BhF' );
define( 'SECURE_AUTH_SALT', '} V^~Pd,YEN/()8<[P=VAi)0hX$^CV1jp&LK-xmJXQ>4 P0pZ&:kaY cxC$0sjHh' );
define( 'LOGGED_IN_SALT',   'p?rWx_>zCtB9tK)O%ScocVhP2SuTjnr[$Bzz.g}p_&f:0,EWo^[=JsgbZ4stJn0[' );
define( 'NONCE_SALT',       '<],Wzv6>-ye@+`:yQG6As8yr6L}QE`n][=wi]hI{+~=96n^7sI^1Qt$y@WIy|hcH' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Multisite */
define('WP_ALLOW_MULTISITE', true);

define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', false);
define('DOMAIN_CURRENT_SITE', 'localhost');
define('PATH_CURRENT_SITE', '/gocom/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
