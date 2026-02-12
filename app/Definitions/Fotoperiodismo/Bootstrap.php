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

namespace EnfantTerrible\Models\Definitions\Fotoperiodismo;

use EnfantTerrible\Models\Interfaces\Registerable;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\PostType;
use EnfantTerrible\Models\Definitions\Fotoperiodismo\Meta;

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
	private $model_name = 'fotoperiodismo';

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
		PostType::register( $this->model_name );
		Meta::register( $this->model_name );
	}

}
