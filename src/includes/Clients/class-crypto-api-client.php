<?php
/**
 * Crypto API Client Interface
 *
 * @package Multi_Crypto_Price_Converter\Clients
 */

declare( strict_types=1 );

namespace Multi_Crypto_Price_Converter\Clients;

use Multi_Crypto_Price_Converter\Entities\Coin_Entity;
use Multi_Crypto_Price_Converter\Entities\Crypto_Price_Entity;
use WP_Error;

/**
 * Defines the contract for any class responsible for fetching external crypto
 * price data.
 */
interface Crypto_API_Client {

	/**
	 * Register the object methods to different WP hooks.
	 *
	 * This method is fired before the init hook if the client is setup.
	 */
	public function register_hooks(): void;

	/**
	 * Retrieves the cached price entities.
	 *
	 * @param string[]|null $coins Optional list of coin symbols to filter
	 * by. If null or empty, returns all cached prices.
	 * @return Crypto_Price_Entity[]
	 */
	public function get_prices( ?array $coins = null ): array;

	/**
	 * Retrieves the cached list of supported coins from the API.
	 *
	 * This method can optionally force a cache update before retrieving the
	 * list because there is not a scheduled update for coin lists.
	 *
	 * @param bool $force_cache_update Whether to force a cache update before.
	 * @return Coin_Entity[]|WP_Error The list of supported coins or an error if
	 * the cache needs to be updated and the request fails.
	 */
	public function get_available_coins( bool $force_cache_update = false ): array|WP_Error;

	/**
	 * Some API services might require attribution for usage.
	 *
	 * @return bool True if attribution is required, otherwise false.
	 */
	public function is_attribution_required(): bool;

	/**
	 * Render the attribution for usage of the API.
	 */
	public function render_attribution_content(): void;

	/**
	 * Sets up the necessary WP-Cron task to periodically update prices.
	 */
	public function schedule_update_prices(): void;

	/**
	 * Get the scheduled time to run the next update of prices.
	 *
	 * @return int The Unix timestamp to run the next update or false if there
	 * isn't an update scheduled.
	 */
	public function get_next_update_prices_scheduled(): int|bool;

	/**
	 * Returns the update interval data for scheduling.
	 *
	 * @return array The interval data.
	 * @phpstan-return array{interval: int, display: string}
	 */
	public function get_prices_update_interval_data(): array;
}
