<?php
/**
 * CoinGecko Client
 *
 * @package Multi_Crypto_Convert\Clients
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Clients;

use Multi_Crypto_Convert\Entities\Coin_Entity;
use Multi_Crypto_Convert\Entities\Crypto_Price_Entity;
use Multi_Crypto_Convert\Settings\Admin_Settings;
use Psr\SimpleCache\CacheInterface;

/**
 * Client implementation for the CoinGecko API.
 */
final class Coingecko_Client extends Abstract_Cached_API_Client {

	private const COINGECKO_API_URL = 'https://api.coingecko.com/api/v3/';
	private const PRICE_ENDPOIINT = 'simple/price';
	private const COIN_LIST_ENDPOINT = 'coins/list';
	private const DEFAULT_TIMEOUT_SECONDS = 10;
	private const MAX_SYMBOLS_PER_REQUEST = 50;
	public const API_KEY_FIELD = 'coingecko_api_key';
	public const API_KEY_TYPE_FIELD = 'coingecko_api_key_type';
	public const DEFAULT_API_KEY_TYPE = 'demo';

	/**
	 * Constructor.
	 *
	 * @param CacheInterface $cache The cache implementation.
	 * @param array<string, mixed> $settings Admin settings values.
	 * @param string[] $tracked_coins_ids List of tracked cryptocurrency symbols.
	 */
	public function __construct(
		private CacheInterface $cache,
		private array $settings = [],
		private array $tracked_coins_ids = []
	) {
		parent::__construct();
	}

	/**
	 * Register the object methods to different WP hooks.
	 *
	 * This method should be used before the hooks are fired.
	 */
	public function register_hooks(): void {
		add_action(
			'mcc_register_client_settings_fields',
			[ $this, 'register_settings_fields' ],
			10,
			2
		);
		add_filter(
			'mcc_sanitize_client_settings',
			[ $this, 'sanitize_settings_fields' ],
			10,
			2
		);
		add_filter(
			'mcc_require_coins_list_update_after_settings_saving',
			[ $this, 'should_update_coins_list' ],
			10,
			3
		);
	}

	/**
	 * Register the settings fields used to connect to the Coingecko API.
	 *
	 * Register the API key field and the type of API key field.
	 *
	 * @param string $page_slug The settings page slug.
	 * @param string $section_id The settings section ID.
	 *
	 * @return void
	 */
	public function register_settings_fields( string $page_slug, string $section_id ): void {
		add_settings_field(
			self::API_KEY_FIELD,
			__( 'API Key', 'multi-crypto-convert' ),
			function () {
				$current_api_key = $this->settings[ self::API_KEY_FIELD ] ?? '';
				?>
				<input
					type="text"
					id="mcc_api_key"
					name="<?php printf( '%s[%s]', esc_attr( Admin_Settings::SETTINGS_OPTION_NAME ), esc_attr( self::API_KEY_FIELD ) ); ?>"
					value="<?php echo esc_attr( $current_api_key ); ?>"
					class="regular-text"
				/>
				<p class="description">
					<?php esc_html_e( 'Enter your API key for the selected cryptocurrency data source.', 'multi-crypto-convert' ); ?>
				</p>
				<?php
			},
			$page_slug,
			$section_id
		);

		add_settings_field(
			self::API_KEY_TYPE_FIELD,
			__( 'API Key Type', 'multi-crypto-convert' ),
			function () {
				$current_api_key_type = $this->settings[ self::API_KEY_TYPE_FIELD ] ?? self::DEFAULT_API_KEY_TYPE;
				?>
				<select
					id="mcc_api_key_type"
					name="<?php printf( '%s[%s]', esc_attr( Admin_Settings::SETTINGS_OPTION_NAME ), esc_attr( self::API_KEY_TYPE_FIELD ) ); ?>"
					class="regular-text"
				>
					<?php foreach ( $this->get_api_key_types() as $value => $label ) : ?>
						<option
							value="<?php echo esc_attr( $value ); ?>"
							<?php selected( $current_api_key_type, $value ); ?>
						>
							<?php echo esc_html( $label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				<p class="description">
					<?php esc_html_e( 'Select the type of API key you are using.', 'multi-crypto-convert' ); ?>
				</p>
				<?php
			},
			$page_slug,
			$section_id
		);
	}

	/**
	 * Sanitize the settings fields for this API client.
	 *
	 * @param array $sanitized The sanitized settings array.
	 * @param array $input The original input settings array.
	 * @return array The sanitized settings array.
	 */
	public function sanitize_settings_fields( array $sanitized, array $input ): array {
		$sanitized[ self::API_KEY_FIELD ] = sanitize_text_field( $input[ self::API_KEY_FIELD ] ?? '' );
		$sanitized[ self::API_KEY_TYPE_FIELD ] = isset( $input[ self::API_KEY_TYPE_FIELD ] ) && in_array(
			$input[ self::API_KEY_TYPE_FIELD ],
			$this->get_api_key_types(),
			true
		) ? $input[ self::API_KEY_TYPE_FIELD ] : self::DEFAULT_API_KEY_TYPE;

		return $sanitized;
	}

	/**
	 * Get the available API key types for this client.
	 *
	 * @return array<string, string> The available API key types.
	 */
	public function get_api_key_types(): array {
		return [
			'demo' => __( 'Demo', 'multi-crypto-convert' ),
			'paid' => __( 'Paid', 'multi-crypto-convert' ),
		];
	}

	/**
	 * Check if the coins list should be updated when the settings are saved.
	 *
	 * @filter mcc_require_coins_list_update_after_settings_saving 10 2
	 *
	 * @param bool $require_update True if the coins list is going to be updated.
	 * @param array $old_value Settings stored before the update.
	 * @param array $new_value New settings to store.
	 * @return bool True if the coins list should be updated, otherwise false.
	 */
	public function should_update_coins_list( bool $require_update, array $old_value, array $new_value ): bool {
		$old_api_key = $old_value[ self::API_KEY_FIELD ] ?? '';
		$new_api_key = $new_value[ self::API_KEY_FIELD ] ?? '';
		return $require_update || $old_api_key !== $new_api_key;
	}

	/**
	 * Get an slug identifying this API client.
	 *
	 * @return string The unique slug for this API client.
	 */
	public function get_api_client_slug(): string {
		return 'coingecko';
	}

	/**
	 * Retrieves the cache implementation used by this client.
	 *
	 * @return CacheInterface The cache implementation.
	 */
	public function get_cache_object(): CacheInterface {
		return $this->cache;
	}

	/**
	 * Returns the update interval data for scheduling.
	 *
	 * @return array The interval data.
	 * @phpstan-return array{interval: int, display: string}
	 */
	public function get_prices_update_interval_data(): array {
		return [
			'interval' => 900, // 15 minutes due to the rate limit for the free tier.
			'display'  => __( 'Every 15 Minutes', 'multi-crypto-convert' ),
		];
	}

	/**
	 * Executes the raw HTTP request to the CoinGecko API.
	 *
	 * @return array|\WP_Error The raw decoded response array from the API or an
	 * error if the API call fails.
	 */
	public function fetch_prices(): array|\WP_Error {
		// Check API key.
		if ( empty( $this->settings[ self::API_KEY_FIELD ] ) ) {
			return new \WP_Error(
				'coingecko_api_error_missing_key',
				__(
					'CoinGecko API key is missing. Please provide a valid API key in the plugin settings.',
					'multi-crypto-convert'
				)
			);
		}

		// Get the symbols to fetch from settings.
		$symbols = array_column(
			array_filter(
				$this->get_available_coins(),
				fn( Coin_Entity $coin ) => in_array( $coin->api_id, $this->tracked_coins_ids, true )
			),
			'symbol'
		);

		// Only 50 symbols are allowed per request in CoinGecko API.
		$prices = [];
		$batches = ceil( count( $symbols ) / self::MAX_SYMBOLS_PER_REQUEST );
		for ( $i = 0; $i < $batches; $i++ ) {
			$symbols = array_slice( $symbols, $i * self::MAX_SYMBOLS_PER_REQUEST, self::MAX_SYMBOLS_PER_REQUEST );

			// Build and execute the request.
			$args = [
				'symbols'       => implode( ',', $symbols ),
				'vs_currencies' => 'usd',
			];
			$url = add_query_arg( $args, self::COINGECKO_API_URL . self::PRICE_ENDPOIINT );
			$api_key_header = isset( $settings[ self::API_KEY_TYPE_FIELD ] ) && 'demo' === $this->settings[ self::API_KEY_TYPE_FIELD ]
				? 'x_cg_pro_api_key'
				: 'x-cg-demo-api-key';
			$response = wp_remote_get(
				$url,
				[
					'timeout' => self::DEFAULT_TIMEOUT_SECONDS,
					'headers' => [
						$api_key_header => $this->settings[ self::API_KEY_FIELD ],
					],
				]
			);

			// Error Checking (WordPress Errors).
			if ( is_wp_error( $response ) ) {
				return $response;
			}

			// Error Checking (HTTP Status Code).
			$status_code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $status_code ) {
				return new \WP_Error(
					'coingecko_api_error_wrong_status',
					sprintf(
						'External API(%s) returned non-200 status code: %s.',
						self::PRICE_ENDPOIINT,
						$status_code
					)
				);
			}

			// Decode and return data.
			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $data ) ) {
				return new \WP_Error(
					'coingecko_api_error_malformed_json',
					sprintf(
						'Invalid or malformed JSON received from API(%s).',
						self::PRICE_ENDPOIINT
					)
				);
			}

			$prices = array_merge( $prices, $data );
		}

		return $prices;
	}

	/**
	 * Converts the raw API response into standardized internal entities.
	 *
	 * @param array $raw_data The unparsed response array from the API.
	 * @return Crypto_Price_Entity[] A list of standardized price entities.
	 */
	public function transform_prices_to_entities( array $raw_data ): array {
		$entities = [];

		/**
		 * When the request is done filtering by symbols, then the response
		 * is index by symbols instead by ids.
		 */
		foreach ( $raw_data as $symbol => $data ) {
			$entities[] = new Crypto_Price_Entity(
				symbol       : $symbol,
				price_usd    : (float) ( $data['usd'] ?? 0.0 ),
				last_updated : $data['last_updated_at'] ?? time()
			);
		}

		return $entities;
	}

	/**
	 * Retrieves the raw coin list from the external API.
	 *
	 * @return array|\WP_Error The raw decoded coin list response array from the
	 * API or an error if the API call fails.
	 */
	public function fetch_coin_list(): array|\WP_Error {
		// Check API key.
		if ( empty( $this->settings[ self::API_KEY_FIELD ] ) ) {
			return new \WP_Error(
				'coingecko_api_error_missing_key',
				__(
					'CoinGecko API key is missing. Please provide a valid API key in the plugin settings.',
					'multi-crypto-convert'
				)
			);
		}

		$url = self::COINGECKO_API_URL . self::COIN_LIST_ENDPOINT;
		$api_key_header = isset( $settings[ self::API_KEY_TYPE_FIELD ] ) && 'demo' === $this->settings[ self::API_KEY_TYPE_FIELD ]
			? 'x_cg_pro_api_key'
			: 'x-cg-demo-api-key';
		$response = wp_remote_get(
			$url,
			[
				'timeout' => self::DEFAULT_TIMEOUT_SECONDS,
				'headers' => [
					$api_key_header => $this->settings[ self::API_KEY_FIELD ],
				],
			]
		);

		// Error Checking (WordPress Errors).
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Error Checking (HTTP Status Code).
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $status_code ) {
			return new \WP_Error(
				'coingecko_api_error_wrong_status',
				sprintf(
					'External API(%s) returned non-200 status code: %s.',
					self::COIN_LIST_ENDPOINT,
					$status_code
				)
			);
		}

		// Decode and return data.
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( json_last_error() !== JSON_ERROR_NONE || ! is_array( $data ) ) {
			return new \WP_Error(
				'coingecko_api_error_malformed_json',
				sprintf(
					'Invalid or malformed JSON received from API(%s).',
					self::COIN_LIST_ENDPOINT
				)
			);
		}

		return $data;
	}

	/**
	 * Converts the raw API coin list response into standardized Coin_Entity objects.
	 *
	 * @param array $raw_data The unparsed coin list response array from the API.
	 * @return Coin_Entity[] A list of standardized coin entities.
	 */
	public function transform_coin_list_to_entities( array $raw_data ): array {
		$entities = [];
		foreach ( $raw_data as $coin ) {
			if ( isset( $coin['id'], $coin['symbol'], $coin['name'] ) ) {
				$entities[] = new Coin_Entity(
					$coin['id'],
					$coin['symbol'],
					$coin['name']
				);
			}
		}
		return $entities;
	}

	/**
	 * Some API services might require attribution for usage.
	 *
	 * @return bool True if attribution is required, otherwise false.
	 */
	public function is_attribution_required(): bool {
		return self::DEFAULT_API_KEY_TYPE === $this->settings[ self::API_KEY_TYPE_FIELD ];
	}

	/**
	 * Render the attribution for usage of the API.
	 */
	public function render_attribution_content(): void {
		?>
		<p>Data provided by <a href="https://www.coingecko.com/" target="_blank" rel="noopener noreferrer">CoinGecko</a></p>
		<?php
	}
}
