<?php
/**
 * Abstract Cached API Client
 *
 * @package Multi_Crypto_Convert\Clients
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Clients;

use Psr\SimpleCache\CacheInterface;
use Multi_Crypto_Convert\Entities\Crypto_Price_Entity;
use Multi_Crypto_Convert\Entities\Coin_Entity;

/**
 * Abstract Base Class for all Crypto API Clients.
 * Manages caching, scheduling, and standard retrieval methods.
 */
abstract class Abstract_Cached_API_Client implements Crypto_API_Client {

	protected const CRON_HOOK = 'mcc_fetch_prices_cron';
	protected const PRICE_CACHE_KEY = 'prices';
	protected const COIN_LIST_CACHE_KEY = 'coin_list';

	/**
	 * Constructor.
	 *
	 * Set up WP-Cron for price updates.
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'update_prices_schedule' ] );
		add_action( $this->get_cron_hook(), [ $this, 'fetch_and_cache_prices' ] );
	}

	/**
	 * Returns the unique cache key for this client's price data.
	 *
	 * @return string The cache key.
	 */
	protected function get_prices_cache_key(): string {
		return sprintf( '%s_%s', $this->get_api_client_slug(), self::PRICE_CACHE_KEY );
	}

	/**
	 * Returns the unique cache key for this client's coin list data.
	 *
	 * @return string The cache key.
	 */
	protected function get_coin_list_cache_key(): string {
		return sprintf( '%s_%s', $this->get_api_client_slug(), self::COIN_LIST_CACHE_KEY );
	}

	/**
	 * Returns the unique cron hook name for this client's scheduled tasks.
	 *
	 * @return string The cron hook name.
	 */
	protected function get_cron_hook(): string {
		return sprintf( '%s_%s', self::CRON_HOOK, $this->get_api_client_slug() );
	}

	/**
	 * Retrieves the cached price entities.
	 *
	 * @param string[]|null $coins Optional list of coin symbols to filter
	 * by. If null or empty, returns all cached prices.
	 * @return Crypto_Price_Entity[]
	 */
	public function get_prices( ?array $coins = null ): array {
		// Retrieve the data array from the cache, defaulting to an empty array if not found.
		$cached_data = $this->get_cache_object()->get( $this->get_prices_cache_key(), [] );
		if ( ! is_array( $cached_data ) ) {
			return [];
		}

		// If no specific coin IDs are provided, return all cached prices.
		if ( empty( $coins ) ) {
			return $cached_data;
		}

		// Filter the cached data to include only the requested coin IDs.
		return array_values(
			array_filter(
				$cached_data,
				function ( Crypto_Price_Entity $entity ) use ( $coins ) {
					return in_array( strtolower( $entity->symbol ), array_map( 'strtolower', $coins ), true );
				}
			)
		);
	}

	/**
	 * Sets up the necessary WP-Cron schedule to periodically update prices.
	 */
	public function update_prices_schedule(): void {
		if ( ! wp_next_scheduled( $this->get_cron_hook() ) ) {
			wp_schedule_event( time(), 'mcc_interval', $this->get_cron_hook() );
		}
	}

	/**
	 * The core cron job method: Fetches prices, transforms them, and caches them.
	 */
	public function fetch_and_cache_prices(): void {
		// Execute the fetching logic.
		$raw_data = $this->fetch_prices();

		// Transform the raw data into our standard Entity objects.
		$entities = $this->transform_prices_to_entities( $raw_data );

		// Cache the entities.
		$this->get_cache_object()->set( $this->get_prices_cache_key(), $entities );
	}

	/**
	 * Hooks into WordPress to add a custom 15-minute cron interval.
	 *
	 * @param array $schedules Existing cron schedules.
	 * @return array Modified cron schedules.
	 */
	public function add_custom_cron_interval( $schedules ): array {
		// Get interval data from the concrete client.
		$interval_data = $this->get_prices_update_interval_data();

		$schedules[ 'mcc_interval_' . $this->get_api_client_slug() ] = [
			'interval' => $interval_data['interval'],
			'display'  => $interval_data['display'],
		];
		return $schedules;
	}

	/**
	 * Retrieves the cached list of supported coins from the API.
	 *
	 * This method can optionally force a cache update before retrieving the
	 * list because there is not a scheduled update for coin lists.
	 *
	 * @param bool $force_cache_update Whether to force a cache update before.
	 * @return Coin_Entity[] The list of supported coins.
	 */
	public function get_available_coins( bool $force_cache_update = false ): array {
		$cached_data = $this->get_cache_object()->get( $this->get_coin_list_cache_key(), [] );
		return is_array( $cached_data ) ? $cached_data : [];
	}

	/**
	 * Fetches the list of supported coins from the API and caches it.
	 */
	public function fetch_and_cache_coins(): void {
		// Execute the fetching logic.
		$raw_data = $this->fetch_coin_list();

		// Transform the raw data into our standard Entity objects.
		$entities = $this->transform_coin_list_to_entities( $raw_data );

		// Cache the entities.
		$this->get_cache_object()->set( $this->get_coin_list_cache_key(), $entities );
	}

	/**
	 * Get an slug identifying this API client.
	 *
	 * @return string The unique slug for this API client.
	 */
	abstract public function get_api_client_slug(): string;

	/**
	 * Retrieves the cache implementation used by this client.
	 *
	 * @return CacheInterface The cache implementation.
	 */
	abstract public function get_cache_object(): CacheInterface;

	/**
	 * Returns the update interval data for scheduling.
	 *
	 * @return array The interval data.
	 * @phpstan-return array{interval: int, display: string}
	 */
	abstract public function get_prices_update_interval_data(): array;

	/**
	 * Executes the raw HTTP request to the external API.
	 *
	 * @return array|\WP_Error The raw decoded response array from the API or an
	 * error if the API call fails.
	 */
	abstract public function fetch_prices(): array|\WP_Error;

	/**
	 * Converts the raw API response into standardized internal entities.
	 *
	 * @param array $raw_data The unparsed response array from the API.
	 * @return Crypto_Price_Entity[] A list of standardized price entities.
	 */
	abstract public function transform_prices_to_entities( array $raw_data ): array;

	/**
	 * Retrieves the raw coin list from the external API.
	 *
	 * @return array|\WP_Error The raw decoded coin list response array from the
	 * API or an error if the API call fails.
	 */
	abstract public function fetch_coin_list(): array|\WP_Error;

	/**
	 * Converts the raw API coin list response into standardized Coin_Entity objects.
	 *
	 * @param array $raw_data The unparsed coin list response array from the API.
	 * @return Coin_Entity[] A list of standardized coin entities.
	 */
	abstract public function transform_coin_list_to_entities( array $raw_data ): array;
}
