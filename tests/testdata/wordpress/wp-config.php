<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'cms_wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '|}Ww9{SB<myXoqL[+y#ZH_}mf-NL/C,~6N4pyYoIVMI:$Jg{[esmgT*`xftqV=14');
define('SECURE_AUTH_KEY',  'Ql;8C706VLG<g3~GC^@D4h;@yK,jdD/W5j6H)T^+<J3%8j7v;s~9Tj@*:UnY7zUL');
define('LOGGED_IN_KEY',    'p`I*>;E!jjx-O?)x1G FV*JX-q.0r?W6/|.e<l2R{^V4%@3Vis?1hMRU&l;H`F%E');
define('NONCE_KEY',        ')yw&bK+?g-lA,>9&yK]]I>HIDg6LU%DW[I5{#SyHyHA>dKN=]Ls@?Kdk_xe}G3z+');
define('AUTH_SALT',        '15<kQndelx@a(4C;(JGO>H[3L^)}e,:gXN$oX&hg{ZZEWs|okG+?_Al/bMR`oHU9');
define('SECURE_AUTH_SALT', '9BByn)P$;G))MLWVZLm?2&_(Y[K#gSb{NsBD->G`^BU]5?<L:-dMmRKY`6u73i4*');
define('LOGGED_IN_SALT',   't>npKw&uq2c9;Z3$^e`!~w9bP&uq#W![uM>AQq{AQMK9OWK{h2xpE@3qj[4U% 6r');
define('NONCE_SALT',       'R7LZ`0(%1gxX`b*ns!GMj5.U?kk]izH~cp gbmzV5d!W^=uwTfBV*xlTNUya||38');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
