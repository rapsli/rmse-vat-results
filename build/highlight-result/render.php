<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = rmse_vat_results_retrieve_team_schedule($attributes['team']);

if ($games !== false) {
	$played = $games[0];
	if ($played === false || count($played) === 0) { ?>
		<div class="rmse-vat-results-highlight-empty" <?php echo get_block_wrapper_attributes(); ?>>
			<?php _e('No games played yet', 'rmse-vat-results') ?>
		</div>
	<?php } else {
		$game = $played[0];
		?>
		<div <?php echo get_block_wrapper_attributes(); ?>>
			<div class="rmse-vat-results-highlight-result">
				<div class="rmse-vat-results-highlight-home">
					<?php if ($attributes['logos']) { ?>
						<div title="<?php echo $game->teamAName; ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo rmse_vat_results_team_logo($game->teamAId, $game->clubTeamAId, $attributes['logosize'], $attributes['logosize']); ?>"
								height="<?php echo $attributes['logosize']; ?>" width="<?php echo $attributes['logosize']; ?>"
								alt="<?php echo $game->teamAName; ?>" />
						</div>
					<?php } ?>
					<?php if ($attributes['names']) { ?>
						<div class="rmse-vat-results-highlight-name">
							<?php echo $game->teamAName; ?>
						</div>
					<?php } ?>
				</div>

				<div class="rmse-vat-results-highlight-info">
					<div class="rmse-vat-results-highlight-info-result">
						<?php echo "$game->teamAScoreFT:$game->teamBScoreFT"; ?>
					</div>
					<?php if ($attributes['halftime']) { ?>
						<div class="rmse-vat-results-highlight-info-halftime">
							<?php echo "$game->teamAScoreHT:$game->teamBScoreHT"; ?>
						</div>
					<?php } ?>
					<div class="rmse-vat-results-highlight-info-date">
						<?php
							$formatter = new IntlDateFormatter(
							    get_locale(),
							    IntlDateFormatter::FULL,
							    IntlDateFormatter::FULL,
							    null,
							    IntlDateFormatter::GREGORIAN,
							    'EEEE, dd.MM., HH:mm'
							);
							echo $formatter->format($game->gameDateTime);
						?>
					</div>
					<?php if ($attributes['venue']) { ?>
						<div class="rmse-vat-results-highlight-info-venue">
						<a href="<?php echo rmse_vat_results_venue_link($game); ?>" target="_blank">
									<?php echo $game->venue; ?>
								</a>
						</div>
					<?php } ?>
					<?php if ($attributes['spectators']) { ?>
						<div class="rmse-vat-results-highlight-info-spectators">
							<?php _e('Spectators', 'rmse-vat-results'); ?>
							<?php echo $game->spectators; ?>
						</div>
					<?php } ?>
				</div>

				<div class="rmse-vat-results-highlight-guest">
					<?php if ($attributes['logos']) { ?>
						<div title="<?php echo $game->teamBName; ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo rmse_vat_results_team_logo($game->teamBId, $game->clubTeamBId, $attributes['logosize'], $attributes['logosize']); ?>"
								height="<?php echo $attributes['logosize']; ?>" width="<?php echo $attributes['logosize']; ?>"
								alt="<?php echo $game->teamBName; ?>" />
						</div>
					<?php } ?>
					<?php if ($attributes['names']) { ?>
						<div class="rmse-vat-results-highlight-name">
							<?php echo $game->teamBName; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php }
}
?>
