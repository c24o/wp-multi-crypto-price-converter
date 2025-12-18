<?php
/**
 * Render template for the converter block
 *
 * @package Multi_Crypto_Convert\Settings
 */

declare( strict_types=1 );

use Multi_Crypto_Convert\Blocks\Converter\Converter_Block;

( new Converter_Block() )->render( $attributes );
