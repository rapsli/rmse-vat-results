<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://gitlab.com/titaniumcoder/tc-shv-resultate-plugin
 * @since             1.0.0
 * @package           TcShvResultate
 *
 * @wordpress-plugin
 * Plugin Name: Handball SHV Resultate Anbindung
 * Plugin URI:  https://github.com/titaniumcoder/tc-shv-resultate-plugin
 * Description: Plugin um die Resultate des SHV in ein Wordpress einzubinden.
 * Version:     1.0.0
 * Author:      Rico Metzger
 * Author URI:  https://titaniumcoder.com
 * License:     Apache License, Version 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: tc-shv-resultate
 * Domain Path: /languages
 * GitHub Plugin URI: titaniumcoder/tc-shv-resultate-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rm-shv-resultate-activator.php
 */
function activate_tc_shv_resultate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tc-shv-resultate-activator.php';
	TcShvResultateActivator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rm-shv-resultate-deactivator.php
 */
function deactivate_tc_shv_resultate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tc-shv-resultate-deactivator.php';
	TcShvResultateDeactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tc_shv_resultate' );
register_deactivation_hook( __FILE__, 'deactivate_tc_shv_resultate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tc-shv-resultate.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tc_shv_resultate() {

	$plugin = new TcShvResultate();
	$plugin->run();

}
run_tc_shv_resultate();
?>
