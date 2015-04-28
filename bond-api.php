<?php

class WP_Bond_API {
	private $args;
	private $stationary;
	private $style;
	private $message;
	private $order;
	private $endpoints = array(
		'mock'       => 'https://private-44ba7-bond.apiary-mock.com/',
		'debugging'  => 'https://private-44ba7-bond.apiary-proxy.com/',
		'production' => 'https://api.hellobond.com/',
	);

	public function __construct( $args = array( 'endpoint' => 'mock' ) ) {

		$this->args = array_merge(
			$args,
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $this->get_api_key() . ':' )
				)
			)
		);

		$this->endpoint = $this->endpoints[ $args['endpoint'] ];
	}

	public function __set( $name, $value ) {
		if ( prop )
	}

	private function get_api_key() {
		return apply_filters( 'wp_bond_api_key', get_option( 'wp_bond_api_key', '' ) );
	}

	/**
	 * Sets up the resource we're hitting on the API.
	 *
	 * Possible resources:
	 *  - account
	 *  - account/products
	 *  - account/products/%id
	 *  - orders
	 *  - orders/%guid
	 *  - orders/%guid/messages
	 *  - orders/%guid/messages/id
	 *  - messages/preview/content
	 *  - messages/preview/envelope
	 *
	 * @param  string $resource [description]
	 * @return [type]           [description]
	 */
	public function setup( $resource = 'orders' ) {
		$this->endpoint .= $resource;

		return $this;
	}

	/**
	 * Builds the arguments for the API request, including the data model/object.
	 *
	 * @return [type] [description]
	 */
	public function build( $args = array() ) {

		return $this;
	}

	public function get() {
		return wp_remote_get( $this->endpoint, $this->args );
	}

	public function post() {
		return wp_remote_post( $this->endpoint, $this->args );
	}
}

// $message = new WP_Bond_API();
// $message->setup( 'orders' )->build( $args )->post();