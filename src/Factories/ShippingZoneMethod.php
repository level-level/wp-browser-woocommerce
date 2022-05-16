<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use BadMethodCallException;
use Exception;
use TypeError;
use WC_Shipping_Method;
use WC_Shipping_Zone;
use WC_Shipping_Zones;
use WP_UnitTest_Factory_For_Thing;

class ShippingZoneMethod extends WP_UnitTest_Factory_For_Thing {
	use APICalling;

	/**
	 * @var int
	 */
	private $zone_id = null;

	public function zone_id( int $zone_id ): self {
		$this->zone_id = $zone_id;
		return $this;
	}

	/**
	 * Creates a shipping zone method. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/?shell#include-a-shipping-method-to-a-shipping-zone
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		if ( empty( $this->zone_id ) ) {
			throw new BadMethodCallException( 'Zone ID not set.' );
		}
		$request = new \WP_REST_Request( 'post', '/wc/v3/shipping/zones/' . $this->zone_id . '/methods' );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $args ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['instance_id'];
	}

	/**
	 * Updates a shipping zone method.
	 *
	 * @param int $object Shipping zone method ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/?shell#update-a-shipping-method-of-a-shipping-zone
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}

		if ( empty( $this->zone_id ) ) {
			throw new BadMethodCallException( 'Zone ID not set.' );
		}

		$request = new \WP_REST_Request( 'put', 'wc/v3/shipping/zones/' . $this->zone_id . '/methods/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $fields ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['instance_id'];
	}

	/**
	 * Gets a woocommerce shipping zone.
	 *
	 * @param int $object_id The shipping zone.
	 *
	 * @return WC_Shipping_Method
	 */
	public function get_object_by_id( $object_id ) {
		if ( empty( $this->zone_id ) ) {
			throw new BadMethodCallException( 'Zone ID not set.' );
		}
		$shipping_zone = new WC_Shipping_Zone( $this->zone_id );
		if ( ! $shipping_zone instanceof WC_Shipping_Zone ) {
			throw new Exception( 'Could not retrieve shipping zone method with ID ' . $object_id );
		}
		$methods = $shipping_zone->get_shipping_methods();
		foreach($methods as $method){
			if( $method->get_instance_id() === $object_id && $method instanceof WC_Shipping_Method ) {
				return $method;
			}
		}
		throw new Exception( 'Could not retrieve shipping zone method with ID ' . $object_id );
	}
}
