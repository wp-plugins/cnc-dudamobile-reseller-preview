<?php



/**



 * Plugin Name.



 *



 * @package   DudaPro_Admin



 * @author    Your Name <email@example.com>



 * @license   GPL-2.0+



 * @link      http://example.com



 * @copyright 2014 Your Name or Company Name



 */







/**



 * Plugin class. This class should ideally be used to work with the



 * administrative side of the WordPress site.



 *



 * If you're interested in introducing public-facing



 * functionality, then refer to `class-dudapro-name.php`



 *



 * @TODO: Rename this class to a proper name for your plugin.



 *



 * @package DudaPro_Admin



 * @author  Your Name <email@example.com>



 */



 



 	require_once( plugin_dir_path( __FILE__ ) . 'includes/wm-settings/wm-settings.php' );









	include_once(ABSPATH . WPINC . '/rss.php');

	

	

class DudaPro_Admin {







	/**



	 * Instance of this class.



	 *



	 * @since    1.0.0



	 *



	 * @var      object



	 */



	protected static $instance = null;







	/**



	 * Slug of the plugin screen.



	 *



	 * @since    1.0.0



	 *



	 * @var      string



	 */



	protected $plugin_screen_hook_suffix = null;







	/**



	 * Initialize the plugin by loading admin scripts & styles and adding a



	 * settings page and menu.



	 *



	 * @since     1.0.0



	 */



	private function __construct() {







		/*



		 * @TODO :



		 *



		 * - Uncomment following lines if the admin class should only be available for super admins



		 */



		/* if( ! is_super_admin() ) {



			return;



		} */







		/*



		 * Call $plugin_slug from public plugin class.



		 *



		 * @TODO:



		 *



		 * - Rename "DudaPro" to the name of your initial plugin class



		 *



		 */



		$plugin = DudaPro::get_instance();



		$this->plugin_slug = $plugin->get_plugin_slug();







		// Load admin style sheet and JavaScript.





	



		// Add the options page and menu item.



		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );



		



		



		// Define the page



// A top level page



$my_top_page = create_settings_page(

  'dudapro',

  __( 'Settings' ),

  array(

   'parent'   => false,

    'title'    => __( 'Duda Pro' ),

	 'icon_url' => 'dashicons-admin-generic',

    'position' => '6.3',

  ),



 array(



    'dudapro_license_settings' => array(



      'title'       => __( 'News & Updates' ),



      'description' => __( $this->dudapro_welcome()),

	   )

	  ),

	  array(

    'tabs'        => true,

    'submit'      => __( 'Save' ),

    'reset'       => __( 'Reset (careful)' ),

    'description' => __( 'This page allows you to enter all the settings for your DudaPro plugin.' ),

    'updated'     => __( 'Settings have been saved!')

  )

);











//add_submenu_page( 'dudapro','My Custom Page', 'My Custom Page', 'manage_options', 'dudapro_dashboard');











$my_top_page->apply_settings( array(



  'dudapro_api_settings' => array(



    'title'  => __( 'API Settings' ),



	'description' => __( 'Enter your API settings exactly as they appear on the <a href="http://my.dudamobile.com/home/dashboard">Duda Dashboard</a>. Account->API->Access' ),



    'fields' => array( 



		'apiendpoint'   => array(



        'label'       => __( 'API Endpoint' ),



        'description' => __( 'API Endpoint URL' )



      ),



		'apiusername'   => array(



        'label'       => __( 'API Username' ),



        'description' => __( 'Your Duda API Username' )



     ),



	 'apipassword'   => array(



        'label'       => __( 'API Password' ),



        'description' => __( 'Your Duda API Password' )



      ),



	  'apissoendpoint'   => array(



        'label'       => __( 'SSO Endpoint' ),



        'description' => __( 'Your Custom Endpoint URL' )



      ),



	  'apissokey'   => array(



        'label'       => __( 'SSO key' ),



        'description' => __( 'Your Duda SSO Key' )



      ),



	  'apissosecretkey'   => array(



        'label'       => __( 'SSO secret key' ),



        'description' => __( 'Your Duda SSO Secret Key' )



      ),



	  



	  



	  



	  



	   )



  ),

  

   'dudapro_general_settings' => array(



		'title'  => __( 'General Options' ),



		'description' => __( 'General site options.' ),



'fields' => array( 



		 'dudaEmailFrom'   => array(



        'label'       => __( 'Your Business Name' ),



        'description' => __( 'This displays in the "From" email address' )

		



      ),

	  

	     'dudaEmail'   => array(



        'label'       => __( 'Email Address' ),



        'description' => __( 'Emails will be sent form this address' )



      	),

		

		

		

			'dudaD1ListPage'   => array(



        'label'       => __( 'Client Login Link' ),



        'description' => __( 'The full path to your client login page, where you put the multi-screen user list shortcode: <pre>[dudapro_client_login]</pre> Ex: http://mysite.com/my-web-sites   This will display a login for users to login and upon logging in they will see their mobile & d1 sites listed. Clicking on the links will take them to the editor for the that site. ' )



      	),


	

		

		



      

		

		)



      ),

	  

	  



  'dudapro_api_display_settings' => array(



		'title'  => __( 'Mobile Options' ),



		'description' => __( 'Mobile site options.' ),







		'fields' => array( 

		

		 



'dudaproductname1'   => array(



        'label'       => __( 'Mobile Product Name' ),



        'description' => __( 'What do you call your product/service?' )



      ),






	   'calltoaction'  => array(





        'label'       => __( 'Call to action text' ),



        'description' => __( 'This is the link that redirect the user to the mobile editor<br/><a href="https://www.youtube.com/watch?v=7dMTYFTTjwM" target="_blank">View video of this</a>'),



        'default'     => __( 'Like what you see? Purchase today and start editing your site!' )



      )	,

		



	  'mobilebutton'   => array(



        'label'       => __( 'Mobile Button Text' ),



        'description' => __( 'Text to Display on the mobile creation button' )



      ),



		'mobileEmailMessage'  => array(



        'type'        => 'textarea',



        'label'       => __( 'Preview Email Message' ),



        'description' => __( 'Email sent to the user to use the mobile edtior)' ),



        'attributes'  => array(



          'rows'  => 3



        ),



        'default'     => __( 'Ready to edit your mobile site?' )

		),



	 'mobileNewPage'   => array(



        'type'    => 'radio',



        'label'   => __( 'Display mobile preview on same page or mobile editor on a new page' ),



        'options' => array(



          '0'   => __( 'Same Page' ),



          '1'   => __( 'New Page' )



        



        )



		 



      ),

	  

	 



	  'mobileType'   => array(



        'type'    => 'radio',



        'label'   => __( 'Display mobile preview or desktop/mobile comparison' ),



        'options' => array(



          '0'   => __( 'Mobile Preview' ),



          '1'   => __( 'Desktop/Mobile Comparison' )



        



        ),

		

		        'default' => '1'





		 



      ),









      



	   )



  ),

  /////////////////////

  

  

  //////////////////

   'dudapro_multiscreen_display_settings' => array(



		'title'  => __( 'Multi-Screen Options' ),



		'description' => __( 'Payment options are not used yet for multiscreen sites, duda does not have a payment flow for this.  However CNC is exploring various options to allow payment.  If you have an idea please contact <a href="kevin@programminggenius.com">kevin@programminggenius.com</a>' ),







		'fields' => array( 



	'multiproductname1'   => array(



        'label'       => __( 'Multi-Screen Product Name' ),



        'description' => __( 'What do you call your product/service?' )



      ),





	  'mutlibutton'   => array(



        'label'       => __( 'Multi-Screen Button Text' ),



        'description' => __( 'Text to display on the multi-screen button' ),

		

		'default'     => __( 'Create responsive website' ),



      ),

		'multiEmailMessage'  => array(



        'type'        => 'textarea',



        'label'       => __( 'Multi-Screen Email Message' ),



        'description' => __( 'Email sent to the user to use the Multi-Screen edtior)' ),



        'attributes'  => array(



          'rows'  => 3



        ),



        'default'     => __( 'Ready to edit your Multi-Screen site?' )

		),



     



	   )



  ),


)



);





// Mobile Accounts

$my_sub_page = create_settings_page(



  'dudapro-mobile-accts',



  __( 'Mobile' ),



  array(



    'parent'   => 'dudapro',



    'title'    => __( 'Mobile Accounts' )



	



  ),



  array(




    'dudapro_license_settings' => array(



      'title'       => __( 'Duda Mobile Accounts' ),



      'description' => __( $this->show_mobile() ),



	    



	   )



	  )



  );



// Multiscreen Accounts

$my_sub_page = create_settings_page(



  'dudapro-multiscreen-accts',



  __( 'Multiscreen' ),



  array(



    'parent'   => 'dudapro',



    'title'    => __( 'Multiscreen Accounts' )



	



  ),



  array(



    'dudapro_license_settings' => array(



      'title'       => __( 'Duda One Accounts' ),



      'description' => __( $this->show_multiscreen() ),



	    



	   )



	  )



  );







$my_sub_page = create_settings_page(



  'dudapro-help',



  __( 'Help' ),



  array(



    'parent'   => 'dudapro',



    'title'    => __( 'Help' )



	



  ),



  array(



    'dudapro_help_settings' => array(



      'title'       => __( 'Help & FAQ' ),



      'description' => __( $this->dudapro_show_help()  ),



	    



	   )



	  )



  );



// Access the values



$my_value = get_setting( 'my_setting_id', 'my_option_name' );



		



		



		







		// Add an action link pointing to the options page.



		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );



		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );







		/*



		 * Define custom functionality.



		 *



		 * Read more about actions and filters:



		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters



		 */



		add_action( 'admin_init', array( $this, 'load_includes' ) );



		add_action( 'admin_init', array( 'DudaPro_Option', 'get_instance' ) );



//		add_filter( '@TODO', array( $this, 'filter_method_name' ) );







	}

	

	

public function dudapro_show_help()

{
	
	
	
$helpFeed .= "<h2><a href=\"http://programminggenius.com/dudapro-for-wordpress/\">Go Premium</a> and unlock more great features such as capturing leads and accepting payments from your clients!</h2> ";

$helpFeed .= "<h2>Quick Start</h2> ";

$helpFeed .= 'This plugin includes 3 shortcodes. These shortcodes are listed below:<br/>';

$helpFeed .= '

<strong>Mobile Page</strong> - Displays mobile preview and links to your branded editor: <code>[dudapro_mobile]</code>

<strong>Multi-Screen Page</strong> - Displays Duda One templates and allows sites to be created: <code>[dudapro_multisite]</code>

<strong>Client Login Page</strong>e - Displays the logged in users D1 sites and lins to the editor (automatically logs them into your editor: <code>[dudapro_client_login]</code>

<br/>';	

	

	



$helpFeed .= '<h2>Instructions</h2>

<ol>

<li>Go to the Duda Pro Settings page

<li>Enter your license key  on the<strong> Licence Key tab</strong> you received from CNC Web Solutions (it was emailed to you when you purchased the plugin)</li>

<li>Enter your Duda API Settings on the <strong>API Settings Tab. <span style="color: #ff0000;">All fields are required.</span></strong></li>

<li><span style="color: #000000;"><strong>Create 3 new WordPress pages</strong> and on each one place a shortcode on them, 1 page for each shortcode. (see shortcodes above)</span></li>

<li><strong>General Options</strong> tab enter all the fields, the Mobile List Page, Multi-Screen List Page and Payment Page willl contain the full urls from the pages you created in step 3.</li>

<li>Enter your Mobile Options on that tab</li>

<li>Enter your <strong>Multi-Screen Options</strong> on that tab</li>

<li>Configure Stripe or Paypal depending on which one you use (optional). Only required if you chose to Enable Payments on the General Options Tab.</li>

<li><strong>Go to Duda and configure your custom mobile payment page</strong> and text (<a href="https://www.youtube.com/watch?v=7dMTYFTTjwM" target="_blank">View video</a>).  **Important with this step your cannot receive payments or have users publish mobile site). On duda&#8217;s site click on Account API Access -&gt; Edit Publish Page (on bottom of page).  Then put in your order page on both tabs (mobile site &amp; responsive site). Check the option that says &#8220;Redirect without publish&#8221;. This way it will redirect to your site and it won&#8217;t publish the site and charge you until you get payment from your client.</li>

</ol>

</li>

</ol><br/>';





$helpFeed .= '<h2>Videos</h2>

<ul>

<li>Admin Quick Start – <a href="http://youtu.be/B2vfWgQDOrc" target="_blank">http://youtu.be/B2vfWgQDOrc</a></li>

<li>Mobile Tutorial – <a href="http://youtu.be/CTwJr7MvLI" target="_blank">http://youtu.be/CTwJr7MvLIc</a></li>

<li>Mobile Pay Flow – <a href="http://youtu.be/7dMTYFTTjwM" target="_blank">http://youtu.be/7dMTYFTTjwM</a></li>

</ul><br/>';



$helpFeed .= '<h2>Support and Feature Requests</h2>

If you need help or would like a quote for a customizatio please <a href="http://programminggenius.com/contact-us/" target="_blank">contact Kevin</a>.';







return $helpFeed;

}



public function dudapro_magicquotes_check()

{

	if (get_magic_quotes_gpc() ==1)

	{

		return 'on, this sometimes causes issues. If you get errors contact support and tell them Magic Quotes is on.';

	}

	else 

	{

		return 'off. This is a good thing as it causes conflicts with some plugins.'; 

	}



}



	

	public function cnc_dudapro_show_leads()

	{

		

global $wpdb;



/*	$res = $wpdb->get_results("SELECT email, url, lead, time as times FROM ". $wpdb->prefix . "cnc_dudapro");

	$tbl = '<table  border="1" cellpadding="3" cellspacing="0">';

				$tbl .= '<tr style="color:#FFF;background-color:#000;"><td>Email Address</td><td> URL </td><td> Product</td><td>  Date</tr>';



		foreach ($res as $rs) 

			{

				

			$tbl .= '<tr bgcolor="#FFF"><td>'.	$rs->email . '</td><td> ' . $rs->url . ' </td><td>  ' . $rs->lead . '</td><td>  ' . $rs->times . '</tr>';

			}

				$tbl .= '</table>';

*/



		require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

	// Gets the data

$id=isset($_POST['id']) ? $_POST['id'] : '';

$search=isset($_POST['search']) ? $_POST['search'] : '';

$multiple_search=isset($_POST['multiple_search']) ? $_POST['multiple_search'] : array();

$items_per_page=isset($_POST['items_per_page']) ? $_POST['items_per_page'] : '';

$sort=isset($_POST['sort']) ? $_POST['sort'] : '';

$page=isset($_POST['page']) ? $_POST['page'] : 1;

$total_items=(isset($_POST['total_items']) and $_POST['total_items']>=0) ? $_POST['total_items'] : '';

$extra_cols=isset($_POST['extra_cols']) ? $_POST['extra_cols'] : array();

$extra_vars=isset($_POST['extra_vars']) ? $_POST['extra_vars'] : array();



	

	require_once( plugin_dir_path( __FILE__ ) . 'creativeTable.php' );

$ct=new CreativeTable();



if($id=='' or $id=='ctLeads'){





$params = array(

    'id'                  => 'ctLeads',

    'sql_query'           => "SELECT id, email, url, lead, time FROM ". $wpdb->prefix . "cnc_dudapro",

    'search'              => $search,

    'multiple_search'     => $multiple_search,

    'items_per_page'      => $items_per_page,

    'sort'                => $sort,

    'page'                => $page,

    'total_items'         => $total_items,

    'header'              => 'id, email, url, lead, time',

    'width'               => '50,40,20,50,50,50',

    'items_per_page_init' => '10,25,50,100,250',

    'ajax_url'            => 'admin.php?page=dudapro-leads',

	'display_cols'        => 'tfttttt'

);







// ***********************************************************************************

// UNCOMMENT TO TEST THE DIFFERENTS OPTIONS AND SEE THE RESULTS AND TEST SOME YOURSELF



// extra columns - array(array(col,header,width,html),array(...),...) - default: array();

$arr_extra_cols[0] = array(1,'<input type="checkbox" id="ct_check_all" name="ct_check_all" onclick="checkAllLeads();" />','20','<input type="checkbox" id="ct_check" name="ct_check[]" value="#COL1#" onclick="check();" />');  // column, header, width, html

//$arr_extra_cols[1] = array(7,'Actions','45','<a href=""  ><img src="' . plugins_url('dudapro') . '/admin/images/icon-delete.gif" id="#COL1#"  class="del"/></a>');  // column, header, width, html

$params['extra_cols'] = $arr_extra_cols;



$arr_actions[0] = array('','-- Actions --');  // value, text

//$arr_actions[1] = array('publish','Publish');  // value, text

$arr_actions[1] = array('delete','Delete');  // value, text

$params['actions'] = $arr_actions;

 

$params['actions_url'] = 'ctActions(\'#ID#\')';  // javascript code triggered when actions change - default

//$params['actions_url'] = 'alert(\'#COL1#\')';  // javascript code triggered when actions change

// ***********************************************************************************





$ct->table($params);



// Insert a Pager into the table (I used this CreativePager Lite version because its very easy to use, but you may use any pager system that you like)

$ct->pager = getCreativePagerLite('ctLeads',$page,$ct->total_items,$ct->items_per_page);



// If its an ajax call

if(isset($_POST['ajax_option'])){

    echo json_encode($ct->display($_POST['ajax_option'],true));

	

    exit;

}else{

    $out=$ct->display();

}

}



$message .=  'Everytime a mobile or d1 site is created that information is saved.  It is then viewable on this page.' . $out;



return $message;

	}

	



	public function dudapro_dashboard(){



		echo 'test';



	}



	







	/**



	 * Return an instance of this class.



	 *



	 * @since     1.0.0



	 *



	 * @return    object    A single instance of this class.



	 */



	public static function get_instance() {

		/*



		 * @TODO :



		 *



		 * - Uncomment following lines if the admin class should only be available for super admins



		 */



		/* if( ! is_super_admin() ) {



			return;



		} */







		// If the single instance hasn't been set, set it now.



		if ( null == self::$instance ) {



			self::$instance = new self;



		}

		return self::$instance;

	}



public function dudapro_welcome() {

/*

	$rss = new DOMDocument();

	$rss->load('http://cncwebsolutions.com/category/dudapro-plugin/feed');

	$feed = array();

	foreach ($rss->getElementsByTagName('item') as $node) {

		$item = array ( 

			'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,

			'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,

			'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,

			'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,

			);

		array_push($feed, $item);

	}

	$limit = 5;

	

	for($x=0;$x<$limit;$x++) {

		$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);

		$link = $feed[$x]['link'];

		$description = $feed[$x]['desc'];

		$date = date('l F d, Y', strtotime($feed[$x]['date']));

		$rssfeed .= '<p><strong><a href="'.$link.'" title="'.$title.'">'.$title.'</a></strong><br />';

		$rssfeed .= '<p>'.$description.'</p>';

	}

*/

$rssfeed = "";

return $rssfeed;

}











// d1 details

public function d1_details($site)

{

return 'test';	

}





public function show_mobile()

{

global $wpdb;	

	

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];





  if(!empty($_REQUEST['dmid']))

   {

	   

	   $theSite = $_REQUEST['dmid'];

	   $stuff = $this->delete_dm($theSite);

	   return $stuff;

   }

   elseif (!empty($_REQUEST['dmids']))

    {

	   

	   $theSite = $_REQUEST['dmids'];

	   $stuff2 = $this->stats_dm($theSite);

	   return  $stuff2;

   }

    elseif (!empty($_REQUEST['dmidc']))

    {

	   

	   $theSite = $_REQUEST['dmidc'];

	   $stuff2 = $this->contact_dm($theSite);

	   return  $stuff2;

   }

   else

   {



$args = array(



		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/mobile/modified?from=2000-01-01&to='. date('Y-m-d'), $args ));



	



	$data = json_decode($json, true);



//print_r( $data);

$i=0;



if (!empty($duda_password))

{

$tbl = '

This page will be getting frequent updates to expand functionality.  BE CAREFUL, DELETING YOUR SITE IS NOT RECOVERABLE. Deleting your site will immediately and permanently delete the site and cancel any subscription associated with the site. Please note that after deleting a site there is no way to bring the site back. Deleting a site will also cancel any active subscription/payment associated with the site. <br/><br/> 

<table>';



$i=0;



if (is_array($data))


		{

	foreach ( $data as $sites ) {

	

	

				 $myarray[]=array($sites,'<img src="http://dp-cdn.multiscreensite.com/template-snapshot-prod/'. $sites .'.jpg?rand=1231S" style="width:100px;height:100px;">',$sites,'<a href="http://'. $apissoendpoint . '/site/'. $sites . '" target="new">preview link</a>','<a href="http://'. $apissoendpoint . '/compare/'. $sites . '" target="new">comparison link</a>','<a href="?page=dudapro-mobile-accts&dmidc='.$sites .'" target="new">view form</a>','<a href="?page=dudapro-mobile-accts&dmids='.$sites .'" target="new">view stats</a>');



	  

	}

	

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

	// Gets the data

$id=isset($_POST['id']) ? $_POST['id'] : '';

$search=isset($_POST['search']) ? $_POST['search'] : '';

$multiple_search=isset($_POST['multiple_search']) ? $_POST['multiple_search'] : array();

$items_per_page=isset($_POST['items_per_page']) ? $_POST['items_per_page'] : '';

$sort=isset($_POST['sort']) ? $_POST['sort'] : '';

$page=isset($_POST['page']) ? $_POST['page'] : 1;

$total_items=(isset($_POST['total_items']) and $_POST['total_items']>=0) ? $_POST['total_items'] : '';

$extra_cols=isset($_POST['extra_cols']) ? $_POST['extra_cols'] : array();

$extra_vars=isset($_POST['extra_vars']) ? $_POST['extra_vars'] : array();



	

	require_once( plugin_dir_path( __FILE__ ) . 'creativeTable.php' );

$ct=new CreativeTable();



if($id=='' or $id=='ctMobile'){



$params = array(

    'id'                  => 'ctMobile',

//	'search_init'		  => false,

    'data'                => $myarray,

    'search'              => $search,

    'multiple_search'     => $multiple_search,

    'items_per_page'      => $items_per_page,

	'sort_init'			  => false,

    'sort'                => $sort,

    'page'                => $page,

    'total_items'         => $total_items,

    'header'              => 'ID,Image,SiteName,PerviewLink,CompareLink,FormLink,StatsLink',

    'width'               => '0,10,105,50,100,50,50',

    'items_per_page_init' => '5,10,25,50,100,200',

    'ajax_url'            => 'admin.php?page=dudapro-mobile-accts',

	'display_cols'		      => 'tftttttt'

);



// ***********************************************************************************

// UNCOMMENT TO TEST THE DIFFERENTS OPTIONS AND SEE THE RESULTS AND TEST SOME YOURSELF



// extra columns - array(array(col,header,width,html),array(...),...) - default: array();

$arr_extra_cols[0] = array(1,'<input type="checkbox" id="ct_check_all" name="ct_check_all" onclick="checkAllMobile();" />','20','<input type="checkbox" id="ct_check" name="ct_check[]" value="#COL1#" onclick="check();" />');  // column, header, width, html





//$arr_extra_cols[1] = array(9,'Actions','45','<a href="javascript: funcEdit(\'#COL4#\');"><img src="' . plugins_url('dudapro') . '/admin/images/icon-edit.gif" /></a>  <a href=""  ><img src="' . plugins_url('dudapro') . '/admin/images/icon-delete.gif" id="#COL1#"  class="del"/></a>');  // column, header, width, html

$params['extra_cols'] = $arr_extra_cols;





$arr_actions[0] = array('','-- Actions --');  // value, text

//$arr_actions[1] = array('publish','Publish');  // value, text

$arr_actions[1] = array('delete','Delete');  // value, text

$params['actions'] = $arr_actions;

 

$params['actions_url'] = 'ctActions(\'#ID#\')';  // javascript code triggered when actions change - default

//$params['actions_url'] = 'alert(\'Actions changed\')';  // javascript code triggered when actions change

// ***********************************************************************************





$ct->table($params);



// Insert a Pager into the table (I used this CreativePager Lite version because its very easy to use, but you may use any pager system that you like)

$ct->pager = getCreativePagerLite('ctMobile',$page,$ct->total_items,$ct->items_per_page);



// If its an ajax call

if(isset($_POST['ajax_option'])){

    echo json_encode($ct->display($_POST['ajax_option'],true));

    exit;

}else{

    $out=$ct->display();

}



}

		}

return $out;  

	}

}

}

// show multiscreen accounts

public function show_multiscreen()

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'headers' => array(

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )

			)

		);

/// empty querystring	

  // param was set in the query string

   if(!empty($_REQUEST['d1id']))

   {

	   $theSite = $_REQUEST['d1id'];

	   $stuff = $this->delete_d1($theSite);

	   return $stuff;

   }

   elseif (!empty($_REQUEST['d1ids']))

    {

	   $theSite = $_REQUEST['d1ids'];

	   $stuff2 = $this->stats_d1($theSite);

	   return  $stuff2;

   }

    elseif (!empty($_REQUEST['d1idc']))

    {

	   $theSite = $_REQUEST['d1idc'];

	   $stuff2 = $this->contact_d1($theSite);

	   return  $stuff2;

   }

   else

   {

     $json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/multiscreen/created?from=2000-01-01&to='. date('Y-m-d'), $args ));

  	$data = json_decode($json, true);



$i=0;

if (is_array($data))
{
	foreach ( $data as $sites ) {

				 $myarray[]=array($sites,'<img src="http://dp-cdn.multiscreensite.com/template-snapshot-prod/'. $sites .'.jpg?rand=1231S" style="width:100px;height:100px;">',$sites,'<a href="http://'. $apissoendpoint . '/preview/'. $sites . '" target="new">preview link</a>','<a href="admin.php?page=dudapro-multiscreen-accts&d1idc='.$sites .'" target="new">view form</a>','<a href="admin.php?page=dudapro-multiscreen-accts&d1ids='.$sites .'" target="new">view stats</a>');

	}
}
	

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

	// Gets the data

$id=isset($_POST['id']) ? $_POST['id'] : '';

$search=isset($_POST['search']) ? $_POST['search'] : '';

$multiple_search=isset($_POST['multiple_search']) ? $_POST['multiple_search'] : array();

$items_per_page=isset($_POST['items_per_page']) ? $_POST['items_per_page'] : '';

$sort=isset($_POST['sort']) ? $_POST['sort'] : '';

$page=isset($_POST['page']) ? $_POST['page'] : 1;

$total_items=(isset($_POST['total_items']) and $_POST['total_items']>=0) ? $_POST['total_items'] : '';

$extra_cols=isset($_POST['extra_cols']) ? $_POST['extra_cols'] : array();

$extra_vars=isset($_POST['extra_vars']) ? $_POST['extra_vars'] : array();



	

	require_once( plugin_dir_path( __FILE__ ) . 'creativeTable.php' );

$ct=new CreativeTable();



if($id=='' or $id=='ctMulti'){



$params = array(

    'id'                  => 'ctMulti',

//	'search_init'		  => false,

    'data'                => $myarray,

    'search'              => $search,

	'sort_init'			  => false,

    'multiple_search'     => $multiple_search,

    'items_per_page'      => $items_per_page,

    'sort'                => $sort,

    'page'                => $page,

    'total_items'         => $total_items,

    'header'              => 'ID,Image,SiteName,PerviewLink,FormLink,StatsLink',

    'width'               => '0,10,105,100,50,50',

    'items_per_page_init' => '5,10,25,50,100,200',

    'ajax_url'            => 'admin.php?page=dudapro-mobile-accts',

	'display_cols'		      => 'tftttttt'

);



// ***********************************************************************************

// UNCOMMENT TO TEST THE DIFFERENTS OPTIONS AND SEE THE RESULTS AND TEST SOME YOURSELF



// extra columns - array(array(col,header,width,html),array(...),...) - default: array();

$arr_extra_cols[0] = array(1,'<input type="checkbox" id="ct_check_all" name="ct_check_all" onclick="checkAllMulti();" />','20','<input type="checkbox" id="ct_check" name="ct_check[]" value="#COL1#" onclick="check();" />');  // column, header, width, html





//$arr_extra_cols[1] = array(9,'Actions','45','<a href="javascript: funcEdit(\'#COL4#\');"><img src="' . plugins_url('dudapro') . '/admin/images/icon-edit.gif" /></a>  <a href=""  ><img src="' . plugins_url('dudapro') . '/admin/images/icon-delete.gif" id="#COL1#"  class="del"/></a>');  // column, header, width, html

$params['extra_cols'] = $arr_extra_cols;





$arr_actions[0] = array('','-- Actions --');  // value, text

//$arr_actions[1] = array('publish','Publish');  // value, text

$arr_actions[1] = array('delete','Delete');  // value, text

$params['actions'] = $arr_actions;

 

$params['actions_url'] = 'ctActions(\'#ID#\')';  // javascript code triggered when actions change - default

//$params['actions_url'] = 'alert(\'Actions changed\')';  // javascript code triggered when actions change

// ***********************************************************************************





$ct->table($params);



// Insert a Pager into the table (I used this CreativePager Lite version because its very easy to use, but you may use any pager system that you like)

$ct->pager = getCreativePagerLite('ctMulti',$page,$ct->total_items,$ct->items_per_page);



// If its an ajax call

if(isset($_POST['ajax_option'])){

    echo json_encode($ct->display($_POST['ajax_option'],true));

    exit;

}else{

    $out=$ct->display();

}



}



return $out;  



  

  

	}

	

}





public function contact_dm($site)

{

		$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'result' => 'traffic',

		'headers' => array(

		

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/mobile/get-forms/'. $site , $args ));



	



	$data = json_decode($json, true);



if (!$data['error_code'])

{

$i=0;

	if (empty($data))

		{

			$contactdata = 'Sorry, no form results found <br/>';

	

		}

		else

		{

if (is_array($data))
{
		foreach ( $data as $sites )

		{

		

			$contactdata  .= 'Form Name: ' . $sites['form_title'] . '<br/>'.	

			'Date: ' . $sites['date'] . '<br/>';

			

			foreach ($sites['message'] as $key => $val) {

				$f .= "$key = $val\n";}

			

			$contactdata .= $f ;

			

		}
}

	}

}

else

{

	

			$contactdata = 'Sorry, no form results found <br/>';

}



return $contactdata . '<br/><a href="?page=dudapro-mobile-accts">Back to accounts</a>';



}





public function stats_dm($site)

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'result' => 'traffic',

		'headers' => array(

		

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/analytics/site/'. $site , $args ));



	



	$data = json_decode($json, true);



$i=0;


if (is_array($data))
{
foreach ( $data as $sites )

{

$dmstats = $sites->value;	

}
}

$display =  '<h2>Stats for '. $site . '</h2>' .

'Visits: ' . $data['VISITS'] . '<br/>' .

'Visitors: ' . $data['VISITORS'] . '<br/>' .

'Page Views: ' . $data['PAGE_VIEWS'] . '<br/><br/>'.

'<a href="?page=dudapro-mobile-accts">Back to accounts</a>';





return $display;



}



// delete duda mobile site

public function delete_dm($site)

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'method' => 'delete',

		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/mobile/'. $site , $args ));



	



	$data = json_decode($json, true);



$i=0;





return 'Mobile site '.  $site . ' was deleted, <a href="?page=dudapro-mobile-accts">go back to accounts</a>';

	

}





// delete multiscreen site

public function delete_d1($site)

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'method' => 'delete',

		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/multiscreen/'. $site , $args ));



	



	$data = json_decode($json, true);



$i=0;





return $site . ' was deleted, <a href="?page=dudapro-multiscreen-accts">go back to accounts</a>';

	

}



public function stats_d1($site)

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'result' => 'traffic',

		'headers' => array(

		

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/analytics/site/'. $site , $args ));



	



	$data = json_decode($json, true);



$i=0;


if (is_array($data))
{
foreach ( $data as $sites )

{

$dmstats = $sites->value;	

}
}

$display =  '<h2>Stats for '. $site . '</h2>' .

'Visits: ' . $data['VISITS'] . '<br/>' .

'Visitors: ' . $data['VISITORS'] . '<br/>' .

'Page Views: ' . $data['PAGE_VIEWS'] . '<br/><br/>'.

'<a href="?page=dudapro-multiscreen-accts">Back to accounts</a>';





return $display;



}



public function contact_d1($site)

{

		$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

		'result' => 'traffic',

		'headers' => array(

		

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/multiscreen/get-forms/'. $site , $args ));



	



	$data = json_decode($json, true);



$i=0;

if (empty($data))

	{

		$contactdata = 'no  form results found <br/>';



	}

	else

	{

if (is_array($data))
{
	foreach ( $data as $sites )

	{

	

		$contactdata  .= 'Form Name: ' . $sites['form_title'] . '<br/>'.	

		'Date: ' . $sites['date'] . '<br/>';

		

		foreach ($sites['message'] as $key => $val) {

			$f .= "$key = $val\n";}

		

		$contactdata .= $f ;

		

	}
}

}





return $contactdata . '<br/><a href="?page=dudapro-multiscreen-accts">Back to accounts</a>';



}





// get account owner



public function get_account()

{

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(

	'headers' => array(

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )

			)

		);

	



	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/analytics/site/'. $siteid ));



	



	$data = json_decode($json, true);



$i=0;





	return $data['visits'];	







	

}





// create stats function



public function create_stats($siteid)

{

		$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];



$args = array(



		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/analytics/site/'. $siteid ));



	



	$data = json_decode($json, true);



$i=0;





	return $data['visits'];	







	

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



	return  $licenseMessage;



	



	//echo cnc_dudapro_get_preview_url('kevinchamplin');







	



}











	/**



	 * Register and enqueue admin-specific style sheet.



	 *



	 * @TODO:



	 *



	 * - Rename "DudaPro" to the name your plugin



	 *



	 * @since     1.0.0



	 *



	 * @return    null    Return early if no settings page is registered.



	 */



	public function admin_styles() {



	echo 'test';exit();



		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {



			return;



		}







		$screen = get_current_screen();



		if ( $this->plugin_screen_hook_suffix == $screen->id ) {



			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), DudaPro::VERSION );



		}



			wp_enqueue_style( $this->plugin_slug .'-admin-jquery-ui-css', '//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css', array(), DudaPro::VERSION );



		wp_register_style( 'custom_wp_admin_css', plugin_dir_url(__FILE__) . 'styles/css/creative.css', false, '1.0.0' );

        wp_enqueue_style( 'custom_wp_admin_css' );





	



			



		$duda_admin = new self();



		







	}







	/**



	 * Register and enqueue admin-specific JavaScript.



	 *



	 * @TODO:



	 *



	 * - Rename "DudaPro" to the name your plugin



	 *



	 * @since     1.0.0



	 *



	 * @return    null    Return early if no settings page is registered.



	 */



	public function enqueue_admin_scripts() {







		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {



			return;



		}





	$screen	 = get_current_screen();

//echo $screen->id ;



		if ( "duda-pro_page_dudapro-leads" == $screen->id || "duda-pro_page_dudapro-mobile-accts" == $screen->id || "duda-pro_page_dudapro-multiscreen-accts" == $screen->id)



			{

				wp_enqueue_script( 'dudapro_tbl', plugin_dir_url( __FILE__ ) . 'js/creative_table_ajax-1.4.js' );

				wp_enqueue_script( 'dudapro_jquery', plugin_dir_url( __FILE__ ) . 'js/jquery-1.8.2.min.js' );

				wp_enqueue_script( 'dudapro_custom_js', plugin_dir_url( __FILE__ ) . 'js/my_javascript.js' );



				wp_register_style( 'dudapro_admin_css', plugin_dir_url( __FILE__ ) . 'styles/creative/style.css', false, '1.0.0' );

				wp_enqueue_style( 'dudapro_admin_css' );

				



				}

				

//echo $screen->id;



//    

			



	





 //wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'admin/creative_table_ajax-1.4.js' );



 

    		



		



		



		



		



	//	wp_enqueue_script(  $this->plugin_slug .'-admin-jquery-jquery', '//code.jquery.com/jquery-1.10.2.js', array(), '1.0.0', false );		



	//	wp_enqueue_script(  $this->plugin_slug .'-admin-jquery-ui-js', '//code.jquery.com/ui/1.11.0/jquery-ui.js', array(), '1.0.0', false);		



	}







	/**



	 * Register the administration menu for this plugin into the WordPress Dashboard menu.



	 *



	 * @since    1.0.0



	 */



	public function add_plugin_admin_menu() {

		add_action( 'wp_enqueue_style', array( $this, 'admin_styles' ) );



		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );







		/*



		 * Add a settings page for this plugin to the Settings menu.



		 *



		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.



		 *



		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus



		 *



		 * @TODO:



		 *



		 * - Change 'Page Title' to the title of your plugin admin page



		 * - Change 'Menu Text' to the text for menu item for the plugin settings page



		 * - Change 'manage_options' to the capability you see fit



		 *   For reference: http://codex.wordpress.org/Roles_and_Capabilities



		 */



		$this->plugin_screen_hook_suffix = add_options_page(



			__( 'DudaPro Settings', $this->plugin_slug ),



			__( 'DudaPro', $this->plugin_slug ),



			'manage_options',



			$this->plugin_slug,



			array( $this, 'display_plugin_admin_page' )



		);







	}



	



	 /**



   * This function loads files in the admin/includes directory



   * 



   * @since 1.0.0



   */



  public function load_includes() {



    require_once( 'includes/class-dudapro-option.php' );







  }















function dudapro_admin_init() {



}



















	/**



	 * Render the settings page for this plugin.



	 *



	 * @since    1.0.0



	 */











	/**



	 * Add settings action link to the plugins page.



	 *



	 * @since    1.0.0



	 */



	public function add_action_links( $links ) {







		return array_merge(



			array(



				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'



			),



			$links



		);







	}







	/**



	 * NOTE:     Actions are points in the execution of a page or process



	 *           lifecycle that WordPress fires.



	 *



	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions



	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference



	 *



	 * @since    1.0.0



	 */



	public function action_method_name() {



		// @TODO: Define your action hook callback here



	}







	/**



	 * NOTE:     Filters are points of execution in which WordPress modifies data



	 *           before saving it or sending it to the browser.



	 *



	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters



	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference



	 *



	 * @since    1.0.0



	 */



	public function filter_method_name() {



		// @TODO: Define your filter hook callback here



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





public function cnc_dudapro_show_permissions()

{

	

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];

	global $display_name , $user_email;

    get_currentuserinfo();



	

	$args = array(



		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/permission-groups/default', $args ));



	



	$data = json_decode($json, true);

	$error = '';



if (is_array($data))

		{

	foreach ( $data as $group ) {

//	Array ( [0] => Array ( [group_name] => administrator [color] => rgb(253,113,34) [title] => Admin [permissions] => Array ( [0] => EDIT_SITES [1] => CREATE_SITES [2] => DELETE_SITES [3] => API [4] => PRO_SETTINGS [5] => MANAGE_STAFF [6] => STATS [7] => E_COMMERCE [8] => MARKETING [9] => REPUBLISH [10] => PUBLISH [11] => DEV_MODE [12] => CUSTOM_DOMAIN [13] => MANAGE_CUSTOMERS ) ) [1] => Array ( [group_name] => salesman [color] => rgb(36,206,151) [title] => Sales [permissions] => Array ( [0] => REPUBLISH [1] => CREATE_SITES [2] => STATS [3] => EDIT_SITES [4] => E_COMMERCE [5] => MARKETING ) ) [2] => Array ( [group_name] => designer [color] => rgb(21,193,191) [title] => Designer [permissions] => Array ( [0] => REPUBLISH [1] => CREATE_SITES [2

	$output .= '<dl style="margin-bottom: 1em;">';



  foreach ( $group as $key => $value ) {

    $output .= "<dt>$key</dt><dd>$value</dd>";

  }



   $output .= '</dl>';

		

		

	}

		

	  

	}



return  $output;	

	

}







// End Check Function

public function dudapro_mobile_sites($email){

	$options = get_option('dudapro_api_settings');

    $apissoendpoint = $options['apissoendpoint'];

	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];



	$path = $_SERVER['DOCUMENT_ROOT'];

	global $display_name , $user_email;

    get_currentuserinfo();













$args = array(



		'headers' => array(



			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )



			)



		);

	



	$json = wp_remote_retrieve_body(wp_remote_get( 'https://api.dudamobile.com/api/sites/mobile/byexternal/'. $user_email, $args ));



	



	$data = json_decode($json, true);

$error = '';



 if ( is_user_logged_in() ) 

 { 

   

//print_r($data); exit();





if ($data['error_code'] == 'ResourceNotExist')

{

	$message .= 'No sites found under this email addres, ' . $user_email ;

	

	

	}

	else

	{

		if (empty($data))

			{

				$message .= 'no sites found <br/>';

		

			}

			else

			{

				$message .= '<ul>';

if (is_array($data))
{
				foreach ( $data as $key => $value )

				{

					

					if ($key == 'site_name') 

					{

						$sso_link = $this->generateSSOLink($value,$user_email);

					 $site_name = $value;	

					}

					

					if ($key == 'original_site_url') 

					{

					//	



						$message .= '<li><a href="'.  $sso_link .  '">'. $value . '</a></li>';

					

					}

				}
}

				$message .= '</ul>';

				

			

			}

	 } 

 }

 

 else {  

 $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

 $args = array(

        'redirect' => $actual_link, 

		'label_username' => __( 'Email Address' ),

        'label_log_in' => __( 'Log in to view your mobile sites' ),

        'remember' => true

    );

    wp_login_form( $args );

 

 

   

 }

	return $message;

}

}

?>