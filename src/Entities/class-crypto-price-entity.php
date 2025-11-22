<?php
/**
 * Crypto Price Entity
 *
 * @package Multi_Crypto_Convert\Entities
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Entities;

/**
 * Represents a single crypto price data point (a Value Object).
 */
final readonly class Crypto_Price_Entity {

	/**
	 * Constructor.
	 *
	 * @param string $symbol The coin symbol (e.g., 'BTC').
	 * @param float  $price_usd The current USD price.
	 * @param string $name The full coin name.
	 * @param int    $last_updated Unix timestamp of data fetch.
	 */
	public function __construct(
		public string $symbol,
		public float $price_usd,
		public string $name,
		public int $last_updated
	) {}
}
