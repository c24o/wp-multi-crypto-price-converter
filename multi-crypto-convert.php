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
define( 'MCC_PLUGIN_DIR', __DIR__ );
define( 'MCC_PLUGIN_VERSION', '0.1.0' );

/**
 * Load Composer autoloader if present.
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}

/**
 * Registers the block using a `blocks-manifest.php` file, which improves the performance of block type registration.
 * Behind the scenes, it also registers all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function register_plugin_blocks() {
	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
	 * based on the registered block metadata.
	 * Added in WordPress 6.8 to simplify the block metadata registration process added in WordPress 6.7.
	 *
	 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
	 */
	if ( function_exists( '\wp_register_block_types_from_metadata_collection' ) ) {
		\wp_register_block_types_from_metadata_collection( MCC_PLUGIN_DIR . '/build/blocks', MCC_PLUGIN_DIR . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		\wp_register_block_metadata_collection( MCC_PLUGIN_DIR . '/build/blocks', MCC_PLUGIN_DIR . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require MCC_PLUGIN_DIR . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( MCC_PLUGIN_DIR . "/build/blocks/{$block_type}" );
	}
}
add_action( 'init', __NAMESPACE__ . '\register_plugin_blocks' );

add_action(
	'plugins_loaded',
	function () {
		$cache = new Crypto_Option_Cache();
		$factory = new Crypto_Client_Factory( $cache );
		new Admin_Settings( $factory );
	}
);
