<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WP_UnitTest_Factory_For_Thing;

class TaxRate extends WP_UnitTest_Factory_For_Thing {
	/**
	 * Creates a new tax rate. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-a-tax-rate
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'post', '/wc/v3/taxes' );
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
	 * Updates a tax rate.
	 *
	 * @param int $object Tax rate ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#update-a-tax-rate
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'put', '/wc/v3/taxes/' . $object );
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
	 * Gets a woocommerce tax rate.
	 *
	 * @param int $object_id The tax rate ID.
	 *
	 * @return array Please not WooCommerce has no TaxRate object, so an unformatted array is given.
	 */
	public function get_object_by_id( $object_id ) {
		if ( ! is_int( $object_id ) ) {
			throw new TypeError( '$object_id must be an int' );
		}
		$this->api_call_setup();

		$request = new \WP_REST_Request( 'get', '/wc/v3/taxes/' . $object_id );
		$request->add_header( 'Content-Type', 'application/json' );

		$response = rest_do_request( $request );

		$this->api_call_teardown();

		if ( $response->is_error() ) {
			throw new Exception( $response->get_data()['message'] );
		}

		return $response->get_data();
	}

	private function api_call_setup() {
		$this->old_user = wp_get_current_user();

		// Setup the administrator user so we can actually retrieve the order.
		$user = new \WP_User( 1 );
		wp_set_current_user( $user );
	}


	private function api_call_teardown() {
		wp_set_current_user( $this->old_user );
	}
}
