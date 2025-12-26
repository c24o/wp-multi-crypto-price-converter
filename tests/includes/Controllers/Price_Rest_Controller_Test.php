<?php
/**
 * Unit Tests for the Price_Rest_Controller class.
 *
 * @package Multi_Crypto_Price_Converter/Tests/Controllers
 */

declare( strict_types=1 );

use Multi_Crypto_Price_Converter\Controllers\Price_Rest_Controller;
use Multi_Crypto_Price_Converter\Settings\Admin_Settings;
use Multi_Crypto_Price_Converter\Clients\Crypto_API_Client;
use Multi_Crypto_Price_Converter\Entities\Crypto_Price_Entity;
use Multi_Crypto_Price_Converter\Entities\Coin_Entity;

/**
 * Unit tests for Price_Rest_Controller.
 */
class Price_Rest_Controller_Test extends WP_UnitTestCase {

	/**
	 * Mock of Admin_Settings.
	 *
	 * @var Admin_Settings|\PHPUnit\Framework\MockObject\MockObject
	 */
	private $settings_mock;

	/**
	 * Mock of Crypto_API_Client.
	 *
	 * @var Crypto_API_Client|\PHPUnit\Framework\MockObject\MockObject
	 */
	private $client_mock;

	/**
	 * The controller instance.
	 *
	 * @var Price_Rest_Controller
	 */
	private Price_Rest_Controller $controller;

	/**
	 * Setup method.
	 */
	public function setUp(): void {
		parent::setUp();

		$this->settings_mock = $this->createMock( Admin_Settings::class );
		$this->client_mock   = $this->createMock( Crypto_API_Client::class );

		$this->controller = new Price_Rest_Controller(
			$this->settings_mock,
			$this->client_mock
		);
	}

	/**
	 * Test register_routes registers the expected routes.
	 */
	public function test_register_routes(): void {
		do_action( 'rest_api_init' );

		$routes = rest_get_server()->get_routes();

		$this->assertArrayHasKey( '/' . Price_Rest_Controller::API_NAMESPACE . '/' . Price_Rest_Controller::ENDPOINT_PRICES, $routes );
		$this->assertArrayHasKey( '/' . Price_Rest_Controller::API_NAMESPACE . '/' . Price_Rest_Controller::ENDPOINT_SELECTED_COINS, $routes );
	}

	/**
	 * Test get_selected_coins returns correct data.
	 */
	public function test_get_selected_coins(): void {
		$this->settings_mock->method( 'get_selected_coins_ids' )
			->willReturn( [ 'bitcoin', 'ethereum' ] );

		$this->client_mock->method( 'get_available_coins' )
			->willReturn(
				[
					new Coin_Entity( 'bitcoin', 'BTC', 'Bitcoin' ),
					new Coin_Entity( 'ethereum', 'ETH', 'Ethereum' ),
					new Coin_Entity( 'cardano', 'ADA', 'Cardano' ),
				]
			);

		$response = $this->controller->get_selected_coins();
		$data     = $response->get_data();

		$this->assertTrue( $data['success'] );
		$this->assertCount( 2, $data['data'] );
		$this->assertEquals( 'BTC', $data['data'][0]['value'] );
		$this->assertEquals( 'Bitcoin', $data['data'][0]['label'] );
		$this->assertEquals( 'ETH', $data['data'][1]['value'] );
	}

	/**
	 * Test get_prices returns prices and next update time.
	 */
	public function test_get_prices_success(): void {
		$request = new WP_REST_Request( 'GET', '/' . Price_Rest_Controller::API_NAMESPACE . '/' . Price_Rest_Controller::ENDPOINT_PRICES );
		$request->set_param( 'coins', 'BTC,ETH' );

		$this->client_mock->method( 'get_prices' )
			->with( [ 'BTC', 'ETH' ] )
			->willReturn(
				[
					new Crypto_Price_Entity( 'BTC', 50000.0, 1600000000 ),
					new Crypto_Price_Entity( 'ETH', 3000.0, 1600000000 ),
				]
			);

		// Mock schedule for calculate_next_update_timestamp.
		$future_time = time() + 300;
		$this->client_mock->method( 'get_next_update_prices_scheduled' )
			->willReturn( $future_time );

		$response = $this->controller->get_prices( $request );
		$data     = $response->get_data();

		$this->assertTrue( $data['success'] );
		$this->assertCount( 2, $data['data']['prices'] );
		$this->assertEquals( 'BTC', $data['data']['prices'][0]['symbol'] );
		$this->assertEquals( 50000.0, $data['data']['prices'][0]['price_usd'] );

		// Check next_update is within jitter range.
		$this->assertGreaterThanOrEqual( $future_time + Price_Rest_Controller::MIN_JITTER, $data['data']['next_update'] );
		$this->assertLessThanOrEqual( $future_time + Price_Rest_Controller::MAX_JITTER, $data['data']['next_update'] );
	}

	/**
	 * Test get_prices handles exceptions gracefully.
	 */
	public function test_get_prices_handles_exception(): void {
		$request = new WP_REST_Request( 'GET', '/' . Price_Rest_Controller::API_NAMESPACE . '/' . Price_Rest_Controller::ENDPOINT_PRICES );

		$this->client_mock->method( 'get_prices' )
			->willThrowException( new \Exception( 'API Error' ) );

		$response = $this->controller->get_prices( $request );
		$data     = $response->get_data();

		$this->assertFalse( $data['success'] );
		$this->assertEquals( 500, $response->get_status() );
	}

	/**
	 * Test sanitize_coins_param cleans input correctly.
	 */
	public function test_sanitize_coins_param(): void {
		$input    = ' btc , eth,  ADA ,, ';
		$expected = 'BTC,ETH,ADA';
		$result   = $this->controller->sanitize_coins_param( $input );

		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test validate_coins_param validates correctly.
	 */
	public function test_validate_coins_param(): void {
		$this->assertTrue( $this->controller->validate_coins_param( 'BTC,ETH' ) );
		$this->assertTrue( $this->controller->validate_coins_param( '' ) ); // Optional.
		$this->assertFalse( $this->controller->validate_coins_param( 'BTC,Invalid@Coin' ) );
		$this->assertFalse( $this->controller->validate_coins_param( 'ThisCoinSymbolIsWayTooLong' ) );
	}

	/**
	 * Test calculate_next_update_timestamp when schedule is in the future.
	 */
	public function test_calculate_next_update_timestamp_future(): void {
		$future_time = time() + 600;
		$this->client_mock->method( 'get_next_update_prices_scheduled' )
			->willReturn( $future_time );

		$result = $this->invoke_private_method( 'calculate_next_update_timestamp' );

		$this->assertGreaterThanOrEqual( $future_time + Price_Rest_Controller::MIN_JITTER, $result );
		$this->assertLessThanOrEqual( $future_time + Price_Rest_Controller::MAX_JITTER, $result );
	}

	/**
	 * Test calculate_next_update_timestamp when schedule is missing.
	 */
	public function test_calculate_next_update_timestamp_no_schedule(): void {
		$this->client_mock->method( 'get_next_update_prices_scheduled' )
			->willReturn( false );

		$interval = 60;
		$this->client_mock->method( 'get_prices_update_interval_data' )
			->willReturn( [ 'interval' => $interval ] );

		$start_time = time();
		$result     = $this->invoke_private_method( 'calculate_next_update_timestamp' );
		$base_time  = $start_time + $interval;

		// Allow small delta for execution time.
		$this->assertGreaterThanOrEqual( $base_time + Price_Rest_Controller::MIN_JITTER, $result );
		$this->assertLessThanOrEqual( $base_time + Price_Rest_Controller::MAX_JITTER + 1, $result );
	}

	/**
	 * Test calculate_next_update_timestamp when schedule is in the past.
	 */
	public function test_calculate_next_update_timestamp_past_schedule(): void {
		$past_time = time() - 100;
		$this->client_mock->method( 'get_next_update_prices_scheduled' )
			->willReturn( $past_time );

		$interval = 60;
		$this->client_mock->method( 'get_prices_update_interval_data' )
			->willReturn( [ 'interval' => $interval ] );

		$start_time = time();
		$result     = $this->invoke_private_method( 'calculate_next_update_timestamp' );
		$base_time  = $start_time + $interval;

		$this->assertGreaterThanOrEqual( $base_time + Price_Rest_Controller::MIN_JITTER, $result );
		$this->assertLessThanOrEqual( $base_time + Price_Rest_Controller::MAX_JITTER + 1, $result );
	}

	/**
	 * Helper to invoke private methods via Reflection.
	 *
	 * @param string $method_name The name of the private method.
	 * @return mixed The result of the method call.
	 */
	private function invoke_private_method( string $method_name ) {
		$method = new \ReflectionMethod( $this->controller, $method_name );
		$method->setAccessible( true );
		return $method->invoke( $this->controller );
	}
}
