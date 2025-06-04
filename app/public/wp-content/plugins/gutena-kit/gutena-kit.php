<?php
/**
 * Gutena Kit
 *
 * @package           Gutena_Kit
 * @author            ExpressTech
 * @copyright         2022 ExpressTech
 * @license           GPL-3.0
 * 
 * Plugin Name:       Gutena Kit - Gutenberg Blocks and Templates
 * Plugin URI:        https://Gutena.io
 * Description:       Gutena Kit gives you advanced controls, powerful blocks and beautiful pre-made templates that help you build websites in less time! These templates can be imported in just a few clicks.
 * Version:           2.0.7
 * Requires at least: 6.5
 * Requires PHP:      5.6
 * Author:            ExpressTech
 * Author URI:        https://expresstech.io
 * License:           GPL v3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       gutena-kit
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin version.
 */
if ( ! defined( 'GUTENA_KIT_VERSION' ) ) {
	define( 'GUTENA_KIT_VERSION', '2.0.7' );
}

/**
 * Plugin dir path
 */
if ( ! defined( 'GUTENA_KIT_DIR_PATH' ) ) {
	define( 'GUTENA_KIT_DIR_PATH', trailingslashit(plugin_dir_path( __FILE__ ) ) );
}

/**
 * Plugin url
 */
if ( ! defined( 'GUTENA_KIT_PLUGIN_URL' ) ) {
	define( 'GUTENA_KIT_PLUGIN_URL', trailingslashit(plugins_url( '', __FILE__ ) ) );
}

if ( ! defined( 'GUTENA_KIT_MIN_FILE' ) ) {
	define( 'GUTENA_KIT_MIN_FILE', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min');
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gutena-kit-activator.php
 */
function activate_gutena_kit() {
	require_once GUTENA_KIT_DIR_PATH . 'includes/class-gutena-kit-activator.php';
	Gutena_Kit_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gutena-kit-deactivator.php
 */
function deactivate_gutena_kit() {
	require_once GUTENA_KIT_DIR_PATH . 'includes/class-gutena-kit-deactivator.php';
	Gutena_Kit_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gutena_kit' );
register_deactivation_hook( __FILE__, 'deactivate_gutena_kit' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require GUTENA_KIT_DIR_PATH . 'includes/class-gutena-kit.php';

/**
 * Begins execution of the plugin.
 */
function run_gutena_kit() {
	$plugin = new Gutena_Kit();
	$plugin->run();
}
run_gutena_kit();