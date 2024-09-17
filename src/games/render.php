<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = rmse_vat_results_retrieve_club_games();

if ($games !== false) {
	$played = array_slice($games[0], 0, $attributes['results']);
	$planned = array_slice($games[1], 0, $attributes['scheduled']);

	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<table class="rmse-vat-results-table">
			<?php if ($attributes['header'] === true) { ?>
				<thead>
					<tr>
						<th class="rmse-vat-results-date">
							<?php _e('Date / Time', 'rmse-vat-results') ?>
						</th>
						<?php if ($attributes['group'] === true) { ?>
							<th class="rmse-vat-results-group">
								<?php _e('Group', 'rmse-vat-results') ?>
							</th>
						<?php } ?>
						<th class="rmse-vat-results-hometeam">
							<?php _e('Home', 'rmse-vat-results') ?>
						</th>
						<th class="rmse-vat-results-guestteam">
							<?php _e('Guest', 'rmse-vat-results') ?>
						</th>
						<?php if ($attributes['venue'] === true) { ?>
							<th class="rmse-vat-results-venue">
								<?php _e('Venue', 'rmse-vat-results') ?>
							</th>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<th class="rmse-vat-results-result">
								<?php _e('Result', 'rmse-vat-results') ?>
							</th>
						<?php } ?>
					</tr>
				</thead>
			<?php } ?>
			<tbody>
				<?php
				foreach ($played as $game) {
					$clz = 'rmse-vat-results-game-played rmse-vat-results-game-type-' . strtolower($game->gameTypeShort);
					if ($game->teamAInClub && $game->teamBInClub) {
						// special case: two teams from the club played against each other, no winner or looser actually
						$clz = $clz . ' rmse-vat-results-game-club-internal';
					} else if ($game->teamAScoreFT === $game->teamBScoreFT) {
						$clz = $clz . ' rmse-vat-results-game-draw';
					} else if ($game->teamAInClub && $game->teamAScoreFT > $game->teamBScoreFT) {
						$clz = $clz . ' rmse-vat-results-game-win';
					} else if ($game->teamAInClub && $game->teamAScoreFT < $game->teamBScoreFT) {
						$clz = $clz . ' rmse-vat-results-game-loss';
					} else if ($game->teamBInClub && $game->teamAScoreFT > $game->teamBScoreFT) {
						$clz = $clz . ' rmse-vat-results-game-loss';
					} else if ($game->teamBInClub && $game->teamAScoreFT < $game->teamBScoreFT) {
						$clz = $clz . ' rmse-vat-results-game-win';
					}
					?>
					<tr class="<?php echo $clz; ?>">
						<td class="rmse-vat-results-date">
							<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
						</td>
						<?php if ($attributes['group'] === true) { ?>
							<td class="rmse-vat-results-group">
								<?php echo $game->groupCupText; ?>
							</td>
						<?php } ?>
						<td class="rmse-vat-results-hometeam">
							<?php echo $game->teamAName; ?>
						</td>
						<td class="rmse-vat-results-guestteam">
							<?php echo $game->teamBName; ?>
						</td>
						<?php if ($attributes['venue'] === true) { ?>
							<td class="rmse-vat-results-venue">
								<a href="<?php echo rmse_vat_results_venue_link($game); ?>" target="_blank">
									<?php echo $game->venue; ?>
								</a>
							</td>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<td class="rmse-vat-results-result">
								<?php echo "$game->teamAScoreFT:$game->teamBScoreFT ($game->teamAScoreHT:$game->teamBScoreHT)"; ?>
							</td>
						<?php } ?>
					</tr>
					<?php
				}
				unset($game);

				foreach ($planned as $game) {
					$clz = 'rmse-vat-results-game-planned rmse-vat-results-game-type-' . strtolower($game->gameTypeShort) . ' rmse-vat-results-game-' . ($game->homegame ? 'home' : 'away');
					?>
					<tr class="<?php echo $clz; ?>">
						<td class="rmse-vat-results-date">
							<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
						</td>
						<?php if ($attributes['group'] === true) { ?>
							<td class="rmse-vat-results-group">
								<?php echo $game->groupCupText; ?>
							</td>
						<?php } ?>
						<td class="rmse-vat-results-hometeam">
							<?php echo $game->teamAName; ?>
						</td>
						<td class="rmse-vat-results-guestteam">
							<?php echo $game->teamBName; ?>
						</td>
						<?php if ($attributes['venue'] === true) { ?>
							<td class="rmse-vat-results-venue">
							<a href="<?php echo rmse_vat_results_venue_link($game); ?>" target="_blank">
									<?php echo $game->venue; ?>
								</a>
							</td>
						<?php } ?>
						<?php if ($attributes['with_result'] === true) { ?>
							<td class="rmse-vat-results-result">
							</td>
						<?php } ?>
						<td class="rmse-vat-results-result"></td>
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
		<?php _e('No games loaded', 'rmse-vat-results'); ?>
	</p>
	<?php
}
?>
