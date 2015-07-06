<?php

/**

 * Plugin Name.

 *

 * @package   DudaPro

 * @author    Your Name <email@example.com>

 * @license   GPL-2.0+

 * @link      http://example.com

 * @copyright 2014 Your Name or Company Name

 */



/**

 * Plugin class. This class should ideally be used to work with the

 * public-facing side of the WordPress site.

 *

 * If you're interested in introducing administrative or dashboard

 * functionality, then refer to `class-dudapro-admin.php`

 *

 * @TODO: Rename this class to a proper name for your plugin.

 *

 * @package DudaPro

 * @author  Your Name <email@example.com>

 */

class DudaPro {

	

	public $website;

	/**

	 * Plugin version, used for cache-busting of style and script file references.

	 *

	 * @since   1.0.0

	 *

	 * @var     string

	 */

	const VERSION = '2.6';



	/**

	 * @TODO - Rename "plugin-name" to the name of your plugin

	 *

	 * Unique identifier for your plugin.

	 *

	 *

	 * The variable name is used as the text domain when internationalizing strings

	 * of text. Its value should match the Text Domain file header in the main

	 * plugin file.

	 *

	 * @since    1.0.0

	 *

	 * @var      string

	 */

	protected $plugin_slug = 'dudapro';



	/**

	 * Instance of this class.

	 *

	 * @since    1.0.0

	 *

	 * @var      object

	 */

	protected static $instance = null;



	/**

	 * Initialize the plugin by setting localization and loading public scripts

	 * and styles.

	 *

	 * @since     1.0.0

	 */

	private function __construct() {



		// Load plugin text domain

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );



		// Activate plugin when new blog is added

		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );



		// Load public-facing style sheet and JavaScript.

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login




	// load admin style sheet & Javascript
	
	

		/* Define custom functionality.

		 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters

		 */

		

		

		

		add_shortcode( 'dudapro_multiscreen', array( $this, 'dudapro_create_multiscreen' ) );
		add_shortcode( 'dudapro_multisite', array( $this, 'dudapro_create_multiscreen' ) );
		
		add_shortcode( 'dudapro_client_login', array( $this, 'dudapro_d1_sites' ) );
		add_shortcode( 'dudapro_d1_sites', array( $this, 'dudapro_d1_sites' ) );

		add_shortcode( 'dudapro_mobile', array( $this, 'dudapro_create_mobile_preview' ) );



		















add_action( 'wp_enqueue_scripts', $this->cnc_dudaone_scripts() ); // wp_enqueue_scripts action hook to link only on the front-end









	}


public function getPreviewLink($templateID)
{
	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];
	
	
$API_USER = $duda_username;
$API_PASS = $duda_password;
//Set parameters to make cURL call to Duda
$ch = curl_init();
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/multiscreen/templates/' . $templateID);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, $API_USER.':'.$API_PASS);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//execute cURL call and get template data
$output = curl_exec($ch);
//check for errors in cURL
if(curl_errno($ch)) {
    die('Curl error: ' . curl_error($ch));
}
$output = json_decode($output);
//Loop through all templates and display all the available templates in a table
foreach($output as $template) {

	if ($template->template_id == $templateID)
	{
    $previewLink =  $template->preview_url;
	}
}
return $previewLink ;	
}


public function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
      exit;
   }
}


public function pg_demo( $atts   ){
global $wpdb;

// [submit-value-button minimum_value=”40″ button_link=”example.com” text=”that number is too low”]

$pull_atts = shortcode_atts( array(
        'minimum_value' => '10',
		'button_link' => '',
		'message' => '',
       
    ), $atts, 'submit-value-button' );

$minimum_value =  $pull_atts[ 'minimum_value' ] ;
$button_link =  $pull_atts[ 'button_link' ] ;
$message =  $pull_atts[ 'message' ] ;


$output .= '
<style>


button, btn
{
	display: block;
	font-size: 1.1em;
	font-weight: bold;
	text-transform: uppercase;
	padding: 10px 15px;
	margin: 20px auto;
	color: #ccc;
	background-color: #555;
	background: -webkit-linear-gradient(#888, #555);
	background: linear-gradient(#888, #555);
	border: 0 none;
	border-radius: 3px;
	text-shadow: 0 -1px 0 #000;
	box-shadow: 0 1px 0 #666, 0 5px 0 #444, 0 6px 6px rgba(0,0,0,0.6);
	cursor: pointer;
	-webkit-transition: all 150ms ease;
	transition: all 150ms ease;
}

button:hover, button:focus, btn:hover, btn:focus
{
	-webkit-animation: pulsate 1.2s linear infinite;
	animation: pulsate 1.2s linear infinite;
}
	
@-webkit-keyframes pulsate
{
	0%   { color: #ddd; text-shadow: 0 -1px 0 #000; }
	50%  { color: #fff; text-shadow: 0 -1px 0 #444, 0 0 5px #ffd, 0 0 8px #fff; }
	100% { color: #ddd; text-shadow: 0 -1px 0 #000; }
}
		
@keyframes pulsate
{
	0%   { color: #ddd; text-shadow: 0 -1px 0 #000; }
	50%  { color: #fff; text-shadow: 0 -1px 0 #444, 0 0 5px #ffd, 0 0 8px #fff; }
	100% { color: #ddd; text-shadow: 0 -1px 0 #000; }
}

button:active, btn:active
{
	color: #fff;
	text-shadow: 0 -1px 0 #444, 0 0 5px #ffd, 0 0 8px #fff;
	box-shadow: 0 1px 0 #666, 0 2px 0 #444, 0 2px 2px rgba(0,0,0,0.9);
	-webkit-transform: translateY(3px);
	transform: translateY(3px);
	-webkit-animation: none;
	animation: none;
}

</style>

<input id="userValue" type="text">
<div style="display:none;" id ="output">Something</div>

<button id="submit">Click me!</button>o
<input type="hidden"  id="message" value="'. $message . '">
<input type="hidden"  id="minimum_value" value="'. $minimum_value . '">
<input type="hidden"  id="button_link" value="'. $button_link . '">
';
//return 'min value =' . $minimum_value . ' ' . 'link=' . $button_link . ' ' . 'text='. $customtext;
return $output;


		}


	

public function cnc_dudaone_scripts() {

	$pluginsURI = plugins_url('/cnc-dudamobile-reseller-preview/');
	


	wp_register_style('cnc_dpro_css', $pluginsURI . 'public/assets/css/public.css' ); 

	wp_enqueue_style( 'cnc_dpro_css' );





 //  wp_deregister_script( 'jquery' );

    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');

    wp_enqueue_script( 'jquery' );

	

	 wp_deregister_script( 'fancybox' );

	    wp_register_script( 'fancybox', $pluginsURI . 'includes/js/fancyBox/source/jquery.fancybox.js?v=2.1.5');

    wp_enqueue_script( 'fancybox' );

	

	wp_register_style('cnc_fancybox_css', $pluginsURI . 'includes/js/fancyBox/source/jquery.fancybox.css' ); 

	wp_enqueue_style( 'cnc_fancybox_css' );
	
	 wp_enqueue_style( 'fontawesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');

    wp_enqueue_style( 'fontawesome' ); 

	

}

	

	



	/**

	 * Return the plugin slug.

	 *

	 * @since    1.0.0

	 *

	 * @return    Plugin slug variable.

	 */

	public function get_plugin_slug() {

		return $this->plugin_slug;

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

	 * Fired when the plugin is activated.

	 *

	 * @since    1.0.0

	 *

	 * @param    boolean    $network_wide    True if WPMU superadmin uses

	 *                                       "Network Activate" action, false if

	 *                                       WPMU is disabled or plugin is

	 *                                       activated on an individual blog.


	 */

	public static function activate( $network_wide ) {



		if ( function_exists( 'is_multisite' ) && is_multisite() ) {



			if ( $network_wide  ) {



				// Get all blog ids

				$blog_ids = self::get_blog_ids();



				foreach ( $blog_ids as $blog_id ) {



					switch_to_blog( $blog_id );

					self::single_activate();



					restore_current_blog();

				}



			} else {

				self::single_activate();

			}



		} else {

			self::single_activate();

		}



	}



	/**

	 * Fired when the plugin is deactivated.

	 *

	 * @since    1.0.0

	 *

	 * @param    boolean    $network_wide    True if WPMU superadmin uses

	 *                                       "Network Deactivate" action, false if

	 *                                       WPMU is disabled or plugin is

	 *                                       deactivated on an individual blog.

	 */

	public static function deactivate( $network_wide ) {



		if ( function_exists( 'is_multisite' ) && is_multisite() ) {



			if ( $network_wide ) {



				// Get all blog ids

				$blog_ids = self::get_blog_ids();



				foreach ( $blog_ids as $blog_id ) {



					switch_to_blog( $blog_id );

					self::single_deactivate();



					restore_current_blog();



				}



			} else {

				self::single_deactivate();

			}



		} else {

			self::single_deactivate();

		}



	}



	/**

	 * Fired when a new site is activated with a WPMU environment.

	 *

	 * @since    1.0.0

	 *

	 * @param    int    $blog_id    ID of the new blog.

	 */

	public function activate_new_site( $blog_id ) {



		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {

			return;

		}



		switch_to_blog( $blog_id );

		self::single_activate();

		restore_current_blog();



	}



	/**

	 * Get all blog ids of blogs in the current network that are:

	 * - not archived

	 * - not spam

	 * - not deleted

	 *

	 * @since    1.0.0

	 *

	 * @return   array|false    The blog ids, false if no matches.

	 */

	private static function get_blog_ids() {



		global $wpdb;



		// get an array of blog ids

		$sql = "SELECT blog_id FROM $wpdb->blogs

			WHERE archived = '0' AND spam = '0'

			AND deleted = '0'";



		return $wpdb->get_col( $sql );



	}



	/**

	 * Fired for each blog when the plugin is activated.

	 *

	 * @since    1.0.0

	 */

	private static function single_activate() {

		// @TODO: Define activation functionality here

	}



	/**

	 * Fired for each blog when the plugin is deactivated.

	 *

	 * @since    1.0.0

	 */

	private static function single_deactivate() {

		// @TODO: Define deactivation functionality here

	}



	/**

	 * Load the plugin text domain for translation.

	 *

	 * @since    1.0.0

	 */

	public function load_plugin_textdomain() {



		$domain = $this->plugin_slug;

		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );



		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );



	}



	/**

	 * Register and enqueue public-facing style sheet.

	 *

	 * @since    1.0.0

	 */

	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );

	}



	/**

	 * Register and enqueues public-facing JavaScript files.

	 *

	 * @since    1.0.0

	 */

	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );

	}



	/**

	 * NOTE:  Actions are points in the execution of a page or process

	 *        lifecycle that WordPress fires.

	 *

	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions

	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference

	 *

	 * @since    1.0.0

	 */

	public function action_method_name() {

		// @TODO: Define your action hook callback here

	}



	/**

	 * NOTE:  Filters are points of execution in which WordPress modifies data

	 *        before saving it or sending it to the browser.

	 *

	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters

	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference

	 *

	 * @since    1.0.0

	 */

	public function filter_method_name() {

		// @TODO: Define your filter hook callback here

	}

	

	

	function dudapro_test() {

	echo 'it works here too!!';

 

 

 

		}
		


public function dudapro_mobile_sites($email){
	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];

	$path = $_SERVER['DOCUMENT_ROOT'];
	global $display_name , $user_email;






$args = array(

		'headers' => array(

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )

			)

		);
	

	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/mobile/created?from=2000-01-01&to=2050-12-31', $args ));
	$data = json_decode($json, true);
	
	// create array of sites from sometime to way out in the future and store them in an array
	$i = 0;
	if (!empty($data))
	{
	foreach ( $data as $sites )
				{
					$allSites[$i] .= $sites;
					$i++;
				}
		
				
	}
	



	
	
	//print_r($allSites); exit(); 
	
	
$error = '';

 if ( is_user_logged_in() ) 
 { 
 
	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];	
	$dudaD1ListPage = $options5['dudaD1ListPage'];	
	$dudaOrderPage = $options5['dudaOrderPage'];	
 
 
   
//print_r($data); exit();


if ($data['error_code'] == 'ResourceNotExist')
{
//	$message .= 'Sorry, no sites found under this email address, ' . $user_email ;
	
	
	}
	else
	{
		if (empty($data))
			{
		//		$message .= 'Sorry, no sites found under this account.<br/>';
		
			}
			else
			{
				
				$found = 0;
				for ($j=0;$j<=$i;$j++){
					
					$rand = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
					//echo $allSites[$j] . '   ';
					$json3 = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/mobile/' . $allSites[$j]  , $args ));
					$data = json_decode($json3, true);
					if ($data['external_uid'] == $user_email)

					{
					$message .= '<div class="dudapro_listing">';

						$sso_link = $this->generateSSOLink($data['site_name'],$user_email);
						$message .= '<a href="'.  $sso_link .  '"><img src="http://dp-cdn.multiscreensite.com/template-snapshot-prod/'. $data['site_name'] .'.jpg?rand='. $rand . '" style="width:125px;height:125px; padding-right:10px" align="left"></a>';
						$message .= '<strong><a href="'.  $sso_link .  '">'. $data['site_name'] .'</a></strong><br/>';
						
						$message .= '<a href="'.  $sso_link .  '"><i class="fa fa-pencil"></i> Edit</a><br/>';
						$message .= '<a href="' . $data['site_extra_info']['preview_url'] . '" target="_blank"><i class="fa fa-desktop"></i> Preview</a><br/>';
							$message .= '<a href="' . $data['site_extra_info']['before_after_preview_url'] . '" target="_blank"><i class="fa fa-desktop"></i> Comparison</a><br/>';

						$found++;
					$message .="</div><hr/>";
					//$sites .= $data->site_name;	 
					}
				//	print_r($data);
			
				}
				
			//	if ($found == 0) {$message = 'Sorry, no mobile sites found under this email address, ' . $user_email . '<br/>';}
							
			
			}
	 } 
 }
 
 /*else {  
 $actual_link = $dudaD1ListPage;
 $args = array(
        'redirect' => $actual_link, 
		'label_username' => __( 'Email Address' ),
		 'remember'       => true,
        'label_log_in' => __( 'Log in to view your mobile sites' ),
        'remember' => true
    );
	
	
    wp_login_form( $args );
	echo '<a href="' .wp_lostpassword_url( get_permalink() ) . '" title="Lost Password">Lost Password? Click here</a>';
	
	
 
 
   
 }*/
	return $message;
}
	
public function dudapro_d1_sites($email){
	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];
	

	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];	
	$dudaD1ListPage = $options5['dudaD1ListPage'];	
	$dudaOrderPage = $options5['dudaOrderPage'];	
	
	

	$path = $_SERVER['DOCUMENT_ROOT'];
	global $display_name , $user_email;






$args = array(

		'headers' => array(

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )

			)

		);
	

	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/multiscreen/created', $args ));
	$data = json_decode($json, true);
	
	// create array of sites from sometime to way out in the future and store them in an array
	$i = 0;
	if (!empty($data))
	{
	foreach ( $data as $sites )
				{
					$allSites[$i] .= $sites;
					$i++;
				}
		
				
	}
	



	
	
	//print_r($allSites); exit(); 
	

$error = '';

 if ( is_user_logged_in() ) 
 { 
   
//print_r($data); exit();


if ($data['error_code'] == 'ResourceNotExist')
{
//	$message .= 'Sorry, no sites found under this email addres, ' . $user_email ;
	
	
	}
	else
	{
		if (empty($data))
			{
		//		$message .= 'Sorry, no sites found under this email addres, ' . $user_email ;
		
			}
			else
			{
				$message .= '';
				
					$found =0;
				for ($j=0;$j<=$i;$j++){
					$rand = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);
					//echo $allSites[$j] . '   ';
					$json3 = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/multiscreen/' . $allSites[$j]  , $args ));
					$data = json_decode($json3, true);
					if ($data['external_uid'] == $user_email)

					{
					$message .= '<div class="dudapro_listing">';
						if (empty($data['site_business_info']['business_name']))
						{
							$siteName=$data['site_default_domain'];
						}
						else
						{
							$siteName=$data['site_business_info']['business_name'];
						}
						
						if (empty($data['last_published_date']))
						{ $lastPublished = "not published"; }
						else {$lastPublished = "published";}
						
						$sso_link = $this->generateSSOLink($data['site_name'],$user_email);
						$message .= '<a href="'.  $sso_link .  '"><img src="http://dp-cdn.multiscreensite.com/template-snapshot-prod/'. $data['site_name'] .'.jpg?rand='. $rand . '" style="width:125px;height:125px; padding-right:10px" align="left"></a>';
						
						$message .= '<strong>' . $siteName . ' </strong><span style="font-size:9px;">(' . $lastPublished . ')</span><br/>';
						$message .= '<a href="'.  $sso_link .  '"><i class="fa fa-pencil"></i> Edit</a><br/>';
						$message .= '<a href="' . $data['preview_site_url'] . '" target="_blank"><i class="fa fa-desktop"></i> Preview</a><br/>';

						$found++;
					//$sites .= $data->site_name;	
					$message .= '</div><hr/>'; 
					}
					

			//print_r($data);
				}
				$message .= '';
				
		//		if ($found == 0) {$message = 'Sorry, no sites found under this email addres, ' . $user_email ;}
							
			
			}
	 } 
 }
 
 else {  
 $actual_link = $dudaD1ListPage;
 $args = array(
        'redirect' => $actual_link, 
		'label_username' => __( 'Email Address' ),
		 'remember'       => true,
        'label_log_in' => __( 'Log in to view your mobile sites' ),
        'remember' => true
    );
    wp_login_form( $args );
	echo '<a href="' .wp_lostpassword_url( get_permalink() ) . '" title="Lost Password">Lost Password? Click here</a>';
 
 
   
 }
 
 	$message .= $this->dudapro_mobile_sites($user_email);
 
	return $message; 
}		

public function dudapro_mobile_order()
{
	if (!empty($_GET['originalurl']) || !empty($_GET['sitename']))
	{
		
		

		
	// <your URI endpoint>?sitename={unique_site_name}&accountname={sub_account_name}&originalurl={desktop_url}&external_uid={external UID provided to Duda}
$sitename = $_GET['sitename'] ;
$accountname  = $_GET['accountname']  ;
$orginalurl  = $_GET['originalurl']  ;
$external_uid = $_GET['external_uid'] ;
$type = $_GET['t'] ;

// duda added this to the images, so I came up with a quick ranom strong to append to images
$s = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

//if (file_exists('//dp-cdn.multiscreensite.com/template-snapshot-prod/'. $sitename . '.jpg?rand='. $s))
//{
//$message .= '<img  src="//dp-cdn.multiscreensite.com/template-snapshot-prod/'. $sitename . '.jpg?rand='. $s  . '" style="float: left; margin: 0px 15px 15px 0px;" width=200px alt=""/>';
//}


		
		if ($type == 'd1' || empty($_GET['originalurl']))
		{
		$options = get_option('dudapro_multiscreen_display_settings');
		$dudaproductname1 = $options['multiproductname1'];
		$mobilemonthly = $options['multimonthly'];
		$mobilesetup = $options['multisetup'];
		$mobileannually = $options['mobileannually'];
		
		

		$mobileButtonText = $options['mutlibutton'];
		$mobilecustomtext = $options['multiEmailMessage'];
		}
		
		else
		{
					$options = get_option('dudapro_api_display_settings');
		$dudaproductname1 = $options['dudaproductname1'];
		$mobilemonthly = $options['mobilemonthly'];
		$mobilesetup = $options['mobilesetup'];
		$mobileannually = $options['multiannually'];

		$mobileType = $options['mobileType'];
		$mobileButtonText = $options['mobilebutton'];
		$mobilecustomtext = $options['mobilecustomtext'];
		}
		
		$orderMessage = $options['orderMessage'];
		
		$options3 = get_option('dudapro_api_display_settings');
		$mobileType = $options['mobileType'];
		
		$optionsPayment = get_option('dudapro_general_settings');
		$paymentType = $optionsPayment['dudaPayments'];




if ($_GET['diap']==1 or !empty($_GET['auth']))
	{
		
		$publish_site = $this->publish_dudamobile($sitename);
	   
	   if ($publish_site['error_code'] == 'InvalidState')
	   {
		$ssolink = $this->generateSSOLink($_GET['sitename'], $_GET['accountname']);
		$message .= 'Your site is now published and additional features unlocked. ';
		$message .= '<br/><br/><a href="'. $ssolink . '" class="button dudabutton">Login to your site</a>';
	   }
	   else
	   {
		$message .= 'A billing error occured, please contact us. ';
	   }
	}
	
	else
	{
	if (empty($orderMessage))
	{
		$message .= 'Great, you\'re seconds away from publishing your website' ;
	}
		
	$message .= $orderMessage . '<br/><br/>'; // 'Great, your seconds away from publishing a mobile site for ' . $orginalurl . '!<br/><br/>';
	$message .= 'One time setup fee:  $' . number_format((float)$mobilesetup, 2, '.', '') . '<br/>';
	$message .= 'Monthly fee: $' . number_format((float)$mobilemonthly, 2, '.', '') . '';
//	$message .= ' or Annualy fee: $' . number_format((float)$mobileannually, 2, '.', '') . '';
//	$message .= '<br/><br/><label>Choose Payment Plan  </label><select name="pricingOption" id="pricingOption">
//  <option value="'. number_format((float)$mobilemonthly, 2, '.', '') . '">Monthly: '. number_format((float)$mobilemonthly, 2, '.', '') . '</option>
//  <option value="'. number_format((float)$mobileannually, 2, '.', '') . '">Annualy: '. number_format((float)$mobileannually, 2, '.', '') . '</option>
//</select>';
	
// TODO
	if (!(empty($_GET['sitename'])))
	{
		
		if ($paymentType  ==1) {
		$message .=  '<br/><br/>' . $this->show_paypal($orginalurl);
		}
		
		if ($paymentType == 2) {
		$message .=  '<br/><br/>' . $this->show_stripe($orginalurl);
		}
	
		
	}
		else
		{
		$message = "You don't have a site to publish yet.";	
		}
	}
}
return $message;

	
}


public function publish_dudamobile($sitename)
{
//$sitename = $_GET['sitename'] ;
$accountname  = $_GET['accountname']  ;
$orginalurl  = $_GET['originalurl']  ;
$external_uid = $_GET['external_uid'] ;
$type = $_GET['t'] ;
	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];


	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];

	$path = $_SERVER['DOCUMENT_ROOT'];

$args = array(
		'method' => 'post',

		'headers' => array(
			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )
			)

		);
	if ($type == 'd1' || empty($_GET['originalurl']))

	{
		$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/multiscreen/publish/'. $sitename  , $args ));
	}
	else
	{
		$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/mobile/publish/'. $sitename , $args ));
	}
	
	
	$data = json_decode($json, true);

return  $data; //Mobile site '.  $site . ' was deleted, <a href="?page=dudapro-mobile-accts">go back to accounts</a>';
	



	
}


		

	public function dudapro_create_mobile_preview()

	{

		

		$dudastatus = $this->license_check();

		if ("Active" == "Active")

		{

		$options = get_option('dudapro_api_settings');

		$apiusername = $options['apiusername'];

		$apipassword = $options['apipassword'];

		

	$options2 = get_option('dudapro_api_display_settings');

	$mobileType = $options2['mobileType'];

	$mobileButtonText = $options2['mobilebutton'];

	$mobilecustomtext = $options2['mobilecustomtext'];
	
	$mobileNewPage  = $options2['mobileNewPage'];

	

	$options3 = get_option('dudapro_api_display_settings');

	$calltoaction = $options3['calltoaction'];

	
	$optionsdudapro_general_settings  = get_option('dudapro_general_settings');
	$dudaEmailrequirement = $optionsdudapro_general_settings['dudaEmailrequirement'];



		

		?>



<script type="text/javascript">

jQuery(document).ready(function( $ ) {

	

//	$("#paypalexpress").hide();

//	$("#stripeform").hide();

		$("#mobilePreview").hide();	
		$(".mobilePreview").hide();	

		$("#paypalexpress").hide();

		$("#stripeform").hide();	

		$("#dudapro_buy").hide();					

							

	

	

	$("#mobileText").hide();
	$('#ssolink').hide();	

    $("#submitButton").click(function(){

	$('#loading').show('fast');



	var user_website = $('input[name=website]').val(); 
	var user_email = $('input[name=cnc-email]').val(); 

        

        //simple validation at client's end

       

        var proceed = true;

        if(user_website==""){ 

		

            $('input[name=website]').css('border-color','red'); 

            proceed = false;

        }
		 if(user_email==""){ 

		

            $('input[name=cnc-email]').css('border-color','red'); 

            proceed = false;

        }
		

		if(user_website.substr(0,7) !== "http://")

   			 user_website = "http://"+user_website;

		 var v = new RegExp(); 

		v.compile("^[A-Za-z]+://[A-Za-z0-9-_]+\\.[A-Za-z0-9-_%&\?\/.=]+$"); 

		if (!v.test(user_website)) { 

		$('#loading').hide('fast');

			$('input[name=website]').css('border-color','red'); 

            proceed = false; 

		}

		

		

        //everything looks good! proceed...

        if(proceed) 

        {

			

			

		

		

			 $.ajax(

		    {

		        url : "<?= plugins_url('cnc-dudamobile-reseller-preview')?>/includes/mobile/mobileMagic.php",

		        type: "POST",

		        data: "website=" + user_website + "&uiosaf=<?=$apiusername?>&pasdfuia=<?=$apipassword?>&em=" + user_email,

				dataType: "json",	

				success: function (data) {

					var mobilesite ='';
					var loginLink = '';

				<?php if ($mobileType   == 0){?> mobilesite = data.preview; <?php } else  ?>

				<?php if ($mobileType  == 1){?> mobilesite = data.url; <?php }  ?> 
				
				 loginLink ='<center><a href="' + data.ssolink + '" class="button dudabutton"><?=$calltoaction; ?></a></center>' ;
				 
				 <?php if (empty($mobileNewPage)) {$mobileNewPage =0; } ?>
                 
				 mobilenewpage = <?=$mobileNewPage; ?>;
			
				if (mobilenewpage  == 1)
				{
					var url = data.ssolink;    
					$(location).attr('href',url);
					
				}
				
					$("#mobilePreview").show();
					$(".mobilePreview").show();
					$("#ssolink").show();

					$('#loading').hide('fast');

					$('#mobileText').html(mobilesite);
					$('#ssolink').html(loginLink);
						$("#paypalexpress").show();

							$("#stripeform").show();

								$("#dudapro_buy").show();					





				

			

				  //change txtInterest% value

				

				  $('input[name=item_name]').val('Mobile website: ' + mobilesite); 

				  $('input[name=item_name_stripe]').val('Mobile website: ' + mobilesite); 

				

				//alert("Details saved successfully!!!" + response.url);

				$("#mobilePreview").attr("src",mobilesite);
				
				



			  },

			  error: function (xhr, ajaxOptions, thrownError) {

				 $('#loading').hide('fast');

				alert(xhr.status);

				alert(thrownError);

			  }        

		    });

		

				}



  });  

});

</script>



<?php

if ($dudaEmailrequirement != 0){
        $dmEmail .= '<label for="email"> <input name="cnc-email" type="text" id="cnc-email" placeholder=" Enter Your e-mail address"  /></label>';
}
else
{
       $dmEmail  .= '<input type="hidden" name="cnc-email" value="email@notProvided.com">';
	
}


$dudapro_mobile_form = '

<fieldset id="website_form" class="centerCNC">




' . $dmEmail  . '
   



  

  <label for="website">



    <input name="website" type="text" id="website" placeholder=" Enter Your Website"  />



  </label>



  <label>


   <button id="submitButton" class="button dudabutton" type="submit" >



'. $mobileButtonText .'



    </button>



  </label> 

<input name="uiosaf" type="hidden" value="' . $apiusername . '" /> 

<input name="pasdfuia" type="hidden" value="' . $apipassword . '" />



</fieldset>



<div id="loading" style="display: none; margin:0 auto;">



  <center>



    <img src="' . plugins_url('cnc-dudamobile-reseller-preview') . '/images/loader.gif" style="vertical-align: middle;margin:0 auto;" />



  </center>



</div>';



$dudapro_mobile_form2 = '<div id="mobileText"></div> '.


'<div class="mobilePreview"> <iframe id="mobilePreview" name="mobilePreview"></iframe><br/><div id="ssolink"></div></div>';

$dudapro_mobile_result =  	$dudapro_mobile_form . $dudapro_mobile_form2;

 

		}

		else  

		{ 

		$dudapro_mobile_result =   'License is ' . $dudastatus;	

		}

		

		return $dudapro_mobile_result;

	}















	

	public function dudapro_create_multiscreen()

 {

	 $cncmulti = new DudaPro();

	 

	return  $cncmulti->dudamulti_displayTemplates();
	 
	 

 }

 







public function dudamulti_displayTemplates() {

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];	

	

	$apissoendpoint = $options['apissoendpoint'];	

	$apissokey = $options['apissokey'];	

	$apissosecretkey = $options['apissosecretkey'];	



	

	

//Set API user and password

define("API_USER",$duda_username);

define("API_PASS",$duda_password);

//Check if a template was selected from page

if (isset($_GET['template_id'])) {
	
	// insert lead info into log, yay leads!!
	$this->cnc_dudapro_insert_leads('n/a', $_GET['email'], $user_Website, 'dudaone' );



	
	
	

	//if site was selected, use the template_id and original url (if set) to create a new site

	$createdSite = $this->createSite($_GET['template_id'],$_GET['original_url'], $_GET['email']);

	//echo 'Site Created: ' . $createdSite . '<br/>';

	$accountCreated = $this->createSubAccount($_GET['email']);

	//echo 'Account created: ' . $accountCreated . '<br/>';

	$grantAccess = $this->grantAccountAccess($accountCreated,$createdSite);

	//echo 'Account granted access.<br/>';

	$this->createWPAccount($accountCreated,$_GET['original_url'] ,$createdSite  );

	$sso_link = $this->generateSSOLink($createdSite,$accountCreated);

//	setcookie('sso_link', $sso_link); 

	

?>

<script type="text/javascript">

window.location = "<?=$sso_link?>";

</script>

<?php

} 

//if a template was note selected, display template selection

else {

	$show = $this->displayTemplates();
	return $show;

}

//Functions below

}


public function createWPAccount($email_address, $originalurl, $sitename)
{
	
	 
	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];	
	$dudaD1ListPage = $options5['dudaD1ListPage'];	
	$dudaOrderPage = $options5['dudaOrderPage'];	
	
	
	
	$emailMessage = get_option('dudapro_multiscreen_display_settings');
    $EmailMessage = $emailMessage['multiEmailMessage'];
	 
	 if( null == username_exists( $email_address ) ) {
// Generate the password and create the user
	  $password = wp_generate_password( 12, false );
	  $user_id = wp_create_user( $email_address, $password, $email_address );
	 
	  // Set the nickname
	  wp_update_user(
		array(
		  'ID'          =>    $user_id,
		  'nickname'    =>    $email_address,
		  'user_url'	=> 	  $originalurl
		)
	  );
	 
	  // Set the role
	  $user = new WP_User( $user_id );
	  $user->set_role( 'subscriber' );
	 
	 // Email the user
	 $to = $user_email;
	 $headers = 'From: '. $dudaEmailFrom . ' <'.  $dudaEmail . '>' . "\r\n";
	$subject = "Your Account Info for " . get_bloginfo('name'); 
	$message = '<html>
				<head>
				<title>HTML email</title>
				</head>
				<body>' .
	        $EmailMessage .
			'<br/>Username: ' . $email_address . '<br/>' .
			'Password: ' . $password . '<br/>' .
			'<br/>Log in and start editing your site by <a href="'. $dudaD1ListPage . '">clicking here</a>' .
			'</body></html>';
		} 
		else
		{
			 // Email the user
	 $to = $user_email;
	 $headers = 'From: '. $dudaEmailFrom . ' <'.  $dudaEmail . '>' . "\r\n";
	$subject = "Your Account Info for " . get_bloginfo('name'); 
	$message = '<html>
				<head>
				<title>HTML email</title>
				</head>
				<body>' .
	        $EmailMessage .
			'Login using your email address: ' . $email_address . '<br/>' .
		
			'<br/>Log in and start editing your site by <a href="'. $dudaD1ListPage . '">clicking here</a>' .
			'</body></html>';
			
			
		}  // end if
		
		if (empty( $sitename))
		{
			$EmailMessage = 'Your site did not generate correctly, please try it again';
		}
		
	

    add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	
	
	
	$kc = wp_mail($email_address, $subject , $message, $headers );
   // $kc =  wp_mail($user_email, $subject, $message, $headers);	 
	
	return $kc;
	  //wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password , $headers);
	 


}


public function generateSSOLink($siteName,$account) {

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];	

	

	$apissoendpoint = $options['apissoendpoint'];	

	$apissokey = $options['apissokey'];	

	$apissosecretkey = $options['apissosecretkey'];	



	

	

//Set API user and password

define("API_USER",$duda_username);

define("API_PASS",$duda_password);	

	

	

	//Set editor custom domain --

$editor_url = $apissoendpoint;

//Set SSO Parameters

$dm_sig_site = $siteName;

$dm_sig_user = $account;

$dm_sig_partner_key = $apissokey;

$dm_sig_timestamp = date_timestamp_get(date_create());

$secret_key = $apissosecretkey;

//Concatenate sso strings so it can be encrypted

$dm_sig_string = $secret_key.'user='.$dm_sig_user.'timestamp='.$dm_sig_timestamp.'site='.$dm_sig_site.'partner_key='.$dm_sig_partner_key;

//Encrypt values

$dm_sig = hash_hmac('sha1', $dm_sig_string, $secret_key);

//Create SSO link

$sso_link = 'http://' . $editor_url.'/home/site/'.$dm_sig_site.'?dm_sig_partner_key='.$dm_sig_partner_key.'&dm_sig_timestamp='.$dm_sig_timestamp.'&dm_sig_user='.$dm_sig_user.'&dm_sig_site='.$dm_sig_site.'&dm_sig='.$dm_sig;

//return SSO link

return $sso_link;

}





public function grantAccountAccess($email,$siteName) {

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];	

	

	$apissoendpoint = $options['apissoendpoint'];	

	$apissokey = $options['apissokey'];	

	$apissosecretkey = $options['apissosecretkey'];	



	

	

//Set API user and password

define("API_USER",$duda_username);

define("API_PASS",$duda_password);		

	

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	//format URL to grant access to email and sitename passed

	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/grant-access/'.$email.'/sites/'.$siteName);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERPWD, API_USER.':'.API_PASS);

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

	//execute cURL call and get template data

	$output = curl_exec($ch);

	curl_close($ch);

	return true;

}





public function createSubAccount($emailToCreate) {

	$data = '{"account_name":"'.$emailToCreate.'"}';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/create');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERPWD, API_USER.':'.API_PASS);

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

	//execute cURL call and get template data

	$output = curl_exec($ch);

	//Check to see if response was successful, if not output error and exit

	if(curl_getinfo($ch,CURLINFO_HTTP_CODE) == 204) {

		curl_close($ch);

		return $emailToCreate;

	} else {

		curl_close($ch);

		return $emailToCreate;

	}

}

public function createSite($tempalte_id,$original_url, $email) {

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];	

	

	$apissoendpoint = $options['apissoendpoint'];	

	$apissokey = $options['apissokey'];	

	$apissosecretkey = $options['apissosecretkey'];	





	

//Set API user and password

define("API_USER",$duda_username);

define("API_PASS",$duda_password);	

	//create array with data

	if($original_url) {

		$data = array("template_id"=>$_GET['template_id'],"url"=>$original_url, "site_data" => array("external_uid" => $email));	

	} else {

		$data = array("template_id"=>$_GET['template_id'],"url"=>"", "site_data" => array("external_uid" => $email));	
	//	$json = json_decode($data, true);
		

	}

	//turn data into json to pass via cURL

	$data = json_encode($data);

	//Set cURL parameters

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/multiscreen/create');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERPWD, API_USER.':'.API_PASS);

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

	//execute cURL call and get template data

	$output = curl_exec($ch);

	//check for errors in cURL

	if(curl_errno($ch)) {

		die('Curl error: ' . curl_error($ch));

	}

	$output = json_decode($output);

	return $output->site_name;

}

public function cnc_dudapro_insert_leads($cnc_name ='n/a', $cnc_email='n/a', $cnc_url='n/a', $cnc_lead='dudaone' )
	{
	global $wpdb;

			
	$table_name = $wpdb->prefix . 'cnc_dudapro';

		
$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $cnc_name, 
			'email' => $_GET['email'],
			'url' => $_GET['original_url'], 
			'lead' => $cnc_lead
		) 
	);	
}


public function displayTemplates() {



	$options = get_option('dudapro_api_settings');



    $duda_username = $options['apiusername'];



	$duda_password = $options['apipassword'];



	$duda_debug = 0;


	$options2 = get_option('dudapro_multiscreen_display_settings');



	$cnc_buttontext = $options2['mutlibutton'];



	$multiscreencustomtext = $options2['multiEmailMessage'];

	
$d1Output .= '<script type="text/javascript">
jQuery(document).ready(function( $ ) {

$(\'input.prefix\').focus(function(){

   var prefix = \'http://\';

    if(!(this.value.match(\'^http://\'))){

         this.value = prefix;                

    }        

});



$(\'input.prefix\').blur(function(){

    var prefix = \'http://\';

    if(!(this.value.match(\'^http://\'))){

         this.value = prefix;                

    }        

});











	/*



			 *  Simple image gallery. Uses default settings



			 */







			$(\'.fancybox\').fancybox();







			/*



			 *  Different effects



			 */







			// Change title type, overlay closing speed



			$(".fancybox-effects-a").fancybox({



				helpers: {



					title : {



						type : \'outside\'



					},



					overlay : {



						speedOut : 0



					}



				}



			});







			// Disable opening and closing animations, change title type



			$(".fancybox-effects-b").fancybox({



				openEffect  : \'none\',



				closeEffect	: \'none\',







				helpers : {



					title : {



						type : \'over\'



					}



				}



			});







			// Set custom style, close if clicked, change title type and overlay color



			$(".fancybox-effects-c").fancybox({



				wrapCSS    : \'fancybox-custom\',



				closeClick : true,







				openEffect : \'none\',







				helpers : {



					title : {



						type : \'inside\'



					},



					overlay : {



						css : {



							\'background\' : \'rgba(238,238,238,0.85)\'



						}



					}



				}



			});







			// Remove padding, set opening and closing animations, close if clicked and disable overlay



			$(".fancybox-effects-d").fancybox({



				padding: 0,







				openEffect : \'none\',



				openSpeed  : 150,







				closeEffect : \'none\',



				closeSpeed  : 150,







				closeClick : true,







				helpers : {



					overlay : null







				}



			});







			/*



			 *  Button helper. Disable animations, hide close button, change title type and content



			 */







			$(\'.fancybox-buttons\').fancybox({



				openEffect  : \'none\',



				closeEffect : \'none\',







				prevEffect : \'none\',



				nextEffect : \'none\',







				closeBtn  : false,







				helpers : {



					title : {



						type : \'inside\'



					},



					buttons	: {}



				},







				afterLoad : function() {



					this.title = \'Image \' + (this.index + 1) + \' of \' + this.group.length + (this.title ? \' - \' + this.title : \'\');



				}



			});











			/*



			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked



			 */







			$(\'.fancybox-thumbs\').fancybox({



				prevEffect : \'none\',



				nextEffect : \'none\',







				closeBtn  : false,



				arrows    : false,



				nextClick : true,







				helpers : {



					thumbs : {



						width  : 50,



						height : 50



					}



				}



			});







			/*



			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.



			*/




			$(\'.fancybox-media\')




				.attr(\'rel\', \'media-gallery\')



				.fancybox({



					openEffect : \'none\',



					closeEffect : \'none\',



					prevEffect : \'none\',



					nextEffect : \'none\',







					arrows : false,



					helpers : {



						media : {},



						buttons : {}



					}



				});







			/*



			 *  Open manually



			 */







			$("#fancybox-manual-a").click(function() {



				$.fancybox.open(\'1_b.jpg\');



			});







			$("#fancybox-manual-b").click(function() {



				$.fancybox.open({



					href : \'iframe.html\',



					type : \'iframe\',



					padding : 5



				});



			});







			$("#fancybox-manual-c").click(function() {



				$.fancybox.open([



					{



						href : \'1_b.jpg\',



						title : \'My title\'



					}, {



						href : \'2_b.jpg\',



						title : \'2nd title\'



					}, {



						href : \'3_b.jpg\'



					}



				], {



					helpers : {



						thumbs : {



							width: 75,



							height: 50



						}



					}



				});



			});



	/////////////



	



	$("#content3").hide();



    



     



        



        //simple validation at client\'s end



       



        var proceed = true;



        



		



		



		



        //everything looks good! proceed...



        if(proceed) 



        {
			if (typeof original_url === \'undefined\' || !original_url) {
			original_url = "";	
			}

if (typeof uiosafdata === \'undefined\' || !uiosafdata) {
			uiosafdata = "";	
			}

 if (typeof pasdfuiadata === \'undefined\' || !pasdfuiadata) {
			pasdfuiadata = "";	
			}

 	 if (typeof sso_key  === \'undefined\' || !sso_key ) {
			sso_key  = "";	
			}
	
	 	 if (typeof sso_secret  === \'undefined\' || !sso_secret ) {
			sso_secret  = "";	
			}		
			 
	 	 if (typeof endpoint  === \'undefined\' || !endpoint  ) {
			endpoint   = "";	
			}				
			
	 if (typeof template_id  === \'undefined\' || !template_id  ) {
			template_id   = "";	
			}	
			
				 if (typeof email  === \'undefined\' || !email  ) {
			email   = "";	
			}	

			$.ajax({                        



            type: "post", 



       		url: "' . plugins_url('/dudapro/') . 'includes/multiscreen/multiMagic.php",



	         data: "url=" + original_url + "&uiosaf=" + uiosafdata + "&pasdfuia="+ pasdfuiadata + "&sso_key=" + sso_key + "&sso_secret="+ sso_secret + "&endpoint="+ endpoint + "&template_id="+ template_id + "&email="+ email,



			dataType: "json",



			success:function(data) 



				{

			if ((navigator.userAgent.match(/(iPhone|iPod|BlackBerry|Android.*Mobile|BB10.*Mobile|webOS|Windows CE|IEMobile|Opera Mini|Opera Mobi|HTC|LG-|LGE|SAMSUNG|Samsung|SEC-SGH|Symbian|Nokia|PlayStation|PLAYSTATION|Nintendo DSi)/i)) ) {



				window.location.href=data.preview;



			}



			



			



			//    $("#content2").html(data.url);



				$("#content3").show();



			    $("#content3").attr("src",data.url);



				$(\'#loading\').hide(\'fast\');




					 



				},



        error: function(jqXHR, textStatus, errorThrown) 



        {



			$("#content3").hide(\'fast\');



			$("#content2").show();



			$(\'#loading\').hide(\'fast\');



			$("#content2").html(errorThrown);



			



		//	 console.log("success" , arguments); // see all the parameters!



		}



        



    });



        }



    });



    



    //reset previously set border colors and hide all message on .keyup()































    $("#website_form input").keyup(function() { 































        $("#website_form input").css(\'border-color\',\'\');  































        $("#result").slideUp();































   































    































});
































</script>';



    






	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];



	$cnc_mobileType = $options['mobileType'];



    $duda_username = $options['apiusername'];



	$duda_password = $options['apipassword'];


	$optionsdudapro_general_settings  = get_option('dudapro_general_settings');
	$dudaEmailrequirement = $optionsdudapro_general_settings['dudaEmailrequirement'];
	$dudaShowCategories = $optionsdudapro_general_settings['dudaShowCategories'];



	$dudaone_api_customtext = get_option('dudaone_api_customtext');







	



	



    //Set parameters to make cURL call to Duda



    $ch = curl_init();



    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);



    curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/multiscreen/templates');



    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);



    curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");



    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);



    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));



    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");



    //execute cURL call and get template data



    $output = curl_exec($ch);



	$output2 = curl_exec($ch);



    //check for errors in cURL



    if(curl_errno($ch)) {



        die('Curl error: ' . curl_error($ch));



    }



    $output = json_decode($output);



	



	$cncformurl = $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";







    curl_close($ch);



    //Loop through all templates and display all the available templates in a table



    $dudaCount = 0;



	$dudaDirections  =  $dudaone_api_customtext;



	

$category = $_GET['c'];




//	$d1Output .= $multiscreencustomtext;
if ($dudaShowCategories == "1")
{
    $d1Output .= '<div id="cnc_dudaone_wrapper">
	<div id="categoryWrapper">
		<a href="?c=all"  class="d1Cateogry '. ( $category =='all' ? 'categoryActive' : '') . '">Show All</a> 
		<a href="?c=popular" class="d1Cateogry '. ( $category =='popular' ? 'categoryActive' : '') . '">Popular</a> 
		<a href="?c=business" class="d1Cateogry '. ( $category =='business' ? 'categoryActive' : '') . '">Business</a> 
		<a href="?c=restaurant" class="d1Cateogry '. ( $category =='restaurant' ? 'categoryActive' : '') . '">Restaurant</a> 
		<a href="?c=portfolio" class="d1Cateogry '. ( $category =='portfolio' ? 'categoryActive' : '') . '">Portfolio</a> 
		<a href="?c=landingPage" class="d1Cateogry '. ( $category =='landingPage' ? 'categoryActive' : '') . '">Landing Page</a>
	</div> ' ;
}
    $d1Output .= '<div id="cnc_dudaone_main">';



    foreach($output as $template) {

 $hideThis = '';

if ($category  == 'popular')
{
	switch ($template->template_id) 
	{
	case '20063':
	case '20016':
	case '20007':
	case '20064':
	case '20022':
	case '20009':
	case '20001':
	case '20006':
	case '20018':
	case '20067':
	case '20066':
	case '20061':
	case '20060':
	case '20023':
	case '20059':
	case '20020':
	case '20011':
	case '20008':
	case '20005':
	case '20073':
	case '20004':
	case '20021':
	case '20012':
	   $hideThis = ' style="display:none;" ';
		break;
	}
}
else if ($category  == 'business')
{
	switch ($template->template_id) 
	{
	case '20001':
	case '20006':
	case '20005':
	case '20073':
	case '20004':
	case '20002':
	case '1000772':
	   $hideThis = ' style="display:none;" ';
		break;
	}
}
else if ($category  == 'restaurant')
{
	switch ($template->template_id) 
	{
	case '20064':
	case '20017':
	case '20067':
	case '20061':
	case '20060':
	case '20012':
		break;
	default:
	   $hideThis = ' style="display:none;" ';
		break;
	}
}
else if ($category  == 'portfolio')
{
	switch ($template->template_id) 
	{
	case '20063':
	case '20016':
	case '20007':
	case '20009':
	case '20022':
	case '20001':
	case '20006':
	case '20019':
	case '20066':
	case '20023':
	case '20059':
	case '20011':
	case '20073':
	case '20004':
		break;
	default:
	   $hideThis = ' style="display:none;" ';
		break;
	}
}
else if ($category  == 'landingPage')
{
	switch ($template->template_id) 
	{
	case '20078':
	case '20077':
	case '20075':
	case '20076':
	case '20074':
	case '20072':
		break;
	default:
	   $hideThis = ' style="display:none;" ';
		break;
	}
}

       

if ($template->template_id != '10s00772')
{
	$previewURL = $template->preview_url ;
}
else
{
	$previewURL2 = 'http://' . $apissoendpoint . $template->preview_url ;
	$previewURL= str_replace('site', 'preview', $previewURL2);
}
		


		
        $d1Output .= '<div class="cnc_dudaone_thumb"' . $hideThis . ' ><center><strong class="dudapro-prod-title">' . $template->template_name .  '</strong></center><div class="dudapro-show-image" >
		<img src="' . $template->thumbnail_url. '" width="250" >
		<a href="#box'. $template->template_id . '" class="fancybox dudabutton start" rel="gallery1">start</a>
    <a href="'. $previewURL  . '" target="_blank" class="dudabutton preview">preview</a>
</div><br/></div>';



		$dudaCount +=1;



     if ($dudaCount == $dudaone_api_columns)



		{



			$dudaCount =0;



	//		$d1Output .= '</tr>';



		}



		



		



    }



    $d1Output .= '</div></div>';



		$optionsFIX = get_option('dudapro_api_settings');







		$endpoint = $optionsFIX['apissoendpoint'];	



	$sso_key = $optionsFIX['apissokey'];	



	$sso_secret = $optionsFIX['apissosecretkey'];	







	



	    foreach($output as $template2) {







	$d1Output .= ' <div style="display:none;"><div id="box'. $template2->template_id . '" style="width:100%; overflow-x:hidden;" >';

	$d1Output .= '<span class="dudaPreviewLink"><a href=" ' .  $template2->preview_url . '" target="_blank" class="dudaPreviewLinkColor">Peview template</a></span>';
	$d1Output .= '<span class="dudaproTitle">'. $template2->template_name . '</span>';
	$d1Output .= '<center><img src="' . $template2->thumbnail_url. '" width="100%"></center>';
	$d1Output .= '<hr/><center><div class="dudaproBuild">Build your site using existing web content</div></center>';


		 $d1Output .= '<center><form method="GET" action=' . $cncformurl . '>';



        $d1Output .= '<input type="hidden" name="template_id" value=' . $template2->template_id . '>';

 $d1Output .= '<div class="clearDudaPro"></div><div class="dudaPro_row1">';

        $d1Output .= '<button class="cnc_dudaone_button cnc_dudapro_tile" type="submit">Start with URL</button>'; 




        $d1Output .= '<input class="cnc_dudaone_input_url cnc_dudapro_tile" type="url" name="original_url" placeholder="  Existing Site URL (optional)">';

if ($dudaEmailrequirement != 0){
        $d1Output .= '<input class="cnc_dudaone_input cnc_dudapro_tile" type="email" name="email" placeholder="  Your e-mail (required)" required>';
}
else
{
        $d1Output .= '<input type="hidden" name="email" value="email@notProvided.com">';
	
}


        $d1Output .= '</div>'; 

        $d1Output .= '<div class="clearDudaPro"></div><div class="dudaPro_row2"><div class="cnc_dudapro_lbl" ><span class="cnc_dudapro_lbl2">Just start with this template</span></div>'; 
        $d1Output .= '<button class="cnc_dudaone_button cnc_dudapro_d1_button cnc_dudapro_tile" type="submit">Start with template</button></div>'; 


		$d1Output .= '<input name="uiosaf" type="hidden" value="'. $duda_username .'" /> ';



		$d1Output .= '<input name="pasdfuia" type="hidden" value="' . $duda_password .'" />';



		$d1Output .= '<input name="sso_key" type="hidden" value="'. $sso_key .'" /> ';



		$d1Output .= '<input name="sso_secret" type="hidden" value="' . $sso_secret .'" />';



		$d1Output .= '<input name="endpoint" type="hidden" value="' . $endpoint . '" /> ';



		







        $d1Output .= '</form></center>';



		$d1Output .= '</div></div>';



		}



	
return $d1Output; 


}








		

		

		

 

public function license_check()

{

		$options = get_option('dudapro_license_settings');

	$licensekey = $options['license_key'];

	$localkey = '9tjIxIzNwgDMwIjI6gjOztjIlRXYkt2Ylh2YioTO6M3OicmbpNnblNWasx1cyVmdyV2ccNXZsVHZv1GX

zNWbodHXlNmc192czNWbodHXzN2bkRHacBFUNFEWcNHduVWb1N2bExFd0FWTcNnclNXVcpzQioDM4ozc

7ISey9GdjVmcpRGZpxWY2JiO0EjOztjIx4CMuAjL3ITMioTO6M3OiAXaklGbhZnI6cjOztjI0N3boxWY

j9Gbuc3d3xCdz9GasF2YvxmI6MjM6M3Oi4Wah12bkRWasFmdioTMxozc7ISeshGdu9WTiozN6M3OiUGb

jl3Yn5WasxWaiJiOyEjOztjI3ATL4ATL4ADMyIiOwEjOztjIlRXYkVWdkRHel5mI6ETM6M3OicDMtcDM

tgDMwIjI6ATM6M3OiUGdhR2ZlJnI6cjOztjIlNXYlxEI5xGa052bNByUD1ESXJiO5EjOztjIl1WYuR3Y

1R2byBnI6ETM6M3OicjI6EjOztjIklGdjVHZvJHcioTO6M3Oi02bj5ycj1Ga3BEd0FWbioDNxozc7ICb

pFWblJiO1ozc7IyUD1ESXBCd0FWTioDMxozc7ISZtFmbkVmclR3cpdWZyJiO0EjOztjIlZXa0NWQiojN

6M3OiMXd0FGdzJiO2ozc7pjMxoTY8baca0885830a33725148e94e693f3f073294c0558d38e31f844

c5e399e3c16a';





# The call below actually performs the license check. You need to pass in the license key and the local key data

$results = $this->check_license($licensekey,$localkey);



# For Debugging, Echo Results

//echo "<textarea cols=100 rows=20>"; print_r($results); echo "</textarea>";

$licenseMessage = '';

if ($results["status"]=="Active") {

				$licenseMessage =  'Active';



		if ($results["localkey"]) {

			# Save Updated Local Key to DB or File

			$localkeydata = $results["localkey"];

	//		echo 'License is active';

		}

	} elseif ($results["status"]=="Invalid") {

		$licenseMessage =  'Invalid';

	} elseif ($results["status"]=="Expired") {

		$licenseMessage =  'Expired';

	} elseif ($results["status"]=="Suspended") {

	   $licenseMessage =  'Suspended';

	}

	return $licenseMessage;

	

	//echo cnc_dudapro_get_preview_url('kevinchamplin');



	

}

	

public function check_license($licensekey,$localkey="") {

    $whmcsurl = "http://cncwebsolutions.com/clients/";

    $licensing_secret_key = "cnckeykevin"; # Unique value, should match what is set in the product configuration for MD5 Hash Verification

    $check_token = time().md5(mt_rand(1000000000,9999999999).$licensekey);

    $checkdate = date("Ymd"); # Current date

    $usersip = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];

    $localkeydays = 15; # How long the local key is valid for in between remote checks

    $allowcheckfaildays = 5; # How many days to allow after local key expiry before blocking access if connection cannot be made

    $localkeyvalid = false;

    if ($localkey) {

        $localkey = str_replace("\n",'',$localkey); # Remove the line breaks

		$localdata = substr($localkey,0,strlen($localkey)-32); # Extract License Data

		$md5hash = substr($localkey,strlen($localkey)-32); # Extract MD5 Hash

        if ($md5hash==md5($localdata.$licensing_secret_key)) {

            $localdata = strrev($localdata); # Reverse the string

    		$md5hash = substr($localdata,0,32); # Extract MD5 Hash

    		$localdata = substr($localdata,32); # Extract License Data

    		$localdata = base64_decode($localdata);

    		$localkeyresults = unserialize($localdata);

            $originalcheckdate = $localkeyresults["checkdate"];

            if ($md5hash==md5($originalcheckdate.$licensing_secret_key)) {

                $localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-$localkeydays,date("Y")));

                if ($originalcheckdate>$localexpiry) {

                    $localkeyvalid = true;

                    $results = $localkeyresults;

                    $validdomains = explode(",",$results["validdomain"]);

                    if (!in_array($_SERVER['SERVER_NAME'], $validdomains)) {

                        $localkeyvalid = false;

                        $localkeyresults["status"] = "Invalid";

                        $results = array();

                    }

                    $validips = explode(",",$results["validip"]);

                    if (!in_array($usersip, $validips)) {

                        $localkeyvalid = false;

                        $localkeyresults["status"] = "Invalid";

                        $results = array();

                    }

                    if ($results["validdirectory"]!=dirname(__FILE__)) {

                        $localkeyvalid = false;

                        $localkeyresults["status"] = "Invalid";

                        $results = array();

                    }

                }

            }

        }

    }

    if (!$localkeyvalid) {

        $postfields["licensekey"] = $licensekey;

        $postfields["domain"] = $_SERVER['SERVER_NAME'];

        $postfields["ip"] = $usersip;

        $postfields["dir"] = dirname(__FILE__);

        if ($check_token) $postfields["check_token"] = $check_token;

        if (function_exists("curl_exec")) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $whmcsurl."modules/servers/licensing/verify.php");

            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $data = curl_exec($ch);

            curl_close($ch);

        } else {

            $fp = fsockopen($whmcsurl, 80, $errno, $errstr, 5);

	        if ($fp) {

        		$querystring = "";

                foreach ($postfields AS $k=>$v) {

                    $querystring .= "$k=".urlencode($v)."&";

                }

                $header="POST ".$whmcsurl."modules/servers/licensing/verify.php HTTP/1.0\r\n";

        		$header.="Host: ".$whmcsurl."\r\n";

        		$header.="Content-type: application/x-www-form-urlencoded\r\n";

        		$header.="Content-length: ".@strlen($querystring)."\r\n";

        		$header.="Connection: close\r\n\r\n";

        		$header.=$querystring;

        		$data="";

        		@stream_set_timeout($fp, 20);

        		@fputs($fp, $header);

        		$status = @socket_get_status($fp);

        		while (!@feof($fp)&&$status) {

        		    $data .= @fgets($fp, 1024);

        			$status = @socket_get_status($fp);

        		}

        		@fclose ($fp);

            }

        }

        if (!$data) {

            $localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-($localkeydays+$allowcheckfaildays),date("Y")));

            if ($originalcheckdate>$localexpiry) {

                $results = $localkeyresults;

            } else {

                $results["status"] = "Invalid";

                $results["description"] = "Remote Check Failed";

                return $results;

            }

        } else {

            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);

            $results = array();

            foreach ($matches[1] AS $k=>$v) {

                $results[$v] = $matches[2][$k];

            }

        }

        if ($results["md5hash"]) {

            if ($results["md5hash"]!=md5($licensing_secret_key.$check_token)) {

                $results["status"] = "Invalid";

                $results["description"] = "MD5 Checksum Verification Failed";

                return $results;

            }

        }

        if ($results["status"]=="Active") {

            $results["checkdate"] = $checkdate;

            $data_encoded = serialize($results);

            $data_encoded = base64_encode($data_encoded);

            $data_encoded = md5($checkdate.$licensing_secret_key).$data_encoded;

            $data_encoded = strrev($data_encoded);

            $data_encoded = $data_encoded.md5($data_encoded.$licensing_secret_key);

            $data_encoded = wordwrap($data_encoded,80,"\n",true);

            $results["localkey"] = $data_encoded;

        }

        $results["remotecheck"] = true;

    }

    unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);

    return $results;

}



// End Check Function

 	public function show_paypal($sitename)

	{
		
	if ($_GET['t'] == 'd1' || empty($_GET['originalurl']))
		{
		
	
		$options = get_option('dudapro_multiscreen_display_settings');

		$dudaproductname1 = $options['multiproductname1'];

		$mobilemonthly = $options['multimonthly'];

		$mobilesetup = $options['multisetup'];
		
		$mobileannually = $options['multiannually'];

		}
		
		else
		{

	$options = get_option('dudapro_api_display_settings');

		$dudaproductname1 = $options['dudaproductname1'];

		$mobilemonthly = $options['mobilemonthly'];

		$mobilesetup = $options['mobilesetup'];
		
		$mobileannually = $options['mobileannually'];
		
		}
		

		

		

		

		$options = get_option('paypal');

		$paypalusername = $options['paypalusername'];

		$paypalpassword = $options['paypalpassword'];

		$paypalsignature = $options['paypalsignature'];

		$paypalprocesspage = $options['processpage'];

		$paypalcancelpage = $options['cancelpage'];

		$paypalpaypalbusiness = $options['paypalbusiness'];
		$payPalTest = $options['payPalTest'];
		

		$options2 = get_option('dudapro_api_display_settings');

		$mobileType = $options2['mobileType'];

		$mobileButtonText = $options2['mobilebutton'];

		$mobilecustomtext = $options2['mobilecustomtext'];




	

		$PayPalMode         = 'sandbox'; // sandbox or live

		$PayPalApiUsername  = $paypalusername; //PayPal API Username

		$PayPalApiPassword  = $paypalpassword ; //Paypal API password

		$PayPalApiSignature     = $paypalsignature; //Paypal API Signature

		$PayPalCurrencyCode     = 'USD'; //Paypal Currency Code

		$PayPalReturnURL    = $paypalprocesspage; //Point to process.php page

		$PayPalCancelURL    = $paypalcancelpage; //yoursite.com/paypal/cancel_url.php'; //Cancel URL if user clicks cancel





if(!empty($PayPalApiUsername))

{
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if ($payPalTest == 0) 
{
	$paypalaction = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
}
else
{
	$paypalaction = 'https://www.paypal.com/cgi-bin/webscr';
}
$totalSetup = $mobilemonthly + $mobilesetup;
$paypalForm = '
<div id="paypalexpress">
<form action="' . $paypalaction  . '" method="post">
    <input type="hidden" name="business" value="'. $paypalpaypalbusiness .'">
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <input type="hidden" name="item_name" value="'. $dudaproductname1 .' for '. $sitename . '">
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="a1" value="' .$totalSetup .'">
    <input type="hidden" name="p1" value="1">
    <input type="hidden" name="t1" value="M">
    
	<!-- Set the terms of the regular subscription. -->
    <input type="hidden" name="a3" value="'. $mobilemonthly .'">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">


    <!-- Set recurring payments until canceled. -->

    <input type="hidden" name="src" value="1">
    <input type="hidden" name="return" value="'. $actual_link .'&diap=1">


	<input type="hidden" value="2" name="rm">   



    <!-- Display the payment button. -->

   
	
	<input type="submit" value="Pay Monthly" name="submit" title="PayPal - The safer, easier way to pay online!" style="float:left;" class="paypal_btn">


</form>';



return $paypalForm ;



}
else { return 'paypay not setup, please set this up in the admin area'; }

		

		

	}

	

public function show_stripe(){

		$options = get_option('stripe');

		$stripetestpublishablekey =  $options['stripetestpublishablekey'];

		$stripelivepublishablekey =  $options['stripelivepublishablekey'];

		

		$stripelivesecretkey =  $options['stripelivesecretkey'];

		

		$stripeType =  $options['stripeType'];



			

		$dudaPaymentOptions = get_option('dudapro_general_settings');
		$dudaPayments = $dudaPaymentOptions['dudaPayments'];
		
		$options2 = get_option('dudapro_api_display_settings');
		$apissoendpoint = $options2['apissoendpoint'];



	if ($_GET['t'] == 'd1' || empty($_GET['originalurl']))
		{
		
		$options3 = get_option('dudapro_multiscreen_display_settings');
		$dudaproductname1 = $options3['multiproductname1'];
		$mobilemonthly = $options3['multimonthly'] *100;
		$mobilesetup = $options3['multisetup'] *100;		
		$total = $mobilemonthly + $mobilesetup;

		
		}
		
		else
		{

		$options3 = get_option('dudapro_api_display_settings');
		$dudaproductname1 = $options3['dudaproductname1'];
		$mobilemonthly = $options3['mobilemonthly'] *100;
		$mobilesetup = $options3['mobilesetup'] *100;		
		$total = $mobilemonthly + $mobilesetup;
		}








		$stripeform = '';

	

		if ($dudaPayments == 2) {





	if ($stripeType ==1) {	

	$stripepkey = $stripelivepublishablekey;

	}

	else

	{

	$stripepkey = $stripetestpublishablekey;

	}





//	require_once(plugins_url('cnc-dudamobile-reseller-preview') .'includes/Stripe.php');

//Stripe::setApiKey("sk_test_4R4oVDP3hxFbo3DGbQM6zahR");



 



$stripeform = '<div id="stripeform" style="text-align:left;"><form action="" method="POST">

  <script

    src="https://checkout.stripe.com/checkout.js" class="stripe-button"

    data-key="'. $stripepkey . '"

    data-amount="'. $total  . '"

    data-name="'. $dudaproductname1 . '"

    data-description="($'. ($mobilemonthly/100) . '/month + $'. ($mobilesetup/100) . ' setup)"

    data-image="'. plugins_url('cnc-dudamobile-reseller-preview') .'/images/mobile-product.png">

  </script>

  <input type="hidden" name="topic_id" value="321"> 

  <input type="hidden" name="item_name_stripe" value="'. $dudaproductname1 .'">



  <input type="hidden" name="clienturl" value=""> 

</form></div>';





$topic_id = isset($_POST['topic_id']) ? $_POST['topic_id'] : '';

// then

if($topic_id == '321'){
	
	$publish_site = $this->publish_dudamobile($_GET['sitename']);
	
	$sso_link = $this->generateSSOLink($_GET['sitename'],$_GET['accountname']);

	$stripeform = '<center>Thank you for your payment, <a href="' .  $sso_link  . '">click here to edit your site</a>.</center>';

$this->dudapro_stripe_create_plan();

$this->dudapro_stripe_charge();

}else{

//something like add

}





		}



return $stripeform;

	

}



public function dudapro_stripe_charge(){

		$options = get_option('stripe');

		$stripetestpublishablekey =  $options['stripetestpublishablekey'];

		$stripetestsecretkey =  $options['stripetestsecretkey'];



		$stripelivesecretkey =  $options['stripelivesecretkey'];

		$stripelivepublishablekey =  $options['stripelivepublishablekey'];

		

		

		

		$stripeType =  $options['stripeType'];

		

		

		$options2 = get_option('dudapro_api_display_settings');

		$dudaproductname1 = $options2['dudaproductname1'];

		$mobilemonthly = $options2['mobilemonthly'] * 100;

		$mobilesetup = $options2['mobilesetup'] * 100;		

		

		

		// Set your secret key: remember to change this to your live secret key in production

	// See your keys here https://dashboard.stripe.com/acco

	

	if ($stripeType ==1) {	

	Stripe::setApiKey($stripelivesecretkey);	

	}

	else

	{

	Stripe::setApiKey($stripetestsecretkey);	

	}

	

		

	

	

	// Get the credit card details submitted by the form

	$token = $_POST['stripeToken'];

	

				$customer = Stripe_Customer::create(array(

							'card' => $token,

							'plan' => 'mobile-site',

							'email' => strip_tags(trim($_POST['stripeEmail'])),

							'description' => $_POST['item_name_stripe']

							

						)

					);

		



				$amount = $mobilesetup; 

 

					$invoice_item = Stripe_InvoiceItem::create( array(

					    'customer'    => $customer->id , // the customer to apply the fee to

					    'amount'      => $amount, // amount in cents

					    'currency'    => 'usd',

					    'description' => 'One-time setup fee' // our fee description

					) );

 

					$invoice = Stripe_Invoice::create( array(

					    'customer'    => $customer->id, // the customer to apply the fee to

					) );

 

					$invoice->pay();

 		

		





		

		

		

	}






public function dudapro_stripe_create_plan(){

		$options = get_option('stripe');

		$stripetestpublishablekey =  $options['stripetestpublishablekey'];

		$stripetestsecretkey =  $options['stripetestsecretkey'];

		

		$stripelivesecretkey =  $options['stripelivesecretkey'];

		$stripelivepublishablekey =  $options['stripelivepublishablekey'];

		

		

		

		$stripeType =  $options['stripeType'];

		

		
	if ($_GET['t'] == 'd1' || empty($_GET['originalurl']))
		{
		$options2 = get_option('dudapro_multiscreen_display_settings');

		$dudaproductname1 = $options2['multiproductname1'];

		$mobilemonthly = $options2['multimonthly'] * 100;

		$mobilesetup = $options2['multisetup'] * 100;	
		}
		else
		{
		$options2 = get_option('dudapro_api_display_settings');

		$dudaproductname1 = $options2['dudaproductname1'];

		$mobilemonthly = $options2['mobilemonthly'] * 100;

		$mobilesetup = $options2['mobilesetup'] * 100;	
		}
		

		





		

			

			if ($stripeType ==1) {	

				Stripe::setApiKey($stripelivesecretkey);	

				}

				else

				{

				Stripe::setApiKey($stripetestsecretkey);	

				}

			

	

	

	$body = Stripe_Plan::all();

//echo $body . '<br/><br/>';



//echo $body['data'][0]->id;



$dataArray = $body['data'];

$result = "";

$plan_exists = 0;

foreach($dataArray as $item){

  

  if ($plan_exists != 1) {

	  if ($item->id == 'mobile-site')

		{

			$plan_exists =1;

		}

		

  }

}

				if ($plan_exists ==0) {

	

			

					Stripe_Plan::create(array(

					  "amount" => $mobilemonthly,

					  "interval" => "month",

					  "name" => $dudaproductname1,

					  "currency" => "usd",

					  "id" => "mobile-site")

					);

				

				}

				

			

		}

}

?>