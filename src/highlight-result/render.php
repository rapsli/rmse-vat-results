<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Team-ID ermitteln (statisch oder ACF)
$team_id = rmse_vat_get_selected_team_id( $attributes, get_the_ID() );

// Wenn keine Team-ID ermittelbar ist → leerer Zustand
if ( empty($team_id) ) { ?>
	<div class="rmse-vat-results-highlight-result-empty" <?php echo get_block_wrapper_attributes(); ?>>
		<?php _e('No team selected', 'rmse-vat-results'); ?>
	</div>
<?php
	return;
}

$games = rmse_vat_results_retrieve_team_schedule( $team_id );

if ($games !== false) {
	require_once dirname(__DIR__) . '/../inc/template-loader.php';

	// Wähle den Block-Namen exakt wie dein Build-Ordner heißt
	$block  = 'highlight-result';

	// Optionales Layout-Attribut (oder per Filter steuern)
	$layout = $attributes['layout'] ?? 'default';

	// Wrapper-Attribute (z. B. eine Layout-Klasse anhängen)
	$wrapper_attrs = get_block_wrapper_attributes( [
		'class' => 'rmse-vat-results-highlight-result layout-' . sanitize_html_class($layout)
	] );

	// Alles, was das Template braucht, als Context übergeben
	echo rmse_vat_render_template($block, $layout, [
		'games'          => $games,
		'attributes'    => $attributes,
		'wrapper_attrs' => $wrapper_attrs,
	]);
}
