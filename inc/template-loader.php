<?php
/**
 * Simple template loader for block templates
 * Looks in:
 *   1) Child/Parent Theme:  /rmse-vat-results/<block>/template[-<layout>].php
 *   2) Plugin build folder: /build/<block>/template/template[-<layout>].php
 */

if ( ! function_exists('rmse_vat_template_candidates') ) {
    function rmse_vat_template_candidates( string $block, string $layout = 'default' ): array {
        $files = [];
        // with layout suffix
        if ( $layout && $layout !== 'default' ) {
            $files[] = "template-{$layout}.php";
        }
        // fallback without suffix
        $files[] = "template.php";
        return $files;
    }
}

if ( ! function_exists('rmse_vat_locate_template') ) {
    function rmse_vat_locate_template( string $block, string $layout = 'default' ): string {
        $candidates = rmse_vat_template_candidates( $block, $layout );

        // 1) Theme override: /rmse-vat-results/<block>/...
        $theme_base = trailingslashit( get_stylesheet_directory() ) . 'rmse-vat-results/' . $block . '/';
        foreach ( $candidates as $file ) {
            $path = $theme_base . $file;
            if ( file_exists( $path ) ) return $path;
        }
        // Also check parent theme if different
        if ( get_template_directory() !== get_stylesheet_directory() ) {
            $parent_base = trailingslashit( get_template_directory() ) . 'rmse-vat-results/' . $block . '/';
            foreach ( $candidates as $file ) {
                $path = $parent_base . $file;
                if ( file_exists( $path ) ) return $path;
            }
        }

        // 2) Plugin fallback: /build/<block>/template/...
        // We are likely in /wp-content/plugins/rmse-vat-results/<block>/render.php
        // -> plugin root is one level up from the block dir.
        $plugin_root = dirname( __DIR__ ); // /rmse-vat-results
        $plugin_base = trailingslashit( $plugin_root ) . 'build/' . $block . '/templates/';
        foreach ( $candidates as $file ) {
            $path = $plugin_base . $file;
            if ( file_exists( $path ) ) return $path;
        }

        return ''; // nothing found
    }
}

if ( ! function_exists('rmse_vat_render_template') ) {
    function rmse_vat_render_template( string $block, string $layout, array $context = [] ): string {
        $file = rmse_vat_locate_template( $block, $layout );
        if ( ! $file ) return '';

        // Make $context keys available as variables inside the template.
        extract( $context, EXTR_SKIP );

        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
