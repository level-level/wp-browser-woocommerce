<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WC_Subscription;
use WP_UnitTest_Factory_For_Thing;

class Subscription extends WP_UnitTest_Factory_For_Thing {
	use APICalling;
	/**
	 * Creates a subscription. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/subscriptions-rest-api-docs/v1.html#create-a-subscription
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$request = new \WP_REST_Request( 'post', '/wc/v1/subscriptions' );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $args ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Updates a subscription.
	 *
	 * @param int $object Subscription ID.
	 * @param array $args See https://woocommerce.github.io/subscriptions-rest-api-docs/v1.html#update-a-subscription
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}

		$request = new \WP_REST_Request( 'put', '/wc/v1/subscriptions/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $fields ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Gets a woocommerce subscription.
	 *
	 * @param int $object_id The subscription ID.
	 *
	 * @return WC_Subscription
	 */
	public function get_object_by_id( $object_id ) {
		$subscription = wcs_get_subscription( $object_id );
		if ( ! $subscription instanceof WC_Subscription ) {
			throw new Exception( 'Could not retrieve subscription with ID ' . $object_id );
		}
		return $subscription;
	}
}
