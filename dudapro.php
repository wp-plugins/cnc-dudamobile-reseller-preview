<?php

/**

 *

 * @package   DudaPro

 * @author    Programming Genius <kevin@programminggenius.com>

 * @license   GPL-2.0+

 * @link      http://programminggenius.com

 * @copyright 2014 CNC Web Solutions

 *

 * @wordpress-plugin

 * Plugin Name:       DudaPro for WordPress

 * Plugin URI:        http://programminggenius.com/dudapro-for-wordpress/

 * Description:       Extend your DudaPro services by bringing features to your own website

 * Version:           2.6.11

 * Author:            Programming Genius

 * Author URI:        http://programminggenius.com

 * Text Domain:       dudapro

 * License:           GPL-2.0+

 * Domain Path:       /languages

 */



// If this file is called directly, abort.

if ( ! defined( 'WPINC' ) ) {

	die;

}



/* hook updater to init */

add_action( 'init', 'cnc_dudapro_plugin_updater_init' );



/**

 * Load and Activate Plugin Updater Class.

 */

function cnc_dudapro_plugin_updater_init() {



    /* Load Plugin Updater */

    require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/plugin-updater.php' );



    /* Updater Config */

    $config = array(

        'base'      => plugin_basename( __FILE__ ), //required

        'dashboard' => false,

        'username'  => false,

        'key'       => '',

        'repo_uri'  => 'http://cncwebsolutions.com/',

        'repo_slug' => '2552-2',

    );



    /* Load Updater Class */

    new CNC_DudaPro_Plugin_Updater( $config );

}









/*----------------------------------------------------------------------------*

 * Public-Facing Functionality

 *----------------------------------------------------------------------------*/







require_once( plugin_dir_path( __FILE__ ) . 'public/class-dudapro-name.php' );

require_once( plugin_dir_path( __FILE__ ) . 'includes/stripe-php-1.17.1/lib/Stripe.php' ); 









/*

 * Register hooks that are fired when the plugin is activated or deactivated.

 * When the plugin is deleted, the uninstall.php file is loaded.

 *

 * @TODO:

 *

 * - replace DudaPro with the name of the class defined in

 *   `class-dudapro-name.php`

 */

register_activation_hook( __FILE__, array( 'DudaPro', 'activate' ) );

register_deactivation_hook( __FILE__, array( 'DudaPro', 'deactivate' ) );



/*

 * @TODO:

 *

 * - replace DudaPro with the name of the class defined in

 *   `class-dudapro-name.php`

 */

add_action( 'plugins_loaded', array( 'DudaPro', 'get_instance' ) );



/*----------------------------------------------------------------------------*

 * Dashboard and Administrative Functionality

 *----------------------------------------------------------------------------*/



/*

 * @TODO:

 *

 * - replace `class-dudapro-admin.php` with the name of the plugin's admin file

 * - replace DudaPro_Admin with the name of the class defined in

 *   `class-dudapro-admin.php`

 *

 * If you want to include Ajax within the dashboard, change the following

 * conditional to:

 *

 * if ( is_admin() ) {

 *   ...

 * }

 *

 * The code below is intended to to give the lightest footprint possible.

 */

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {



	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-dudapro-admin.php' );









	add_action( 'plugins_loaded', array( 'DudaPro_Admin', 'get_instance' ) );



}


// create table to store data
global $cnc_dudapro_db_version;
$cnc_dudapro_db_version = '1.0';

function cnc_dudapro_install() {
	global $wpdb;
	global $cnc_dudapro_db_version;

	$table_name = $wpdb->prefix . 'cnc_dudapro';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		email text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		lead text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'cnc_dudapro_db_version', $cnc_dudapro_db_version );
}

function cnc_dudapro_install_data() {
	global $wpdb;
	$cnc_dudapro_name = "sample name";
	$cnc_dudapro_email = "sample email";
	$cnc_dudapro_url = "sample url";
	$cnc_dudapro_lead = "mobile";
	
	$table_name = $wpdb->prefix . 'cnc_dudapro';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $cnc_dudapro_name, 
			'email' => $cnc_dudapro_email,
			'url' => $cnc_dudapro_url, 
			'lead' => $cnc_dudapro_lead
		) 
	);
}

register_activation_hook( __FILE__, 'cnc_dudapro_install' );
register_activation_hook( __FILE__, 'cnc_dudapro_install_data' );