<?php

/**
 * This will execute the update as scheduled.
 *
 * @package    TcShvResultate
 * @subpackage TcShvResultate/includes
 */
class TcShvResultateCronjob {
  public static function updateData()
	{
	  // do nothing for now but write something somewhere to show it's running
	  // what it should do:
	  // read the next 5 jobs from the tc_shv_jobs table
	  // call the corresponding class functions with the parameters as defined in the table
	  //
	  global $wpdb;

    $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

		$executions_per_minute = get_option('tc_shv_calls_per_minute');

    // make sure there is something. if nothing is inside the table,
    // insert the club check (24h job), which will then be executed on the next run...
    $update_count = $wpdb->get_var("select count(*) from $updated_table_name");

		if ($update_count == 0) {
			$wpdb->insert($updated_table_name,
				array(
					'type' => 'club_update',
					'param' => get_option('tc_shv_club_id'),
					'next_execute' => current_time('mysql')
				)
			);
		} else {
			$sql = "select id, type, param from $updated_table_name where next_execute <= '" . current_time('mysql') . "' order by next_execute limit $executions_per_minute";

			$tobe_executed = $wpdb->get_results($sql);

			foreach ( $tobe_executed as $update )
			{
				// make sure that last executed is updated so it comes to the end of the list...
				switch ($update->type) {
					case "club_update":
						self::update_club($update->id, $update->param);
						break;
					case "team_group_update":
						self::update_team_group($update->id, $update->param);
						break;
					case "team_games_update":
						self::update_team_games($update->id, $update->param);
						break;
					case "last_results":
						self::update_last_results($update->id, $update->param);
						break;
					case "next_games":
						self::update_next_games($update->id, $update->param);
						break;
					default:
						error_log('Unexpected update type: ' . $update->type . ', updating row to make sure it\'s non-blocking.');
						self::update_next_execute($update->id, $update->type);
						break;
				}
			}
		}
	}

	private static function update_next_execute($id, $type)
	{
		global $wpdb;

		$updated_table_name = $wpdb->prefix . 'tc_shv_updates';

		$next_execution = self::next_execute($type);

		$wpdb->update(
			$updated_table_name,
			array('next_execute' => $next_execution),
			array('id' => $id)
		);
	}

	private static function next_execute($type) {
		switch ($type)
		{
			case 'error':
				return self::later_in_minutes( get_option('tc_shv_wait_time_error') );
			case 'fast':
				return self::later_in_minutes( 1 );
			case 'medium':
				return self::later_in_minutes( 3 );
			case 'club_update':
			  return self::later_in_minutes( get_option('tc_shv_wait_time_club_update') );
			case 'team_group_update':
				return self::later_in_minutes( get_option('tc_shv_wait_time_team_group_update') );
			case 'team_games_update':
				return self::later_in_minutes( get_option('tc_shv_wait_time_team_games_update') );
			case 'last_results':
				return self::later_in_minutes( get_option('tc_shv_wait_time_last_results') );
			case 'next_games':
				return self::later_in_minutes( get_option('tc_shv_wait_time_next_games') );
			default:
				// come back in 15 minutes
				return self::later_in_minutes( get_option('tc_shv_wait_time_default') );
		}
	}

	private static function later_in_minutes($minutes)
	{
		return gmdate( 'Y-m-d H:i:s', current_time('timestamp') + $minutes * MINUTE_IN_SECONDS );
	}

	private static function authorization_headers()
	{
		return array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode(get_option('tc_shv_vat_user') . ':' . get_option('tc_shv_vat_password'))
			)
		);
	}

	private static function shv_url($url)
	{
		return get_option('tc_shv_rest_api_base_url') . $url;
	}

	private static function request_shv($url)
	{
		$http_url = self::shv_url($url);

		$result = wp_remote_request($http_url, self::authorization_headers());

		$response_code = wp_remote_retrieve_response_code($result);
		if ( $response_code === 200)
		{
			return json_decode(wp_remote_retrieve_body($result));
		}
		else
	  {
			error_log(
				'Response_code from SHV VAT: ' . $response_code
			);
			return false;
		}
	}

	private static function update_club($upd_id, $param)
	{
		global $wpdb;

		$url = "clubs/$param/teams";

		$result = self::request_shv($url);

		if (false === $result) {
			self::update_next_execute($id, 'error');
			return;
		} else {
			// we have now all teams in the format
			// { "groupText": "...", "leagueId": 0, "teamId": 0, "teamName": "..." }
			// extract the id and create the update jobs for the team.
			// important: team_group_update must run before team_games_update...
			$updated_table_name = $wpdb->prefix . 'tc_shv_updates';
			$team_table_name = $wpdb->prefix . 'tc_shv_team';

			$wpdb->delete($updated_table_name, array('type' => 'team_group_update'));
			$wpdb->delete($updated_table_name, array('type' => 'team_games_update'));

			$idfunc = function($t) { return $t->teamId; };

			$ids = array_unique(array_map($idfunc, $result));

			$wpdb->insert(
				$updated_table_name,
				array(
					'type' => 'last_results',
					'param' => $param,
          'next_execute' => current_time('mysql')
				)
			);

			$wpdb->insert(
				$updated_table_name,
				array(
					'type' => 'next_games',
					'param' => $param,
          'next_execute' => current_time('mysql')
				)
			);

			foreach ( $ids as $id )
			{
				$wpdb->insert(
					$updated_table_name,
					array(
						'type' => 'team_group_update',
						'param' => $id,
						'next_execute' => current_time('mysql')
					)
				);

				$wpdb->insert(
					$updated_table_name,
					array(
						'type' => 'team_games_update',
						'param' => $id,
						'next_execute' => self::next_execute('medium')
					)
				);
			}

			self::update_next_execute($upd_id, 'club_update');
		}
	}

	private static function update_team_group($upd_id, $param)
	{
		global $wpdb;

		$url = "teams/$param/group";
		$url_t = "teams/$param";

		$result = self::request_shv($url);
		$result_t = self::request_shv($url_t);

		if (false === $result || false === $result_t) {
			self::update_next_execute($id, 'error');
			return;
		} else {
			// we have now have the team info including the ranking
			// extract the group information.
			$group_table_name = $wpdb->prefix . 'tc_shv_group';
	    $ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';

			$directRelegationRank = $result->totalTeams - $result->directRelegation + 1;
			$relegationRank = $result->totalTeams - $result->directRelegation - $result->relegationCandidate + 1;

			$id = $result->groupId;

			$count = $wpdb->replace(
				$group_table_name,
				array(
					'direct_promotion' => $result->directPromotion,
					'direct_relegation' => $directRelegationRank,
					'games_per_round' => $result->gamesPerRound,
					'group_text' => $result->groupText,
					'league_id' => $result->leagueId,
					'league_long' => $result->leagueLong,
					'league_short' => $result->leagueShort,
					'modus' => $result->modus,
					'modus_html' => $result->modusHtml,
					'promotion_candidate' => $result->directPromotion + $result->promotionCandidate,
					'relegation_candidate' => $relegationRank,
					'total_rounds' => $result->totalRounds,
					'total_teams' => $result->totalTeams,
					'id' => $result->groupId
				),
				array(
					'%d',
					'%d',
					'%d',
					'%d',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%d',
					'%d',
					'%d',
					'%d'
				)
			);

			// rankings including flags whether it's relegate or not. the rankings will just be deleted and recreated...
			$wpdb->delete($ranking_table_name, array('group_id' => $result->groupId), array('%d'));

			foreach ( $result->ranking as $ranking )
			{
				$wpdb->insert(
					$ranking_table_name,
					array(
						'group_id' => $result->groupId,
						'ranking' => $ranking->rank,
						'team' => $ranking->teamName,
						'total_points' => $ranking->totalPoints,
						'total_wins' => $ranking->totalWins,
						'total_draws' => $ranking->totalDraws,
						'total_loss' => $ranking->totalLoss,
						'total_scores_plus' => $ranking->totalScoresPlus,
						'total_scores_minus' => $ranking->totalScoresMinus,
						'total_games' => $ranking->totalGames,
						'total_scores_diff' => $ranking->totalScoresDiff,
						'direct_promotion' => $ranking->rank <= $result->directPromotion,
						'promotion' => ($ranking->rank > $result->directPromotion) && ($ranking->rank <= $result->promotionCandidate),
						'relegation' => ($ranking->rank >= $relegationRank) && ($ranking->rank < $directRelegationRank),
						'direct_relegation' => $ranking->rank >= $directRelegationRank
					),
					array(
						'%d',
						'%d',
						'%s',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
						'%d',
					)
				);
			}

			// we have now have the team info including the ranking
			// extract the group information.
			$team_table_name = $wpdb->prefix . 'tc_shv_team';

			$count = $wpdb->replace(
				$team_table_name,
				array(
					'id' => $result_t->teamId,
					'name' => $result_t->teamName,
					'group_id' => $result->groupId,
					'league_id' => $result->leagueId,
					'group_name' => $result->groupText
				),
				array(
					'%d',
					'%s',
					'%d',
					'%d',
					'%s'
				)
			);
		}

		self::update_next_execute($upd_id, 'team_group_update');
	}

	private static function update_team_games($upd_id, $param)
	{
		global $wpdb;

		$url = "teams/$param/games?order=asc";

		$result = self::request_shv($url);

		if (false === $result) {
			self::update_next_execute($id, 'error');
			return;
		} else {
			$team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';

			$wpdb->delete($team_games_table_name, array('team_id' => $param), '%d');

			foreach ( $result as $game )
			{
				self::save_game($game);

				$count = $wpdb->insert(
					$team_games_table_name,
					array(
						'team_id' => $param,
						'game_id' => $game->gameId
					),
					array(
						'%d',
						'%d'
					)
				);
			}
		}

		self::update_next_execute($upd_id, 'team_games_update');
	}

	private static function update_last_results($upd_id, $param)
	{
		global $wpdb;

		$url = "clubs/$param/games?order=desc&limit=50&status=played";

		$result = self::request_shv($url);

		if (false === $result) {
			self::update_next_execute($id, 'error');
			return;
		} else {
      $last_results_table_name = $wpdb->prefix . 'tc_shv_last_results';

			$wpdb->query("truncate table $last_results_table_name");

			foreach ( $result as $game )
			{
				self::save_game($game);

				$count = $wpdb->insert(
					$last_results_table_name,
					array(
						'game_id' => $game->gameId
					),
					array(
						'%d'
					)
				);
			}
		}

		self::update_next_execute($upd_id, 'last_results');
	}

	private static function update_next_games($upd_id, $param)
	{
		global $wpdb;

		$url = "clubs/$param/games?order=asc&limit=50&status=planned";

		$result = self::request_shv($url);

		if (false === $result) {
			self::update_next_execute($id, 'error');
			return;
		} else {
      $next_games_table_name = $wpdb->prefix . 'tc_shv_next_games';

			$wpdb->query("truncate table $next_games_table_name");

			foreach ( $result as $game )
			{
				self::save_game($game);

				$count = $wpdb->insert(
					$next_games_table_name,
					array(
						'game_id' => $game->gameId
					),
					array(
						'%d'
					)
				);
			}
		}

		self::update_next_execute($upd_id, 'next_games');
	}

	private static function defaultVal($val)
	{
		if (!!$val) {
			return $val;
		} else {
			return '';
		}
	}

	private static function save_game($game)
	{
		global $wpdb;

    $game_table_name = $wpdb->prefix . 'tc_shv_game';

    $played = $game->gameStatus == 'Gespielt';

		$preview = null;
		$report = null;

		$gameId = $game->gameId;

		$current_date = current_time('timestamp');
		$game_date = strtotime($game->gameDateTime);

		$diff = abs($game_date - $current_date);

		$within_3_weeks = $diff < 1900800;

		if (false === ($reports = get_transient("game-reports-$gameId"))) {
			$args = array(
				'post_type' => 'post',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key' => 'game_id_report',
						'value' => $gameId,
						'type' => 'NUMERIC'
					),
					array(
						'key' => 'game_id_preview',
						'value' => $gameId,
						'type' => 'NUMERIC'
					)
				)
			);

			$possiblePosts = new WP_Query($args);

			$preview = null;
			$report = null;

			if ($possiblePosts->have_posts()) {
				while ($possiblePosts->have_posts()) {
					$possiblePosts->the_post();
					$possible_id = get_the_ID();
					$link = get_permalink($possible_id);
					$preview_meta = get_post_meta($possible_id, 'game_id_preview', true);
					$report_meta = get_post_meta($possible_id, 'game_id_report', true);

					if (!empty($preview_meta) && ($preview_meta == $gameId)) {
						$preview = $link;
					}
					if (!empty($report_meta) && ($report_meta == $gameId)) {
						$report = $link;
					}
				}
			}

			$reports = array(
				"preview" => $preview,
				"report" => $report
			);

			wp_reset_query();

			$transient_time = get_option('tc_shv_transient_time_nothing_distant') * MINUTE_IN_SECONDS;
			if (!!$report) {
				$transient_time = get_option('tc_shv_transient_time_report') * MINUTE_IN_SECONDS;
			} else if ($within_3_weeks && !!$preview) {
				$transient_time = get_option('tc_shv_transient_time_preview_actual') * MINUTE_IN_SECONDS;
      } else if (!$within_3_weeks && !!$preview) {
				$transient_time = get_option('tc_shv_transient_time_preview_distant') * MINUTE_IN_SECONDS;
      } else if ($within_3_weeks && !$preview) {
				$transient_time = get_option('tc_shv_transient_time_nothing_actual') * MINUTE_IN_SECONDS;
			}

			set_transient("game-reports-$gameId", $reports, $transient_time);
		}

		$preview = $reports["preview"];
		$report = $reports["report"];

		$count = $wpdb->replace(
			$game_table_name,
			array(
				'id' => $game->gameId,
				'game_no' => $game->gameNr,
				'game_date' => $game->gameDateTime,
				'type' => $game->gameTypeShort,
				'home' => $game->teamAName,
				'guest' => $game->teamBName,
				'league' => $game->leagueShort,
				'league_full' => $game->leagueLong,
				'played' => $played,
				'halftime_home' => $game->teamAScoreHT,
				'halftime_guest' => $game->teamBScoreHT,
				'result_home' => $game->teamAScoreFT,
				'result_guest' => $game->teamBScoreFT,
				'venue' => self::defaultVal($game->venue),
				'address' => self::defaultVal($game->venueAddress) . ' ' . self::defaultVal($game->venueZip) . ' ' . self::defaultVal($game->venueCity),
				'spectators' => $game->spectators,
				'preview' => $preview,
				'report' => $report
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s',
				'%s',
				'%d',
				'%s',
				'%s'
			)
		);

		return $count;
	}
}
?>
