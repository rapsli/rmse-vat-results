<?php

/**
 * Plugin Name: Handball VAT Results ClubAPI Integration
 * Description: Reads and actualizes results and rankings directly via VAT interface
 * Version:     1.0.0
 * Author:      Rico Metzger
 * Author URI:  https://rmse.ch
 * License:     Apache License, Version 2.0
 * License URI: https://www.apache.org/licenses/LICENSE-2.0
 * Text Domain: rmse-vat-results
 * Domain Path: /languages
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
require_once plugin_dir_path(__FILE__) . 'rmse-vat-results-vat-rest.php';

/**
 * Settings Admin page
 */
require_once plugin_dir_path(__FILE__) . 'rmse-vat-results-settings.php';

/*
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_rmse_vat_results_block_init()
{
	register_block_type(__DIR__ . '/build/games');
	register_block_type(__DIR__ . '/build/highlight-result');
	register_block_type(__DIR__ . '/build/highlight-preview');
	register_block_type(__DIR__ . '/build/rankings');
	register_block_type(__DIR__ . '/build/schedule');

	$team_selection = rmse_vat_results_load_team_selection();
	if (false !== $team_selection) {
		$team_selection = json_encode($team_selection);
		wp_add_inline_script('rmse-vat-results-rankings-editor-script', 'var rmse_vat_team_selection = ' . $team_selection . ';', 'before');
		wp_add_inline_script('rmse-vat-results-schedule-editor-script', 'var rmse_vat_team_selection = ' . $team_selection . ';', 'before');
		wp_add_inline_script('rmse-vat-results-highlight-result-editor-script', 'var rmse_vat_team_selection = ' . $team_selection . ';', 'before');
		wp_add_inline_script('rmse-vat-results-highlight-preview-editor-script', 'var rmse_vat_team_selection = ' . $team_selection . ';', 'before');
	}

}
add_action('init', 'create_block_rmse_vat_results_block_init');

function rmse_vat_results_enqueue()
{
	wp_enqueue_style(
		'rmse_vat_results_css',
		plugin_dir_url(__FILE__) . 'styles.css'
	);
}

add_action('wp_enqueue_scripts', 'rmse_vat_results_enqueue');
add_action('admin_enqueue_scripts', 'rmse_vat_results_enqueue');

// rest endpoint preparation, for now just the teams with team id and group short cut for the selection fields
function rmse_vat_results_rest_api_routes()
{
	add_action('rest_api_init', function () {
		register_rest_route('rmse-vat-results/v2', '/teams', array(
			'methods' => 'GET',
			'callback' => 'rmse_vat_results_load_team_selection',
			'permission_callback' => '__return_true'
		)
		);
	});
}

add_action('init', 'rmse_vat_results_rest_api_routes');

function rmse_vat_results_load_textdomain() {
	load_plugin_textdomain( 'rmse-vat-results', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action('init', 'rmse_vat_results_load_textdomain');

# TODO: remove once the plugin has been released and I can use translations of wordpress...
function rmse_vat_load_own_textdomain( $mofile, $domain ) {
	if ( 'rmse-vat-results' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		var_dump($locale);
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
		var_dump($mofile);
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'rmse_vat_load_own_textdomain', 10, 2 );
?>
