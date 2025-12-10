<?php
/**
 * Crypto Client Factory
 *
 * @package Multi_Crypto_Convert\Clients
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Clients;

use Psr\SimpleCache\CacheInterface;

/**
 * Factory for creating cryptocurrency API client instances.
 *
 * This factory encapsulates the logic for instantiating the appropriate
 * crypto client based on the requested source. It receives the cache
 * dependency and injects it into each client instance.
 */
final class Crypto_Client_Factory {

	/**
	 * Constructor.
	 *
	 * @param CacheInterface $cache The cache implementation to inject into clients.
	 */
	public function __construct(
		private CacheInterface $cache
	) {}

	/**
	 * Creates and returns a crypto API client instance for the specified source.
	 *
	 * @param string $source The cryptocurrency data source identifier.
	 * @param array<string, mixed> $settings Admin settings values.
	 * @param string[] $tracked_symbols List of tracked cryptocurrency symbols.
	 * @return Crypto_API_Client An instance of the requested crypto client.
	 *
	 * @throws \InvalidArgumentException If the source is not supported.
	 */
	public function create( string $source, array $settings, array $tracked_symbols ): Crypto_API_Client {
		return match ( $source ) {
			'coingecko' => new Coingecko_Client( $this->cache, $settings, $tracked_symbols ),
			default => throw new \InvalidArgumentException(
				esc_html(
					sprintf(
						// translators: %s is the unsupported source identifier.
						__( 'Unsupported crypto source: %s', 'multi-crypto-convert' ),
						$source
					)
				)
			),
		};
	}

	/**
	 * Returns the list of available cryptocurrency data sources.
	 *
	 * @return array<string, string> An associative array where keys are source identifiers
	 * and values are human-readable source names.
	 */
	public function get_available_sources(): array {
		return [
			'coingecko' => 'CoinGecko',
		];
	}

	/**
	 * Checks if a cryptocurrency data source is supported.
	 *
	 * @param string $source The source identifier to check.
	 * @return bool True if the source is supported, false otherwise.
	 */
	public function is_source_supported( string $source ): bool {
		return isset( $this->get_available_sources()[ $source ] );
	}
}
