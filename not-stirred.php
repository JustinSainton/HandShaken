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

		register_post_type( 'notes', $args );

		$labels = array(
			'name'                => _x( 'Recipients', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Recipient', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Recipients', 'handshaken' ),
			'name_admin_bar'      => __( 'Recipients', 'handshaken' ),
			'all_items'           => __( 'All Recipients', 'handshaken' ),
			'add_new_item'        => __( 'Add New Recipient', 'handshaken' ),
			'add_new'             => __( 'Add New', 'handshaken' ), // Is this needed?
			'edit_item'           => __( 'Edit Recipient', 'handshaken' ),
			'update_item'         => __( 'Update Recipient', 'handshaken' ),
			'view_item'           => __( 'View Recipient', 'handshaken' ),
			'search_items'        => __( 'Search Recipients', 'handshaken' ),
			'not_found'           => __( 'Recipient not found', 'handshaken' ),
			'not_found_in_trash'  => __( 'Recipient not found in Trash', 'handshaken' ),
		);
		$args = array(
			'label'               => __( 'Recipients', 'handshaken' ),
			'description'         => __( 'Recipient of a Bond Handwritten Note', 'handshaken' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields', ),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=notes',
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'recipients', $args );

		$labels = array(
			'name'                => _x( 'Templates', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Template', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Templates', 'handshaken' ),
			'name_admin_bar'      => __( 'Templates', 'handshaken' ),
			'all_items'           => __( 'All Templates', 'handshaken' ),
			'add_new_item'        => __( 'Add New Template', 'handshaken' ),
			'add_new'             => __( 'Add New', 'handshaken' ), // Is this needed?
			'edit_item'           => __( 'Edit Template', 'handshaken' ),
			'update_item'         => __( 'Update Template', 'handshaken' ),
			'view_item'           => __( 'View Template', 'handshaken' ),
			'search_items'        => __( 'Search Templates', 'handshaken' ),
			'not_found'           => __( 'Template not found', 'handshaken' ),
			'not_found_in_trash'  => __( 'Template not found in Trash', 'handshaken' ),
		);
		$args = array(
			'label'               => __( 'Templates', 'handshaken' ),
			'description'         => __( 'Template of a Bond Handwritten Note', 'handshaken' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields', ),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => 'edit.php?post_type=notes',
			'menu_position'       => 5,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'templates', $args );

	}

	public function init_taxonomies() {

	}

	private function includes() {
		include_once 'bond-api.php';
	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );