<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use TypeError;
use WC_Product;
use WP_UnitTest_Factory_For_Thing;

class Product extends WP_UnitTest_Factory_For_Thing {
	use APICalling;

	/**
	 * Creates a product. Using the API method.
	 *
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-a-product
	 *
	 * @return int
	 */
	public function create_object( $args ) {
		$request = new \WP_REST_Request( 'post', '/wc/v3/products' );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $args ) ); //phpcs:ignore

		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Updates a product.
	 *
	 * @param int $object Product ID.
	 * @param array $args See https://woocommerce.github.io/woocommerce-rest-api-docs/#update-a-product
	 *
	 * @return int
	 */
	public function update_object( $object, $fields ) {
		if ( ! is_int( $object ) ) {
			throw new TypeError( '$object must be an int' );
		}

		$request = new \WP_REST_Request( 'put', '/wc/v3/products/' . $object );
		$request->add_header( 'Content-Type', 'application/json' );
		$request->set_body( json_encode( $fields ) ); //phpcs:ignore
		
		$response = $this->do_request( $request );
		return $response->get_data()['id'];
	}

	/**
	 * Gets a woocommerce product.
	 *
	 * @param int $object_id The product ID.
	 *
	 * @return WC_Product
	 */
	public function get_object_by_id( $object_id ) {
		$product = wc_get_product( $object_id );
		if ( ! $product instanceof WC_Product ) {
			throw new Exception( 'Could not retrieve product with ID ' . $object_id );
		}
		return $product;
	}
}
