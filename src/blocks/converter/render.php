<?php
/**
 * Render template for the converter block
 *
 * @package Multi_Crypto_Price_Converter\Settings
 */

declare( strict_types=1 );

use Multi_Crypto_Price_Converter\Blocks\Converter\Converter_Block;
use Multi_Crypto_Price_Converter\Settings\Admin_Settings;

use function Multi_Crypto_Price_Converter\get_container;

$settings = get_container()->get( Admin_Settings::class );
( new Converter_Block( $settings->get_active_source_client() ) )->render( $attributes );
