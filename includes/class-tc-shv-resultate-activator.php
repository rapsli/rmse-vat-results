<?php

/**
 * Fired during plugin activation.
 */
class TcShvResultateActivator
{

    /**
     * Will initialize or update the tables.
     *
     * This also means that the plugin needs to be deactivated and reactivated should
     * the version change but this is acceptable as the data will be restored within
     * minutes.
     */
    public static function activate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $group_table_name = $wpdb->prefix . 'tc_shv_group';
        $team_table_name = $wpdb->prefix . 'tc_shv_team';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';
        $ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';
        $next_games_table_name = $wpdb->prefix . 'tc_shv_next_games';
        $last_results_table_name = $wpdb->prefix . 'tc_shv_last_results';
        $team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';
        $updated_table_name = $wpdb->prefix . 'tc_shv_updates';
        $logo_table_name = $wpdb->prefix . 'tc_shv_team_logos';

        $sql = "CREATE TABLE $group_table_name (
		  id mediumint(9) NOT NULL,
		  group_text tinytext NOT NULL,
		  total_teams tinyint(4) NOT NULL,
		  promotion_candidate tinyint(4) NOT NULL,
		  relegation_candidate tinyint(4) NOT NULL,
		  direct_promotion tinyint(4) NOT NULL,
		  direct_relegation tinyint(4) NOT NULL,
		  league_long tinytext NOT NULL,
		  league_short tinytext NOT NULL,
		  league_id mediumint(9) NOT NULL,
		  modus text NOT NULL,
		  total_rounds smallint(6) NOT NULL,
		  games_per_round smallint(6) NOT NULL,
		  modus_html text,
		  PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $team_table_name (
			id mediumint(9) NOT NULL,
			name tinytext NOT NULL,
			club_id mediumint(9) DEFAULT -1 NOT NULL,
			group_id mediumint(9),
			league_id mediumint(9) NOT NULL,
			group_name tinytext NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $logo_table_name (
			id mediumint(9) NOT NULL,
			logo_url tinytext,
			logo_path tinytext,
			logo_last_update datetime,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $game_table_name (
			id mediumint(9) NOT NULL,
			game_no mediumint(9) NOT NULL,
			game_date datetime NOT NULL,
			type tinytext NOT NULL,
			home tinytext NOT NULL,
			guest tinytext NOT NULL,
			league tinytext NOT NULL,
			league_full text NOT NULL,
			played boolean NOT NULL,
			halftime_home smallint(6) NOT NULL,
			halftime_guest smallint(6) NOT NULL,
			result_home smallint(6) NOT NULL,
			result_guest smallint(6) NOT NULL,
			venue tinytext NOT NULL,
			address tinytext,
			spectators smallint(6) DEFAULT 0 NOT NULL,
			preview text,
			report text,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $ranking_table_name (
			id bigint(21) NOT NULL AUTO_INCREMENT,
			group_id mediumint(9) NOT NULL,
			ranking tinyint(4) DEFAULT 1 NOT NULL,
			team tinytext NOT NULL,
			total_points smallint(6) DEFAULT 0 NOT NULL,
			total_wins smallint(6) DEFAULT 0 NOT NULL,
			total_draws smallint(6) DEFAULT 0 NOT NULL,
			total_loss smallint(6) DEFAULT 0 NOT NULL,
			total_scores_plus smallint(6) DEFAULT 0 NOT NULL,
			total_scores_minus smallint(6) DEFAULT 0 NOT NULL,
			total_games smallint(6) DEFAULT 0 NOT NULL,
			total_scores_diff SMALLINT(6) DEFAULT 0 NOT NULL,
			direct_promotion boolean NOT NULL,
			promotion boolean NOT NULL,
			relegation boolean NOT NULL,
			direct_relegation boolean NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $next_games_table_name (
			id bigint(21) NOT NULL AUTO_INCREMENT,
			game_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $last_results_table_name (
			id bigint(21) NOT NULL AUTO_INCREMENT,
			game_id mediumint(9) NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;
		CREATE TABLE $team_games_table_name (
			team_id mediumint(9) NOT NULL,
			game_id mediumint(9) NOT NULL,
			PRIMARY KEY  (team_id, game_id)
		) $charset_collate;
		CREATE TABLE $updated_table_name (
			id bigint(21) NOT NULL AUTO_INCREMENT,
			type tinytext NOT NULL,
			param tinytext NOT NULL,
			next_execute datetime NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
