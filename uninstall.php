<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   DudaPro
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */


 
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { 
	exit;
}
 
if ( get_option( 'sunny_demo_cloudflare_email' ) != false )
	delete_option('sunny_demo_cloudflare_email');
 
if ( get_option( 'sunny_demo_cloudflare_api_key' ) != false )
	delete_option('sunny_demo_cloudflare_api_key');