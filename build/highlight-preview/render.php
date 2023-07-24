<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$games = tc_shv_results_retrieve_team_schedule($attributes['team']);

if ($games !== false) {
	$planned = $games[1];
	if ($planned === false || count($planned) === 0) { ?>
		<div class="tc-shv-results-highlight-empty" <?php echo get_block_wrapper_attributes(); ?>>
			<?php _e('No games planned yet!') ?>
		</div>
	<?php } else {
		$game = $planned[0];
		?>
		<div <?php echo get_block_wrapper_attributes(); ?>>
			<div class="tc-shv-results-highlight-result">
				<div clas="tc-shv-results-highlight-home">
					<?php if ($attributes['logos']) { ?>
						<div class="tc-shv-results-highlight-logo">
							<img
								src="<?php echo tc_shv_results_team_logo($game->teamAId, $game->clubTeamAId, $attributes['logosize'], $attributes['logosize']); ?>"
								height="<?php echo $attributes['logosize']; ?>" width="<?php echo $attributes['logosize']; ?>"
								alt="<?php echo $game->teamAName; ?>" />
						</div>
					<?php } ?>
					<?php if ($attributes['names']) { ?>
						<div class="tc-shv-results-highlight-name">
							<?php echo $game->teamAName; ?>
						</div>
					<?php } ?>
				</div>

				<div class="tc-shv-results-highlight-info">
					<div class="tc-shv-results-highlight-info-date">
						<?php echo date_format($game->gameDateTime, $attributes['dateformat']); ?>
					</div>
					<?php if ($attributes['venue']) { ?>
						<div class="tc-shv-results-highlight-info-venue">
							<?php echo $game->venue; ?>
						</div>
					<?php } ?>
				</div>

				<div clas="tc-shv-results-highlight-guest">
					<?php if ($attributes['logos']) { ?>
						<div class="tc-shv-results-highlight-logo"><img
								src="<?php echo tc_shv_results_team_logo($game->teamBId, $game->clubTeamBId, $attributes['logosize'], $attributes['logosize']); ?>"
								height="<?php echo $attributes['logosize']; ?>" width="<?php echo $attributes['logosize']; ?>"
								alt="<?php echo $game->teamBName; ?>" /></div>
					<?php } ?>
					<?php if ($attributes['names']) { ?>
						<div class="tc-shv-results-highlight-name">
							<?php echo $game->teamBName; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		<?php }
}
?>
