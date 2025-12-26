<?php
/**
 * Mock implementation of Abstract_Cached_API_Client for testing.
 *
 * @package Multi_Crypto_Price_Converter/Tests/Clients
 */

declare( strict_types=1 );

namespace Tests\Multi_Crypto_Price_Converter\Clients;

use Multi_Crypto_Price_Converter\Clients\Abstract_Cached_API_Client;
use Multi_Crypto_Price_Converter\Entities\Crypto_Price_Entity;
use Multi_Crypto_Price_Converter\Entities\Coin_Entity;
use Psr\SimpleCache\CacheInterface;
use WP_Error;

/**
 * Mock implementation of Abstract_Cached_API_Client for testing.
 */
class Mock_Cached_API_Client extends Abstract_Cached_API_Client {

	/**
	 * The cache object.
	 *
	 * @var CacheInterface
	 */
	private CacheInterface $cache;

	/**
	 * Mock data for fetch_prices().
	 *
	 * @var array|WP_Error|null
	 */
	private array|WP_Error|null $mock_prices = null;

	/**
	 * Mock data for fetch_coin_list().
	 *
	 * @var array|WP_Error|null
	 */
	private array|WP_Error|null $mock_coins = null;

	/**
	 * Constructor.
	 *
	 * @param CacheInterface $cache The cache object.
	 */
	public function __construct( CacheInterface $cache ) {
		$this->cache = $cache;
		parent::__construct();
	}

	/**
	 * Set mock prices for testing.
	 *
	 * @param array|WP_Error $data The mock data.
	 */
	public function set_mock_prices( array|WP_Error $data ): void {
		$this->mock_prices = $data;
	}

	/**
	 * Set mock coins for testing.
	 *
	 * @param array|WP_Error $data The mock data.
	 */
	public function set_mock_coins( array|WP_Error $data ): void {
		$this->mock_coins = $data;
	}

	/**
	 * Set mock error for testing.
	 *
	 * @param WP_Error $error The error.
	 */
	public function set_mock_error( WP_Error $error ): void {
		$this->mock_prices = $error;
		$this->mock_coins  = $error;
	}

	/**
	 * Get API client slug.
	 *
	 * @return string The slug.
	 */
	public function get_api_client_slug(): string {
		return 'test_api';
	}

	/**
	 * Get cache object.
	 *
	 * @return CacheInterface The cache.
	 */
	public function get_cache_object(): CacheInterface {
		return $this->cache;
	}

	/**
	 * Get prices update interval data.
	 *
	 * @return array The interval data.
	 */
	public function get_prices_update_interval_data(): array {
		return [
			'interval' => 15 * MINUTE_IN_SECONDS,
			'display'  => 'Every 15 Minutes',
		];
	}

	/**
	 * Fetch prices from API.
	 *
	 * @return array|WP_Error The prices.
	 */
	public function fetch_prices(): array|WP_Error {
		if ( $this->mock_prices instanceof WP_Error ) {
			return $this->mock_prices;
		}
		return $this->mock_prices ?? [];
	}

	/**
	 * Transform raw prices to entities.
	 *
	 * @param array $raw_data The raw data.
	 * @return Crypto_Price_Entity[] The entities.
	 */
	public function transform_prices_to_entities( array $raw_data ): array {
		$entities = [];
		foreach ( $raw_data as $coin_id => $data ) {
			if ( isset( $data['usd'], $data['symbol'] ) ) {
				$entities[] = new Crypto_Price_Entity(
					$data['symbol'],
					$data['usd'],
					(int) time()
				);
			}
		}
		return $entities;
	}

	/**
	 * Fetch coin list from API.
	 *
	 * @return array|WP_Error The coin list.
	 */
	public function fetch_coin_list(): array|WP_Error {
		if ( $this->mock_coins instanceof WP_Error ) {
			return $this->mock_coins;
		}
		return $this->mock_coins ?? [];
	}

	/**
	 * Transform raw coins to entities.
	 *
	 * @param array $raw_data The raw data.
	 * @return Coin_Entity[] The entities.
	 */
	public function transform_coin_list_to_entities( array $raw_data ): array {
		$entities = [];
		foreach ( $raw_data as $coin ) {
			if ( isset( $coin['id'], $coin['symbol'], $coin['name'] ) ) {
				$entities[] = new Coin_Entity(
					$coin['id'],
					$coin['symbol'],
					$coin['name']
				);
			}
		}
		return $entities;
	}

	/**
	 * Register the object methods to different WP hooks.
	 */
	public function register_hooks(): void {
	}

	/**
	 * Some API services might require attribution for usage.
	 *
	 * @return bool True if attribution is required, otherwise false.
	 */
	public function is_attribution_required(): bool {
		return false;
	}

	/**
	 * Render the attribution for usage of the API.
	 */
	public function render_attribution_content(): void {
	}
}
