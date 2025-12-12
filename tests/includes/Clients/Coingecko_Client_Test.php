<?php
/**
 * Unit Tests for the Coingecko Client.
 *
 * @package Multi_Crypto_Convert/Tests/Clients
 */

declare( strict_types=1 );

use Multi_Crypto_Convert\Clients\Coingecko_Client;
use Multi_Crypto_Convert\Cache\Crypto_Option_Cache;
use Multi_Crypto_Convert\Entities\Crypto_Price_Entity;
use Multi_Crypto_Convert\Entities\Coin_Entity;

/**
 * Unit tests for Coingecko_Client class using pre_http_request filter.
 */
class Coingecko_Client_Test extends WP_UnitTestCase {

	/**
	 * The Coingecko_Client instance being tested.
	 *
	 * @var Coingecko_Client
	 */
	private Coingecko_Client $client;

	/**
	 * The cache object used for testing.
	 *
	 * @var Crypto_Option_Cache
	 */
	private Crypto_Option_Cache $cache;

	/**
	 * Mock coins list  for testing.
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
	];

	/**
	 * Setup method called before each test.
	 */
	public function setUp(): void {
		parent::setUp();
		$this->cache  = new Crypto_Option_Cache();
		$settings = [
			Coingecko_Client::API_KEY_FIELD => 'test_api_key',
			Coingecko_Client::API_KEY_TYPE_FIELD => 'demo',
		];
		$this->client = new Coingecko_Client( $this->cache, $settings, [ 'btc', 'eth' ] );
	}

	/**
	 * Teardown method called after each test.
	 */
	public function tearDown(): void {
		parent::tearDown();
		// Clear all filters added during testing.
		remove_all_filters( 'pre_http_request' );
	}

	/**
	 * Test fetch_prices() with a successful API response.
	 */
	public function test_fetch_prices_success(): void {
		$mock_response = [
			'bitcoin'  => [ 'usd' => 45000.00 ],
			'ethereum' => [ 'usd' => 2500 ],
		];

		$this->mock_http_request( 200, wp_json_encode( $mock_response ) );

		$result = $this->client->fetch_prices();

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'bitcoin', $result );
		$this->assertArrayHasKey( 'ethereum', $result );
		$this->assertEquals( 45000.00, $result['bitcoin']['usd'] );
		$this->assertEquals( 2500, $result['ethereum']['usd'] );
	}

	/**
	 * Test fetch_prices() with HTTP error response (non-200 status).
	 */
	public function test_fetch_prices_http_error(): void {
		$this->mock_http_request( 500, 'Internal Server Error' );

		$result = $this->client->fetch_prices();

		$this->assertInstanceOf( \WP_Error::class, $result );
		$this->assertEquals( 'coingecko_api_error_wrong_status', $result->get_error_code() );
	}

	/**
	 * Test fetch_prices() with invalid JSON response.
	 */
	public function test_fetch_prices_invalid_json(): void {
		$this->mock_http_request( 200, 'invalid json {' );

		$result = $this->client->fetch_prices();

		$this->assertInstanceOf( \WP_Error::class, $result );
		$this->assertEquals( 'coingecko_api_error_malformed_json', $result->get_error_code() );
	}

	/**
	 * Test fetch_prices() when wp_remote_get returns WP_Error.
	 */
	public function test_fetch_prices_wp_error(): void {
		$this->mock_http_request_with_error( 'Connection failed' );

		$result = $this->client->fetch_prices();

		$this->assertInstanceOf( \WP_Error::class, $result );
	}

	/**
	 * Test transform_prices_to_entities() converts raw API data correctly.
	 */
	public function test_transform_prices_to_entities(): void {
		$raw_data = [
			'bitcoin'  => [ 'usd' => 45000.00 ],
			'ethereum' => [ 'usd' => 2500.00 ],
		];

		// Mock and cache the coin list to ensure coins are found.
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );
		$this->client->fetch_and_cache_coins();

		$result = $this->client->transform_prices_to_entities( $raw_data );

		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
		$this->assertInstanceOf( Crypto_Price_Entity::class, $result[0] );
		$this->assertInstanceOf( Crypto_Price_Entity::class, $result[1] );
		$this->assertEquals( 'btc', $result[0]->symbol );
		$this->assertEquals( 45000.00, $result[0]->price_usd );
		$this->assertEquals( 'eth', $result[1]->symbol );
		$this->assertEquals( 2500.00, $result[1]->price_usd );
	}

	/**
	 * Test fetch_coin_list() with successful API response.
	 */
	public function test_fetch_coin_list_success(): void {
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );

		$result = $this->client->fetch_coin_list();

		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
		$this->assertEquals( 'bitcoin', $result[0]['id'] );
		$this->assertEquals( 'ethereum', $result[1]['id'] );
	}

	/**
	 * Test fetch_coin_list() with HTTP error response.
	 */
	public function test_fetch_coin_list_http_error(): void {
		$this->mock_http_request( 403, 'Invalid API key.' );

		$result = $this->client->fetch_coin_list();

		$this->assertInstanceOf( \WP_Error::class, $result );
		$this->assertEquals( 'coingecko_api_error_wrong_status', $result->get_error_code() );
	}

	/**
	 * Test transform_coin_list_to_entities() converts coin data correctly.
	 */
	public function test_transform_coin_list_to_entities(): void {
		$result = $this->client->transform_coin_list_to_entities( $this->mock_coins );

		$this->assertIsArray( $result );
		$this->assertCount( 2, $result );
		$this->assertInstanceOf( Coin_Entity::class, $result[0] );
		$this->assertInstanceOf( Coin_Entity::class, $result[1] );
		$this->assertEquals( 'bitcoin', $result[0]->api_id );
		$this->assertEquals( 'btc', $result[0]->symbol );
		$this->assertEquals( 'ethereum', $result[1]->api_id );
		$this->assertEquals( 'eth', $result[1]->symbol );
	}

	/**
	 * Test fetch_and_cache_prices() caches prices correctly.
	 */
	public function test_fetch_and_cache_prices(): void {
		// Mock and cache the coin list to ensure coins are found.
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );
		$this->client->fetch_and_cache_coins();
		remove_all_filters( 'pre_http_request' );

		$mock_response = [
			'bitcoin'  => [ 'usd' => 45000.00 ],
			'ethereum' => [ 'usd' => 2500.00 ],
		];

		$this->mock_http_request( 200, wp_json_encode( $mock_response ) );

		$this->assertEmpty( $this->client->get_prices() );

		$this->client->fetch_and_cache_prices();

		$cached = $this->client->get_prices();

		$this->assertNotNull( $cached );
		$this->assertIsArray( $cached );
		$this->assertCount( 2, $cached );
		$this->assertInstanceOf( Crypto_Price_Entity::class, $cached[0] );
	}

	/**
	 * Test fetch_and_cache_coins() caches coins correctly.
	 */
	public function test_fetch_and_cache_coins(): void {
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );

		$this->assertEmpty( $this->client->get_available_coins() );

		$this->client->fetch_and_cache_coins();

		$cached = $this->client->get_available_coins();

		$this->assertNotNull( $cached );
		$this->assertIsArray( $cached );
		$this->assertCount( 2, $cached );
		$this->assertInstanceOf( Coin_Entity::class, $cached[0] );
	}

	/**
	 * Test get_prices() filters coins correctly when available coins are cached.
	 */
	public function test_get_prices_with_coin_filtering(): void {
		// Mock and cache the coin list to ensure coins are found.
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );
		$this->client->fetch_and_cache_coins();
		remove_all_filters( 'pre_http_request' );

		$mock_response = [
			'bitcoin'  => [ 'usd' => 45000.00 ],
			'ethereum' => [ 'usd' => 2500.00 ],
		];

		$this->mock_http_request( 200, wp_json_encode( $mock_response ) );

		$this->assertEmpty( $this->client->get_prices() );

		$this->client->fetch_and_cache_prices();

		$cached = $this->client->get_prices( [ 'eth' ] );

		$this->assertNotNull( $cached );
		$this->assertIsArray( $cached );
		$this->assertCount( 1, $cached );
		$this->assertInstanceOf( Crypto_Price_Entity::class, $cached[0] );
		$this->assertEquals( 'eth', $cached[0]->symbol );
	}

	/**
	 * Test get_available_coins() returns cached coins.
	 */
	public function test_get_available_coins_returns_cached(): void {
		$this->mock_http_request( 200, wp_json_encode( $this->mock_coins ) );

		$this->assertEmpty( $this->client->get_available_coins() );

		$this->client->fetch_and_cache_coins();

		$cached = $this->client->get_available_coins();

		$this->assertNotNull( $cached );
		$this->assertIsArray( $cached );
		$this->assertCount( 2, $cached );
		$this->assertInstanceOf( Coin_Entity::class, $cached[0] );
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
	 * Test add_custom_cron_interval() adds schedule with correct interval.
	 */
	public function test_add_custom_cron_interval(): void {
		$schedules = [];
		$result    = $this->client->add_custom_cron_interval( $schedules );

		$this->assertIsArray( $result );
		$this->assertArrayHasKey( 'mcc_interval_coingecko', $result );
		$this->assertArrayHasKey( 'interval', $result['mcc_interval_coingecko'] );
		$this->assertArrayHasKey( 'display', $result['mcc_interval_coingecko'] );
		$this->assertIsInt( $result['mcc_interval_coingecko']['interval'] );
		$this->assertGreaterThan( 0, $result['mcc_interval_coingecko']['interval'] );
	}

	/**
	 * Test request data is correct when fetching prices.
	 */
	public function test_fetch_prices_request_data(): void {
		$captured_request = null;

		add_filter(
			'pre_http_request',
			function ( $preempt, $r, $url ) use ( &$captured_request ) {
				if ( strpos( $url, 'simple/price' ) !== false ) {
					$captured_request = [
						'url'  => $url,
						'args' => $r,
					];
					return [
						'response' => [ 'code' => 200 ],
						'body'     => wp_json_encode( [ 'bitcoin' => [ 'usd' => 45000 ] ] ),
					];
				}
				return $preempt;
			},
			10,
			3
		);

		$this->client->fetch_prices();

		$this->assertNotNull( $captured_request );
		$this->assertStringContainsString( 'https://api.coingecko.com', $captured_request['url'] );
		$this->assertStringContainsString( 'simple/price', $captured_request['url'] );
		$this->assertEquals( 10, $captured_request['args']['timeout'] );
	}

	/**
	 * Test request data is correct when fetching coin list.
	 */
	public function test_fetch_coin_list_request_data(): void {
		$captured_request = null;

		add_filter(
			'pre_http_request',
			function ( $preempt, $r, $url ) use ( &$captured_request ) {
				if ( strpos( $url, 'coins/list' ) !== false ) {
					$captured_request = [
						'url'  => $url,
						'args' => $r,
					];
					return [
						'response' => [ 'code' => 200 ],
						'body'     => wp_json_encode(
							[
								[
									'id'     => 'bitcoin',
									'symbol' => 'btc',
									'name'   => 'Bitcoin',
								],
							]
						),
					];
				}
				return $preempt;
			},
			10,
			3
		);

		$this->client->fetch_coin_list();

		$this->assertNotNull( $captured_request );
		$this->assertStringContainsString( 'https://api.coingecko.com', $captured_request['url'] );
		$this->assertStringContainsString( 'coins/list', $captured_request['url'] );
	}

	/**
	 * Helper method to mock HTTP requests using pre_http_request filter.
	 *
	 * @param int    $status_code The HTTP status code to return.
	 * @param string $body The response body.
	 */
	private function mock_http_request( int $status_code, string $body ): void {
		add_filter(
			'pre_http_request',
			function ( $preempt, $r, $url ) use ( $status_code, $body ) {
				if ( strpos( $url, 'api.coingecko.com' ) !== false ) {
					return [
						'response' => [ 'code' => $status_code ],
						'body'     => $body,
					];
				}
				return $preempt;
			},
			10,
			3
		);
	}

	/**
	 * Helper method to mock wp_remote_get returning WP_Error.
	 *
	 * @param string $error_message The error message.
	 */
	private function mock_http_request_with_error( string $error_message ): void {
		add_filter(
			'pre_http_request',
			function ( $preempt, $r, $url ) use ( $error_message ) {
				if ( strpos( $url, 'api.coingecko.com' ) !== false ) {
					return new \WP_Error( 'http_error', $error_message );
				}
				return $preempt;
			},
			10,
			3
		);
	}
}
