<?php
/**
 * Crypto Option Cache
 *
 * @package Multi_Crypto_Convert\Cache
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Cache;

use Psr\SimpleCache\CacheInterface;
use DateInterval;

/**
 * Implements the Cache interface using the WordPress Options API.
 *
 * This ensures data persistence across page loads (not just within a request)
 * and that it is not expired.
 */
final class Crypto_Option_Cache implements CacheInterface {

	// The option key used to store the cached data.
	private const OPTION_KEY = 'mcc_cache';

	/**
	 * Validates if the cache key follows PSR-16 standard.
	 *
	 * @param string $key The cache key to validate.
	 * @return bool True if the key is valid, false otherwise.
	 */
	private function is_key_valid( string $key ): bool {
		if ( empty( $key ) ) {
			return false;
		}

		// The regular expression checks for the presence of any of the forbidden characters.
		if ( preg_match( '/[\{\}\(\)\/\@\:]/', $key ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Retrieves data from the cache by key.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @param mixed  $default Optional default value to return if the key does not exist.
	 * @return mixed The cached data or null if not found.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the $key string is not a legal value.
	 */
	public function get( string $key, mixed $default = null ): mixed { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.defaultFound -- Used to follow PSR-16 spec.
		if ( ! $this->is_key_valid( $key ) ) {
			throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
		}

		$cache = get_option( self::OPTION_KEY, [] );
		return isset( $cache[ $key ] ) ? $cache[ $key ] : $default;
	}

	/**
	 * Stores data in the cache with a specified key.
	 *
	 * @param string                $key The unique identifier for the cached data.
	 * @param mixed                 $value The data to be cached.
	 * @param null|int|DateInterval $ttl Optional time-to-live for the cached data (ignored).
	 * @return bool True on success, false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the $key string is not a legal value.
	 */
	public function set( string $key, mixed $value, null|int|DateInterval $ttl = null ): bool {
		if ( ! $this->is_key_valid( $key ) ) {
			throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
		}

		// In this specific project, we IGNORE the $ttl parameter
		// because cache expiry is handled externally by our WP-Cron job.
		$cache = get_option( self::OPTION_KEY, [] );
		$cache[ $key ] = $value;
		return update_option( self::OPTION_KEY, $cache, 'no' );
	}

	/**
	 * Deletes data from the cache by key.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @return bool True on success, false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the $key string is not a legal value.
	 */
	public function delete( string $key ): bool {
		if ( ! $this->is_key_valid( $key ) ) {
			throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
		}

		$cache = get_option( self::OPTION_KEY, [] );
		if ( isset( $cache[ $key ] ) ) {
			unset( $cache[ $key ] );
			update_option( self::OPTION_KEY, $cache, 'no' );
		}

		return false;
	}

	/**
	 * Checks if a cache entry exists for the given key.
	 *
	 * @param string $key The unique identifier for the cached data.
	 * @return bool True if the cache entry exists, false otherwise.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if the $key string is not a legal value.
	 */
	public function has( string $key ): bool {
		if ( ! $this->is_key_valid( $key ) ) {
			throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
		}

		$cache = get_option( self::OPTION_KEY, [] );
		return isset( $cache[ $key ] );
	}

	/**
	 * Retrieves multiple cache entries by their keys.
	 *
	 * @param iterable $keys An array or iterable of unique identifiers for the cached data.
	 * @param mixed    $default Optional default value to return if a key does not exist.
	 * @return iterable An array of keys to get.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if any of the $keys are not a legal value.
	 */
	public function getMultiple( iterable $keys, mixed $default = null ): iterable { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.defaultFound -- Used to follow PSR-16 spec.
		$results = array();
		$cache = get_option( self::OPTION_KEY, [] );
		foreach ( $keys as $key ) {
			if ( ! $this->is_key_valid( $key ) ) {
				throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
			}
			// Re-use the single-item get method.
			$results[ $key ] = isset( $cache[ $key ] ) ? $cache[ $key ] : $default;
		}
		return $results;
	}

	/**
	 * Stores multiple cache entries.
	 *
	 * @param iterable              $values An array or iterable of key => value pairs to cache.
	 * @param null|int|DateInterval $ttl Optional time-to-live for the cached data (ignored).
	 * @return bool True on success, false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if any of the $keys are not a legal value.
	 */
	public function setMultiple( iterable $values, null|int|DateInterval $ttl = null ): bool {
		$cache = get_option( self::OPTION_KEY, [] );
		foreach ( $values as $key => $value ) {
			if ( ! $this->is_key_valid( $key ) ) {
				throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
			}
			// Re-use the single-item set method.
			$cache[ $key ] = $value;
		}
		return update_option( self::OPTION_KEY, $cache, 'no' );
	}

	/**
	 * Deletes multiple cache entries by their keys.
	 *
	 * @param iterable $keys An array or iterable of unique identifiers for the cached data.
	 * @return bool True on success, false on failure.
	 *
	 * @throws \Psr\SimpleCache\InvalidArgumentException MUST be thrown if any of the $keys are not a legal value.
	 */
	public function deleteMultiple( iterable $keys ): bool {
		$cache = get_option( self::OPTION_KEY, [] );
		foreach ( $keys as $key ) {
			if ( ! $this->is_key_valid( $key ) ) {
				throw new \Psr\SimpleCache\InvalidArgumentException( 'Invalid cache key provided.' );
			}
			if ( isset( $cache[ $key ] ) ) {
				unset( $cache[ $key ] );
			}
		}
		return update_option( self::OPTION_KEY, $cache, 'no' );
	}

	/**
	 * Clears the entire cache.
	 *
	 * There is no implementation for this method since WordPress Options API
	 *
	 * @return bool True on success, false on failure.
	 */
	public function clear(): bool {
		return delete_option( self::OPTION_KEY );
	}
}
