<?php

/**
 * This will execute the update as scheduled.
 */
class TcShvResultateCronjob
{
    public static function update_data()
    {
        global $wpdb;

        $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

        // check the config how many updates we should execute
        $executions_per_minute = get_option('tc_shv_calls_per_minute');

        // make sure there is something. if nothing is inside the table,
        // insert the club check (24h job), which will then be executed on the next run...
        $update_count = $wpdb->get_var("select count(*) from $updated_table_name");

        $club_id = get_option('tc_shv_club_id');

        $sql = "select id, type, param from $updated_table_name where next_execute <= '" . current_time('mysql') . "' order by next_execute limit $executions_per_minute";

        $tobe_executed = $wpdb->get_results($sql);

        // execute the correct job according to the job table. fall through if something is not yet there (for example
        // if someone downgrades the plugin)
        foreach ($tobe_executed as $update) {
            switch ($update->type) {
                case "club_update":
                    self::update_club($update->id, $update->param);
                    break;
                case "other_clubs_update":
                    self::other_clubs_update($update->id, $update->param);
                    break;
                case "club_teams_update":
                    self::club_teams_update($update->id, $update->param);
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

    // helper function to enter the next execution time for the given job
    private static function update_next_execute($id, $type)
    {
        global $wpdb;

        $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

        $next_execution = self::next_execute($type);

        if ($next_execution === -1) {
            // delete it
            $wpdb->delete(
                $updated_table_name,
                array('id' => $id)
            );
        } else {
            $wpdb->update(
                $updated_table_name,
                array('next_execute' => $next_execution),
                array('id' => $id)
            );
        }
    }

    // calculate when the job should be run next
    private static function next_execute($type)
    {
        switch ($type) {
            case 'error':
                return self::later_in_minutes(get_option('tc_shv_wait_time_error'));
            case 'fast':
                return self::later_in_minutes(1);
            case 'medium':
                return self::later_in_minutes(3);
            case 'club_update':
                return -1;
            case 'other_clubs_update':
                return -1;
            case 'club_teams_update':
                return -1;
            case 'team_group_update':
                return self::later_in_minutes(get_option('tc_shv_wait_time_team_group_update'));
            case 'team_games_update':
                return self::later_in_minutes(get_option('tc_shv_wait_time_team_games_update'));
            case 'last_results':
                return self::later_in_minutes(get_option('tc_shv_wait_time_last_results'));
            case 'next_games':
                return self::later_in_minutes(get_option('tc_shv_wait_time_next_games'));
            default:
                // come back in 15 minutes
                return self::later_in_minutes(get_option('tc_shv_wait_time_default'));
        }
    }

    private static function later_in_minutes($minutes)
    {
        return gmdate('Y-m-d H:i:s', current_time('timestamp') + $minutes * MINUTE_IN_SECONDS);
    }

    // the Basic auth we need to send to the REST API
    private static function authorization_headers()
    {
        return array(
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode(get_option('tc_shv_vat_user') . ':' . get_option('tc_shv_vat_password')),
            ),
        );
    }

    private static function shv_url($url)
    {
        return get_option('tc_shv_rest_api_base_url') . $url;
    }

    // call the REST API
    private static function request_shv($url)
    {
        $http_url = self::shv_url($url);

        $result = wp_remote_request($http_url, self::authorization_headers());

        $response_code = wp_remote_retrieve_response_code($result);
        if ($response_code === 200) {
            return json_decode(wp_remote_retrieve_body($result));
        } else {
            error_log(
                'Response_code from SHV VAT: ' . $response_code
            );
            return false;
        }
    }

    // read out the information for the given club from the homepage
    // this will read out all the teams so we can prepare the teams table
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

            $wpdb->delete($updated_table_name, array('type' => 'team_group_update'));
            $wpdb->delete($updated_table_name, array('type' => 'team_games_update'));

            $idfunc = function ($t) {return $t->teamId;};

            $ids = array_unique(array_map($idfunc, $result));

            $wpdb->insert(
                $updated_table_name,
                array(
                    'type' => 'last_results',
                    'param' => $param,
                    'next_execute' => current_time('mysql'),
                )
            );

            $wpdb->insert(
                $updated_table_name,
                array(
                    'type' => 'next_games',
                    'param' => $param,
                    'next_execute' => current_time('mysql'),
                )
            );

            foreach ($ids as $id) {
                $wpdb->insert(
                    $updated_table_name,
                    array(
                        'type' => 'team_group_update',
                        'param' => $id,
                        'next_execute' => current_time('mysql'),
                    )
                );

                $wpdb->insert(
                    $updated_table_name,
                    array(
                        'type' => 'team_games_update',
                        'param' => $id,
                        'next_execute' => self::next_execute('medium'),
                    )
                );
            }

            self::update_next_execute($upd_id, 'club_update');
        }
    }

    // read out the information for all available clubs and then add a job to update the table
    // with all teams for a given club
    private static function other_clubs_update($upd_id, $param)
    {
        global $wpdb;

        $url = "clubs";

        $result = self::request_shv($url);

        if (false === $result) {
            self::update_next_execute($id, 'error');
            return;
        } else {
            // we now have a list of all clubs in the format
            // { clubId, clubName }
            // so we can now create updates to read all teams for a given club as a one time action
            // we also only update this club info once every week because updates are expected to
            // be very rare.
            $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

            $wpdb->delete($updated_table_name, array('type' => 'club_teams_update'));

            // just need the club id
            $idfunc = function ($c) {return $c->clubId;};

            // ignore own club
            $filterfunc = function ($id) {return $id !== $param;};

            $ids = array_unique(array_filter(array_map($idfunc, $result), $filterfunc));

            foreach ($ids as $id) {
                $wpdb->insert(
                    $updated_table_name,
                    array(
                        'type' => 'club_teams_update',
                        'param' => $id,
                        'next_execute' => current_time('mysql'),
                    )
                );
            }

            self::update_next_execute($upd_id, 'other_clubs_update');
        }
    }

    // read out the information for the given club from the homepage
    // this will read out all the teams so we can prepare the teams table
    private static function club_teams_update($upd_id, $param)
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
            // extract the id and create the update jobs for the team-logos.
            $updated_table_name = $wpdb->prefix . 'tc_shv_updates';

            $idfunc = function ($t) {return $t->teamId;};

            $ids = array_unique(array_map($idfunc, $result));

            $success = true;

            foreach ($ids as $id) {
                $success = $success && self::logos_update($id, $param);
            }

            if ($success) {
                self::update_next_execute($upd_id, 'club_teams_update');
            } else {
                self::update_next_execute($upd_id, 'error');
            }
        }
    }

    // update the group information (general info + rankings) for a team
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

            // ranking rules: direct promotion, promotion, "white area", relegation, direct relegation
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
                    'id' => $result->groupId,
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
                    '%d',
                )
            );

            // rankings including flags whether it's relegate or not. the rankings will just be deleted and recreated...
            $wpdb->delete($ranking_table_name, array('group_id' => $result->groupId), array('%d'));

            foreach ($result->ranking as $ranking) {
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
                        'direct_relegation' => $ranking->rank >= $directRelegationRank,
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
            $logo_table_name = $wpdb->prefix . 'tc_shv_team_logos';

            $count1 = $wpdb->replace(
                $team_table_name,
                array(
                    'id' => $result_t->teamId,
                    'name' => $result_t->teamName,
                    'group_id' => $result->groupId,
                    'league_id' => $result->leagueId,
                    'group_name' => $result->groupText,
                ),
                array(
                    '%d',
                    '%s',
                    '%d',
                    '%d',
                    '%s',
                )
            );
        }

        self::update_next_execute($upd_id, 'team_group_update');
    }

    // update the games for a team (inkludes also the results of games for a team)
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

            foreach ($result as $game) {
                self::save_game($game);

                $count = $wpdb->insert(
                    $team_games_table_name,
                    array(
                        'team_id' => $param,
                        'game_id' => $game->gameId,
                    ),
                    array(
                        '%d',
                        '%d',
                    )
                );
            }
        }

        self::update_next_execute($upd_id, 'team_games_update');
    }

    // just update all results for a club
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

            foreach ($result as $game) {
                self::save_game($game);

                $count = $wpdb->insert(
                    $last_results_table_name,
                    array(
                        'game_id' => $game->gameId,
                    ),
                    array(
                        '%d',
                    )
                );
            }
        }

        self::update_next_execute($upd_id, 'last_results');
    }

    // update the next games for a club
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

            foreach ($result as $game) {
                self::save_game($game);

                $count = $wpdb->insert(
                    $next_games_table_name,
                    array(
                        'game_id' => $game->gameId,
                    ),
                    array(
                        '%d',
                    )
                );
            }
        }

        self::update_next_execute($upd_id, 'next_games');
    }

    // fall through so I never store "invalid" values
    private static function defaultVal($val)
    {
        if (!!$val) {
            return $val;
        } else {
            return '';
        }
    }

    // we store the game only once but get it from multiple sources, so this function just makes
    // sure there is still only one place where this is stored in the table
    private static function save_game($game)
    {
        global $wpdb;

        $game_table_name = $wpdb->prefix . 'tc_shv_game';

        $played = $game->gameStatus == 'Gespielt' || $game->teamAScoreFT > 0 || $game->teamBScoreFT > 0;

        $preview = null;
        $report = null;

        $gameId = $game->gameId;

        $current_date = current_time('timestamp');
        $game_date = strtotime($game->gameDateTime);

        $diff = abs($game_date - $current_date);

        $within_3_weeks = $diff < 1900800;

        // TODO not sure whether I even need this because it's probably cheap to get them anyways...
        if (false === ($reports = get_transient("game-reports-$gameId"))) {
            $args = array(
                'post_type' => 'post',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key' => 'game_id_report',
                        'value' => $gameId,
                        'type' => 'NUMERIC',
                    ),
                    array(
                        'key' => 'game_id_preview',
                        'value' => $gameId,
                        'type' => 'NUMERIC',
                    ),
                ),
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
                "report" => $report,
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
                'report' => $report,
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
                '%s',
            )
        );

        return $count;
    }

    public static function logos_update($teamid, $clubid)
    {
        global $wpdb;

        $upload_dir = wp_upload_dir();

        $filename = "logo-$teamid";
        $upload_path = "${upload_dir['path']}/tc-shv-resultate/team-logo/";
        $upload_url = "${upload_dir['url']}/tc-shv-resultate/team-logo/";

        wp_mkdir_p($upload_path);

        error_log("Requesting Logos for $teamid ($clubid) into $upload_path");

        if (
            ($im90 = self::request_image($teamid, $clubid, 90, $filename . '-90.png', $upload_path)) &&
            ($im60 = self::request_image($teamid, $clubid, 60, $filename . '-60.png', $upload_path)) &&
            ($im35 = self::request_image($teamid, $clubid, 35, $filename . '-35.png', $upload_path))
        ) {
            $logo_table_name = $wpdb->prefix . 'tc_shv_team_logos';
            $existing = $wpdb->get_row("select * from $logo_table_name where id = $teamid");

            if ($existing) {
                $wpdb->delete(
                    $logo_table_name,
                    array('id' => $teamid)
                );

                if ($existing->logo_path !== $upload_path . $filename) {
                    unlink($existing->logo_path . '-90.png');
                    unlink($existing->logo_path . '-60.png');
                    unlink($existing->logo_path . '-35.png');
                }
            }

            $wpdb->insert(
                $logo_table_name,
                array(
                    'id' => $teamid,
                    'logo_url' => $upload_url . $filename,
                    'logo_path' => $upload_path . $filename,
                    'logo_last_update' => current_time('mysql'),
                )
            );

            return true;
        } else {
            return false;
        }
    }

    private static function request_image($teamid, $clubid, $resolution, $filename, $dir)
    {
        $http_url = "https://www.handball.ch/images/logo/$teamid.png?fallbackType=club&fallbackId=$clubid&width=$resolution&height=$resolution&scale=canvas";

        $result = wp_remote_get($http_url, array(
            'stream' => true,
            'filename' => trailingslashit($dir) . $filename,
        ));

        if (wp_remote_retrieve_response_code($result) === 200) {
            return $filename;
        } else {
            error_log('Failed to fetch image: ' . wp_remote_retrieve_response_code($result) . '(' . wp_remote_retrieve_response_message($result) . ')');
            return false;
        }
    }
}
