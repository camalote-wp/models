<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/tingeka
 * @since             1.0.0
 * @package           Et_Models
 *
 * @wordpress-plugin
 * Plugin Name:       Enfant Terrible - Models
 * Plugin URI:        https://github.com/enfantterribleAR
 * Description:       Defines Enfant Terrible content models, including post types, taxonomies, metadata, templates, and blocks.
 * Version:           1.0.0
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       et-models
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ET_MODELS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-et-models-activator.php
 */
function activate_et_models() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-et-models-activator.php';
	Et_Models_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-et-models-deactivator.php
 */
function deactivate_et_models() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-et-models-deactivator.php';
	Et_Models_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_et_models' );
register_deactivation_hook( __FILE__, 'deactivate_et_models' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-et-models.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_et_models() {

	$plugin = new Et_Models();
	$plugin->run();

}
run_et_models();
