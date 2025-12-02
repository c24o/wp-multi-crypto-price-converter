<?php
/**
 * Plugin Name: Multi Crypto Convert
 * Plugin URI:  https://github.com/c24o/multi-crypto-convert-wp-plugin
 * Description: A WordPress plugin for instant multi-crypto conversions.
 * Version:     0.1.0
 * Author:      Carlos Guzman
 * Text Domain: multi-crypto-convert
 *
 * @package Multi_Crypto_Convert
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert;

use Multi_Crypto_Convert\Cache\Crypto_Option_Cache;
use Multi_Crypto_Convert\Clients\Crypto_Client_Factory;
use Multi_Crypto_Convert\Settings\Admin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MCC_PLUGIN_FILE', __FILE__ );
define( 'MCC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MCC_PLUGIN_VERSION', '0.1.0' );

/**
 * Load Composer autoloader if present.
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

add_action(
	'plugins_loaded',
	function () {
		$cache = new Crypto_Option_Cache();
		$factory = new Crypto_Client_Factory( $cache );
		new Admin_Settings( $factory );
	}
);
