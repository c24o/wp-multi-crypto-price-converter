<?php
/**
 * Coin Entity
 *
 * @package Multi_Crypto_Convert\Entities
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Entities;

/**
 * Represents an available cryptocurrency for selection (Value Object).
 */
final readonly class Coin_Entity {

	/**
	 * Constructor.
	 *
	 * @param string $api_id The unique identifier used by the external API.
	 * @param string $symbol The ticker symbol of the cryptocurrency.
	 * @param string $name The full name of the cryptocurrency.
	 */
	public function __construct(
		public string $api_id,
		public string $symbol,
		public string $name
	) {}

	/**
	 * Custom serialization to optimize storage.
	 *
	 * @return array The serialized representation of the object.
	 */
	public function __serialize(): array {
		return [
			'i' => $this->api_id,
			's' => $this->symbol,
			'n' => $this->name,
		];
	}

	/**
	 * Custom unserialization to restore the object state.
	 *
	 * @param array $data The serialized representation of the object.
	 */
	public function __unserialize( array $data ): void {
		$this->api_id = $data['i'] ?? '';
		$this->symbol = $data['s'] ?? '';
		$this->name   = $data['n'] ?? '';
	}
}
