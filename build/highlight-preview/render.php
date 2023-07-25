<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = rmse_vat_results_retrieve_team_schedule($attributes['team']);

if ($games !== false) {
	$planned = $games[1];
	if ($planned === false || count($planned) === 0) { ?>
		<div class="rmse-vat-results-highlight-empty" <?php echo get_block_wrapper_attributes(); ?>>
			<?php _e('No games planned yet', 'rmse-vat-results') ?>
		</div>
	<?php } else {
		$game = $planned[0];
		?>
		<div <?php echo get_block_wrapper_attributes(); ?>>
			<div class="rmse-vat-results-highlight-result">
				<div class="rmse-vat-results-highlight-home">
					<?php if ($attributes['logos']) { ?>
						<div title="<?php echo $game->teamAName; ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo rmse_vat_results_team_logo($game->teamAId, $game->clubTeamAId, $attributes['logosize'], $attributes['logosize']); ?>"
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
					<div class="rmse-vat-results-highlight-info-date">
						<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
					</div>
					<?php if ($attributes['venue']) { ?>
						<div class="rmse-vat-results-highlight-info-venue">
						<a href="<?php echo rmse_vat_results_venue_link($game); ?>" target="_blank">
									<?php echo $game->venue; ?>
								</a>
						</div>
					<?php } ?>
				</div>

				<div class="rmse-vat-results-highlight-guest">
					<?php if ($attributes['logos']) { ?>
						<div title="<?php echo $game->teamBName; ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo rmse_vat_results_team_logo($game->teamBId, $game->clubTeamBId, $attributes['logosize'], $attributes['logosize']); ?>"
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
