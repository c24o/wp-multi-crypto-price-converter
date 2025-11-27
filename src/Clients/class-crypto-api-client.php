<?php
/**
 * Crypto API Client Interface
 *
 * @package Multi_Crypto_Convert\Clients
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Clients;

use Multi_Crypto_Convert\Entities\Coin_Entity;
use Multi_Crypto_Convert\Entities\Crypto_Price_Entity;

/**
 * Defines the contract for any class responsible for fetching external crypto
 * price data.
 */
interface Crypto_API_Client {

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
	 * @return Coin_Entity[] The list of supported coins.
	 */
	public function get_available_coins( bool $force_cache_update = false ): array;
}
