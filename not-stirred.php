<?php
/*
Plugin Name: HandShaken - a BOND integration for WordPress
Description: Creates a useful interface for sending thoughtful, hand-written notes to anyone.
Version: 1.0
Author: JustinSainton, lizkaraffa
Author URI: http://zao.is
Plugin URI: http://zao.is
Text Domain: handshaken
Domain Path: /languages
License: GPL

Copyright 2015 Zao <justin@zao.is>

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, version 2.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * TODO :
 *
 * 1. Build out Handshaken API SDK.
 * 2. Build out settings panel for entering Handshaken data.
 * 3. Build out custom post types and taxonomies for notes, customers, addresses.
 */

class WP_HandShaken {
	private static $instance;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
			self::$instance->init();
		}
	}

	private function init() {

		self::$instance->includes();

		add_action( 'init', array( self::$instance, 'init_post_types' ) );
		add_action( 'init', array( self::$instance, 'init_taxonomies' ) );
	}

	public function init_post_types() {

	}

	public function init_taxonomies() {

	}

	private function includes() {
		include_once 'bond-api.php';
	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );