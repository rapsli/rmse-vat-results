<?php


/**
 * Get the selected team ID based on block attributes and post context.
 * @param array $attributes Block attributes.
 * @param int|null $post_id Optional post ID for ACF context.
 * @return string|null Team ID or null if not found.
 */
function rmse_vat_get_selected_team_id( $attributes, $post_id = null ) {
    $source = isset($attributes['teamSource']) ? $attributes['teamSource'] : 'static';

    // ACF-Quelle
    if ( $source === 'acf' && !empty($attributes['teamAcfField']) && function_exists('get_field') ) {
        $val = get_field( $attributes['teamAcfField'], $post_id ?: get_the_ID() );

        // Falls ACF-Select als Array zurückkommt (z. B. ['value' => '123'])
        if ( is_array($val) && isset($val['value']) ) {
            $team_id = $val['value'];
        } else {
            $team_id = $val;
        }
        return $team_id ? (string) $team_id : null;
    }

    // Statisch
    if ( !empty($attributes['team']) ) {
        return (string) $attributes['team'];
    }

    return null;
}