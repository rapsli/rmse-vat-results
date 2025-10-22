<?php
/**
 * Template for Highlight Preview block - modern layout
 * 
 * Overwrite this file by placing a copy in your (child) theme:
 *  /rmse-vat-results/highlight-preview/template-modern.php
 * 
 * 
 * $context array contains:
 * - $games: array of games
 * - $attributes: block attributes
 * - $wrapper_attrs: attributes for the block wrapper
 * 
 */

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

			<div class="rmse-vat-results-highlight-result-top-info">
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


			</div>


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
			<div class="rmse-vat-results-highlight-result-bottom-info">
					<div>Spielbericht</div>
			</div>
		</div>
	<?php }