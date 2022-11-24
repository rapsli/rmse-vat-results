<?php

/**
 * The core plugin class.
 */
class TcShvResultate
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     */
    public function __construct()
    {
        $this->plugin_name = 'tc-shv-resultate';

        $this->version = '1.1.2';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - TcShvResultateLoader. Orchestrates the hooks of the plugin.
     * - TcShvResultateAdmin. The part for the admin interface of the plugin.
     * - TcShvResultatePublic. Defines all hooks for the public side of the site.
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tc-shv-resultate-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tc-shv-resultate-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tc-shv-resultate-public.php';

        /**
         * The class responsible for defining all shortcodes.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tc-shv-resultate-shortcodes.php';

        /**
         * The class responsible for defining the cronjob.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tc-shv-resultate-cronjob.php';

        /**
         * Adding the activator because I need it to update the tables
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tc-shv-resultate-activator.php';

        $this->loader = new TcShvResultateLoader();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new TcShvResultateAdmin($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     */
    private function define_public_hooks()
    {
        $plugin_public = new TcShvResultatePublic($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }
}
