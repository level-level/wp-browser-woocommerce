<?php

namespace LevelLevel\WPBrowserWooCommerce;

use LevelLevel\WPBrowserWooCommerce\Factories\Coupon;
use LevelLevel\WPBrowserWooCommerce\Factories\Product;
use LevelLevel\WPBrowserWooCommerce\Factories\Order;
use LevelLevel\WPBrowserWooCommerce\Factories\ShippingZone;
use LevelLevel\WPBrowserWooCommerce\Factories\ShippingZoneMethod;
use LevelLevel\WPBrowserWooCommerce\Factories\Subscription;
use LevelLevel\WPBrowserWooCommerce\Factories\TaxRate;
use WP_UnitTest_Factory;

class WC_UnitTest_Factory extends WP_UnitTest_Factory {

	public Product $product;
	public Order $order;
	public TaxRate $tax_rate;
	public Coupon $coupon;
	public ShippingZone $shipping_zone;
	public ShippingZoneMethod $shipping_zone_method;
	public Subscription $subscription;

	public function __construct() {
		parent::__construct();
		$this->product              = new Product( $this );
		$this->order                = new Order( $this );
		$this->tax_rate             = new TaxRate( $this );
		$this->coupon               = new Coupon( $this );
		$this->shipping_zone        = new ShippingZone( $this );
		$this->shipping_zone_method = new ShippingZoneMethod( $this );
		$this->subscription         = new Subscription( $this );
	}
}
