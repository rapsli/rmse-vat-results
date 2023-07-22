<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$group_info = tc_shv_results_retrieve_team_group($attributes['team']);

if ($group_info !== false) {?>
<div  <?php echo get_block_wrapper_attributes(); ?>>
	<h3 class="tc-shv-results-rankings-header"><?php echo $group_info->leagueLong; ?> (<?php echo $group_info->groupText; ?>)</h3>
	<table>
<?php if($attributes['header']) { ?>
	<thead>
		<tr>
			<th>Rank</th>
			<th>Team</th>
			<th>Sp</th>
			<th>S</th>
			<th>U</th>
			<th>N</th>
			<th>TD (+/-)</th>
			<th>Punkte</th>
		</tr>
		</thead>
	<?php } ?>
	<tbody>
		<?php foreach($group_info->ranking as $ranking) {
			$clz = '';
			if ($ranking->promotion === true) {
				$clz = $clz . ' tc-shv-results-ranking-promotion';
			}
			if ($ranking->promotion_candidate === true) {
				$clz = $clz . ' tc-shv-results-ranking-promotion-candidate';
			}
			if ($ranking->relegation === true) {
				$clz = $clz . ' tc-shv-results-ranking-relegation';
			}
			if ($ranking->relegation_candidate === true) {
				$clz = $clz . ' tc-shv-results-ranking-relegation-candidate';
			}
			if ($ranking->teamId === $attributes['team']) {
				$clz = $clz . ' tc-shv-results-ranking-own-team';
			}
			?>
			<tr class="<?php echo $clz; ?>">
				<td><?php echo $ranking->rank; ?></td>
				<td>
					<?php if ($attributes['logo'] === true) { ?>
						<img src="<?php echo tc_shv_results_team_logo($ranking->teamId, $ranking->clubId, 35, 35); ?>" alt="Team Logo <?php echo $team->teamName; ?>" />&nbsp;
					<?php } ?>
					<?php echo $ranking->teamName; ?>
				</td>
				<td><?php echo $ranking->totalGames; ?></td>
				<td><?php echo $ranking->totalWins; ?></td>
				<td><?php echo $ranking->totalDraws; ?></td>
				<td><?php echo $ranking->totalLoss; ?></td>
				<td><?php echo $ranking->totalScoresDiff; ?> (<?php echo $ranking->totalScoresPlus; ?>:<?php echo $ranking->totalScoresMinus; ?>)</td>
				<td><?php echo $ranking->totalPoints; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<div class="tc-shv-results-ranking-modus"><?php echo $group_info->modus ?></div>
<?php if ($group_info->modusHtml) { ?>
<div class="tc-shv-results-ranking-modus-html"><?php echo $group_info->modusHtml ?></div>
<?php } ?>
</div><?php
} else {
	?><div <?php echo get_block_wrapper_attributes(); ?>><?php esc_html_e('Team could not be loaded!', 'tc-shv-results'); ?></div><?php
}
