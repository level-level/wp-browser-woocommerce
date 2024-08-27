<?php

namespace LevelLevel\WPBrowserWooCommerce\Factories;

use Exception;
use WC_API_Server;
use WP_REST_Request;
use WP_REST_Response;

trait APICalling{
    private function api_call_setup(): void{
		$this->old_user = get_current_user_id();

		// Setup the administrator user so we can actually retrieve the order.
		$user = new \WP_User( 1 );
		wp_set_current_user( $user->ID );
	}

    private function do_request( WP_REST_Request $request ): WP_REST_Response{
        $this->api_call_setup();
        $response = rest_do_request( $request );
        $this->api_call_teardown();
        if ( $response->is_error() ) {
			throw new Exception( $response->get_data()['message'] );
		}
        return $response;
    }

	private function api_call_teardown(): void{
		wp_set_current_user( $this->old_user );
	}
}