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
		// add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
  //       add_action( 'save_post', array( $this, 'save' ) );
	}

	public function init_post_types() {

		$labels = array(
			'name'                => _x( 'Notes', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Note', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Notes', 'handshaken' ),
			'name_admin_bar'      => __( 'Notes', 'handshaken' ),
			'all_items'           => __( 'All Notes', 'handshaken' ),
			'add_new_item'        => __( 'Create New Note', 'handshaken' ),
			'add_new'             => __( 'Create New Note', 'handshaken' ), 
			'new_item'            => __( 'New Note', 'handshaken' ),
			'edit_item'           => __( 'Edit Note', 'handshaken' ),
			'view_item'           => __( 'View Note', 'handshaken' ),
			'search_items'        => __( 'Search Notes', 'handshaken' ),
			'not_found'           => __( 'No notes found', 'handshaken' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'handshaken' ),
		);

		$args = array(
			'label'               => __( 'Notes', 'handshaken' ),
			'description'         => __( 'Custom handwritten notes', 'handshaken' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields', 'editor' ),
			'taxonomies'          => array( 'category' ),
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
			'all_items'           => __( 'Recipients', 'handshaken' ),
			'add_new_item'        => __( 'Add New Recipient', 'handshaken' ),
			'add_new'             => __( 'Add New Recipient', 'handshaken' ), 
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
			'taxonomies'          => array( 'category' ),
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
			'name'                => _x( 'Senders', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Sender', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Senders', 'handshaken' ),
			'name_admin_bar'      => __( 'Senders', 'handshaken' ),
			'all_items'           => __( 'Senders', 'handshaken' ),
			'add_new_item'        => __( 'Add New Sender', 'handshaken' ),
			'add_new'             => __( 'Add New Sender', 'handshaken' ), 
			'edit_item'           => __( 'Edit Sender', 'handshaken' ),
			'update_item'         => __( 'Update Sender', 'handshaken' ),
			'view_item'           => __( 'View Sender', 'handshaken' ),
			'search_items'        => __( 'Search Senders', 'handshaken' ),
			'not_found'           => __( 'Sender not found', 'handshaken' ),
			'not_found_in_trash'  => __( 'Sender not found in Trash', 'handshaken' ),
		);
		$args = array(
			'label'               => __( 'Senders', 'handshaken' ),
			'description'         => __( 'Sender of a Bond Handwritten Note', 'handshaken' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'custom-fields', ),
			'taxonomies'          => array( 'category' ),
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
		register_post_type( 'senders', $args );

		$labels = array(
			'name'                => _x( 'Templates', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Template', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Templates', 'handshaken' ),
			'name_admin_bar'      => __( 'Templates', 'handshaken' ),
			'all_items'           => __( 'Templates', 'handshaken' ),
			'add_new_item'        => __( 'Add New Template', 'handshaken' ),
			'add_new'             => __( 'Add New Template', 'handshaken' ),
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
			'supports'            => array( 'title', 'editor' ),
			'taxonomies'          => array(),
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

	/**
	 * Adds the meta box container.
	 */

	// public function add_meta_box( $post_type ) {
 //        $post_types = 'notes';             //limit meta box to certain post types
 //        if ( in_array( $post_type, $post_types )) {
	// 		add_meta_box(
	// 			'handshaken_fields', 
	// 			__( 'Handwritten Note Options', 'handshaken' ), 
	// 			array( $this, 'render_meta_box_content' ),
	// 			$post_type,
	// 			'advanced',
	// 			'high'
	// 		);
	//     }
	// }

	// /**
	//  * Save the meta when the post is saved.
	//  *
	//  * @param int $post_id The ID of the post being saved.
	//  */
	// public function save( $post_id ) {
	
	// 	/*
	// 	 * We need to verify this came from the our screen and with proper authorization,
	// 	 * because save_post can be triggered at other times.
	// 	 */

	// 	// Check if our nonce is set.
	// 	if ( ! isset( $_POST['handshaken_inner_custom_box_nonce'] ) )
	// 		return $post_id;

	// 	$nonce = $_POST['handshaken_inner_custom_box_nonce'];

	// 	// Verify that the nonce is valid.
	// 	if ( ! wp_verify_nonce( $nonce, 'handshaken_inner_custom_box' ) )
	// 		return $post_id;

	// 	// If this is an autosave, our form has not been submitted,
 //                //     so we don't want to do anything.
	// 	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	// 		return $post_id;

	// 	// Check the user's permissions.
	// 	if ( 'page' == $_POST['post_type'] ) {

	// 		if ( ! current_user_can( 'edit_page', $post_id ) )
	// 			return $post_id;
	
	// 	} else {

	// 		if ( ! current_user_can( 'edit_post', $post_id ) )
	// 			return $post_id;
	// 	}

	// 	/* OK, its safe for us to save the data now. */

	// 	// Sanitize the user input.
	// 	$mydata = sanitize_text_field( $_POST['handshaken_message'] );

	// 	// Update the meta field.
	// 	update_post_meta( $post_id, '_my_meta_value_key', $mydata );
	// }


	// /**
	//  * Render Meta Box content.
	//  *
	//  * @param WP_Post $post The post object.
	//  */
	// public function render_meta_box_content( $post ) {
	
	// 	// Add an nonce field so we can check for it later.
	// 	wp_nonce_field( 'handshaken_inner_custom_box', 'handshaken_inner_custom_box_nonce' );

	// 	// Use get_post_meta to retrieve an existing value from the database.
	// 	$value = get_post_meta( $post->ID, '_my_meta_value_key', true );

	// 	// Display the form, using the current value.
	// 	echo '<label for="handshaken_message">';
	// 	_e( 'Note Message', 'handshaken' );
	// 	echo '</label> ';
	// 	echo '<textarea id="handshaken_message" name="handshaken_message">' . esc_attr( $value ) . '</textarea>';

	// 	echo '<label for="myplugin_new_field">';
	// 	_e( 'Description for this field', 'myplugin_textdomain' );
	// 	echo '</label> ';
	// 	echo '<input type="text" id="myplugin_new_field" name="myplugin_new_field"';
 //                echo ' value="' . esc_attr( $value ) . '" size="25" />';



	public function init_taxonomies() {

	}

	private function includes() {
		include_once 'bond-api.php';
	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );