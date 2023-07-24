<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = tc_shv_results_retrieve_team_schedule($attributes['team']);

if ($games !== false) {
	$played = $games[0];
	$planned = $games[1];

	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<table class="tc-shv-results-table">
			<?php if ($attributes['header'] === true) { ?>
				<thead>
					<tr>
						<th class="tc-shv-results-date">
							<?php _e('Date / Time', 'tc-shv-results') ?>
						</th>
						<?php if ($attributes['type'] === true) { ?>
							<th class="tc-shv-results-type">
								<?php _e('Type', 'tc-shv-results') ?>
							</th>
						<?php } ?>
						<th class="tc-shv-results-hometeam">
							<?php _e('Home', 'tc-shv-results') ?>
						</th>
						<th class="tc-shv-results-guestteam">
							<?php _e('Guest', 'tc-shv-results') ?>
						</th>
						<?php if ($attributes['venue'] === true) { ?>
							<th class="tc-shv-results-venue">
								<?php _e('Venue', 'tc-shv-results') ?>
							</th>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<th class="tc-shv-results-result">
								<?php _e('Result', 'tc-shv-results') ?>
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
						<td class="tc-shv-results-date">
							<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
						</td>
						<?php if ($attributes['type'] === true) { ?>
							<td class="tc-shv-results-type">
								<?php echo $game->groupCupText; ?>
							</td>
						<?php } ?>
						<td class="tc-shv-results-hometeam">
							<?php echo $game->teamAName; ?>
						</td>
						<td class="tc-shv-results-guestteam">
							<?php echo $game->teamBName; ?>
						</td>
						<?php if ($attributes['venue'] === true) { ?>
							<td class="tc-shv-results-venue">
								<?php echo $game->venue; ?>
							</td>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<td class="tc-shv-results-result">
								<?php echo "$game->teamAScoreFT:$game->teamBScoreFT ($game->teamAScoreHT:$game->teamBScoreHT)"; ?>
							</td>
						<?php } ?>
					</tr>
					<?php
				}
				unset($game);

				foreach ($planned as $game) {
					$clz = 'tc-shv-results-game-planned tc-shv-results-game-type-' . strtolower($game->gameTypeShort) . ' tc-shv-results-game-' . ($game->homegame ? 'home' : 'away');
					?>
					<tr class="<?php echo $clz; ?>">
						<td class="tc-shv-results-date">
							<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
						</td>
						<?php if ($attributes['type'] === true) { ?>
							<td class="tc-shv-results-type">
								<?php echo $game->groupCupText; ?>
							</td>
						<?php } ?>
						<td class="tc-shv-results-hometeam">
							<?php echo $game->teamAName; ?>
						</td>
						<td class="tc-shv-results-guestteam">
							<?php echo $game->teamBName; ?>
						</td>
						<?php if ($attributes['venue'] === true) { ?>
							<td class="tc-shv-results-venue">
								<?php echo $game->venue; ?>
							</td>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<td class="tc-shv-results-result">
							</td>
						<?php } ?>
						<td class="tc-shv-results-result"></td>
					</tr>
					<?php
				}
				unset($game);
				?>
			</tbody>
		</table>
	</div>
	<?php
} else {
	?>
	<p <?php echo get_block_wrapper_attributes(); ?>>
		<?php _e('No games loaded!', 'tc-shv-results'); ?>
	</p>
	<?php
}
?>
