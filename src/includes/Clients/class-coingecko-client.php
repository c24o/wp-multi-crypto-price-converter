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
	public const API_KEY_FIELD = 'coingecko_api_key';
	public const API_KEY_TYPE_FIELD = 'coingecko_api_key_type';

	/**
	 * Constructor.
	 *
	 * @param CacheInterface $cache The cache implementation.
	 * @param array<string, mixed> $settings Admin settings values.
	 * @param string[] $tracked_symbols List of tracked cryptocurrency symbols.
	 */
	public function __construct(
		private CacheInterface $cache,
		private array $settings = [],
		private array $tracked_symbols = []
	) {
		parent::__construct();
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
				$current_api_key = Admin_Settings::get_settings()[ self::API_KEY_FIELD ] ?? '';
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
				$current_api_key_type = Admin_Settings::get_settings()[ self::API_KEY_TYPE_FIELD ] ?? 'demo';
				$types = [
					'demo' => __( 'Demo', 'multi-crypto-convert' ),
					'paid' => __( 'Paid', 'multi-crypto-convert' ),
				];
				?>
				<select
					id="mcc_api_key_type"
					name="<?php printf( '%s[%s]', esc_attr( Admin_Settings::SETTINGS_OPTION_NAME ), esc_attr( self::API_KEY_TYPE_FIELD ) ); ?>"
					class="regular-text"
				>
					<?php foreach ( $types as $value => $label ) : ?>
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
		$symbols = $this->tracked_symbols;

		// Only 50 symbols are allowed per request in CoinGecko API.
		$prices = [];
		$batches = ceil( count( $symbols ) / 50.0 );
		for ( $i = 0; $i < $batches; $i++ ) {
			$symbols = array_slice( $symbols, $i * 50, 50 );

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
}
