<?php


/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Team-ID ermitteln (statisch oder ACF)
$team_id = rmse_vat_get_selected_team_id( $attributes, get_the_ID() );

// Wenn keine Team-ID ermittelbar ist → leerer Zustand
if ( empty($team_id) ) { ?>
	<div class="rmse-vat-results-highlight-empty" <?php echo get_block_wrapper_attributes(); ?>>
		<?php _e('No team selected', 'rmse-vat-results'); ?>
	</div>
<?php
	return;
}

$games = rmse_vat_results_retrieve_team_schedule( $team_id );

if ($games !== false) {
	$played = $games[0];

	if ($played === false || count($played) === 0) { ?>
		<div class="rmse-vat-results-highlight-empty" <?php echo get_block_wrapper_attributes(); ?>>
			<?php _e('No games played yet', 'rmse-vat-results') ?>
		</div>
	<?php } else {
		$game = $played[count($played) - 1];

		// sanfte Defaults + Escapes
		$logos   = !empty($attributes['logos']);
		$names   = !empty($attributes['names']);
		$venue   = !empty($attributes['venue']);
		$half    = !empty($attributes['halftime']);
		$spect   = !empty($attributes['spectators']);
		$size    = isset($attributes['logosize']) ? absint($attributes['logosize']) : 48;
		$fmt     = isset($attributes['dateformat']) && $attributes['dateformat'] !== '' ? $attributes['dateformat'] : get_option('date_format');

		?>
		<div <?php echo get_block_wrapper_attributes(); ?>>
			<div class="rmse-vat-results-highlight-result">

				<div class="rmse-vat-results-highlight-home">
					<?php if ($logos) { ?>
						<div title="<?php echo esc_attr($game->teamAName); ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo esc_url( rmse_vat_results_team_logo($game->teamAId, $game->clubTeamAId, $size, $size) ); ?>"
								height="<?php echo esc_attr($size); ?>" width="<?php echo esc_attr($size); ?>"
								alt="<?php echo esc_attr($game->teamAName); ?>" />
						</div>
					<?php } ?>
					<?php if ($names) { ?>
						<div class="rmse-vat-results-highlight-name">
							<?php echo esc_html($game->teamAName); ?>
						</div>
					<?php } ?>
				</div>

				<div class="rmse-vat-results-highlight-info">
					<div class="rmse-vat-results-highlight-info-result">
						<?php echo esc_html("{$game->teamAScoreFT}:{$game->teamBScoreFT}"); ?>
					</div>
					<?php if ($half) { ?>
						<div class="rmse-vat-results-highlight-info-halftime">
							<?php echo esc_html("{$game->teamAScoreHT}:{$game->teamBScoreHT}"); ?>
						</div>
					<?php } ?>
					<div class="rmse-vat-results-highlight-info-date">
						<?php echo esc_html( date_i18n($fmt, $game->gameDateTime->getTimestamp()) ); ?>
					</div>
					<?php if ($venue) { ?>
						<div class="rmse-vat-results-highlight-info-venue">
							<a href="<?php echo esc_url( rmse_vat_results_venue_link($game) ); ?>" target="_blank" rel="noopener">
								<?php echo esc_html($game->venue); ?>
							</a>
						</div>
					<?php } ?>
					<?php if ($spect) { ?>
						<div class="rmse-vat-results-highlight-info-spectators">
							<?php _e('Spectators', 'rmse-vat-results'); ?>
							<?php echo ' ' . intval($game->spectators); ?>
						</div>
					<?php } ?>
				</div>

				<div class="rmse-vat-results-highlight-guest">
					<?php if ($logos) { ?>
						<div title="<?php echo esc_attr($game->teamBName); ?>" class="rmse-vat-results-highlight-logo">
							<img
								src="<?php echo esc_url( rmse_vat_results_team_logo($game->teamBId, $game->clubTeamBId, $size, $size) ); ?>"
								height="<?php echo esc_attr($size); ?>" width="<?php echo esc_attr($size); ?>"
								alt="<?php echo esc_attr($game->teamBName); ?>" />
						</div>
					<?php } ?>
					<?php if ($names) { ?>
						<div class="rmse-vat-results-highlight-name">
							<?php echo esc_html($game->teamBName); ?>
						</div>
					<?php } ?>
				</div>

			</div>
		</div>
	<?php }
}
