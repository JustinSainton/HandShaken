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
		add_action( 'init', array( self::$instance, 'init_post_types' ) );
		add_action( 'init', array( self::$instance, 'init_taxonomies' ) );
	}

	public function init_post_types() {
		if ( ! function_exists('wp_handshaken_custom_post_type') ) {

		// Register Custom Post Type
		function wp_handshaken_custom_post_type() {

			$labels = array(
				'name'                => _x( 'Notes', 'Post Type General Name', 'handshaken' ),
				'singular_name'       => _x( 'Note', 'Post Type Singular Name', 'handshaken' ),
				'menu_name'           => __( 'Notes', 'handshaken' ),
				'name_admin_bar'      => __( 'Notes', 'handshaken' ),
				'all_items'           => __( 'All Notes', 'handshaken' ),
				'add_new_item'        => __( 'Create New Note', 'handshaken' ),
				'add_new'             => __( 'Add New', 'handshaken' ), //Is this Line needed?
				'new_item'            => __( 'New Note', 'handshaken' ),
				'edit_item'           => __( 'Edit Note', 'handshaken' ),
				'view_item'           => __( 'View Note', 'handshaken' ),
				'search_items'        => __( 'Search Notes', 'handshaken' ),
				'not_found'           => __( 'Note Not found', 'handshaken' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'handshaken' ),
			);
			$args = array(
				'label'               => __( 'Notes', 'handshaken' ),
				'description'         => __( 'Custom handwritten notes', 'handshaken' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'custom-fields', ),
				'taxonomies'          => array( 'category', 'post_tag' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 5,
				'menu_icon'           => 'dashicons-welcome-write-blog',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => false,
				'capability_type'     => 'post',
			);
			register_post_type( 'Notes', $args );

		}

// Hook into the 'init' action
add_action( 'init', 'wp_handshaken_custom_post_type', 0 );

}
	}

	public function init_taxonomies() {

	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );