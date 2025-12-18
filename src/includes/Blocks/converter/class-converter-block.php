<?php
/**
 * Converter Block Logic
 *
 * @package Multi_Crypto_Convert\Blocks\Converter
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Blocks\Converter;

/**
 * Render the output for the Converter Block.
 */
class Converter_Block {

	/**
	 * Renders the block.
	 *
	 * @param array $attributes Block attributes.
	 */
	public function render( array $attributes ): void {
		$coins_list = $attributes['coins'] ?? '';

		$wrapper_attributes = get_block_wrapper_attributes(
			[
				'data-coins' => $coins_list,
			]
		);

		?>
		<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- The output is already escaped. ?>>
			<?php if ( empty( $coins_list ) ) : ?>
				<p><?php esc_html_e( 'Please select coins in the block editor.', 'multi-crypto-convert' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
