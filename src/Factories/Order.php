<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WC_API_Server;
use WC_Order;
use WP_UnitTest_Factory_For_Thing;

class Order extends WP_UnitTest_Factory_For_Thing {
	/**
	 * Creates a product. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-an-order
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'post', '/wc/v3/orders' );
		$request->add_header( 'Content-Type', 'application/json' );

		$request->set_body( json_encode( $args ) ); //phpcs:ignore
		$response = rest_do_request( $request );

		$this->api_call_teardown();

		if ( $response->is_error() ) {
			throw new Exception( $response->get_data()['message'] );
		}

		return $response->get_data()['id'];
	}

	/**
	 * Updates an order.
	 *
	 * @param int $object Order ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#update-an-order
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'put', '/wc/v3/orders/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );

		$request->set_body( json_encode( $fields ) ); //phpcs:ignore
		$response = rest_do_request( $request );

		$this->api_call_teardown();

		if ( $response->is_error() ) {
			throw new Exception( $response->get_data()['message'] );
		}

		return $response->get_data()['id'];
	}

	/**
	 * Gets a woocommerce order.
	 *
	 * @param int $object_id The order ID.
	 *
	 * @return WC_Order
	 */
	public function get_object_by_id( $object_id ) {
		$order = wc_get_order( $object_id );
		if ( ! $order instanceof WC_Order ) {
			throw new Exception( 'Could not retrieve order with ID ' . $object_id );
		}
		return $order;
	}

	private function api_call_setup() {
		$this->old_user = wp_get_current_user();

		// Setup the administrator user so we can actually retrieve the order.
		$user = new \WP_User( 1 );
		wp_set_current_user( $user );

		WC()->api->includes();
		WC()->api->register_resources( new WC_API_Server( '/' ) );
	}


	private function api_call_teardown() {
		wp_set_current_user( $this->old_user );
	}
}
