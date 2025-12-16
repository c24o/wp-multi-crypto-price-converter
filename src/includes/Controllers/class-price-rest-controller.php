<?php
/**
 * Price REST Controller
 *
 * @package Multi_Crypto_Convert\Controllers
 */

declare( strict_types=1 );

namespace Multi_Crypto_Convert\Controllers;

use Multi_Crypto_Convert\Clients\Crypto_API_Client;
use Multi_Crypto_Convert\Settings\Admin_Settings;
use WP_REST_Request;
use WP_REST_Response;

/**
 * REST API controller for cryptocurrency price data.
 *
 * Provides a public REST endpoint for retrieving current cryptocurrency prices.
 * Security is implemented via rate limiting, nonce verification, and input validation.
 */
final class Price_Rest_Controller {

	private const API_NAMESPACE = 'mcc/v1';
	private const ENDPOINT_PRICES = 'prices';
	private const ENDPOINT_SELECTED_COINS = 'selected-available-coins';
	private const NONCE_ACTION = 'mcc_prices_nonce';
	private const RATE_LIMIT_WINDOW = 3600; // 1 hour in seconds.
	private const RATE_LIMIT_MAX_REQUESTS = 100; // Max requests per hour per IP.

	/**
	 * Constructor.
	 *
	 * @param Admin_Settings $settings Plugin settings.
	 * @param Crypto_API_Client $client The cryptocurrency API client.
	 */
	public function __construct(
		private Admin_Settings $settings,
		private Crypto_API_Client $client
	) {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Registers the REST API routes.
	 */
	public function register_routes(): void {
		$this->register_prices_route();
		$this->register_selected_coins_route();
	}

	/**
	 * Registers the prices REST API route.
	 */
	private function register_prices_route(): void {
		register_rest_route(
			self::API_NAMESPACE,
			'/' . self::ENDPOINT_PRICES,
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get_prices' ],
				'permission_callback' => [ $this, 'check_permissions' ],
				'args'                => [
					'coins' => [
						'description'       => __( 'Comma-separated list of coin symbols (e.g., BTC,ETH,ADA)', 'multi-crypto-convert' ),
						'type'              => 'string',
						'required'          => false,
						'sanitize_callback' => [ $this, 'sanitize_coins_param' ],
						'validate_callback' => [ $this, 'validate_coins_param' ],
					],
					'nonce' => [
						'description'       => __( 'WordPress nonce for CSRF protection', 'multi-crypto-convert' ),
						'type'              => 'string',
						'required'          => true,
						'sanitize_callback' => 'sanitize_text_field',
					],
				],
			]
		);
	}

	/**
	 * Registers the selected coins REST API route.
	 *
	 * This endpoint allows block editors to retrieve the list of coins
	 * that administrators have selected in the settings.
	 */
	private function register_selected_coins_route(): void {
		register_rest_route(
			self::API_NAMESPACE,
			'/' . self::ENDPOINT_SELECTED_COINS,
			[
				'methods' => 'GET',
				'callback' => [ $this, 'get_selected_coins' ],
				'permission_callback' => '__return_true',
			]
		);
	}

	/**
	 * Callback for the GET /selected-coins endpoint.
	 *
	 * Returns the list of coin IDs selected by the administrator
	 * in the plugin settings.
	 *
	 * @return WP_REST_Response The response containing selected coins data.
	 */
	public function get_selected_coins(): WP_REST_Response {
		$selected_coins = [];
		$selected_coins_ids = $this->settings->get_selected_coins_ids();
		$available_coins = $this->client->get_available_coins();
		if ( is_array( $selected_coins ) && is_array( $available_coins ) ) {
			$selected_coins = array_values(
				array_filter(
					array_map(
						function ( string $coin_id ) use ( $available_coins ) {
							foreach ( $available_coins as $coin ) {
								if ( $coin->api_id === $coin_id ) {
									return [
										'value' => $coin->symbol,
										'label' => $coin->name,
									];
								}
							}
							return false;
						},
						$selected_coins_ids
					)
				)
			);
		}

		return new WP_REST_Response(
			[
				'success' => true,
				'data'    => $selected_coins,
				'count'   => count( $selected_coins ),
			],
			200
		);
	}

	/**
	 * Callback for the GET /prices endpoint.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return WP_REST_Response The response containing price data.
	 */
	public function get_prices( WP_REST_Request $request ): WP_REST_Response {
		try {
			// Get coins parameter (already sanitized by REST API).
			$coins_param = $request->get_param( 'coins' );
			$coins = $coins_param ? explode( ',', $coins_param ) : null;

			// Get prices from client.
			$prices = $this->client->get_prices( $coins );

			// Transform entities to array format for JSON response.
			$prices_data = array_map(
				function ( $price_entity ) {
					return [
						'symbol'       => $price_entity->symbol,
						'price_usd'    => $price_entity->price_usd,
						'last_updated' => $price_entity->last_updated,
					];
				},
				$prices
			);

			return new WP_REST_Response(
				[
					'success' => true,
					'data'    => $prices_data,
					'count'   => count( $prices_data ),
				],
				200
			);
		} catch ( \Exception $e ) {
			return new WP_REST_Response(
				[
					'success' => false,
					'message' => __( 'Failed to retrieve prices. Please try again later.', 'multi-crypto-convert' ),
				],
				500
			);
		}
	}

	/**
	 * Permission callback for the prices endpoint.
	 *
	 * Performs security checks including:
	 * - Nonce verification (CSRF protection)
	 * - Rate limiting (DoS protection)
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return bool|WP_Error True if allowed, WP_Error otherwise.
	 */
	public function check_permissions( WP_REST_Request $request ) {
		// Check nonce for CSRF protection.
		$nonce = $request->get_param( 'nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			return new \WP_Error(
				'invalid_nonce',
				__( 'Security check failed. Invalid or missing nonce.', 'multi-crypto-convert' ),
				[ 'status' => 403 ]
			);
		}

		// Check rate limiting.
		if ( ! $this->check_rate_limit() ) {
			return new \WP_Error(
				'rate_limit_exceeded',
				__( 'Too many requests. Please try again later.', 'multi-crypto-convert' ),
				[ 'status' => 429 ]
			);
		}

		return true;
	}

	/**
	 * Sanitizes the coins query parameter.
	 *
	 * Accepts comma-separated coin symbols and returns them as a normalized string.
	 *
	 * @param string $value The coins parameter value.
	 * @return string The sanitized coins parameter.
	 */
	public function sanitize_coins_param( string $value ): string {
		if ( empty( $value ) ) {
			return '';
		}

		// Split by comma and sanitize each coin symbol.
		$coins = array_map( 'strtoupper', explode( ',', $value ) );
		$coins = array_map( 'trim', $coins );
		$coins = array_filter( $coins ); // Remove empty strings.

		return implode( ',', $coins );
	}

	/**
	 * Validates the coins query parameter.
	 *
	 * Ensures coin symbols are valid format (alphanumeric, max 10 chars each).
	 *
	 * @param string $value The coins parameter value.
	 * @return bool True if valid, false otherwise.
	 */
	public function validate_coins_param( string $value ): bool {
		if ( empty( $value ) ) {
			return true; // Optional parameter, empty is valid.
		}

		$coins = explode( ',', $value );

		// Check each coin symbol.
		foreach ( $coins as $coin ) {
			$coin = trim( $coin );

			// Validate format: alphanumeric only, max 10 characters.
			if ( ! preg_match( '/^[A-Z0-9]{1,10}$/i', $coin ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Checks if the request is within rate limits.
	 *
	 * Uses transients to track requests per IP address.
	 *
	 * @return bool True if within limits, false if exceeded.
	 */
	private function check_rate_limit(): bool {
		$ip = $this->get_client_ip();
		$transient_key = 'mcc_price_api_' . md5( $ip );

		// Get current request count for this IP.
		$request_count = (int) get_transient( $transient_key );

		// Increment and check against limit.
		if ( $request_count >= self::RATE_LIMIT_MAX_REQUESTS ) {
			return false;
		}

		// Increment counter and set/update transient.
		set_transient(
			$transient_key,
			$request_count + 1,
			self::RATE_LIMIT_WINDOW
		);

		return true;
	}

	/**
	 * Retrieves the client's IP address.
	 *
	 * Attempts to get the real client IP, accounting for proxies and CDNs.
	 *
	 * @return string The client IP address.
	 */
	private function get_client_ip(): string {
		// Check for shared internet.
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			// Handle multiple IPs from proxies.
			$ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			$ip = trim( $ips[0] );
		} else {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ) );
		}

		return $ip;
	}
}
