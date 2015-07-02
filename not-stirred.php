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
 * 4. Fix formatting of textarea field for messages in Notes so formatting sends to Bond correctly.
 * 5. Get custom fields to save on publish.
 * 6. Make sender default to user if they've been connected.
 * 7. Explore using placeholders from custom fields in message templates.
 * 8. Connect dropdowns with Select2 AJAX
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
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_select2_jquery' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_handshaken_styles' ) );
        add_filter( 'enter_title_here', function( $placeholder, $post ) {
            if ( 'recipients' == $post->post_type ) {
                $placeholder = 'Enter full name of recipient here';
            } else if ( 'message_templates' == $post->post_type ) {
                $placeholder = 'Enter message template name here';
            } else if ( 'notes' == $post->post_type ) {
                $placeholder = 'Enter note title here';
            } else if ( 'senders' == $post->post_type ) {
                $placeholder = 'Enter full name of sender here';
            }

            return $placeholder;
        }, 10, 2 );
	}

	public function enqueue_select2_jquery() {

		wp_register_style( 'select2css', 'http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css', false, '1.0', 'all' );
	    wp_register_script( 'select2', 'http://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js', array( 'jquery' ), '1.0', true );
	    wp_enqueue_style( 'select2css' );
	    wp_enqueue_script( 'select2' );

	}

	public function enqueue_handshaken_styles() {

		wp_register_style( 'handshaken_css', plugins_url( 'HandShaken/css/handshaken_style.css' ) );
	    wp_enqueue_style( 'handshaken_css' );

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
			'supports'            => array( 'title' ),
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
			'rewrite'  			  => array('slug' => 'notes'),

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
			'supports'            => array( 'title' ),
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
			'supports'            => array( 'title' ),
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
			'name'                => _x( 'Message Templates', 'Post Type General Name', 'handshaken' ),
			'singular_name'       => _x( 'Message Template', 'Post Type Singular Name', 'handshaken' ),
			'menu_name'           => __( 'Message Templates', 'handshaken' ),
			'name_admin_bar'      => __( 'Message Templates', 'handshaken' ),
			'all_items'           => __( 'Message Templates', 'handshaken' ),
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
			'label'               => __( 'Message Templates', 'handshaken' ),
			'description'         => __( 'Pre-drafted reusable messages', 'handshaken' ),
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
		register_post_type( 'message_templates', $args );

	}

	/**
	 * Adds the meta box container.
	 */

	public function add_meta_box( $post_type ) {
        $post_type = 'notes';             //limit meta box to certain post types
			add_meta_box(
				'handshaken_note_settings', 
				__( 'Note Settings', 'handshaken' ), 
				array( $this, 'render_meta_box_content_note' ),
				$post_type,
				'normal',
				'high'
			);
		$post_type = 'senders';             //limit meta box to certain post types
			add_meta_box(
				'handshaken_sender_settings', 
				__( 'Sender Settings', 'handshaken' ), 
				array( $this, 'render_meta_box_content_sender' ),
				$post_type,
				'normal',
				'high'
			);
		$post_type = 'recipients';             //limit meta box to certain post types
			add_meta_box(
				'handshaken_recipient_settings', 
				__( 'Recipient Settings', 'handshaken' ), 
				array( $this, 'render_meta_box_content_recipient' ),
				$post_type,
				'normal',
				'high'
			);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {
	
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['handshaken_inner_custom_box_nonce'] ) )
			return $post_id;

		$nonce = $_POST['handshaken_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'handshaken_inner_custom_box' ) )
			return $post_id;

		// If this is an autosave, our form has not been submitted,
                //     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			return $post_id;

		/* OK, its safe for us to save the data now. */

		// //All inputs fields
		// $inputs = array (
		// 	'handshaken_recipient',
		// 	'handshaken_sender',
		// 	'handshaken_stationary',
		// 	'handshaken_message_template',
		// 	'handshaken_message',
		// 	'handshaken_recipient_first',
		// 	'handshaken_recipient_organization',
		// 	'handshaken_recipient_address1',
		// 	'handshaken_recipient_address2',
		// 	'handshaken_recipient_city',
		// 	'handshaken_recipient_state',
		// 	'handshaken_recipient_zip',
		// 	'handshaken_sender_first',
		// 	'handshaken_sender_organization',
		// 	'handshaken_sender_address1',
		// 	'handshaken_sender_address2',
		// 	'handshaken_sender_city',
		// 	'handshaken_sender_state',
		// 	'handshaken_sender_zip',
		// 	'handshaken_sender_handwriting',
		// 	'handshaken_sender_user'
		// );


		// Sanitize the user input.
		$handshaken = array_map( 'sanitize_text_field', $_POST['handshaken'] );

		// Update the meta field.
		update_post_meta( $post_id, 'handshaken', $handshaken );
	}


	/**
	 * Render Note Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content_note( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'handshaken_inner_custom_box', 'handshaken_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'handshaken', true );

		// Display the form, using the current value.

		// Recipient Field
		echo '<p class="label">';
		echo '<label for="handshaken_recipient">';
		_e( 'Recipient', 'handshaken' ); 
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<select id="handshaken_recipient" name="handshaken[handshaken_recipient]" class="handshaken_field">';
			echo '<option value="Select a recipient" selected="selected">Select a recipient</option>';

               //The Loop for Recipients
				$args = array( 'post_type' => 'recipients', 'posts_per_page' => '-1' );
				$recipients = new WP_Query( $args );
				if ( $recipients->have_posts() ) : while ( $recipients->have_posts() ) : $recipients->the_post();
					echo '<option value="Add New Recipient">Add New Recipient</option>';
					echo '<option value="' . get_the_title() . '" ' . selected( get_the_title(), $value['handshaken_recipient'], false ) . '>' . get_the_title() . '</option>'; 
				endwhile;

				wp_reset_postdata();

				else:
					echo '<option value="Add New Recipient">Add New Recipient</option>';
				endif;

        echo '</select><br>'; 

        //Sender Field
        echo '<p class="label">';
        echo '<label for="handshaken_sender">';
		_e( 'Sender', 'handshaken' ); 
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<select id="handshaken_sender" name="handshaken[handshaken_sender]" class="handshaken_field">';
               echo '<option value="Select a sender" selected="selected">Select a sender</option>';
               echo '<option value="Add New Sender" >Add New Sender</option>';

               //The Loop for Senders
				$args = array( 'post_type' => 'senders', 'posts_per_page' => '-1' );
				$senders = new WP_Query( $args );
				if ( $senders->have_posts() ) : while ( $senders->have_posts() ) : $senders->the_post();
					echo '<option value="' . get_the_title() . '" ' . selected( get_the_title(), $value['handshaken_sender'], false ) . '>' . get_the_title() . '</option>'; 
				endwhile;

				wp_reset_postdata();

				else:
					echo '<option value="Add New Sender">Add New Sender</option>';
				endif;

        echo '</select><br>'; 

        //Stationary Field
        echo '<p class="label">';
        echo '<label for="handshaken_stationary">';
		_e( 'Stationary', 'handshaken' ); 
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<select id="handshaken_stationary" name="handshaken[handshaken_stationary]" class="handshaken_field">';
			echo '<option value="Select a stationary" selected="selected">Select a stationary</option>';
				
				//Stationary API and loop
				$data = json_decode( wp_remote_retrieve_body( wp_remote_get('https://private-85d07-bond.apiary-mock.com/account/products/?type=stationery&count=25&page=1&sort_by=id&sort_dir=asc' ) ) ); 
				$response = $data->data;

				foreach ( $response as $item ) {
					if( $item->type == 'stationery' ) {
						$stationary_id = $item->id; 
						$stationary_name = $item->name;
						$front = $item->front_img_url;
						echo '<option value="' . $stationary_name . '">' . $stationary_name . '</option>';
					}
				}
               
        echo '</select><br>'; 

        echo '<img src=' . $front . '" />';

        //Message Template Field
        echo '<p class="label">';
        echo '<label for="handshaken_message_template">';
		_e( 'Message Template', 'handshaken' ); 
		echo '</label> <br>';
		echo '(Optional) Choose a pre-drafted message instead of writing your own.';
		echo '</p>';
		echo '<select id="handshaken_message_template" name="handshaken[handshaken_message_template]" class="handshaken_field">';
               
               //The Loop for Message Templates
				$args = array( 'post_type' => 'message_templates', 'posts_per_page' => '-1' );
				$senders = new WP_Query( $args );
				if ( $senders->have_posts() ) : while ( $senders->have_posts() ) : $senders->the_post();
					echo '<option value="I will write my own message">I will write my own message</option>';
					echo '<option value="' . get_the_title() . '" ' . selected( get_the_title(), $value['handshaken_message_template'], false ) . '>' . get_the_title() . '</option>'; 				
				endwhile;

				wp_reset_postdata();

				else:
					echo '<option value="I will write my own message">I will write my own message</option>';
				endif;

        echo '</select><br>'; 

        //Message Field
        echo '<p class="label">';
		echo '<label for="handshaken_message">';
		_e( 'Message', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo 'Write your message below.';
		echo '</p>';
		echo '<textarea id="handshaken_message" name="handshaken[handshaken_message]" class="handshaken_field">' . esc_textarea( $value['handshaken_message'] ) . '</textarea>';
    }

    /**
	 * Render Recipient Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content_recipient( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'handshaken_inner_custom_box', 'handshaken_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'handshaken', true );

		// Display the form, using the current value.
		echo '<p class="label">';
		echo '<label for="handshaken_recipient_first">';
		_e( 'First Name', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_first" name="handshaken[handshaken_recipient_first]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_first' ] ) . '" />';

        //Organization Name
        echo '<p class="label">';
		echo '<label for="handshaken_recipient_organization">';
		_e( 'Organization Name', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_organization" name="handshaken[handshaken_recipient_organization]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_organization' ] ) . '" />';

        //Address Line 1
        echo '<p class="label">';
		echo '<label for="handshaken_recipient_address1">';
		_e( 'Address Line 1', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_address1" name="handshaken[handshaken_recipient_address1]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_address1' ] ) . '" />';

        //Address Line 2
		echo '<p class="label">';
		echo '<label for="handshaken_recipient_address2">';
		_e( 'Address Line 2', 'handshaken' );
		echo '</label> <br>';
		echo 'Enter sender&#39;s suite, apt, etc.';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_address2" name="handshaken[handshaken_recipient_address2]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_address2' ] ) . '" />';

        //City
        echo '<p class="label">';
		echo '<label for="handshaken_recipient_city">';
		_e( 'City', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_city" name="handshaken[handshaken_recipient_city]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_city' ] ) . '" />';

        //State/Providence
        echo '<p class="label">';
		echo '<label for="handshaken_recipient_state">';
		_e( 'State/Providence', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_state" name="handshaken[handshaken_recipient_state]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_state' ] ) . '" />';

        //Zip/Postal Code
        echo '<p class="label">';
		echo '<label for="handshaken_recipient_zip">';
		_e( 'Zip/Postal Code', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_recipient_zip" name="handshaken[handshaken_recipient_zip]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_recipient_zip' ] ) . '" />';

    }

    /**
	 * Render Sender Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content_sender( $post ) {
	
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'handshaken_inner_custom_box', 'handshaken_inner_custom_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, 'handshaken', true );

		// Display the form, using the current value.

		//First Name
		echo '<p class="label">';
		echo '<label for="handshaken_sender_first">';
		_e( 'First Name', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_first" name="handshaken[handshaken_sender_first]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_first' ] ) . '" />';

        //Organization Name
        echo '<p class="label">';
		echo '<label for="handshaken_sender_organization">';
		_e( 'Organization Name', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_organization" name="handshaken[handshaken_sender_organization]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_organization' ] ) . '" />';

        //Address Line 1
        echo '<p class="label">';
		echo '<label for="handshaken_sender_address1">';
		_e( 'Address Line 1', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_address1" name="handshaken[handshaken_sender_address1]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_address1' ] ) . '" />';

        //Address Line 2
		echo '<p class="label">';
		echo '<label for="handshaken_sender_address2">';
		_e( 'Address Line 2', 'handshaken' );
		echo '</label> <br>';
		echo 'Enter sender&#39;s suite, apt, etc.';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_address2" name="handshaken[handshaken_sender_address2]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_address2' ] ) . '" />';

        //City
        echo '<p class="label">';
		echo '<label for="handshaken_sender_city">';
		_e( 'City', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_city" name="handshaken[handshaken_sender_city]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_city' ] ) . '" />';

        //State/Providence
        echo '<p class="label">';
		echo '<label for="handshaken_sender_state">';
		_e( 'State/Providence', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_state" name="handshaken[handshaken_sender_state]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_state' ] ) . '" />';

        //Zip/Postal Code
        echo '<p class="label">';
		echo '<label for="handshaken_sender_zip">';
		_e( 'Zip/Postal Code', 'handshaken' );
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<input type="text" class="handshaken_field" id="handshaken_sender_zip" name="handshaken[handshaken_sender_zip]"';
            echo ' value="' . esc_attr( $value[ 'handshaken_sender_zip' ] ) . '" />';

        //Handwriting Style
        echo '<p class="label">';
        echo '<label for="handshaken_sender_handwriting">';
		_e( 'Handwriting Style', 'handshaken' ); 
		echo '<span class="required">*</span>';
		echo '</label> <br>';
		echo '</p>';
		echo '<select id="handshaken_sender_handwriting" name="handshaken[handshaken_sender_handwriting]" class="handshaken_field">';
			echo '<option value="Select a handwriting style" selected="selected">Select a handwriting style</option>';
				
				//Stationary API and loop
				$stationary = wp_remote_get( 'https://private-85d07-bond.apiary-mock.com/account/products/?type=stationery&count=25&page=1&sort_by=id&sort_dir=asc' );
				$data = json_decode( wp_remote_retrieve_body( wp_remote_get('https://private-85d07-bond.apiary-mock.com/account/products/?type=stationery&count=25&page=1&sort_by=id&sort_dir=asc' ) ) ); 
				$response = $data->data;

				foreach ( $response as $item ) {
					if( $item->type == 'handwriting-style' ) {
						$handwriting_id = $item->id; 
						$handwriting_name = $item->name;
						echo '<option value="' . $handwriting_name . '">' . $handwriting_name . '</option>';
					}
				}
               
        echo '</select><br>'; 

        //Existing User
        echo '<p class="label">';
        echo '<label for="handshaken_sender_user">';
		_e( 'Connect with a user?', 'handshaken' ); 
		echo '</label> <br>';
		echo 'Choose the existing user this sender will be associated with.';
		echo '</p>';
		echo '<select id="handshaken_sender_user" name="handshaken[handshaken_sender_user]" class="handshaken_field">';
			echo '<option value="Select a user" selected="selected">Select a user</option>';
				
				//User Data
				$args = array(
					'blog_id'      => $GLOBALS['blog_id'],
					'role'         => array( 'author', 'editor', 'admin' ),
					'orderby'      => 'nicename',
					'order'        => 'ASC',
				);
				$blogusers = get_users( $args );
				
				// User loop
				foreach ( $blogusers as $user ) {
					$user_name = $user->display_name;
					echo '<option value="' . $user_name . '">' . $user_name . '</option>';
				}
               
        echo '</select><br>'; 

    }

	public function init_taxonomies() {

	}

	private function includes() {
		include_once 'bond-api.php';
	}
}

add_action( 'plugins_loaded', 'WP_HandShaken::get_instance' );