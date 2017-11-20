<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://viewup.com.br/
 * @since             1.0.0
 * @package           Cnf
 *
 * @wordpress-plugin
 * Plugin Name:       Custom 404
 * Plugin URI:        https://github.com/viewup/custom-404
 * Description:       Customize your 404 (Not Found) page!
 * Version:           1.0.0
 * Author:            Viewup
 * Author URI:        http://viewup.com.br/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       cnf
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'WPINC' ) or die;

define( 'CUSTOM_404_VERSION', '1.0.0' );
define( 'CUSTOM_404_DIR_PATH', plugin_dir_path( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-cnf-activator.php
 */
function activate_cnf() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cnf-activator.php';
	Cnf_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-cnf-deactivator.php
 */
function deactivate_cnf() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-cnf-deactivator.php';
	Cnf_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_cnf' );
register_deactivation_hook( __FILE__, 'deactivate_cnf' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-cnf.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_cnf() {

	$plugin = new Cnf();
	$plugin->run();

}

run_cnf();
