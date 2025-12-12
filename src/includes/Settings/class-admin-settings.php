<?php
/**
 * Admin Settings
 *
 * @package Multi_Crypto_Convert\Settings
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Settings;

use Multi_Crypto_Convert\Clients\Crypto_API_Client;
use Multi_Crypto_Convert\Clients\Crypto_Client_Factory;

/**
 * Manages the admin settings page for the Multi-Crypto Convert plugin.
 */
final class Admin_Settings {

	public const PAGE_SLUG = 'mcc_settings';
	public const SETTINGS_OPTION_NAME = 'mcc_general_settings';
	public const OPTION_API_SOURCE = 'mcc_api_source';
	public const OPTION_SELECTED_COINS = 'mcc_selected_coins';
	private const REFRESH_COINS_ACTION = 'mcc_refresh_coins';
	private const MAX_SELECTABLE_COINS = 50;

	/**
	 * Constructor.
	 *
	 * @param Crypto_Client_Factory $factory The client factory for creating API clients.
	 */
	public function __construct(
		private Crypto_Client_Factory $factory
	) {
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_menu', [ $this, 'register_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
		add_action( 'wp_ajax_' . self::REFRESH_COINS_ACTION, [ $this, 'handle_refresh_coins_ajax' ] );
	}

	/**
	 * Retrieves the selected coins for use in blocks.
	 *
	 * @return string[] The list of selected coin symbols (lowercase).
	 */
	public function get_selected_coins(): array {
		$settings = $this->get_settings();
		$selected = $settings[ self::OPTION_SELECTED_COINS ] ?? [];
		if ( ! is_array( $selected ) ) {
			return [];
		}

		return array_values(
			array_filter(
				$this->get_active_source_client()->get_available_coins(),
				function ( $coin ) use ( $selected ) {
					return in_array( $coin->api_id, $selected, true );
				}
			)
		);
	}

	/**
	 * Retrieves the current settings.
	 *
	 * @return array The current settings array.
	 */
	public function get_settings(): array {
		$settings = get_option( self::SETTINGS_OPTION_NAME, [] );
		return is_array( $settings ) ? $settings : [];
	}

	/**
	 * Retrieves the active cryptocurrency API client based on current settings.
	 *
	 * @return Crypto_API_Client The active API client instance.
	 *
	 * @throws \InvalidArgumentException If the configured API source is not supported.
	 */
	public function get_active_source_client(): Crypto_API_Client {
		$settings = $this->get_settings();
		$source = $settings[ self::OPTION_API_SOURCE ] ?? '';
		return $this->factory->create( $source, $this->get_settings(), $this->get_tracked_crypto_symbols() );
	}

	/**
	 * Gets the list of cryptocurrency symbols used by existing converter
	 * blocks.
	 *
	 * @return string[] The list of tracked cryptocurrency symbols.
	 */
	public function get_tracked_crypto_symbols(): array {
		return [ 'btc', 'eth' ];
	}

	/**
	 * Registers the settings fields and sections.
	 */
	public function register_settings(): void {
		register_setting(
			self::PAGE_SLUG,
			self::SETTINGS_OPTION_NAME,
			[
				'sanitize_callback' => [ $this, 'sanitize_settings' ],
			]
		);

		$api_section = 'mcc_api_settings_section';
		add_settings_section(
			$api_section,
			__( 'API Configuration', 'multi-crypto-convert' ),
			[ $this, 'render_api_settings_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			self::OPTION_API_SOURCE,
			__( 'API Source', 'multi-crypto-convert' ),
			function () {
				$current_source = $this->get_settings()[ self::OPTION_API_SOURCE ] ?? '';
				?>
				<select
					id="mcc_api_source"
					name="<?php printf( '%s[%s]', esc_attr( self::SETTINGS_OPTION_NAME ), esc_attr( self::OPTION_API_SOURCE ) ); ?>"
					class="regular-text"
				>
					<?php foreach ( $this->factory->get_available_sources() as $value => $label ) : ?>
						<option
							value="<?php echo esc_attr( $value ); ?>"
							<?php selected( $current_source, $value ); ?>
						>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<?php
			},
			self::PAGE_SLUG,
			$api_section
		);

		/**
		 * Allow clients to add their own settings fields.
		 *
		 * Each source might have different settings requirements to establish
		 * the connection with its REST API.
		 *
		 * @param string $page_slug The settings page slug.
		 * @param string $section_id The settings section ID.
		 */
		do_action( 'mcc_register_client_settings_fields', self::PAGE_SLUG, $api_section );

		// Coins Selection Section.
		$coins_section = 'mcc_coins_selection_section';
		add_settings_section(
			$coins_section,
			__( 'Available Coins for Blocks', 'multi-crypto-convert' ),
			[ $this, 'render_coins_selection_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			self::OPTION_SELECTED_COINS,
			__( 'Select Coins', 'multi-crypto-convert' ),
			[ $this, 'render_coins_selection_field' ],
			self::PAGE_SLUG,
			$coins_section
		);
	}

	/**
	 * Sanitizes and validates settings input.
	 *
	 * @param array $input The input settings array.
	 * @return array The sanitized settings array.
	 */
	public function sanitize_settings( array $input ): array {
		$sanitized = [];

		// Sanitize API source.
		if ( isset( $input[ self::OPTION_API_SOURCE ] ) ) {
			$sanitized[ self::OPTION_API_SOURCE ] = sanitize_text_field( $input[ self::OPTION_API_SOURCE ] );
		}

		// Sanitize selected coins.
		if ( isset( $input[ self::OPTION_SELECTED_COINS ] ) && is_array( $input[ self::OPTION_SELECTED_COINS ] ) ) {
			$selected = array_map( 'sanitize_text_field', $input[ self::OPTION_SELECTED_COINS ] );
			// Limit to MAX_SELECTABLE_COINS.
			$selected = array_slice( $selected, 0, self::MAX_SELECTABLE_COINS );
			$sanitized[ self::OPTION_SELECTED_COINS ] = array_filter( $selected );
		}

		/**
		 * Allow clients to sanitize additional settings.
		 *
		 * @param array $sanitized The sanitized settings array.
		 * @param array $input The original input settings array.
		 */
		$sanitized = apply_filters( 'mcc_sanitize_client_settings', $sanitized, $input );

		return $sanitized;
	}

	/**
	 * Renders the coins selection section description.
	 */
	public function render_coins_selection_section(): void {
		echo wp_kses_post(
			sprintf(
				/* translators: %d is the maximum number of coins that can be selected */
				__( 'Select which cryptocurrencies should be available for use in converter blocks (maximum %d coins). Only coins selected here will be available when configuring blocks.', 'multi-crypto-convert' ),
				self::MAX_SELECTABLE_COINS
			)
		);
	}

	/**
	 * Renders the coins selection field.
	 */
	public function render_coins_selection_field(): void {
		try {
			$client = $this->get_active_source_client();
			$available_coins = $client->get_available_coins();
			$selected_coins_ids = array_column( $this->get_selected_coins(), 'api_id' );

			if ( empty( $available_coins ) ) {
				echo '<p>' . esc_html__( 'No coins available. Please configure your API source and refresh the coin list.', 'multi-crypto-convert' ) . '</p>';
				return;
			}

			$field_name = sprintf( '%s[%s][]', esc_attr( self::SETTINGS_OPTION_NAME ), esc_attr( self::OPTION_SELECTED_COINS ) );
			?>
			<select
				id="mcc-coins-selection"
				name="<?php echo esc_attr( $field_name ); ?>"
				multiple="multiple"
				class="mcc-coins-select2"
			>
				<?php foreach ( $available_coins as $coin ) : ?>
					<option
						value="<?php echo esc_attr( $coin->api_id ); ?>"
						data-symbol="<?php echo esc_attr( strtolower( $coin->symbol ) ); ?>"
						<?php selected( in_array( $coin->api_id, $selected_coins_ids, true ) ); ?>
					>
						<?php echo esc_html( sprintf( '%s (%s)', $coin->name, strtoupper( $coin->symbol ) ) ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<p class="description">
				<?php
				printf(
					/* translators: %1$d is the number of available coins in the source, %2$d is the maximum coins that can be selected. */
					esc_html__( 'Available coins found in the source: %1$d. Maximum %2$d coins can be selected.', 'multi-crypto-convert' ),
					count( $available_coins ),
					esc_html( (string) self::MAX_SELECTABLE_COINS )
				);
				?>
			</p>
			<script>
				(function() {
					const maxCoins = <?php echo (int) self::MAX_SELECTABLE_COINS; ?>;
					const $select = jQuery( '.mcc-coins-select2' );

					// Initialize Select2 with search functionality.
					$select.select2( {
						width: '100%',
						search: {
							matcher: function( params, data ) {
								const searchTerm = params.term.toLowerCase();
								if ( ! searchTerm ) {
									return data;
								}

								const matchesName = data.text.toLowerCase().includes( searchTerm );
								const matchesSymbol = jQuery( data.element ).data( 'symbol' ).includes( searchTerm );

								if ( matchesName || matchesSymbol ) {
									return data;
								}

								return null;
							}
						}
					} );

					// Enforce maximum coin limit on change.
					$select.on( 'change', function() {
						const selectedCount = jQuery( this ).val().length;
						if ( selectedCount > maxCoins ) {
							// Get the current value before the last change.
							const currentValue = $select.val() || [];
							// Revert to the first maxCoins selections.
							$select.val( currentValue.slice( 0, maxCoins ) ).trigger( 'change' );
						}
					} );
				})();
			</script>
			<?php
		} catch ( \Exception $e ) {
			echo '<p>' . esc_html__( 'Error loading coins. Please configure your API source first.', 'multi-crypto-convert' ) . '</p>';
		}
	}

	/**
	 * Renders the API settings section description.
	 */
	public function render_api_settings_section(): void {
		echo esc_html__( 'Configure your cryptocurrency data source and API credentials.', 'multi-crypto-convert' );
	}

	/**
	 * Registers the admin menu and settings page.
	 */
	public function register_menu(): void {
		add_menu_page(
			__( 'Multi-Crypto Convert Settings', 'multi-crypto-convert' ),
			__( 'Multi-Crypto Convert', 'multi-crypto-convert' ),
			'manage_options',
			self::PAGE_SLUG,
			[ $this, 'render_settings_page' ],
			'dashicons-chart-line',
			30
		);
	}

	/**
	 * Renders the settings page HTML.
	 */
	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'multi-crypto-convert' ) );
		}

		try {
			// Create client to get coin count.
			$client = $this->get_active_source_client();
			$coins = $client->get_available_coins();
			$coin_count = count( $coins );
		} catch ( \Exception $e ) {
			$coin_count = 0;
		}

		// Add error/update messages.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Only checking for settings-updated query var.
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				self::PAGE_SLUG,
				'mcc_settings_updated',
				__( 'Settings Saved', 'multi-crypto-convert' ),
				'updated'
			);
		}

		// Show error/update messages.
		settings_errors( self::PAGE_SLUG );

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Multi-Crypto Convert Settings', 'multi-crypto-convert' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( self::PAGE_SLUG ); ?>
				<?php do_settings_sections( self::PAGE_SLUG ); ?>
				<?php submit_button(); ?>
			</form>

			<hr />

			<h2><?php esc_html_e( 'Coin Management', 'multi-crypto-convert' ); ?></h2>

			<p>
				<?php esc_html_e( 'Amount of coins found in the source:', 'multi-crypto-convert' ); ?>
				<strong id="mcc-coin-count"><?php echo esc_html( $coin_count ); ?></strong>
			</p>

			<button
				type="button"
				id="mcc-refresh-coins-btn"
				class="button button-primary"
				data-nonce="<?php echo esc_attr( wp_create_nonce( self::REFRESH_COINS_ACTION ) ); ?>"
			>
				<?php esc_html_e( 'Refresh Coins List', 'multi-crypto-convert' ); ?>
			</button>

			<div id="mcc-refresh-status" style="margin-top: 20px; display: none;">
				<p id="mcc-status-message"></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueues admin assets (CSS and JavaScript).
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_admin_assets( string $hook ): void {
		if ( 'toplevel_page_' . self::PAGE_SLUG !== $hook ) {
			return;
		}

		// Enqueue Select2 CSS and JS from WordPress bundles.
		wp_enqueue_style(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
			[],
			'4.1.0-rc.0'
		);
		wp_enqueue_script(
			'select2',
			'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
			[ 'jquery' ],
			'4.1.0-rc.0',
			false
		);

		wp_enqueue_script(
			'mcc-admin-settings',
			plugins_url( 'assets/js/admin-settings.js', MCC_PLUGIN_FILE ),
			[],
			MCC_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'mcc-admin-settings',
			'mccSettings',
			[
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'action'  => self::REFRESH_COINS_ACTION,
			]
		);
	}

	/**
	 * Handles the AJAX request to refresh the coins list.
	 */
	public function handle_refresh_coins_ajax(): void {
		// Security check.
		if (
			! isset( $_POST['nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), self::REFRESH_COINS_ACTION )
		) {
			wp_send_json_error(
				[
					'message' => __( 'Security check failed.', 'multi-crypto-convert' ),
				]
			);
		}

		// Permission check.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				[
					'message' => __(
						'You do not have sufficient permissions to perform this action.',
						'multi-crypto-convert'
					),
				]
			);
		}

		try {
			// Get the selected API source.
			$source = $this->get_settings()[ self::OPTION_API_SOURCE ] ?? '';
			if ( ! is_string( $source ) || empty( $source ) ) {
				wp_send_json_error(
					[
						'message' => __( 'API source is not configured.', 'multi-crypto-convert' ),
					]
				);
			}

			// Create client instance from factory.
			$client = $this->get_active_source_client();

			// Fetch and cache the coins list.
			$coins = $client->get_available_coins( true );
			if ( is_wp_error( $coins ) ) {
				wp_send_json_error(
					[
						'message' => $coins->get_error_message(),
					]
				);
			}

			// Get the updated coin count.
			$coin_count = count( $coins );

			wp_send_json_success(
				[
					'message'    => __( 'Coins list refreshed successfully.', 'multi-crypto-convert' ),
					'coin_count' => $coin_count,
				]
			);
		} catch ( \Exception $e ) {
			wp_send_json_error(
				[
					'message' => sprintf(
						/* translators: %s is the error message */
						__( 'Error refreshing coins list: %s', 'multi-crypto-convert' ),
						$e->getMessage()
					),
				]
			);
		}
	}
}
