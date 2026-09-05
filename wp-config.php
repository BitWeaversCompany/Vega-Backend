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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u335864675_qMx2p' );

/** Database username */
define( 'DB_USER', 'u335864675_OaGdC' );

/** Database password */
define( 'DB_PASSWORD', 'pENSCFhOuy' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          'h^^in}!%<l).OY2]U.`Set:[d3[4u.ye1Hmf;_K5+7Nh#>5j`ZX9$y]y!xO><BCW' );
define( 'SECURE_AUTH_KEY',   'N7?cFnLbr)a!U%Fg0h$};-!nP`;oV,!6EgPg53=XgMGWqvH_uU|A[G7^I8@v#=1C' );
define( 'LOGGED_IN_KEY',     'v_+Nfw($lW?[khC+Hw=wXq;F<J%y&ejuoG#;FZv?4c[gw;L9*By2@MBSeap%*8$:' );
define( 'NONCE_KEY',         '_]IOjuYRptOAY%z0W{=PAe-Qk6e+lT1-PRv/7#viUt9mG^. M#Z,iY9yg oZ<oy`' );
define( 'AUTH_SALT',         ',y9KlB@~o~syWXJRcEkq-VUH|7y{!RHV{6zbYW[EdeAPe,iYwE%qrq]HFCOx+Lfc' );
define( 'SECURE_AUTH_SALT',  '4pBLK:@6@L^1!(WpV8kK+q]Tf&_xrhc$ZP`*{`$i}@CN}Mb%xDe6*&ifQ^!)L`0[' );
define( 'LOGGED_IN_SALT',    '%ePB.>y4I`[{*{*SuKE$44yZ=4IoXX:k%f:IbfO5<#e<|=)5NbfTP+oYIY-lP/B=' );
define( 'NONCE_SALT',        'U&1@kjc-+A+ARf36RWMxVnEzLsI}W30)Ek:c5B|=9T,*]!`r6?kiP_m_-keH$(=x' );
define( 'WP_CACHE_KEY_SALT', ']wqP|3$M[Yfi/3m0pHY9S)<|tw#yJ;KuZ;kM+/|:+~jS73v%P(Jn$,MbzuksYFqS' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'FS_METHOD', 'direct' );
define( 'COOKIEHASH', '9b6ac2053c292c07bb53857d604d39e1' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
