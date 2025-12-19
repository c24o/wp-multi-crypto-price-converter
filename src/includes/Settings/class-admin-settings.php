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
		add_action( 'update_option_' . self::SETTINGS_OPTION_NAME, [ $this, 'maybe_update_coins_list' ], 10, 2 );
	}

	/**
	 * Retrieves the selected coins for use in blocks.
	 *
	 * @return string[] The list of selected coin ids.
	 */
	public function get_selected_coins_ids(): array {
		$settings = $this->get_settings();
		$selected = $settings[ self::OPTION_SELECTED_COINS ] ?? [];
		if ( ! is_array( $selected ) ) {
			return [];
		}

		return $selected;
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
		return $this->factory->create( $source, $this->get_settings(), $this->get_selected_coins_ids() );
	}

	/**
	 * Update the list of coins retrieved from the source.
	 *
	 * @return bool True if the list is retrieved and saved, otherwise false.
	 */
	public function update_coins_list(): bool {
		// Get the selected API source.
		$source = $this->get_settings()[ self::OPTION_API_SOURCE ] ?? '';
		if ( ! is_string( $source ) || empty( $source ) ) {
			return false;
		}

		// Create client instance from factory.
		$client = $this->get_active_source_client();
		if ( ! $client instanceof Crypto_API_Client ) {
			return false;
		}

		// Fetch and cache the coins list.
		$coins = $client->get_available_coins( true );
		return ! is_wp_error( $coins );
	}

	/**
	 * Check if the coins list should be updated when the settings are saved.
	 *
	 * @filter update_option_mcc_general_settings 10 2
	 *
	 * @param array $old_value Settings stored before the update.
	 * @param array $new_value New settings to store.
	 */
	public function maybe_update_coins_list( array $old_value, array $new_value ): void {
		// Check if API source was changed.
		$old_source = $old_value['mcc_api_source'] ?? '';
		$new_source = $new_value['mcc_api_source'] ?? '';
		$require_update = $old_source !== $new_source;

		/**
		 * Allow clients to set if the coins list needs to be updated.
		 *
		 * @param
		 */
		$require_update = apply_filters(
			'mcc_require_coins_list_update_after_settings_saving',
			$require_update,
			$old_value,
			$new_value
		);

		if ( $require_update ) {
			$this->update_coins_list();
		}
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
			$selected_coins_ids = $this->get_selected_coins_ids();

			if ( is_wp_error( $available_coins ) ) {
				echo '<p>' . esc_html__( 'An error getting the coins has occurred.', 'multi-crypto-convert' ) . '</p>';
				return;
			}

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
			plugins_url( 'build/js/admin-settings.js', MCC_PLUGIN_FILE ),
			[ 'jquery' ],
			MCC_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'mcc-admin-settings',
			'mccSettings',
			[
				'maxCoins' => self::MAX_SELECTABLE_COINS,
			]
		);
	}
}
