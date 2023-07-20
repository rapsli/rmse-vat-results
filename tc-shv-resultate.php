<?php

/**
 * Plugin Name: Handball VAT Results ClubAPI Integration
 * Description: Reads and actualizes results and rankings directly via VAT interface
 * Version:     2.0.0
 * Author:      Rico Metzger
 * Author URI:  https://rmse.ch
 * License:     Apache License, Version 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: tc-shv-resultate
 *
 * @package           create-block
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

*
* @see https://developer.wordpress.org/reference/functions/register_block_type/
*/
function create_block_tc_shv_resultate_block_init() {
 register_block_type( __DIR__ . '/build' );
}
add_action( 'init', 'create_block_tc_shv_resultate_block_init' );
?>
