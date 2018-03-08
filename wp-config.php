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
define('DB_NAME', 'wp-mybestwebsites-bk');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1q2w3e4r');

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
define('AUTH_KEY',         'jcg74cmljzyrh4n2xmy3d3ew8n9hjjxbws8ku55h0k3qyuovqmqdmgjnota7nlgg');
define('SECURE_AUTH_KEY',  'ndqjkxzfkiwzspkv4mcgtplskhclqbgxfcgdb6qhurxvqxvwwemvo0guw0brcrhl');
define('LOGGED_IN_KEY',    'gr5muz9jyvnoekptlc4yz6pfbtv8vq6kbiw6dauksswyeemo3pa94rrh92pn8dzd');
define('NONCE_KEY',        '2uxkolespcwylvzotomga3lwyv4xgnlplxo4c0sqr2gvl6hxrfs92tmbqdejpxzn');
define('AUTH_SALT',        'yf6b8vctze7bjor6nn75b2hka7736o0a5ydmrlenaftjunjdkhubupwtuworirkx');
define('SECURE_AUTH_SALT', 'unaf61bi4os9v6vdz4quqtd1rmerz0nscjjwsrpwu5hyxuap2fxa07nihgnnlgaq');
define('LOGGED_IN_SALT',   'k0wc8qtrzxwfppbj61xxf43yzky5mnid5zvieqjeirznnegygz0jtzphz1f9uj9c');
define('NONCE_SALT',       'vxownxo8e3ortt7hxjhmd8ug4upqrmlywtr1qu8qzxzfebsohyd4xrscnvqpol6p');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpe3_';

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
define( 'WP_MEMORY_LIMIT', '128M' );
define( 'WP_AUTO_UPDATE_CORE', false );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
