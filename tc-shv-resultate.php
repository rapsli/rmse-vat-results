<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name: Handball SHV Resultate Anbindung
 * Plugin URI:  http://plugins.svn.wordpress.org/tc-shv-resultate/
 * Description: Aktualisiert die Resultate vom SHV direkt im eigenen Wordpress.
 * Version:     1.1.2
 * Author:      Rico Metzger
 * Author URI:  https://titaniumcoder.com
 * License:     Apache License, Version 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: tc-shv-resultate
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
 */
function run_tc_shv_resultate() {

	$plugin = new TcShvResultate();
	$plugin->run();

}
run_tc_shv_resultate();
?>
