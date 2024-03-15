<?php

namespace LevelLevel\WPBrowserWooCommerce;

use Codeception\TestCase\WPTestCase;

class WCTestCase extends WPTestCase {
	private $original_cart;
	private $original_session;
	private $original_customer;
	private $original_query;

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
		$this->original_cart = WC()->cart;
		$this->original_session = WC()->session;
		$this->original_customer = WC()->customer;
		$this->original_query = WC()->query;
	}

	public function _tearDown()
	{
		parent::_tearDown();
		WC()->cart = $this->original_cart;
		WC()->session = $this->original_session;
		WC()->customer = $this->original_customer;
		WC()->query = $this->original_query;
	}
}
