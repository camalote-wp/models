<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/tingeka
 * @since      1.0.0
 *
 * @package    Et_Models
 * @subpackage Et_Models/admin
 */

namespace EnfantTerrible\Models\Definitions\ExampleModel;

use EnfantTerrible\Models\Interfaces\Registerable;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Et_Models
 * @subpackage Et_Models/admin
 * @author     Martín García <tin.geka@gmail.com>
 */
class Bootstrap implements Registerable {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The model name.
	 * 
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $model_name    The name of the model.
	 */
	private $model_name = 'ExampleModel';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the model.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function register() {
		$this->register_post_type();
		$this->register_meta();
		$this->register_taxonomy();
	}

	private function register_post_type() {
		$labels = array(

			'name'                     => __( 'Example Model', 'et-models' ),
			'singular_name'            => __( 'Example Model Item', 'et-models' ),
			'add_new'                  => __( 'Add New', 'et-models' ),
			'add_new_item'             => __( 'Add New Example Model Item', 'et-models' ),
			'edit_item'                => __( 'Edit Example Model Item', 'et-models' ),
			'new_item'                 => __( 'New Example Model Item', 'et-models' ),
			'view_item'                => __( 'View Example Model Item', 'et-models' ),
			'view_items'               => __( 'View Example Models', 'et-models' ),
			'search_items'             => __( 'Search Example Models', 'et-models' ),
			'not_found'                => __( 'No Example Models found.', 'et-models' ),
			'not_found_in_trash'       => __( 'No Example Models found in Trash.', 'et-models' ),
			'parent_item_colon'        => __( 'Parent Example Models:', 'et-models' ),
			'all_items'                => __( 'All Example Models', 'et-models' ),
			'archives'                 => __( 'Example Model Archives', 'et-models' ),
			'attributes'               => __( 'Example Model Attributes', 'et-models' ),
			'insert_into_item'         => __( 'Insert into Example Model', 'et-models' ),
			'uploaded_to_this_item'    => __( 'Uploaded to this Example Model', 'et-models' ),
			'featured_image'           => __( 'Featured Image', 'et-models' ),
			'set_featured_image'       => __( 'Set featured image', 'et-models' ),
			'remove_featured_image'    => __( 'Remove featured image', 'et-models' ),
			'use_featured_image'       => __( 'Use as featured image', 'et-models' ),
			'menu_name'                => __( 'Example Models', 'et-models' ),
			'filter_items_list'        => __( 'Filter Example Model list', 'et-models' ),
			'filter_by_date'           => __( 'Filter by date', 'et-models' ),
			'items_list_navigation'    => __( 'Example Models list navigation', 'et-models' ),
			'items_list'               => __( 'Example Models list', 'et-models' ),
			'item_published'           => __( 'Example Model published.', 'et-models' ),
			'item_published_privately' => __( 'Example Model published privately.', 'et-models' ),
			'item_reverted_to_draft'   => __( 'Example Model reverted to draft.', 'et-models' ),
			'item_scheduled'           => __( 'Example Model scheduled.', 'et-models' ),
			'item_updated'             => __( 'Example Model updated.', 'et-models' ),
			'item_link'                => __( 'Example Model Link', 'et-models' ),
			'item_link_description'    => __( 'A link to an example model.', 'et-models' ),

		);

		$args = array(

			'labels'                => $labels,
			'description'           => __( 'organize and manage company example models', 'et-models' ),
			'public'                => false,
			'hierarchical'          => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => false,
			'show_in_admin_bar'     => false,
			'show_in_rest'          => true,
			'menu_position'         => null,
			'menu_icon'             => 'dashicons-megaphone',
			'capability_type'       => 'post',
			'capabilities'          => array(),
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'            => array(),
			'has_archive'           => false,
			'rewrite'               => array(
				'slug'       => 'example-models',
			),
			'query_var'             => true,
			'can_export'            => true,
			'delete_with_user'      => false,
			'template'              => array(),
			'template_lock'         => false,

		);

		register_post_type( 'example_model', $args );
	}

	private function register_meta() {
		register_post_meta( 'example_model', 'example_meta_key', array(
			'show_in_rest' => true,
			'type'         => 'string',
			'single'       => true,
			'default'      => '',
		) );
	}

	private function register_taxonomy() {
		// Add new taxonomy, make it hierarchical (like categories)
		$labels = array(
			'name'              => __( 'Example categories', 'et-models' ),
			'singular_name'     => __( 'Example category', 'et-models' ),
			'search_items'      => __( 'Search Example categories', 'et-models' ),
			'all_items'         => __( 'All Example categories', 'et-models' ),
			'parent_item'       => __( 'Parent Example category', 'et-models' ),
			'parent_item_colon' => __( 'Parent Example category:', 'et-models' ),
			'edit_item'         => __( 'Edit Example category', 'et-models' ),
			'update_item'       => __( 'Update Example category', 'et-models' ),
			'add_new_item'      => __( 'Add New Example category', 'et-models' ),
			'new_item_name'     => __( 'New Example category Name', 'et-models' ),
			'menu_name'         => __( 'Example categories', 'et-models' ),
		);

		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'example-category' ),
		);

		register_taxonomy( 'example_category', array( 'example_model' ), $args );

		unset( $args );
		unset( $labels );

		// Add new taxonomy, NOT hierarchical (like tags)
		$labels = array(
			'name'                       => __( 'Example tags', 'taxonomy general name', 'et-models' ),
			'singular_name'              => __( 'Example tag', 'taxonomy singular name', 'et-models' ),
			'search_items'               => __( 'Search Example tags', 'et-models' ),
			'popular_items'              => __( 'Popular Example tags', 'et-models' ),
			'all_items'                  => __( 'All Example tags', 'et-models' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Example tag', 'et-models' ),
			'update_item'                => __( 'Update Example tag', 'et-models' ),
			'add_new_item'               => __( 'Add New Example tag', 'et-models' ),
			'new_item_name'              => __( 'New Example tag Name', 'et-models' ),
			'separate_items_with_commas' => __( 'Separate example tags with commas', 'et-models' ),
			'add_or_remove_items'        => __( 'Add or remove example tags', 'et-models' ),
			'choose_from_most_used'      => __( 'Choose from the most used example tags', 'et-models' ),
			'not_found'                  => __( 'No example tags found.', 'et-models' ),
			'menu_name'                  => __( 'Example tags', 'et-models' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'example-tag' ),
		);

		register_taxonomy( 'example_tag', 'example_model', $args );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Et_Models_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Et_Models_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/et-models-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Et_Models_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Et_Models_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/et-models-admin.js', array( 'jquery' ), $this->version, false );

	}

}
