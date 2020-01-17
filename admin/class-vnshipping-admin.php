<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://plugin.com/
 * @since      1.0.0
 *
 * @package    Vnshipping
 * @subpackage Vnshipping/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Vnshipping
 * @subpackage Vnshipping/admin
 * @author     Dmitry <abakan_ac545@mail.ru>
 */
class Vnshipping_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action("admin_menu", array($this, "set_options_page"));
    }

    public function set_options_page()
    {
        add_menu_page(
            "Shipping Options",
            "Shipping Wordpres",
            "manage_options",
            "shipping_options",
            array($this, 'render'),
            "dashicons-cart",
            25
        );
        add_submenu_page(
            "shipping_options",
            "submenu Shipping Wordpres",
            "submenu Shipping",
            "manage_options",
            "shipping_options_sub",
            array($this, 'render1')
        );
    }

    public function render()
    {
        require plugin_dir_path(dirname(__FILE__)) . 'admin/partials/vnshipping-admin-display.php';
    }

    public function render1()
    {
        echo "sddsds";
        //require plugin_dir_path( dirname(__FILE__) ) . 'admin/partials/vnshipping-admin-display.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Vnshipping_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Vnshipping_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/vnshipping-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Vnshipping_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Vnshipping_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/vnshipping-admin.js', array('jquery'), $this->version, false);

    }

}
