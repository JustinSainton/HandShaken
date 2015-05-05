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

		register_post_type( 'Notes', $args );

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
		register_post_type( 'Recipients', $args );

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
		register_post_type( 'Templates', $args );

	}

	/**
	 * Adds a box to the main column on the Notes edit screen.
	 */
	function handshaken_add_meta_box() {
		
		add_meta_box( 
			'handshaken_fields', 
			__( 'Handwritten Note Options', 'handshaken' ), 
			'handshaken_metabox_callback',
			'notes'
		);
	}

	add_action( 'add_meta_boxes', 'handshaken_add_meta_box' );

	/**
	 * Prints the box content.
	 * 
	 * @param WP_Post $post The object for the current post/page.
	 */
	function handshaken_meta_box_callback( $post ) {

		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'handshaken_meta_box', 'handshaken_meta_box_nonce' );

		/*
		 * Use get_post_meta() to retrieve an existing value
		 * from the database and use the value for the form.
		 */
		$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

		echo '<label for="handshaken_message">';
		_e( 'Note Message', 'handshaken' );
		echo '</label> ';
		echo '<textarea id="handshaken_message" name="handshaken_new_field">' . esc_attr( $value ) . '</textarea>';
	}

	/**
	 * When the post is saved, saves our custom data.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	function myplugin_save_meta_box_data( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['handshaken_meta_box_nonce'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['handshaken_meta_box_nonce'], 'handshaken_meta_box' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		/* OK, it's safe for us to save the data now. */
		
		// Make sure that it is set.
		if ( ! isset( $_POST['handshaken_new_field'] ) ) {
			return;
		}

		// Sanitize user input.
		$my_data = sanitize_text_field( $_POST['handshaken_new_field'] );

		// Update the meta field in the database.
		update_post_meta( $post_id, '_my_meta_value_key', $my_data );
	}
	add_action( 'save_post', 'handshaken_save_meta_box_data' );



	public function init_taxonomies() {

	}

	private function includes() {
		include_once 'bond-api.php';
	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );