<?php
/**
 * Converter Block Logic
 *
 * @package Multi_Crypto_Convert\Blocks\Converter
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Blocks\Converter;

use Multi_Crypto_Convert\Clients\Crypto_API_Client;

/**
 * Render the output for the Converter Block.
 */
class Converter_Block {

	/**
	 * Constructor.
	 *
	 * @param Crypto_API_Client $client The client to get the prices.
	 */
	public function __construct(
		private Crypto_API_Client $client
	) {}

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
			<?php else : ?>
				<div class="mcc-converter-container"></div>
				<?php
				if ( $this->client->is_attribution_required() ) {
					$this->client->render_attribution_content();
				}
				?>
			<?php endif; ?>
		</div>
		<?php
	}
}
