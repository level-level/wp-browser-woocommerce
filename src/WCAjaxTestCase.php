<?php

namespace LevelLevel\WPBrowserWooCommerce;

use Codeception\TestCase\WPAjaxTestCase;

class WCAjaxTestCase extends WPAjaxTestCase {
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
