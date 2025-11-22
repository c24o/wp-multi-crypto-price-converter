<?php
/**
 * Cache Interface
 *
 * @package Multi_Crypto_Convert\Cache
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Cache;

/**
 * Defines the contract for persistent key-value storage.
 */
interface Cache_Interface {
	/**
	 * Retrieves data from the cache by key.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @return Crypto_Price_Entity[]|null The cached data as an array, or null if not found.
	 */
	public function get( string $key ): ?array;

	/**
	 * Stores data in the cache under the specified key.
	 *
	 * @param string                $key The unique identifier for the cached data.
	 * @param Crypto_Price_Entity[] $data The data to cache as an array.
	 * @param int                   $expiration_seconds Optional expiration time in seconds (0 = no expiration).
	 * @return bool True on success, false on failure.
	 */
	public function set( string $key, array $data, int $expiration_seconds = 0 ): bool;
}
