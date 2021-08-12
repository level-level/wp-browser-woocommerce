<?php

namespace LevelLevel\WPBrowserWooCommerce;

use LevelLevel\WPBrowserWooCommerce\Factories\Coupon;
use LevelLevel\WPBrowserWooCommerce\Factories\Product;
use LevelLevel\WPBrowserWooCommerce\Factories\Order;
use LevelLevel\WPBrowserWooCommerce\Factories\Subscription;
use LevelLevel\WPBrowserWooCommerce\Factories\TaxRate;
use WP_UnitTest_Factory;

class WC_UnitTest_Factory extends WP_UnitTest_Factory {
	/**
	 * @var Product
	 */
	public $product;

	/**
	 * @var Order
	 */
	public $order;

	/**
	 * @var TaxRate
	 */
	public $tax_rate;

	/**
	 * @var Coupon
	 */
	public $coupon;

	/**
	 * @var Subscription
	 */
	public $subscription;

	public function __construct() {
		parent::__construct();
		$this->product      = new Product( $this );
		$this->order        = new Order( $this );
		$this->tax_rate     = new TaxRate( $this );
		$this->coupon       = new Coupon( $this );
		$this->subscription = new Subscription( $this );
	}
}
