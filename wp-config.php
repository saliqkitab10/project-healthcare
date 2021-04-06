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
define( 'DB_NAME', 'test_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '|CzD^UxXV`m-S87/.6]N`3M/@h%,Go2cO:QK._y8AmakH%[)1+Bc<-loDXkJnW&Z' );
define( 'SECURE_AUTH_KEY',  '8[d!R*BNKFHY30&g,,dunfJNEod03MVm)>zU|A}Ko1;=Dme#F5E-Lh<Z&`l#!}:Q' );
define( 'LOGGED_IN_KEY',    'dDf~VT*C&>#WA/h5gD61An#W8lT_b2=iUpb?e/zxR&3]Q7IoN$&Kkw_8eS3bo[gm' );
define( 'NONCE_KEY',        'vbYo*Xs:.-wk!7MYpe=OUcvik{fpm6^zQl{x9Dw-if<PR`T?_HDqg0Y@qCUOaI/ ' );
define( 'AUTH_SALT',        'pZ^sVz9r%`l=_N@BcgC|t< 7b0W+S{JiR0f$9%d%)Q+?^QJ/n1#RAVUPpg`-Ax}+' );
define( 'SECURE_AUTH_SALT', ';ynwOLK]Hze1kU5WqV ^VCPA[&C[cO[i#=4P(7-dSA4U|=iw4/(db{Fp?&kc&D9$' );
define( 'LOGGED_IN_SALT',   'U$-O6mZYm..Sj>k5ha2=Y*P#Yx}4oi1j$+8x>!YpMd27qL{X*s9UGSv:JI0Lj=4b' );
define( 'NONCE_SALT',       'sbIoYo6BO=H!Z5+)W@o7hW=M4Z;t,/J.vU$wT][`.wQ^Ssqp[H{It}3b-K!]T{6(' );

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
