<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WC_Coupon;
use WP_UnitTest_Factory_For_Thing;

class Coupon extends WP_UnitTest_Factory_For_Thing {
	/**
	 * Creates a coupon. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-a-coupon
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'post', '/wc/v3/coupons' );
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
	 * Updates a coupon.
	 *
	 * @param int $object Coupon ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#update-a-coupon
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'put', '/wc/v3/coupons/' . $object );
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
	 * Gets a woocommerce coupon.
	 *
	 * @param int $object_id The coupon ID.
	 *
	 * @return WC_Coupon
	 */
	public function get_object_by_id( $object_id ) {
		$coupon = new WC_Coupon( $object_id );
		if ( ! $coupon instanceof WC_Coupon ) {
			throw new Exception( 'Could not retrieve coupon with ID ' . $object_id );
		}
		return $coupon;
	}

	private function api_call_setup() {
		$this->old_user = get_current_user_id();

		// Setup the administrator user so we can actually retrieve the order.
		$user = new \WP_User( 1 );
		wp_set_current_user( $user->ID );
	}


	private function api_call_teardown() {
		wp_set_current_user( $this->old_user );
	}
}
