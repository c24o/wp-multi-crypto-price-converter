<?php
/**
 * Crypto Option Cache
 *
 * @package Multi_Crypto_Convert\Cache
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Cache;

/**
 * Implements the Cache interface using the WordPress Options API.
 * This ensures data persistence across page loads (not just within a request)
 * and that it is not expired.
 */
final class Crypto_Option_Cache implements Cache_Interface {

	// Prefix for the option key to prevent conflicts with other plugins.
	private const KEY_PREFIX = 'mcc_cache_';

	/**
	 * Retrieves data from the cache by key.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @return Crypto_Price_Entity[]|null The cached data as an array, or null
	 * if not found.
	 */
	public function get( string $key ): ?array {
		$data = get_option( self::KEY_PREFIX . $key, null );
		return is_array( $data ) ? $data : null;
	}

	/**
	 * Stores data in the cache under the specified key.
	 *
	 * @param string                $key The unique identifier for the cached data.
	 * @param Crypto_Price_Entity[] $data The data to cache as an array.
	 * @param int                   $expiration_seconds Optional expiration time in seconds (0 = no expiration).
	 * @return bool True on success, false on failure.
	 */
	public function set( string $key, array $data, int $expiration_seconds = 0 ): bool {
		return update_option( self::KEY_PREFIX . $key, $data, 'no' );
	}
}
