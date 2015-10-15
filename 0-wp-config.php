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
define('DB_NAME', 'pdmwordpress');

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
define('AUTH_KEY',         '|8Z0 }?=++!jtN=a_$Xv53}qynr`cc=`[PmlilJ(zHw<#>9I[/_>e6Un4)Ep=Gk ');
define('SECURE_AUTH_KEY',  'e7.35.agLucLGdXiqD=UB@e.xRw~3iC.<3$tZ*Uxk|g!Q5wExf+$%?#5NQ^jIuKn');
define('LOGGED_IN_KEY',    'B1|Hcz16I[z;Krl8/5tXI&Dnn>/T-:,B5qj]}@&=3P#S{,z?*gA)|+kG3CPyJLHa');
define('NONCE_KEY',        'diP:n`-03_-0v0C7=%]_vp@{:*2N< :XFBg_A#d_Atk:1A|jhC33YYQ#!g1LBOC`');
define('AUTH_SALT',        '`kp&Wm^R,tReJbRkrOevim.P>:-bLl;4SJaZ@C|@<pB_/7L}RalK$7R@}f/A(|fI');
define('SECURE_AUTH_SALT', '+-|-:4a[._cCxL-t~w>Vl5SH|eJ|I9Z^oyE0S.NXZk6.0]yGkUaj/Y}o~V0Rm@pv');
define('LOGGED_IN_SALT',   'ZPV(=*.!ApO.?aSX7j`^t8v[!v`Vzc=3?kt)&={ 1I+Ujn]6$a[$YyIuFK!P<s/D');
define('NONCE_SALT',       'M,)f=vUhdA@@k+/?hQX0u!_4w,fLtf-F7>Y,(Si}kl~/CVD+DH{W-6o}s9#2<3IQ');

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
