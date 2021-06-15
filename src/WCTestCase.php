<?php

namespace LevelLevel\WPBrowserWooCommerce;

use Codeception\TestCase\WPTestCase;

class WCTestCase extends WPTestCase {
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
}
