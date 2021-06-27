<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Communicate with server API (activate, update)
 */

class WOODMART_Api {

    public $token = '';

    public $base_url;

    public $url = '';

    public function __construct() {
        $this->base_url = WOODMART_API_URL;
    }

    public function call($method, $data = array() ) {

        $response = wp_remote_get( $this->get_url($method, $data), array(
            'headers'     => $this->get_headers(),
        ) );

        return $response;

    }

    public function get_headers() {
        if( empty( $this->token ) ) return array();
        return array(
            'Authorization' => 'Bearer ' . $this->token
        );
    }

    public function get_url( $method, $args = array() ) {
        $this->url = $this->base_url;

        $this->url .= $method;

        if( ! empty( $args ) ) {
            foreach ($args as $key => $value) {
                $this->add_url_param($key, $value);
            }
        }


        return $this->url;
    }

    public function add_url_param( $key, $value ) {
        $this->url = add_query_arg( $key, $value, $this->url );

    }
}