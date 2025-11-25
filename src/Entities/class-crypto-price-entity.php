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
	 * @param int    $last_updated Unix timestamp of data fetch.
	 */
	public function __construct(
		public string $symbol,
		public float $price_usd,
		public int $last_updated
	) {}

	/**
	 * Called when the object is serialized.
	 *
	 * @return array The list of properties that should be serialized.
	 */
	public function __serialize(): array {
		return [
			's' => $this->symbol,
			'p' => $this->price_usd,
			'l' => $this->last_updated,
		];
	}

	/**
	 * Called when the object is unserialized.
	 *
	 * @param array $data The properties array created by __serialize.
	 */
	public function __unserialize( array $data ): void {
		// Use property assignment to restore the object state.
		$this->symbol       = $data['s'] ?? '';
		$this->price_usd    = (float) $data['p'] ?? 0.0;
		$this->last_updated = (int) $data['l'] ?? 0;
	}
}
