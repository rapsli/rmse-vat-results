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
if (!defined('WPINC')) {
	die;
}

/**
 * API Rest Calls
 */
require_once plugin_dir_path(__FILE__) . 'tc-shv-results-vat-rest.php';

/**
 * Settings Admin page
 */
require_once plugin_dir_path(__FILE__) . 'tc-shv-results-settings.php';

/*
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_tc_shv_results_block_init()
{
	register_block_type(__DIR__ . '/build/games');
	register_block_type(__DIR__ . '/build/highlight');
	register_block_type(__DIR__ . '/build/rankings');
	register_block_type(__DIR__ . '/build/schedule');
}
add_action('init', 'create_block_tc_shv_results_block_init');

function tc_shv_results_admin_enqueue()
{
	wp_enqueue_style(
		'tc_shv_results_admin_css',
		plugin_dir_url(__FILE__) . 'admin.css'
	);
}

// you may want to wrap add_action() in a conditional to prevent enqueue on every page
add_action('admin_enqueue_scripts', 'tc_shv_results_admin_enqueue');
?>
