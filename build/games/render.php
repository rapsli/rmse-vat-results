<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<p <?php echo get_block_wrapper_attributes(); ?>>
	<?php
	$all_club_games = retrieve_club_games();
	$games_played = $all_club_games[0];
	$games_planned = $all_club_games[1];
	?>
<table>
	<thead>
		<tr>
			<?php
			// <th>ID</th>
			// <th>Nr</th>
			?>
			<th>Datum und Uhrzeit</th>
			<th>Typ</th>
			<th>Team A</th>
			<th>Team B</th>
			<th>Austragungsort</th>
			<th>Resultat</th>
		</tr>
	</thead>
	<tbody>
		<?php
		// TODO: filter according to attributes!
		foreach ($games_played as $game) {
			?>
			<tr>
				<td>
					<? print_r($game); ?>
					<?php echo $game->gameId ?>
				</td>
				<td>
					<?php echo $game->gameNr ?>
				</td>
				<td>
					<?php echo $game->gameDateTimeString ?>
				</td>
				<td>
					<?php echo $game->groupCupText; ?>
				</td>
				<td>
					<?php echo $game->teamAName ?>
				</td>
				<td>
					<?php echo $game->teamBName ?>
				</td>
				<td>
					<?php echo $game->venue ?>
				</td>
				<td>
					<?php echo "$game->teamAScoreFT:$game->teamBScoreFT ($game->teamAScoreHT:$game->teamBScoreHT)" ?>
				</td>
			</tr>
			<?php
		}
		unset($game);

		// TODO: filter according to attributes!
		foreach ($games_planned as $game) {
			?>
			<tr>
				<td>
					<? print_r($game); ?>
					<?php echo $game->gameId ?>
				</td>
				<td>
					<?php echo $game->gameNr ?>
				</td>
				<td>
					<?php echo $game->gameDateTimeString ?>
				</td>
				<td>
					<?php echo $game->groupCupText; ?>
				</td>
				<td>
					<?php echo $game->teamAName ?>
				</td>
				<td>
					<?php echo $game->teamBName ?>
				</td>
				<td>
					<?php echo $game->venue ?>
				</td>
				<td>
					<?php echo "$game->teamAScoreFT:$game->teamBScoreFT ($game->teamAScoreHT:$game->teamBScoreHT)" ?>
				</td>
			</tr>
			<?php
		}
		unset($game);
?>
	</tbody>
</table>
</p>
