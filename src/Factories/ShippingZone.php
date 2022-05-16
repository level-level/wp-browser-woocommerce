<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WC_Shipping_Zone;
use WP_UnitTest_Factory_For_Thing;

class ShippingZone extends WP_UnitTest_Factory_For_Thing {
	use APICalling;

	/**
	 * Creates a shipping zone. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/?shell#create-a-shipping-zone
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$request = new \WP_REST_Request( 'post', '/wc/v3/shipping/zones' );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $args ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Updates a shipping zone.
	 *
	 * @param int $object Shipping zone ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/?shell#update-a-shipping-zone
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}

		$request = new \WP_REST_Request( 'put', '/wc/v3/shipping/zones/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $fields ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Gets a woocommerce shipping zone.
	 *
	 * @param int $object_id The shipping zone.
	 *
	 * @return WC_Shipping_Zone
	 */
	public function get_object_by_id( $object_id ) {
		$shipping_zone = new WC_Shipping_Zone( $object_id );
		if ( ! $shipping_zone instanceof WC_Shipping_Zone ) {
			throw new Exception( 'Could not retrieve shipping zone with ID ' . $object_id );
		}
		return $shipping_zone;
	}
}
