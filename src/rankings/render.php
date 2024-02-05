<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$group_info = rmse_vat_results_retrieve_team_group($attributes['team']);

if ($group_info !== false) { ?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<h3 class="rmse-vat-results-rankings-header">
			<?php echo $group_info->leagueLong; ?> (
			<?php echo $group_info->groupText; ?>)
		</h3>
		<table class="rmse-vat-results-table">
			<?php if ($attributes['header']) { ?>
				<thead>
					<tr>
						<th class="rmse-vat-results-rankings-rank"><?php _e('Rank', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-team" <?php if ($attributes['logo'] === true) { ?>colspan="2" <?php } ?>><?php _e('Team', 'rmse-vat-results'); ?>
						</th>
						<th class="rmse-vat-results-rankings-games"><?php _e('G', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-wins"><?php _e('W', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-draws"><?php _e('D', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-losses"><?php _e('L', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-diff"><?php _e('+/-', 'rmse-vat-results'); ?></th>
						<th class="rmse-vat-results-rankings-points"><?php _e('Pts', 'rmse-vat-results'); ?></th>
					</tr>
				</thead>
			<?php } ?>
			<tbody>
				<?php foreach ($group_info->ranking as $ranking) {
					$clz = '';
					if ($ranking->promotion === true) {
						$clz = $clz . ' rmse-vat-results-rankings-promotion';
					}
					if ($ranking->promotion_candidate === true) {
						$clz = $clz . ' rmse-vat-results-rankings-promotion-candidate';
					}
					if ($ranking->relegation === true) {
						$clz = $clz . ' rmse-vat-results-rankings-relegation';
					}
					if ($ranking->relegation_candidate === true) {
						$clz = $clz . ' rmse-vat-results-rankings-relegation-candidate';
					}
					if (strval($ranking->teamId) === $attributes['team']) {
						$clz = $clz . ' rmse-vat-results-rankings-own-team';
					}
					?>
					<tr class="<?php echo $clz; ?>">
						<td class="rmse-vat-results-rankings-rank">
							<?php echo $ranking->rank; ?>
						</td>
						<?php if ($attributes['logo'] === true) { ?>
							<td class="rmse-vat-results-rankings-team-logo">
								<img src="<?php echo rmse_vat_results_team_logo($ranking->teamId, $ranking->clubId, $attributes['logosize'], $attributes['logosize']); ?>"
									alt="Logo <?php echo $ranking->teamName; ?>" />&nbsp;
							</td>
						<?php } ?>
						<td class="rmse-vat-results-rankings-team">
							<?php echo $ranking->teamName; ?>
						</td>
						<td class="rmse-vat-results-rankings-games">
							<?php echo $ranking->totalGames; ?>
						</td>
						<td class="rmse-vat-results-rankings-wins">
							<?php echo $ranking->totalWins; ?>
						</td>
						<td class="rmse-vat-results-rankings-draws">
							<?php echo $ranking->totalDraws; ?>
						</td>
						<td class="rmse-vat-results-rankings-losses">
							<?php echo $ranking->totalLoss; ?>
						</td>
						<td class="rmse-vat-results-rankings-diff">
							<?php echo $ranking->totalScoresDiff; ?> (
							<?php echo $ranking->totalScoresPlus; ?>:
							<?php echo $ranking->totalScoresMinus; ?>)
						</td>
						<td class="rmse-vat-results-rankings-points">
							<?php echo $ranking->totalPoints; ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<?php
} else {
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>><?php _e('Team could not be loaded', 'rmse-vat-results'); ?>
	</div>
	<?php
}
