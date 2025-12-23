<?php
/**
 * Plugin Name: Multi Crypto Price Converter
 * Plugin URI:  https://github.com/c24o/multi-crypto-convert-wp-plugin
 * Description: A WordPress plugin that lists cryptocurrency prices and allows instant conversion of prices between any of the listed coins.
 * Version:     1.0.0
 * Author:      Carlos Guzman
 * Text Domain: multi-crypto-price-converter
 *
 * @package Multi_Crypto_Price_Converter
 */

declare( strict_types=1 );

namespace Multi_Crypto_Price_Converter;

use InvalidArgumentException;
use League\Container\Container;
use Multi_Crypto_Price_Converter\Cache\Crypto_Option_Cache;
use Multi_Crypto_Price_Converter\Clients\Crypto_API_Client;
use Multi_Crypto_Price_Converter\Clients\Crypto_Client_Factory;
use Multi_Crypto_Price_Converter\Controllers\Price_Rest_Controller;
use Multi_Crypto_Price_Converter\Settings\Admin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MCPC_PLUGIN_FILE', __FILE__ );
define( 'MCPC_PLUGIN_DIR', __DIR__ );

$plugin_data = get_file_data(
	__FILE__,
	[
		'version' => 'Version',
	],
	false
);
define( 'MCPC_PLUGIN_VERSION', $plugin_data['version'] ?? '0.0.0' );

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
		\wp_register_block_types_from_metadata_collection( MCPC_PLUGIN_DIR . '/build/blocks', MCPC_PLUGIN_DIR . '/build/blocks-manifest.php' );
		return;
	}

	/**
	 * Registers the block(s) metadata from the `blocks-manifest.php` file.
	 * Added to WordPress 6.7 to improve the performance of block type registration.
	 *
	 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
	 */
	if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
		\wp_register_block_metadata_collection( MCPC_PLUGIN_DIR . '/build/blocks', MCPC_PLUGIN_DIR . '/build/blocks-manifest.php' );
	}
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require MCPC_PLUGIN_DIR . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( MCPC_PLUGIN_DIR . "/build/blocks/{$block_type}" );
	}
}
add_action( 'init', __NAMESPACE__ . '\register_plugin_blocks' );

/**
 * Get the container for the plugin.
 */
function get_container() {
	global $mcpc_container;
	if ( ! $mcpc_container ) {
		$mcpc_container = new Container();
	}
	return $mcpc_container;
}

add_action(
	'plugins_loaded',
	function () {
		$container = get_container();
		$container->add( Crypto_Option_Cache::class );
		$container->add( Crypto_Client_Factory::class )->addArgument( Crypto_Option_Cache::class );
		$container->addShared( Admin_Settings::class )->addArgument( Crypto_Client_Factory::class );

		// Register API client settings for the source selected.
		$client = null;
		try {
			$client = $container->get( Admin_Settings::class )->get_active_source_client();
		} catch ( InvalidArgumentException $e ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedCatch -- See below.
			// A valid source has not been selected yet, so no client can be instantiated.
		}
		if ( $client instanceof Crypto_API_Client ) {
			$client->register_hooks();

			// Register the REST endpoints.
			new Price_Rest_Controller( $container->get( Admin_Settings::class ), $client );
		}
	}
);
