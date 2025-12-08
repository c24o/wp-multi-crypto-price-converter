<?php
/**
 * Bootstrap file for WordPress plugin tests.
 *
 * @package Multi_Crypto_Convert\Tests
 */

// Load the Composer autoloader.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

// Set the plugins path for the WordPress testing environment.
define( 'WP_PLUGIN_DIR', dirname( __DIR__, 2 ) );

// Path to your plugin's main PHP file.
define( 'PLUGIN_MAIN_FILE', dirname( __DIR__ ) . '/multi-crypto-convert.php' );

// Include Polyfills for PHPUnit compatibility.
require_once dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';

// Start the WordPress testing environment bootstrapper.
if ( ! defined( 'WP_TESTS_CONFIG_FILE_PATH' ) ) {
	define( 'WP_TESTS_CONFIG_FILE_PATH', __DIR__ . '/wp-tests-config.php' );
}
require_once dirname( __DIR__ ) . '/vendor/wp-phpunit/wp-phpunit/includes/bootstrap.php';
