<?php

/**
 * This will execute the shortcodes to show certain parts.
 */
class TcShvResultateShortcodes
{
    private static function isHomeclub($hometeam)
    {
        global $wpdb;
        $tHomenames = get_option('tc_shv_home_names');
        if (!!$tHomenames) {
            $homenames = explode(',', get_option('tc_shv_home_names'));
        } else {
            $homenames = [];
        }
        foreach ($homenames as $homename) {
            if (strpos($hometeam, $homename) !== false) {
                return true;
            }
        }
        return false;
    }

    public static function resultate($atts = [], $content = null, $tag = '')
    {
        global $wpdb, $current_user;

        $m_atts = shortcode_atts([
            'max' => '50',
            'header' => 'false',
        ], $atts, $tag);

        $maxRecords = intval($m_atts['max']);
        $header = $m_atts['header'] === 'true';

        $last_results_table_name = $wpdb->prefix . 'tc_shv_last_results';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';

        $logged_in = $current_user->ID !== 0;

        $games = $wpdb->get_results(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.result_home,
            a.result_guest, a.halftime_home, a.halftime_guest, a.report
            from $last_results_table_name b inner join $game_table_name a on (a.id = b.game_id)
            order by b.id"
        );

        // do something to $content
        $content .=
            '<div class="tc-shv-resultate-lastresults">' .
            '  <table class="table table-sm">';

        if ($header) {
            $content .=
                '    <thead>' .
                '      <tr>' .
                '        <th class="text-center"></th>' .
                ($logged_in ? ('<th class=\"small\">No</th>') : '') .
                '        <th>Datum</th>' .
                '        <th class="text-center">Liga</th>' .
                '        <th>Heim</th>' .
                '        <th>Gast</th>' .
                '        <th class="text-center">Resultat</th>' .
                '    </thead>';
        }
        $content .=
            '    <tbody>';

        // games
        $lastdate = null;

        foreach (array_slice($games, 0, $maxRecords) as $game) {
            $isHome = self::isHomeclub($game->home);
            $homeWin = $game->result_home > $game->result_guest;
            $draw = $game->result_home == $game->result_guest;

            $weWon = !$draw && (($isHome && $homeWin) || (!$isHome && !$homeWin));

            $rowClass = ($weWon) ? "tc-shv-result-won" : ($draw ? "tc-shv-result-draw" : "tc-shv-result-lost");

            $dateF = date_i18n('d.m.', strtotime($game->game_date));

            if ($lastdate == $dateF) {
                $dateF = '';
                $newDate = '';
            } else {
                $lastdate = $dateF;
                $newDate = 'tc-shv-new-date';
            }

            $content .= "<tr class=\"$rowClass $newDate\">
        <td>";
            if ($game->report) {
                $content .= '
          <a class="bericht-link" href="' . $game->report . '">
            <img src="' . plugins_url('../public/images/document-icon.png', __FILE__) . '" height="18" width="18">
          </a>';
            }

            $upload_dir = wp_upload_dir();
            $upload_url = "${upload_dir['url']}/tc-shv-resultate/team-logo/";

            $content .= "</td>" .
                ($logged_in ? "<td class=\"small\">$game->id</td>" : '') .
                "<td>$dateF</td>
					<td class=\"text-center\">$game->league</td>
					<td>$game->home</td>
					<td>$game->guest</td>
					<td class=\"text-center\">$game->result_home:$game->result_guest ($game->halftime_home:$game->halftime_guest)</td>
				</tr>";
        }

        $content .=
            '    </tbody>' .
            '  </table>' .
            '</div>';

        // always return
        return $content;
    }

    public static function spiele($atts = [], $content = null, $tag = '')
    {
        global $wpdb, $current_user;

        $m_atts = shortcode_atts([
            'header' => 'false',
            'max' => '50',
        ], $atts, $tag);

        $maxRecords = intval($m_atts['max']);

        $logged_in = $current_user->ID !== 0;

        $next_games_table_name = $wpdb->prefix . 'tc_shv_next_games';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';
        $header = $m_atts['header'] === 'true';

        $games = $wpdb->get_results(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.venue, a.address, a.preview
            from $next_games_table_name b inner join $game_table_name a on (a.id = b.game_id)
            order by b.id"
        );

        // do something to $content
        $content =
            '<div class="tc-shv-resultate-nextgames">' .
            '  <table class="table table-sm">';
        if ($header) {
            $content .=
                '    <thead>' .
                '      <tr>' .
                '        <th class="text-center"></th>' .
                ($logged_in ? ('<th class=\"small\">No</th>') : '') .
                '        <th>Datum</th>' .
                '        <th>Zeit</th>' .
                '        <th class="text-center">Liga</th>' .
                '        <th>Heim</th>' .
                '        <th>Gast</th>' .
                '        <th>Ort</th>' .
                '    </thead>';
        }
        $content .=
            '    <tbody>';

        // games
        $lastdate = null;
        foreach (array_slice($games, 0, $maxRecords) as $game) {
            $isHome = self::isHomeclub($game->home);
            $homegameClass = $isHome ? "tc-shv-resultate-heimspiel" : "";

            // todo sort with header per date makes it probably more readable
            $dateF = date_i18n('d.m.', strtotime($game->game_date));
            $timeF = date_i18n('H:i', strtotime($game->game_date));

            if ($lastdate == $dateF) {
                $dateF = '';
                $newDate = '';
            } else {
                $lastdate = $dateF;
                $newDate = 'tc-shv-new-date';
            }

            $content .= "<tr class=\"$homegameClass $newDate\">" .
                "<td class=\"text-center\">";
            if ($game->preview) {
                $content .= '
					<a class="bericht-link" href="' . $game->preview . '">
						<img src="' . plugins_url('../public/images/document-icon.png', __FILE__) . '" height="18" width="18">
					</a>';
            }
            $content .= "</td>" .
                ($logged_in ? "<td class=\"small\">$game->id</td>" : '') .
                "<td>$dateF</td>
					<td class=\"text-center\">$timeF</td>
					<td class=\"text-center\">$game->league</td>
					<td>$game->home</td>
					<td>$game->guest</td>
					<td>
						<a href=\"https://www.google.com/maps/search/?api=1&query=$game->address\" target=\"tc_shv_maps\">$game->venue</a>
					</td>
				</tr>";
        }

        $content .=
            '    </tbody>' .
            '  </table>' .
            '</div>';

        // always return
        return $content;
    }

    public static function halle($atts = [], $content = null)
    {
        global $wpdb, $current_user;

        $logged_in = $current_user->ID !== 0;
        $venue = $atts['halle'];

        if (!$venue) {
            return 'must enter a halle attribute';
        }

        $next_games_table_name = $wpdb->prefix . 'tc_shv_next_games';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';

        $games = $wpdb->get_results(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.venue, a.address, a.preview
            from $next_games_table_name b inner join $game_table_name a on (a.id = b.game_id)
            where a.venue = '" . esc_sql($venue) . "'
            order by b.id"
        );

        // do something to $content
        if (!$games) {
            return '<div>(Noch) keine Spiele vorhanden.</div>';
        }

        $content =
            '<div class="tc-shv-resultate-nextgames">' .
            '  <table class="table table-sm">' .
            '    <thead>' .
            '      <tr>' .
            ($logged_in ? ('<th class=\"small\">No</th>') : '') .
            '        <th>Datum</th>' .
            '        <th class="text-center">Zeit</th>' .
            '        <th class="text-center">Liga</th>' .
            '        <th>Heim</th>' .
            '        <th>Gast</th>' .
            '    </thead>' .
            '    <tbody>';

        // games
        $lastdate = null;

        foreach ($games as $game) {
            $isHome = self::isHomeclub($game->home);
            $homegameClass = $isHome ? "tc-shv-resultate-heimspiel" : "";

            // TODO refactor this: make one title with the date and below the games!
            $dateF = date_i18n('d.m.', strtotime($game->game_date));
            $timeF = date_i18n('H:i', strtotime($game->game_date));

            if ($lastdate == $dateF) {
                $dateF = '';
                $newDate = '';
            } else {
                $lastdate = $dateF;
                $newDate = 'tc-shv-new-date';
            }

            $content .= "<tr class=\"$homegameClass $newDate\">" .
                "<td class=\"text-center\">";
            if ($game->preview) {
                $content .= '
					<a class="bericht-link" href="' . $game->preview . '">
						<img src="' . plugins_url('../public/images/document-icon.png', __FILE__) . '" height="18" width="18">
					</a>';
            }
            $content .= "</td>" .
                "<td>$dateF</td>
					<td class=\"text-center\">$timeF</td>
					<td class=\"text-center\">$game->league</td>
					<td>$game->home</td>
					<td>$game->guest</td>
				</tr>";
        }

        $content .=
            '    </tbody>' .
            '  </table>' .
            '</div>';

        // always return
        return $content;
    }

    public static function team($atts = [], $content = null)
    {
        global $wpdb, $current_user;

        $logged_in = $current_user->ID !== 0;

        $team = intval($atts['team']);

        if (!$team) {
            return 'must enter a team no';
        }

        $team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';
        $team_table_name = $wpdb->prefix . 'tc_shv_team';
        $group_table_name = $wpdb->prefix . 'tc_shv_group';
        $ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';

        $games = $wpdb->get_results($wpdb->prepare(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.venue, a.address, a.preview,
            a.result_home, a.result_guest, a.halftime_home, a.halftime_guest, a.report,
            a.played
            from $team_games_table_name b inner join $game_table_name a on (a.id = b.game_id)
            where b.team_id = %d
            order by a.game_date", $team
        ));

        $teaminfo = $wpdb->get_row($wpdb->prepare(
            "select a.id, a.name,
            b.group_text, b.league_short, a.group_id
            from $team_table_name a left outer join $group_table_name b on (a.group_id = b.id)
            where a.id = %d", $team
        ));

        $rankings = null;
        $rankings = $wpdb->get_results($wpdb->prepare(
            "select b.*
            from $ranking_table_name b
            where b.group_id = %d
            order by b.ranking", $teaminfo->group_id
        ));

        if (!$teaminfo) {
            return '<div>(Noch) keine Daten vorhanden.</div>';
        }

        // do something to $content
        $content =
            "<h2 class=\"tc-shv-resultate-team-name\">$teaminfo->name ($teaminfo->league_short)</h2>";
        if ($games) {
            $content .=
                '<div class="tc-shv-resultate-team-spiele">' .
                '  <table class="table table-sm">' .
                '    <thead>' .
                '      <tr>' .
                '        <th class="text-center"></th>' .
                ($logged_in ? ('<th class=\"small\">No</th>') : '') .
                '        <th>Datum</th>' .
                '        <th class="text-center">Zeit</th>' .
                '        <th class="text-center">Liga</th>' .
                '        <th>Heim</th>' .
                '        <th>Gast</th>' .
                '        <th class="text-center">Resultat</th>' .
                '        <th>Ort</th>' .
                '    </thead>' .
                '    <tbody>';
            foreach ($games as $game) {
                $isHome = self::isHomeclub($game->home);
                $played = $game->played != 0;
                $homegameClass = ($isHome && !$played) ? "tc-shv-resultate-heimspiel" : "";
                $playedClass = $played ? 'tc-shv-resultate-team-played' : 'tc-shv-resultate-team-planned';

                $homeWin = $game->result_home > $game->result_guest;
                $draw = $game->result_home == $game->result_guest;

                $weWon = !$draw && (($isHome && $homeWin) || (!$isHome && !$homeWin));

                $rowClass =
                (!$played) ? '' : (
                    ($weWon) ? "tc-shv-result-won" : ($draw ? "tc-shv-result-draw" : "tc-shv-result-lost")
                );

                $dateF = date_i18n('d.m.', strtotime($game->game_date));
                $timeF = date_i18n('H:i', strtotime($game->game_date));

                $showResult = '';
                if ($game->result_home != 0 || $game->result_guest != 0) {
                    $showResult = "$game->result_home:$game->result_guest ($game->halftime_home:$game->halftime_guest)";
                }

                $content .= "<tr class=\"$homegameClass $playedClass $rowClass\">" .
                    "<td class=\"text-center\">";
                if (($played && $game->report) || (!$played && $game->preview)) {
                    $link = $played ? "<a class=\"bericht-link\" href=\"$game->report\">" : "<a class=\"bericht-link\" href=\"$game->preview\">";
                    $content .= $link . '
						<img src="' . plugins_url('../public/images/document-icon.png', __FILE__) . '" height="18" width="18">
					</a>';
                }
                $content .= "</td>" .
                    ($logged_in ? "<td class=\"small\">$game->id</td>" : '') .
                    "<td>$dateF</td>
					<td class=\"text-center\">$timeF</td>
					<td class=\"text-center\">$game->league</td>
					<td>$game->home</td>
					<td>$game->guest</td>
					<td class=\"text-center\">$showResult</td>
					<td>
						<a href=\"https://www.google.com/maps/search/?api=1&query=$game->address\" target=\"tc_shv_maps\">$game->venue</a>
					</td>
				</tr>";
            }

            $content .=
                '    </tbody>' .
                '  </table>' .
                '</div>';
        }

        if ($rankings) {
            $content .=
                '<div class="tc-shv-resultate-team-rangliste">' .
                '  <h4>Rangliste</h4>' .
                '  <table class="table table-sm">' .
                '    <thead>' .
                '      <tr>' .
                '        <th></th>' .
                '        <th>Team</th>' .
                '        <th class="text-center">Sp</th>' .
                '        <th class="text-center">S</th>' .
                '        <th class="text-center">U</th>' .
                '        <th class="text-center">N</th>' .
                '        <th class="text-center">TD (+/-)</th>' .
                '        <th class="text-center">Punkte</th>' .
                '      </tr>' .
                '    </thead>' .
                '    <tbody>';
            foreach ($rankings as $rank) {
                $rowClass = "";
                if ($rank->direct_promotion) {
                    $rowClass .= "tc-shv-resultate-team-rangliste-direct-promotion ";
                } else if ($rank->promotion) {
                    $rowClass .= "tc-shv-resultate-team-rangliste-promotion ";
                } else if ($rank->direct_relegation) {
                    $rowClass .= "tc-shv-resultate-team-rangliste-direct-relegation ";
                } else if ($rank->relegation) {
                    $rowClass .= "tc-shv-resultate-team-rangliste-relegation ";
                }
                if ($rank->team == $teaminfo->name) {
                    $rowClass .= "tc-shv-resultate-team-rangliste-self ";
                }

                $content .=
                    "      <tr class=\"$rowClass\">" .
                    "        <td class=\"text-center\">$rank->ranking</td>" .
                    "        <td>$rank->team</td>" .
                    "        <td class=\"text-center\">$rank->total_games</td>" .
                    "        <td class=\"text-center\">$rank->total_wins</td>" .
                    "        <td class=\"text-center\">$rank->total_draws</td>" .
                    "        <td class=\"text-center\">$rank->total_loss</td>" .
                    "        <td class=\"text-center\">$rank->total_scores_diff ($rank->total_scores_plus:$rank->total_scores_minus)</td>" .
                    "        <td class=\"text-center\">$rank->total_points</td>" .
                    "      </tr>";
            }
            $content .=
                '    </tbody>' .
                '  </table>' .
                '</div>';
        }

        // always return
        return $content;
    }

    public static function teamlastresult($atts = [], $content = null)
    {
        global $wpdb, $current_user;

        $logged_in = $current_user->ID !== 0;

        $team = intval($atts['team']);

        if (!$team) {
            return 'must enter a team no';
        }

        $team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';
        $team_table_name = $wpdb->prefix . 'tc_shv_team';
        $group_table_name = $wpdb->prefix . 'tc_shv_group';
        $ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';

        $game = $wpdb->get_row($wpdb->prepare(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.venue, a.address,
            a.result_home, a.result_guest, a.halftime_home, a.halftime_guest, a.report
            from $team_games_table_name b inner join $game_table_name a on (a.id = b.game_id)
            where b.team_id = %d
            and a.played = 1
            order by a.game_date desc
            limit 1", $team
        ));

        // do something to $content
        if (!$game) {
            return '<span class="tc-shv-resultate-lastresultat tc-shv-resultate-lastresult-nogame">Es gab noch kein Spiel.</span>';
        } else {
            $isHome = self::isHomeclub($game->home);
            $homeWin = $game->result_home > $game->result_guest;
            $draw = $game->result_home == $game->result_guest;

            $weWon = !$draw && (($isHome && $homeWin) || (!$isHome && !$homeWin));

            $typus = $weWon ? "(S)" : ($draw ? "(U)" : "(N)");
            $opp = $isHome ? $game->guest : $game->home;

            $result = '';
            if ($isHome) {
                $result = "$game->result_home:$game->result_guest ($game->halftime_home:$game->halftime_guest) $opp (Heim)";
            } else {
                $result = "$game->result_guest:$game->result_home ($game->halftime_guest:$game->halftime_home) $opp (Auswärts)";
            }

            $rowClass = ($weWon) ? "tc-shv-resultate-lastresult-won" : ($draw ? "tc-shv-resultate-lastresult-draw" : "tc-shv-resultate-lastresult-lost");

            $dateF = date_i18n('d.m.Y', strtotime($game->game_date));
            $timeF = date_i18n('H:i', strtotime($game->game_date));

            $link = $game->report ? "<a href=\"$game->report\">$result</a>" : $result;

            return (
                "<span class=\"tc-shv-resultate-lastresult $rowClass\">$link</span>"
            );
        }
    }

    public static function teamnextgame($atts = [], $content = null)
    {
        global $wpdb, $current_user;

        $logged_in = $current_user->ID !== 0;

        $team = intval($atts['team']);

        if (!$team) {
            return 'must enter a team no';
        }

        $team_games_table_name = $wpdb->prefix . 'tc_shv_team_games';
        $game_table_name = $wpdb->prefix . 'tc_shv_game';
        $team_table_name = $wpdb->prefix . 'tc_shv_team';
        $group_table_name = $wpdb->prefix . 'tc_shv_group';
        $ranking_table_name = $wpdb->prefix . 'tc_shv_ranking';

        $game = $wpdb->get_row($wpdb->prepare(
            "select a.id, a.game_date, a.league, a.home, a.guest, a.venue, a.address,
            a.preview
            from $team_games_table_name b inner join $game_table_name a on (a.id = b.game_id)
            where b.team_id = %d
            and a.played = 0
            order by a.game_date asc
            limit 1", $team
        ));

        // do something to $content
        if (!$game) {
            return '<span class="tc-shv-resultate-nextgame tc-shv-resultate-nextgame-nogame">Es ist kein Spiel geplant.</span>';
        } else {
            $isHome = self::isHomeclub($game->home);
            $opp = $isHome ? $game->guest : $game->home;

            $typus = $isHome ? "Heimspiel gegen" : "@";

            $rowClass = ($isHome) ? "tc-shv-resultate-nextgame-home" : "tc-shv-resultate-nextgame-guest";

            $dateF = date_i18n('d.m.', strtotime($game->game_date));
            $timeF = date_i18n('H:i', strtotime($game->game_date));

            $google = "<a href=\"https://www.google.com/maps/search/?api=1&query=$game->address\" target=\"_blank\"><img src=\"https://img.icons8.com/color/18/000000/google-maps.png\"></a>";

            $whereWhen = "$dateF / $timeF $typus $opp $google";

            $link = $game->preview ? "<a href=\"$game->preview\">$whereWhen</a>" : $whereWhen;

            return (
                "<span class=\"tc-shv-resultate-nextgame $rowClass\">$link</span>"
            );
        }

        // always return
        return $content;
    }

    public static function teamhighlight($atts = [], $content = null)
    {
        $title = $atts['title'];
        $team = intval($atts['team']);

        if (!$team || !$title) {
            return 'must enter a team no and a title';
        }

        $nextgame = self::teamnextgame($atts, null);
        $lastresult = self::teamlastresult($atts, null);

        return "
            <div class=\"card hvh-bg-dark-cards text-light\">
                <h3 class=\"card-header\">$title</h3>
                <div class=\"card-body\">
                    <div class=\"card-text\">$nextgame</div>
                </div>
                <div class=\"card-footer\">
                    <div class=\"text-white\">Letztes Resultat: $lastresult</div>
                </div>
            </div>
        ";
    }
}
