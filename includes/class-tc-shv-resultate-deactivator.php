<?php

/**
 * Fired during plugin deactivation.
 */
class TcShvResultateDeactivator
{

    /**
     * This will just deactivate the Update of the Data in the DB
     */
    public static function deactivate()
    {
        // deactivate the update sceduler
        $timestamp = wp_next_scheduled('tc-shv-resultate-update-data');
        wp_unschedule_event($timestamp, 'tc-shv-resultate-update-data');

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
