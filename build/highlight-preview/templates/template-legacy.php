<?php
/**
 * Template for Highlight Preview block - legacy layout
 * 
 * Overwrite this file by placing a copy in your (child) theme:
 *  /rmse-vat-results/highlight-preview/template-legacy.php
 * 
 * 
 * $context array contains:
 * - $games: array of games
 * - $attributes: block attributes
 * - $wrapper_attrs: attributes for the block wrapper
 * 
 */


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
						<?php echo date_i18n($attributes['dateformat'], $game->gameDateTime->getTimestamp()); ?>
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