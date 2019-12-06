<?php

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 */
class TcShvResultateLoader
{

    /**
     * The array of actions registered with WordPress.
     */
    protected $actions;

    /**
     * The array of filters registered with WordPress.
     */
    protected $filters;

    /**
     * Initialize all our shortcodes and start the cronjob that is executed regularly
     * to update our database from the SHV REST API.
     */
    public function __construct()
    {

        $this->actions = array(
            array("hook" => "init", "component" => &$this, "callback" => "shortcodes_init"),
            array("hook" => "wp", "component" => &$this, "callback" => "cronstarter_activation"),
            array("hook" => "tc-shv-resultate-update-data", "component" => "TcShvResultateCronJob", "callback" => "update_data"),
        );
        $this->filters = array(
            array("hook" => "cron_schedules", "component" => &$this, "callback" => "cron_add_minute", "accepted_args" => 1),
        );

    }

    /**
     * Add a new action to the collection to be registered with WordPress.
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Add a new filter to the collection to be registered with WordPress.
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * A utility function that is used to register the actions and hooks into a single
     * collection.
     */
    private function add($hooks, $hook, $component, $callback, $priority, $accepted_args)
    {

        $hooks[] = array(
            'hook' => $hook,
            'component' => $component,
            'callback' => $callback,
            'priority' => $priority,
            'accepted_args' => $accepted_args,
        );

        return $hooks;

    }

    /**
     * Register the filters and actions with WordPress.
     */
    public function run()
    {

        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'] ?? 10, $hook['accepted_args'] ?? 1);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], array($hook['component'], $hook['callback']), $hook['priority'] ?? 10, $hook['accepted_args'] ?? 1);
        }

    }

    /**
     * Responsible for starting the cronjob if it's not already running.
     */
    public function cronstarter_activation()
    {
        if (!wp_next_scheduled('tc-shv-resultate-update-data')) {
            wp_schedule_event(time(), 'everyminute', 'tc-shv-resultate-update-data');
        }
    }

    /**
     * This registers all the shortcodes that we use on our website for showing the
     * records from the database.
     */
    public function shortcodes_init()
    {
        add_shortcode('tc-shv-resultate', array('TcShvResultateShortcodes', 'resultate'));
        add_shortcode('tc-shv-spiele', array('TcShvResultateShortcodes', 'spiele'));
        add_shortcode('tc-shv-halle', array('TcShvResultateShortcodes', 'halle'));
        add_shortcode('tc-shv-team', array('TcShvResultateShortcodes', 'team'));
        add_shortcode('tc-shv-team-lastresult', array('TcShvResultateShortcodes', 'teamlastresult'));
        add_shortcode('tc-shv-team-nextgame', array('TcShvResultateShortcodes', 'teamnextgame'));
        add_shortcode('tc-shv-team-highlight', array('TcShvResultateShortcodes', 'teamhighlight'));
    }

    // add custom interval
    public function cron_add_minute($schedules)
    {
        // Adds once every minute to the existing schedules.
        $schedules['everyminute'] = array(
            'interval' => 60,
            'display' => __('Once Every Minute'),
        );
        return $schedules;
    }
}
