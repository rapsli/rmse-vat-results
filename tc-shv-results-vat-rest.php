<?php
function tc_shv_results_authorization_headers()
{
	$options = get_option('tc_shv_results_options');
	if (isset($options) && $options !== false) {
		$username = $options['vat_user'];
		$password = $options['vat_password'];

		return array(
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode("$username:$password"),
			)
		);
	} else {
		return array();
	}
}

function tc_shv_results_shv_url($url)
{
	// TODO allow to use the test api too!
	return 'https://clubapi.handball.ch/rest/' . $url;
}

function tc_shv_results_execute_request($url)
{
	$http_url = tc_shv_results_shv_url($url);

	$result = wp_remote_request($http_url, tc_shv_results_authorization_headers());

	$response_code = wp_remote_retrieve_response_code($result);
	if ($response_code === 200) {
		return json_decode(wp_remote_retrieve_body($result));
	} else {
		error_log('Response Code from SHV VAT: ' . $response_code);
		return false;
	}
}

function tc_shv_results_request_response_code($url)
{
	$http_url = tc_shv_results_shv_url($url);

	$result = wp_remote_request($http_url, tc_shv_results_authorization_headers());

	return wp_remote_retrieve_response_code($result);
}

function tc_shv_results_club_id()
{
	$options = get_option('tc_shv_results_options');
	if (isset($options) && $options !== false) {
		return $options['club_id'];
	} else {
		return '';
	}
}

function tc_shv_results_retrieve_teams()
{
	return tc_shv_results_execute_request('v1/clubs/' . tc_shv_results_club_id() . '/teams');
}

function tc_shv_results_update_club_teams()
{
	$teams = tc_shv_results_retrieve_teams();

	if ($teams !== false) {
		usort(
			$teams,
			function ($team_a, $team_b) {
				if ($team_a->groupText === $team_b->groupText) {
					return 0;
				}

				return ($team_a->groupText < $team_b->groupText) ? -1 : 1;
			}
		);

		update_option('tc_shv_results_teams', $teams);
	}

	return $teams;
}

function tc_shv_results_get_club_teams()
{
	$teams = get_option('tc_shv_results_teams');

	if ($teams === false) {
		return tc_shv_results_update_club_teams();
	} else {
		return $teams;
	}
}

function tc_shv_results_get_club_team_ids()
{
	$club_teams = tc_shv_results_get_club_teams();
	if ($club_teams === false) {
		$club_teams = array();
	}
	return array_map(function ($team) {
		return $team->teamId;
	}, $club_teams);
}

function tc_shv_results_test_retrieve_club_info()
{
	return tc_shv_results_request_response_code('v1/clubs/' . tc_shv_results_club_id());
}

function tc_shv_results_enrich_game_info($game, $club_teams)
{
	$game->gameDateTime = new DateTimeImmutable($game->gameDateTime);
	$game->homegame = in_array($game->teamAId, $club_teams);
	$game->teamAInClub = in_array($game->teamAId, $club_teams);
	$game->teamBInClub = in_array($game->teamBId, $club_teams);

	$game->club_internal = false;
	$game->win = false;
	$game->draw = false;
	$game->loss = false;

	if ($game->gameStatusId === 2) {
		if ($game->teamAInClub && $game->teamBInClub) {
			$game->club_internal = true;
		} else if ($game->teamAScoreFT === $game->teamBScoreFT) {
			$game->draw = true;
		} else if ($game->teamAInClub && $game->teamAScoreFT > $game->teamBScoreFT) {
			$game->win = true;
		} else if ($game->teamAInClub && $game->teamAScoreFT < $game->teamBScoreFT) {
			$game->loss = true;
		} else if ($game->teamBInClub && $game->teamBScoreFT > $game->teamBScoreFT) {
			$game->win = true;
		} else if ($game->teamBInClub && $game->teamBScoreFT < $game->teamBScoreFT) {
			$game->loss = true;
		}
	}
}

function tc_shv_results_retrieve_club_games()
{
	$club_games_played = get_transient('tc_shv_results_club_games_played');
	$club_games_planned = get_transient('tc_shv_results_club_games_planned');

	if ($club_games_played === false || $club_games_planned === false) {
		// load the games for the club and save them to the transient too, unless we are in development mode (how to find out?)
		$all_club_games = tc_shv_results_execute_request('v1/clubs/' . tc_shv_results_club_id() . '/games');
		$club_teams = tc_shv_results_get_club_team_ids();

		if ($all_club_games !== false) {
			foreach ($all_club_games as $game) {
				tc_shv_results_enrich_game_info($game, $club_teams);
			}

			$club_games_planned = array_filter($all_club_games, function ($game) {
				return $game->gameStatusId === 1;
			});
			$club_games_played = array_filter($all_club_games, function ($game) {
				return $game->gameStatusId !== 1;
			});

			usort($club_games_planned, function ($a, $b) {
				$adt = $a->gameDateTime;
				$bdt = $b->gameDateTime;

				return $adt === $bdt ? 0 : ($adt < $bdt ? -1 : 1);
			});

			usort($club_games_played, function ($a, $b) {
				$adt = $a->gameDateTime;
				$bdt = $b->gameDateTime;

				return $adt === $bdt ? 0 : ($adt > $bdt ? -1 : 1);
			});

			set_transient('tc_shv_results_club_games_played', $club_games_played, MINUTE_IN_SECONDS * 1);
			set_transient('tc_shv_results_club_games_planned', $club_games_planned, MINUTE_IN_SECONDS * 1);
		} else {
			$club_games_played = false;
			$club_games_planned = false;
		}
	}

	return array($club_games_played, $club_games_planned);
}

function tc_shv_results_load_team_selection()
{
	$teams = tc_shv_results_get_club_teams();

	$reduced_teams = array_map(function ($entry) {
		return array('id' => $entry->teamId, 'name' => $entry->teamName . ' (' . $entry->leagueShort . ')');
	}, $teams);

	return $reduced_teams;
}
function tc_shv_results_retrieve_team_group($id)
{
	$group_info = get_transient('tc_shv_results_team_group_' . $id);

	if ($group_info === false) {
		$group_info = tc_shv_results_execute_request("v1/teams/$id/group");

		if ($group_info !== false) {
			$team_count = $group_info->totalTeams;

			$promotion_max_idx = $group_info->directPromotion;
			$promotion_candidate_max_idx = $promotion_max_idx + $group_info->promotionCandidate;

			$relegation_min_idx = $team_count - $group_info->directRelegation;
			$relegation_candidate_min_idx = $team_count - $group_info->directRelegation - $group_info->relegationCandidate;

			$rankings = $group_info->ranking;

			foreach($rankings as $idx => $ranking) {
				$ranking->promotion = $idx + 1 <= $promotion_max_idx;
				$ranking->promotion_candidate = !$ranking->promotion && $idx + 1 <= $promotion_candidate_max_idx;

				$ranking->relegation = $idx > $relegation_min_idx;
				$ranking->relegation_candidate = !$ranking->relegation && $idx > $relegation_candidate_min_idx;
			}

			set_transient('tc_shv_results_team_group_' . $id, $group_info, MINUTE_IN_SECONDS * 5);
		}
	}

	return $group_info;
}

function tc_shv_results_team_logo($team_id, $club_id, $width, $height) {
	$options = get_option('tc_shv_results_options');
	if (isset($options) && $options !== false) {
		$logo_url = $options['logo_url'];
		return sprintf($logo_url, $team_id, $club_id, $width, $height);
	}
	return false;
}

?>
