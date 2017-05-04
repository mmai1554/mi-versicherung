<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mai-internet.de
 * @since             1.0.0
 * @package           Mi_Versicherung
 *
 * @wordpress-plugin
 * Plugin Name:       mi-versicherung
 * Plugin URI:        https://kachelblitz@bitbucket.org/mai-internet/mi-versicherung.git
 * Description:       Versicherungs-Makler Plugin. Arbeitet nur mit Theme mi-makler
 * Version:           1.0.0
 * Author:            Michael Mai
 * Author URI:        https://mai-internet.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mi-versicherung
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mi-versicherung-activator.php
 */
function activate_mi_versicherung() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mi-versicherung-activator.php';
	Mi_Versicherung_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mi-versicherung-deactivator.php
 */
function deactivate_mi_versicherung() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mi-versicherung-deactivator.php';
	Mi_Versicherung_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mi_versicherung' );
register_deactivation_hook( __FILE__, 'deactivate_mi_versicherung' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mi-versicherung.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mi_versicherung() {

	$plugin = new Mi_Versicherung();
	$plugin->run();

}
run_mi_versicherung();
