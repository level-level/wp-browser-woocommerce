<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use TypeError;
use WP_UnitTest_Factory_For_Thing;

class TaxRate extends WP_UnitTest_Factory_For_Thing {
	use APICalling;

	/**
	 * Creates a new tax rate. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-a-tax-rate
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$request = new \WP_REST_Request( 'post', '/wc/v3/taxes' );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $args ) ); //phpcs:ignore

		$response = $this->do_request( $request );
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
		$request = new \WP_REST_Request( 'put', '/wc/v3/taxes/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $fields ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Gets a woocommerce tax rate.
	 *
	 * @param int $object_id The tax rate ID.
	 *
	 * @return array WooCommerce has no TaxRate object, so an unformatted array is given.
	 */
	public function get_object_by_id( $object_id ) {
		if ( ! is_int( $object_id ) ) {
			throw new TypeError( '$object_id must be an int' );
		}

		$request = new \WP_REST_Request( 'get', '/wc/v3/taxes/' . $object_id );
		$request->add_header( 'Content-Type', 'application/json' );

		$response = $this->do_request( $request );
		return $response->get_data();
	}
}
