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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ppclass');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'f_Zsk0,Km-c(.:|T9Z93!yKrkj0uS?PwTH4^_E4dI/vsf[rD%)R8&q*x+pv9#J>o');
define('SECURE_AUTH_KEY',  'BDk.1:-E>zut_<%@ud2btt|@EQ?360bs9Zh|8m.,zLG9+u[Ur;b6=Mz]r3Zg*who');
define('LOGGED_IN_KEY',    '^,e_om9E+[5DMs?n<7+fYLjp4:MI={k`:6w-;C[MSi6If+S |=g$[._lZg]&cZpr');
define('NONCE_KEY',        'wthUa y|}F4 j{[whdC1M4t(`x:AnURGdZ(l<1a3-a=oy/+2,U`tDDf+[@2iTv&a');
define('AUTH_SALT',        '6hQrw{@4jaT!){qFhZqJT%4)IuUveWhASHqS<%q^Pb)~99?T:j(VmoPNWM3.I@@w');
define('SECURE_AUTH_SALT', '#lyoxpD+[%WBPZwGHAW]sdo#{ri,L#CR#+ND<{1Sh~C%%uxPbIb%9D$8Mg}-|mi7');
define('LOGGED_IN_SALT',   'lC:Hc^D}j .,e~W<Z3Dow?UkYQR}7wc-V~YUMQRMM;P>TatmLGQ<tU]~V-+D_%fB');
define('NONCE_SALT',       'l, _mRs)7`|M~vzC>KJ2k;yGjk}eH!#A>b!R51k,92^6vie75x<@9;t9G+E}$|*D');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
