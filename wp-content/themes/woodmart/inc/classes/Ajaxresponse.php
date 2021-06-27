<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * Ajax response helper
 */

class WOODMART_Ajaxresponse {
	public $response = array();

	public function __construct() {
		$this->response = array( 'status' => 'fail', 'message' => '' );
	}

	public function send_response( $array = array() ) {

		if( empty( $array ) && ! empty( $this->response ) ) 
			echo json_encode( $this->response );
		elseif( ! empty( $array ) ) 
			echo json_encode( $array );
		else 
			echo json_encode( array( 'message' => 'empty response') );

		die();
	}

	public function add_msg( $msg ) {
		$this->response['status'] = 'success';
		$this->response['message'] .= $msg . '<br>';
	}

	public function send_success_msg( $msg ) {

		$this->send_msg( 'success', $msg );

	}

	public function send_fail_msg( $msg ) {

		$this->send_msg( 'fail', $msg );

	}

	public function send_msg( $status, $message ) {
		$this->response = array(
			'status' => $status,
			'message' => $message
		);

		$this->send_response();
	}
}