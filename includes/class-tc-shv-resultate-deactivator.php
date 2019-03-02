<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    TcShvResultate
 * @subpackage TcShvResultate/includes
 */
class TcShvResultateDeactivator {

	/**
	 * This will just deactivate the Update of the Data in the DB
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// deactivate the update sceduler
		$timestamp = wp_next_scheduled('tc-shv-resultate-update-data');
		wp_unschedule_event ($timestamp, 'tc-shv-resultate-update-data');

		global $wpdb;

		delete_site_option('tc_shv_db_version');

		$group_table_name = $wpdb->prefix . 'tc_shv_group';
		$team_table_name = $wpdb->prefix . 'tc_shv_team';
		$game_table_name = $wpdb->prefix . 'tc_shv_game';
		$ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';
		$next_games_table_name = $wpdb->prefix . 'tc_shv_next_games';
		$last_results_table_name = $wpdb->prefix . 'tc_shv_last_results';
		$team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';
		$updated_table_name = $wpdb->prefix . 'tc_shv_updates';

		$wpdb->query("DROP TABLE $updated_table_name");
		$wpdb->query("DROP TABLE $team_games_table_name");
		$wpdb->query("DROP TABLE $next_games_table_name");
		$wpdb->query("DROP TABLE $last_results_table_name");
		$wpdb->query("DROP TABLE $ranking_table_name");
		$wpdb->query("DROP TABLE $team_table_name");
		$wpdb->query("DROP TABLE $group_table_name");
		$wpdb->query("DROP TABLE $game_table_name");
	}

}
