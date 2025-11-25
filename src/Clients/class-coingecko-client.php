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
use Psr\SimpleCache\CacheInterface;

/**
 * Client implementation for the CoinGecko API.
 */
final class Coingecko_Client extends Abstract_Cached_API_Client {

	private const COINGECKO_API_URL = 'https://api.coingecko.com/api/v3/';
	private const PRICE_ENDPOIINT = 'simple/price';
	private const COIN_LIST_ENDPOINT = 'coins/list';
	private const DEFAULT_TIMEOUT_SECONDS = 10;

	/**
	 * Constructor.
	 *
	 * @param CacheInterface $cache The cache implementation.
	 */
	public function __construct(
		private CacheInterface $cache
	) {
		parent::__construct();
	}

	/**
	 * Get an slug identifying this API client.
	 *
	 * @return string The unique slug for this API client.
	 */
	protected function get_api_client_slug(): string {
		return 'coingecko';
	}

	/**
	 * Retrieves the cache implementation used by this client.
	 *
	 * @return CacheInterface The cache implementation.
	 */
	protected function get_cache_object(): CacheInterface {
		return $this->cache;
	}

	/**
	 * Returns the update interval data for scheduling.
	 *
	 * @return array The interval data.
	 * @phpstan-return array{interval: int, display: string}
	 */
	protected function get_prices_update_interval_data(): array {
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
	protected function fetch_prices(): array|\WP_Error {
		// TO-DO: Get the symbols from configuration or another source.
		$ids = [ 'bitcoin', 'ethereum' ];

		// Build and execute the request.
		$args = [
			'ids'           => implode( ',', $ids ), // it is possible to use symbols instead.
			'vs_currencies' => 'usd',
		];
		$url = add_query_arg( $args, self::COINGECKO_API_URL . self::PRICE_ENDPOIINT );
		$response = wp_remote_get( $url, [ 'timeout' => self::DEFAULT_TIMEOUT_SECONDS ] );

		// Error Checking (WordPress Errors).
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Error Checking (HTTP Status Code).
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $status_code ) {
			return new \WP_Error(
				'coingecko_api_error',
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
				'coingecko_api_error',
				sprintf(
					'Invalid or malformed JSON received from API(%s).',
					self::PRICE_ENDPOIINT
				)
			);
		}

		return $data;
	}

	/**
	 * Converts the raw API response into standardized internal entities.
	 *
	 * @param array $raw_data The unparsed response array from the API.
	 * @return Crypto_Price_Entity[] A list of standardized price entities.
	 */
	protected function transform_prices_to_entities( array $raw_data ): array {
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
	protected function fetch_coin_list(): array|\WP_Error {
		$url = self::COINGECKO_API_URL . self::COIN_LIST_ENDPOINT;
		$response = wp_remote_get( $url, [ 'timeout' => self::DEFAULT_TIMEOUT_SECONDS ] );

		// Error Checking (WordPress Errors).
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Error Checking (HTTP Status Code).
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $status_code ) {
			return new \WP_Error(
				'coingecko_api_error',
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
				'coingecko_api_error',
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
	protected function transform_coin_list_to_entities( array $raw_data ): array {
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
