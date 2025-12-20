<?php
/**
 * Unit Tests for the Abstract_Cached_API_Client class.
 *
 * @package Multi_Crypto_Price_Converter/Tests/Clients
 */

declare( strict_types=1 );

use Multi_Crypto_Price_Converter\Cache\Crypto_Option_Cache;
use Multi_Crypto_Price_Converter\Entities\Crypto_Price_Entity;
use Multi_Crypto_Price_Converter\Entities\Coin_Entity;
use Tests\Multi_Crypto_Price_Converter\Clients\Mock_Cached_API_Client;

/**
 * Unit tests for Abstract_Cached_API_Client non-abstract methods.
 */
class Abstract_Cached_Api_Client_Test extends WP_UnitTestCase {

	/**
	 * Mock implementation of Abstract_Cached_API_Client for testing.
	 *
	 * @var Mock_Cached_API_Client
	 */
	private Mock_Cached_API_Client $client;

	/**
	 * The cache object used for testing.
	 *
	 * @var Crypto_Option_Cache
	 */
	private Crypto_Option_Cache $cache;

	/**
	 * Mock price data for testing.
	 *
	 * @var array<string, array<string, float|string>>
	 */
	private array $mock_prices = [
		'bitcoin'  => [
			'usd' => 45000.00,
			'symbol' => 'btc',
		],
		'ethereum' => [
			'usd' => 2500.00,
			'symbol' => 'eth',
		],
		'cardano'  => [
			'usd' => 0.50,
			'symbol' => 'ada',
		],
	];

	/**
	 * Mock coin data for testing.
	 *
	 * @var array<int, array<string, string>>
	 */
	private array $mock_coins = [
		[
			'id'     => 'bitcoin',
			'symbol' => 'btc',
			'name'   => 'Bitcoin',
		],
		[
			'id'     => 'ethereum',
			'symbol' => 'eth',
			'name'   => 'Ethereum',
		],
		[
			'id'     => 'cardano',
			'symbol' => 'ada',
			'name'   => 'Cardano',
		],
	];

	/**
	 * Setup method called before each test.
	 */
	public function setUp(): void {
		parent::setUp();
		$this->cache  = new Crypto_Option_Cache();
		$this->client = new Mock_Cached_API_Client( $this->cache );
	}

	/**
	 * Teardown method called after each test.
	 */
	public function tearDown(): void {
		parent::tearDown();

		// Unschedule cron events.
		wp_clear_scheduled_hook( 'mcpc_fetch_prices_cron_test_api' );
	}

	/**
	 * Test get_prices() returns all cached prices when no coins filter is provided.
	 */
	public function test_get_prices_returns_all_prices(): void {
		$this->assertEmpty( $this->client->get_prices() );

		$this->client->set_mock_prices( $this->mock_prices );
		$this->client->fetch_and_cache_prices();

		$result = $this->client->get_prices();

		$this->assertIsArray( $result );
		$this->assertCount( count( $this->mock_prices ), $result );
		$this->assertInstanceOf( Crypto_Price_Entity::class, $result[0] );
	}

	/**
	 * Test get_prices() filters prices by coin symbols.
	 */
	public function test_get_prices_filters_by_coins(): void {
		$this->client->set_mock_prices( $this->mock_prices );
		$this->client->set_mock_coins( $this->mock_coins );
		$this->client->get_available_coins( true ); // Ensure coins are cached.
		$this->client->fetch_and_cache_prices();

		$result = $this->client->get_prices( [ 'btc', 'eth' ] );
		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
	}

	/**
	 * Test get_prices() is case-insensitive when filtering.
	 */
	public function test_get_prices_filter_case_insensitive(): void {
		$this->client->set_mock_prices( $this->mock_prices );
		$this->client->fetch_and_cache_prices();

		$result = $this->client->get_prices( [ 'BTC', 'ETH' ] );

		$this->assertCount( 2, $result );
	}

	/**
	 * Test get_prices() returns empty array when cache is empty.
	 */
	public function test_get_prices_empty_cache(): void {
		$result = $this->client->get_prices();

		$this->assertIsArray( $result );
		$this->assertEmpty( $result );
	}

	/**
	 * Test get_prices() returns empty array when filter matches no coins.
	 */
	public function test_get_prices_no_matching_coins(): void {
		$this->client->set_mock_prices( $this->mock_prices );
		$this->client->fetch_and_cache_prices();

		$result = $this->client->get_prices( [ 'doge', 'xrp' ] );

		$this->assertIsArray( $result );
		$this->assertEmpty( $result );
	}

	/**
	 * Test fetch_and_cache_prices() handles WP_Error correctly.
	 */
	public function test_fetch_and_cache_prices_handles_error(): void {
		// Set up mock to return WP_Error.
		$this->client->set_mock_error( new \WP_Error( 'api_error', 'API unavailable' ) );

		$this->client->fetch_and_cache_prices();

		// Cache should not be updated on error.
		$this->assertEmpty( $this->client->get_prices() );
	}

	/**
	 * Test add_custom_cron_interval() adds schedule to schedules array.
	 */
	public function test_add_custom_cron_interval(): void {
		$schedules = [
			'hourly' => [
				'interval' => 3600,
				'display'  => 'Once Hourly',
			],
		];

		$result = $this->client->add_custom_cron_interval( $schedules );

		$this->assertArrayHasKey( 'mcpc_interval_test_api', $result );
		$this->assertArrayHasKey( 'interval', $result['mcpc_interval_test_api'] );
		$this->assertArrayHasKey( 'display', $result['mcpc_interval_test_api'] );
		$this->assertIsInt( $result['mcpc_interval_test_api']['interval'] );
		$this->assertGreaterThan( 0, $result['mcpc_interval_test_api']['interval'] );
	}

	/**
	 * Test add_custom_cron_interval() preserves existing schedules.
	 */
	public function test_add_custom_cron_interval_preserves_existing(): void {
		$schedules = [
			'hourly' => [
				'interval' => 3600,
				'display'  => 'Once Hourly',
			],
		];

		$result = $this->client->add_custom_cron_interval( $schedules );

		$this->assertArrayHasKey( 'hourly', $result );
		$this->assertCount( 2, $result );
	}

	/**
	 * Test get_available_coins() returns empty array when cache is empty.
	 */
	public function test_get_available_coins_empty_cache(): void {
		$result = $this->client->get_available_coins();

		$this->assertIsArray( $result );
		$this->assertEmpty( $result );
	}

	/**
	 * Test get_available_coins() returns cached coins.
	 */
	public function test_get_available_coins_returns_cached(): void {
		$this->assertEmpty( $this->client->get_available_coins() );

		$this->client->set_mock_coins( $this->mock_coins );
		$this->client->fetch_and_cache_coins();

		$result = $this->client->get_available_coins();

		$this->assertIsArray( $result );
		$this->assertCount( count( $this->mock_coins ), $result );
		$this->assertInstanceOf( Coin_Entity::class, $result[0] );
	}

	/**
	 * Test get_available_coins() with force_cache_update forces refresh.
	 */
	public function test_get_available_coins_force_update(): void {
		// Save coins in cache.
		$this->client->set_mock_coins( $this->mock_coins );
		$this->client->fetch_and_cache_coins();
		$this->assertNotEmpty( $this->client->get_available_coins() );

		$mock_coins = [
			[
				'id'     => 'bitcoin',
				'symbol' => 'btc',
				'name'   => 'Bitcoin',
			],
		];

		// Set up mock to return coins.
		$this->client->set_mock_coins( $mock_coins );

		$result = $this->client->get_available_coins( true );

		$this->assertIsArray( $result );
		$this->assertCount( 1, $result );
		$this->assertInstanceOf( Coin_Entity::class, $result[0] );
	}

	/**
	 * Test get_available_coins() returns WP_Error when force_update fails.
	 */
	public function test_get_available_coins_force_update_error(): void {
		// Set up mock to return WP_Error.
		$this->client->set_mock_error( new \WP_Error( 'api_error', 'Failed to fetch coins' ) );

		$result = $this->client->get_available_coins( true );

		$this->assertInstanceOf( \WP_Error::class, $result );

		// Check that the cache was not updated.
		$this->assertEmpty( $this->client->get_available_coins() );
	}

	/**
	 * Test fetch_and_cache_coins() returns WP_Error on failure.
	 */
	public function test_fetch_and_cache_coins_error(): void {
		$this->client->set_mock_error( new \WP_Error( 'api_error', 'API failed' ) );

		$result = $this->client->fetch_and_cache_coins();

		$this->assertInstanceOf( \WP_Error::class, $result );
	}
}
