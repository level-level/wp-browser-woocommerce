# wp-browser-woocommerce
This library simplifies testing of WooCommerce themes and plugins with [wp-browser](https://github.com/lucatume/wp-browser). Several [Unit Test Factories](https://make.wordpress.org/core/handbook/testing/automated-testing/writing-phpunit-tests/#fixtures-and-factories) are added that allow you to quickly create WooCommerce products and orders within an integration test.

## Getting started
Before getting started with `wp-browser-woocommerce`, make sure you read the excellent [documentation for wp-browser](https://wpbrowser.wptestkit.dev/) first.
### Installation
To install `wp-browser-woocommerce` you use [composer](https://getcomposer.org/). The library is published on [packagist](https://packagist.org/packages/level-level/wp-browser-woocommerce). 

```shell
composer require --dev level-level/wp-browser-woocommerce
```

### Your first WooCommerce test

Tests written with `wp-browser-woocommerce` are a lot like regular `wp-browser` integration tests. By extending from `\LevelLevel\WPBrowserWooCommerce\WCTestCase` instead of the regular `\WPTestCase`, you will get access to WooCommerce unit test factories. 

```php
<?php // ./tests/wpunit/ExampleTest.php

use LevelLevel\WPBrowserWooCommerce\WCTestCase;

class ExampleTest extends WCTestCase{
    public function test_something(){
        // Create a WooCommerce product.
        $product = $this->factory()->product->create_and_get(
			array(
				'name'          => 'test',
				'regular_price' => '12.12',
			)
		);

        // Create a WooCommerce order with two products.
		$order   = $this->factory()->order->create_and_get(
			array(
				'payment_method'       => 'bacs',
				'payment_method_title' => 'BACS',
				'set_paid'             => true,
				'line_items'           => array(
					array(
						'product_id' => $product->get_id(),
						'quantity'   => 2,
					),
				),
			)
		);

        // Make sure the order total price is correct.
        $this->assertEquals( 24.24, $order->get_total() );
    }
}
```

## Factories
The factories provide methods to allow for quick object creation. The factories are access with the `$this->factory()` method on a testcase.

In the background, the factories use [the WooCommerce REST API](https://woocommerce.github.io/woocommerce-rest-api-docs/#introduction) methods to create and retrieve objects. These are not actual `GET`/`POST` requests, but rather internal calls to the methods that would process the regular requests to the API.

All factories extend from the WordPress default [WP_UnitTest_Factory_For_Thing](https://github.com/WordPress/wordpress-develop/blob/master/tests/phpunit/includes/factory/class-wp-unittest-factory-for-thing.php). All methods that are specified on this base class are available on the factories you will use in WooCommerce tests.

In this documentation you will only find the most used ones, refer to the base class or WordPress documentation for others.

### Orders
You can access the order factory by using `$this->factory()->order` within a WooCommerce integration test. 

The main method you'll use is `create_and_get( $args )`. The input you can give to an order are the same as you can give to the order creation API endpoint. 

`create_and_get($args)` returns the result of `wc_get_order()` for the created object.

See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-an-order

Example:
```php
$order = $this->factory()->order->create_and_get(
    array(
        'payment_method'       => 'bacs',
        'payment_method_title' => 'BACS',
        'set_paid'             => true,
        'billing'              => array(
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'address_1'    => 'Market',
            'house_number' => '1',
            'address_2'    => '',
            'city'         => 'Rotterdam',
            'postcode'     => '3456AB',
            'country'      => 'NL',
            'email'        => 'john.doe@example.com',
        ),
        'shipping'             => array(
            'first_name'   => 'John',
            'last_name'    => 'Doe',
            'address_1'    => 'Memory Lane',
            'house_number' => '1',
            'address_2'    => '',
            'city'         => 'Rotterdam',
            'postcode'     => '3456AB',
            'country'      => 'NL',
        ),
        'line_items'           => array(
            array(
                'product_id' => 1,
                'quantity'   => 1,
                'meta_data'  => array(
                    array(
                        'key'   => 'made_by',
                        'value' => 'Level Level',
                    ),
                    array(
                        'key'   => 'with_love',
                        'value' => 'obviously'
                    ),
                ),
            ),
        ),
        'shipping_lines': array(
            array(
                'method_id': 'flat_rate',
                'method_title': 'Flat Rate',
                'total': '10.00'
            )
        )
    )
);
```

### Products

You can access the order factory by using `$this->factory()->product` within a WooCommerce integration test. 

The main method you'll use is `create_and_get( $args )`. The input you can give to an order are the same as you can give to the product creation API endpoint. 

`create_and_get($args)` returns the result of `wc_get_product()` for the created object.

See https://woocommerce.github.io/woocommerce-rest-api-docs/#create-a-product

Example:

```php
$this->factory()->product->create_and_get(
    array(
        'name'            => 'test',
        'regular_price'   => '103.11',
        'weight'          => '14',
        'dimensions'      => array(
            'height' => '1',
        ),
        'reviews_allowed' => false,
        'manage_stock'    => true,
        'stock_status'    => 'onbackorder',
        'backorders'      => 'yes',
        'meta_data'       => array(
            array(
                'key'   => 'made_in',
                'value' => 'Rotterdam',
            ),
        ),
    )
);
```

## Testcases
For most testcases you will want to use `\LevelLevel\WPBrowserWooCommerce\WCTestCase`

### Ajax calls
For ajax calls, the regular [\WPAjaxTestCase](https://wpbrowser.wptestkit.dev/commands#generate-wpajax) would be replaced with `\LevelLevel\WPBrowserWooCommerce\WCAjaxTestCase`

Example:

```php
public function test_can_add_sample_to_cart() {
    WC_AJAX::init();

    $product = $this->factory()->product->create_and_get(
        array(
            'name'          => 'test',
            'regular_price' => '12.12',
        )
    );
    
    // ... testing logic ...
    
    try {
        $this->_handleAjax( 'woocommerce_add_to_cart' );
    } catch ( WPAjaxDieContinueException $e ) {
        ob_end_flush();
    }
    $this->assertEmpty( wc_get_notices( 'error' ), 'There should be no error notices after making this ajax call.' );
}
```

## Development
`wp-browser-woocommerce` is actively being used at [Level Level](https://level-level.com/). The library will get new features as we need them for client projects.

### Roadmap

The main focus is on implementing more factories for other WooCommerce objects such as **coupons**, **customers**, **refunds** and **shipping methods**.

After this, focus might shift to popular extensions for WooCommerce, such as Subscriptions or Bookings.

### Contributing
Feel free to open issues or create pull requests if you feel something is missing or working incorrectly.