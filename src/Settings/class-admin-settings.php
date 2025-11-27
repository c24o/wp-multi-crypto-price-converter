<?php
/**
 * Admin Settings
 *
 * @package Multi_Crypto_Convert\Settings
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Settings;

use Multi_Crypto_Convert\Clients\Crypto_Client_Factory;

/**
 * Manages the admin settings page for the Multi-Crypto Convert plugin.
 */
final class Admin_Settings {

	private const PAGE_SLUG = 'mcc_settings';
	private const REFRESH_COINS_ACTION = 'mcc_refresh_coins';
	private const OPTION_API_SOURCE = 'mcc_api_source';
	private const OPTION_API_KEY = 'mcc_api_key';

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
	 * Registers the settings fields and sections.
	 */
	public function register_settings(): void {
		register_setting( self::PAGE_SLUG, 'mcc_general_settings' );

		$api_section = 'mcc_api_settings_section';
		add_settings_section(
			$api_section,
			__( 'API Configuration', 'multi-crypto-convert' ),
			[ $this, 'render_api_settings_section' ],
			self::PAGE_SLUG
		);

		add_settings_field(
			'mcc_api_source',
			__( 'API Source', 'multi-crypto-convert' ),
			function () {
				$current_source = get_option( self::PAGE_SLUG, [] )[ self::OPTION_API_SOURCE ] ?? '';
				?>
				<select
					id="mcc_api_source"
					name="<?php printf( '%s[%s]', esc_attr( self::PAGE_SLUG ), esc_attr( self::OPTION_API_SOURCE ) ); ?>"
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

		add_settings_field(
			'mcc_api_key',
			__( 'API Key', 'multi-crypto-convert' ),
			function () {
				$current_api_key = get_option( self::PAGE_SLUG, [] )[ self::OPTION_API_KEY ] ?? '';
				?>
				<input
					type="password"
					id="mcc_api_key"
					name="<?php printf( '%s[%s]', esc_attr( self::PAGE_SLUG ), esc_attr( self::OPTION_API_KEY ) ); ?>"
					value="<?php echo esc_attr( $current_api_key ); ?>"
					class="regular-text"
				/>
				<p class="description">
					<?php esc_html_e( 'Enter your API key for the selected cryptocurrency data source.', 'multi-crypto-convert' ); ?>
				</p>
				<?php
			},
			self::PAGE_SLUG,
			$api_section
		);
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
			$current_source = get_option( self::PAGE_SLUG, [] )[ self::OPTION_API_SOURCE ] ?? '';
			$client = $this->factory->create( $current_source );
			$coins = $client->get_available_coins();
			$coin_count = count( $coins );
		} catch ( \Exception $e ) {
			$coin_count = 0;
		}

		// Add error/update messages.
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
				<?php esc_html_e( 'Cached coins:', 'multi-crypto-convert' ); ?>
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
			$source = get_option( self::PAGE_SLUG, [] )[ self::OPTION_API_SOURCE ] ?? '';
			if ( ! is_string( $source ) || empty( $source ) ) {
				wp_send_json_error(
					[
						'message' => __( 'API source is not configured.', 'multi-crypto-convert' ),
					]
				);
			}

			// Create client instance from factory.
			$client = $this->factory->create( $source );

			// Fetch and cache the coins list.
			$coins = $client->get_available_coins( true );

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
