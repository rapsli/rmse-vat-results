<?php
function authorization_headers()
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

function shv_url($url)
{
	// TODO allow to use the test api too!
	return 'https://clubapi.handball.ch/rest/' . $url;
}

function execute_request($url)
{
	$http_url = shv_url($url);

	$result = wp_remote_request($http_url, authorization_headers());

	$response_code = wp_remote_retrieve_response_code($result);
	if ($response_code === 200) {
		return json_decode(wp_remote_retrieve_body($result));
	} else {
		error_log('Response Code from SHV VAT: ' . $response_code);
		return false;
	}
}

function request_response_code($url)
{
	$http_url = shv_url($url);

	$result = wp_remote_request($http_url, authorization_headers());

	return wp_remote_retrieve_response_code($result);
}

function club_id()
{
	$options = get_option('tc_shv_results_options');
	if (isset($options) && $options !== false) {
		return $options['club_id'];
	} else {
		return '';
	}
}

function retrieve_teams()
{
	return execute_request('v1/clubs/' . club_id() . '/teams');
}

function update_club_teams()
{
	$teams = retrieve_teams();

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

function get_club_teams()
{
	$teams = get_option('tc_shv_results_teams');

	if ($teams === false) {
		return update_club_teams();
	} else {
		return $teams;
	}
}

function get_club_team_ids()
{
	return array_map(function ($team) {
		return $team->teamId;
	}, get_club_teams());
}

function test_retrieve_club_info()
{
	return request_response_code('v1/clubs/' . club_id());
}

function retrieve_club_games()
{
	$club_games_played = get_transient('tc_shv_results_club_games_played');
	$club_games_planned = get_transient('tc_shv_results_club_games_planned');

	if ($club_games_played === false || $club_games_planned === false) {
		// load the games for the club and save them to the transient too, unless we are in development mode (how to find out?)
		$all_club_games = execute_request('v1/clubs/' . club_id() . '/games');
		$home_teams = get_club_team_ids();
		if ($home_teams === false) {
			$home_teams = array();
		}

		if ($all_club_games !== false) {
			foreach ($all_club_games as $game) {
				$game->gameDateTime = new DateTimeImmutable($game->gameDateTime);
				$game->homegame = in_array($game->teamAId, $home_teams);
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

			set_transient('tc_shv_results_club_games_played', $club_games_played, MINUTE_IN_SECONDS * 5);
			set_transient('tc_shv_results_club_games_planned', $club_games_planned, MINUTE_IN_SECONDS * 5);
		} else {
			$club_games_played = false;
			$club_games_planned = false;
		}
	}

	return array($club_games_played, $club_games_planned);
}
?>
