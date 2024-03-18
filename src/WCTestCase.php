<?php

namespace LevelLevel\WPBrowserWooCommerce;

use Codeception\TestCase\WPTestCase;

class WCTestCase extends WPTestCase {
	private $original_acf_stores;

	/**
	 * @return WC_UnitTest_Factory
	 */
	protected static function factory() {
		static $factory = null;
		if ( ! $factory ) {
			$factory = new WC_UnitTest_Factory();
		}
		return $factory;
	}

	public function _setUp()
	{
		parent::_setUp();
		global $acf_stores;
		$this->original_acf_stores = serialize( $acf_stores );
		WC()->shipping()->unregister_shipping_methods();
		WC()->shipping()->reset_shipping();
		WC()->cart = null;
		WC()->session = null;
		WC()->customer = null;
		wc_load_cart();
	}

	public function _tearDown()
	{
		parent::_tearDown();
		wc_clear_notices();
		global $acf_stores;
		$acf_stores = unserialize($this->original_acf_stores);
	}
}
