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
 * @package           CamaloteWP/Models
 *
 * @wordpress-plugin
 * Plugin Name:       CamaloteWP - Models
 * Plugin URI:        https://github.com/camalote-wp/models
 * Description:       CamaloteWP defines content models, including post types, taxonomies, metadata, templates, and blocks.
 * Version:           1.0.0
 * Author:            Martín García
 * Author URI:        https://github.com/tingeka/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       camalote-wp-models
 * Domain Path:       /languages
 */

use CamaloteWP\Models\Core\Activator;
use CamaloteWP\Models\Core\Deactivator;
use CamaloteWP\Models\Core\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'CAMALOTE_WP_MODELS_VERSION', '1.0.0' );
define( 'CAMALOTE_WP_MODELS_DIR', plugin_dir_path( __FILE__ ) );
define( 'CAMALOTE_WP_MODELS_URL', plugin_dir_url( __FILE__ ) );

/**
 * Imports the Composer autoloader if it exists.
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * The code that runs during plugin activation.
 */
function activate_camalote_wp_models() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_camalote_wp_models() {
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_camalote_wp_models' );
register_deactivation_hook( __FILE__, 'deactivate_camalote_wp_models' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_camalote_wp_models() {

	$plugin = new Plugin();
	$plugin->run();

}
run_camalote_wp_models();
