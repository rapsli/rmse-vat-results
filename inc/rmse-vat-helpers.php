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



/**
 * Check if a URL is external (not on the same domain). The URL can also be relative. In that case it is considered internal.
 * @param string $url The URL to check.
 * @return bool True if the URL is external, false otherwise.
 */
function rmse_vat_results_is_external_url($url) {
    $site_url = parse_url( home_url() );
    $input_url = parse_url( $url );

    if ( empty( $input_url['host'] ) ) {
        // Relative URL, consider it internal
        return false;
    }

    return ( $input_url['host'] !== $site_url['host'] );
}