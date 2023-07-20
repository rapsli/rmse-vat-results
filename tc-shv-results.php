<?php

/**
 * Plugin Name: Handball VAT Results ClubAPI Integration
 * Description: Reads and actualizes results and rankings directly via VAT interface
 * Version:     2.0.0
 * Author:      Rico Metzger
 * Author URI:  https://rmse.ch
 * License:     Apache License, Version 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: tc-shv-results
 *
 * @package           create-block
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Settings Admin page
 */
require_once plugin_dir_path( __FILE__) . 'tc-shv-results-settings.php';

/*
*
* @see https://developer.wordpress.org/reference/functions/register_block_type/
*/
function create_block_tc_shv_results_block_init() {
 register_block_type( __DIR__ . '/build/games' );
 register_block_type( __DIR__ . '/build/highlight' );
 register_block_type( __DIR__ . '/build/rankings' );
 register_block_type( __DIR__ . '/build/schedule' );
}
add_action( 'init', 'create_block_tc_shv_results_block_init' );
?>
