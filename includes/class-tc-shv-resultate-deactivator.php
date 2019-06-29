<?php

/**
 * Fired during plugin deactivation.
 */
class TcShvResultateDeactivator
{

    /**
     * This will just deactivate the Update of the Data in the DB
     */
    public static function deactivate()
    {
        // deactivate the update sceduler
        $timestamp = wp_next_scheduled('tc-shv-resultate-update-data');
        wp_unschedule_event($timestamp, 'tc-shv-resultate-update-data');

        global $wpdb;
    }
}
