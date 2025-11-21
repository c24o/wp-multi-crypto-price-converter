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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load Composer autoloader if present.
 */
$autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $autoload ) ) {
	require_once $autoload;
}
