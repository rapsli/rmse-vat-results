<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$group_info = tc_shv_results_retrieve_team_group($attributes['team']);

if ($group_info !== false) { ?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<h3 class="tc-shv-results-rankings-header">
			<?php echo $group_info->leagueLong; ?> (
			<?php echo $group_info->groupText; ?>)
		</h3>
		<table class="tc-shv-results-table">
			<?php if ($attributes['header']) { ?>
				<thead>
					<tr>
						<th class="tc-shv-results-rankings-rank"><?php _e('Rank', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-team" <?php if ($attributes['logo'] === true) { ?>colspan="2" <?php } ?>><?php _e('Team', 'tc-shv-results'); ?>
						</th>
						<th class="tc-shv-results-rankings-games"><?php _e('G', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-wins"><?php _e('W', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-draws"><?php _e('D', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-losses"><?php _e('L', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-diff"><?php _e('+/-', 'tc-shv-results'); ?></th>
						<th class="tc-shv-results-rankings-points"><?php _e('Pts', 'tc-shv-results'); ?></th>
					</tr>
				</thead>
			<?php } ?>
			<tbody>
				<?php foreach ($group_info->ranking as $ranking) {
					$clz = '';
					if ($ranking->promotion === true) {
						$clz = $clz . ' tc-shv-results-rankings-promotion';
					}
					if ($ranking->promotion_candidate === true) {
						$clz = $clz . ' tc-shv-results-rankings-promotion-candidate';
					}
					if ($ranking->relegation === true) {
						$clz = $clz . ' tc-shv-results-rankings-relegation';
					}
					if ($ranking->relegation_candidate === true) {
						$clz = $clz . ' tc-shv-results-rankings-relegation-candidate';
					}
					if (strval($ranking->teamId) === $attributes['team']) {
						$clz = $clz . ' tc-shv-results-rankings-own-team';
					}
					?>
					<tr class="<?php echo $clz; ?>">
						<td class="tc-shv-results-rankings-rank">
							<?php echo $ranking->rank; ?>
						</td>
						<?php if ($attributes['logo'] === true) { ?>
							<td class="tc-shv-results-rankings-team-logo">
								<img src="<?php echo tc_shv_results_team_logo($ranking->teamId, $ranking->clubId, $attributes['logosize'], $attributes['logosize']); ?>"
									alt="Team Logo <?php echo $team->teamName; ?>" />&nbsp;
							</td>
						<?php } ?>
						<td class="tc-shv-results-rankings-team">
							<?php echo $ranking->teamName; ?>
						</td>
						<td class="tc-shv-results-rankings-games">
							<?php echo $ranking->totalGames; ?>
						</td>
						<td class="tc-shv-results-rankings-wins">
							<?php echo $ranking->totalWins; ?>
						</td>
						<td class="tc-shv-results-rankings-draws">
							<?php echo $ranking->totalDraws; ?>
						</td>
						<td class="tc-shv-results-rankings-losses">
							<?php echo $ranking->totalLoss; ?>
						</td>
						<td class="tc-shv-results-rankings-diff">
							<?php echo $ranking->totalScoresDiff; ?> (
							<?php echo $ranking->totalScoresPlus; ?>:
							<?php echo $ranking->totalScoresMinus; ?>)
						</td>
						<td class="tc-shv-results-rankings-points">
							<?php echo $ranking->totalPoints; ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php if ($group_info->modus && !$group_info->modusHtml) { ?>
			<div class="tc-shv-results-ranking-modus">
				<h4><?php _e('Modus', 'tc-shv-results') ?></h4>
				<div>
					<?php echo $group_info->modus ?>
				</div>
			</div>
		<?php } ?>
		<?php if ($group_info->modusHtml) { ?>
			<div class="tc-shv-results-ranking-modus">
			<h4><?php _e('Modus', 'tc-shv-results') ?></h4>
				<div>
					<?php echo $group_info->modusHtml ?>
				</div>
			</div>
		<?php } ?>
	</div>
	<?php
} else {
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>><?php _e('Team could not be loaded!', 'tc-shv-results'); ?>
	</div>
	<?php
}
