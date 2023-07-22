<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = tc_shv_results_retrieve_club_games();

if ($games !== false) {
	$played = array_reverse(array_slice($games[0], 0, $attributes['results']));
	$planned = array_slice($games[1], 0, $attributes['scheduled']);

	?>
	<table <?php echo get_block_wrapper_attributes(); ?>>
		<?php if ($attributes['header'] === true) { ?>
			<thead>
				<tr>
					<th>
						<?php esc_html_e('Datum / Zeit', 'tc-shv-results') ?>
					</th>
					<?php if ($attributes['type'] === true) { ?>
						<th>
							<?php esc_html_e('Typ', 'tc-shv-results') ?>
						</th>
					<?php } ?>
					<th>
						<?php esc_html_e('Heim', 'tc-shv-results') ?>
					</th>
					<th>
						<?php esc_html_e('Gast', 'tc-shv-results') ?>
					</th>
					<?php if ($attributes['venue'] === true) { ?>
						<th>
							<?php esc_html_e('Ort', 'tc-shv-results') ?>
						</th>
					<?php } ?>
					<?php if ($attributes['with_result'] === true) { ?>
						<th>
							<?php esc_html_e('Resultat', 'tc-shv-results') ?>
						</th>
					<?php } ?>
				</tr>
			</thead>
		<?php } ?>
		<tbody>
			<?php
			foreach ($played as $game) {
				$clz = 'tc-shv-results-game-played tc-shv-results-game-type-' . strtolower($game->gameTypeShort);
				if ($game->teamAInClub && $game->teamBInClub) {
					// special case: two teams from the club played against each other, no winner or looser actually
					$clz = $clz . ' tc-shv-results-game-club-internal';
				} else if ($game->teamAScoreFT === $game->teamBScoreFT) {
					$clz = $clz . ' tc-shv-results-game-draw';
				} else if ($game->teamAInClub && $game->teamAScoreFT > $game->teamBScoreFT) {
					$clz = $clz . ' tc-shv-results-game-win';
				} else if ($game->teamAInClub && $game->teamAScoreFT < $game->teamBScoreFT) {
					$clz = $clz . ' tc-shv-results-game-loss';
				} else if ($game->teamBInClub && $game->teamAScoreFT > $game->teamBScoreFT) {
					$clz = $clz . ' tc-shv-results-game-loss';
				} else if ($game->teamBInClub && $game->teamAScoreFT < $game->teamBScoreFT) {
					$clz = $clz . ' tc-shv-results-game-win';
				}
				?>
				<tr class="<?php echo $clz; ?>">
					<td>
						<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
					</td>
					<?php if ($attributes['type'] === true) { ?>
						<td>
							<?php echo $game->groupCupText; ?>
						</td>
					<?php } ?>
					<td>
						<?php echo $game->teamAName; ?>
					</td>
					<td>
						<?php echo $game->teamBName; ?>
					</td>
					<?php if ($attributes['venue'] === true) { ?>
						<td>
							<?php echo $game->venue; ?>
						</td>
					<?php } ?>
					<?php if ($attributes['with_result'] === true) { ?>
						<td>
							<?php echo "$game->teamAScoreFT:$game->teamBScoreFT ($game->teamAScoreHT:$game->teamBScoreHT)"; ?>
						</td>
					<?php } ?>
				</tr>
				<?php
			}
			unset($game);

			foreach ($planned as $game) {
				$clz = 'tc-shv-results-game-played tc-shv-results-game-type-' . strtolower($game->gameTypeShort) . ' tc-shv-results-game-' . ($game->homegame ? 'home' : 'away');
				?>
				<tr class="<?php echo $clz; ?>">
					<td>
						<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
					</td>
					<?php if ($attributes['type'] === true) { ?>
						<td>
							<?php echo $game->groupCupText; ?>
						</td>
					<?php } ?>
					<td>
						<?php echo $game->teamAName; ?>
					</td>
					<td>
						<?php echo $game->teamBName; ?>
					</td>
					<?php if ($attributes['venue'] === true) { ?>
						<td>
							<?php echo $game->venue; ?>
						</td>
					<?php } ?>
					<td></td>
				</tr>
				<?php
			}
			unset($game);
			?>
		</tbody>
	</table>
	<?php
} else {
	?>
	<p <?php echo get_block_wrapper_attributes(); ?>>
		<?php esc_html_e('No games loaded yet!!', 'tc-shv-results'); ?>
	</p>
	<?php
}
?>
