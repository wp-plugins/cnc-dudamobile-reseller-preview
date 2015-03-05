<?php
// sunny-demo/admin/includes/class-sunny-demo-option.php
 
/**
 * @package     Sunny_Demo
 * @subpackage  Sunny_Demo_Admin
 * @author      Tang Rufus <tangrufus@gmail.com>
 * @license     GPL-2.0+
 * @link        http://tangrufus.com/wordpress-plugin-boilerplate-tutorial-options-page/
 * @copyright   2014 Tang Rufus
 * @author      Tang Rufus <tangrufus@gmail.com>
 */
 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
 
class DudaPro_Option {
    /**
     * Instance of this class.
     *
     * @since    1.0.0
     *
     * @var      object
     */
    protected static $instance = null;
 
    /**
     * Initialize the plugin by registrating settings
     *
     * @since     1.0.0
     */
    private function __construct() {
 
        // Get $plugin_slug from public plugin class.
        $plugin = Dudapro::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();
 
        // Register Settings
        $this->register_settings();
    }
 
    /**
     * Return an instance of this class.
     *
     * @since     1.0.0
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
 
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
 
        return self::$instance;
    }
 
    /**
     * Register the CloudFlare account section, CloudFlare email field
     * and CloudFlare api key field
     *
     * @since     1.0.0
     */
    private function register_settings() {


	}
  
  
} //end Sunny_Demo_Option Class