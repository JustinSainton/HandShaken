<?php

class WP_Bond_API {
	private $args;
	private $stationary;
	private $style;
	private $message;
	private $order;

	public function __construct( $args = array() ) {
		$this->args = array_merge(
			$args,
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $this->get_api_key() . ':' )
				)
			)
		);
	}

	private function get_api_key() {
		return apply_filters( 'wp_bond_api_key', get_option( 'wp_bond_api_key', '' ) );
	}

	public function set(  ) {

	}
}

$message = new WP_Bond_API( array( 'url' => '' ) );