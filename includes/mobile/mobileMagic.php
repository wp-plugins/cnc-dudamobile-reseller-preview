<?php
require_once('../../../../../wp-config.php' );

$user_Website  = filter_var($_POST["website"], FILTER_SANITIZE_STRING);
$user_email = $_POST['em'];

cnc_dudapro_insert_leads('n/a', $user_email , $user_Website, 'mobile' );

cnc_dudapro_create_mobile($_POST['uiosaf'],$_POST['pasdfuia'],$user_Website, $user_email );

createWPAccount($user_email, $user_Website);

//Functions below


function createWPAccount($email_address, $user_Website)
{
	
	 
	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];	
	$dudaMobileListPage = $options5['dudaMobileListPage'];	
	$dudaOrderPage = $options5['dudaOrderPage'];	
	
	$emailMessage = get_option('dudapro_api_display_settings');
    $EmailMessage = $emailMessage['mobileEmailMessage'];

	 if( null == username_exists( $email_address ) ) {
// Generate the password and create the user
	  $password = wp_generate_password( 12, false );
	  $user_id = wp_create_user( $email_address, $password, $email_address );
	 
	  // Set the nickname
	  wp_update_user(
		array(
		  'ID'          =>    $user_id,
		  'nickname'    =>    $email_address,
		  'user_url'	=>	  $user_Website
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
			'Username: ' . $email_address . '<br/>' .
			'Password: ' . $password . '<br/>' .
			'<br/>Log in and start editing your site by <a href="'. $dudaMobileListPage . '">clicking here</a>' .
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
		
			'<br/>Log in and start editing your site by <a href="'. $dudaMobileListPage . '">clicking here</a>' .
			'<br/>Ready to publish your site? <a href="'. $dudaOrderPage . '?sitename=' . $sitename . '&accountname='. urlencode ($email_address) . '&originalurl='. $originalurl . '&t=d1">Order today!</a>' .
			
			
			'</body></html>';
			
			
		}  // end if
	
	if (empty( $user_Website))
		{
			$EmailMessage = 'Your site did not generate correctly, please try it again';
		}
		
	

    add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
	$kc = wp_mail($email_address, $subject , $message, $headers );
   // $kc =  wp_mail($user_email, $subject, $message, $headers);	 
	
	return $kc;
	  //wp_mail( $email_address, 'Welcome!', 'Your Password: ' . $password , $headers);
	 


}



	function cnc_dudapro_insert_leads($cnc_name , $cnc_email, $cnc_url, $cnc_lead )
	{
	global $wpdb;
			
	$table_name = $wpdb->prefix . 'cnc_dudapro';

		
$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'name' => $cnc_name, 
			'email' => $cnc_email,
			'url' => $cnc_url, 
			'lead' => $cnc_lead
		) 
	);	
}


function cnc_dudapro_create_mobile($duda_username,$duda_password,$user_Website, $user_email) {






	$data = '

			{	

			"site_data":

				{						

					"original_site_url":"'. $user_Website . '",
					"external_uid":"' . $user_email .'"
				}

			}

		';

	

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/mobile/create');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password"); 

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		curl_setopt($ch, CURLOPT_POST,1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

			'Content-Type: application/json', 		

			'Content-Length: ' . strlen($data))                                                                       

		);   	

		$output = curl_exec($ch);

		$info = curl_getinfo($ch);

			

		

					



			



					

					

		

		// Get result site name

		$output = json_decode($output, true);

		$siteName = $output['site_name'];

	

	

//	 echo json_encode(array("url" => $siteName, "preview" => $previewUrl ));



	 

	 cnc_dudapro_get_mobile_preview($duda_username,$duda_password,$siteName, $user_email);

 }

 

 
function sso_login($account, $siteName)
{
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];	
	$ssoEndPointURL = $options['apissoendpoint'];	
	$apissokey = $options['apissokey'];	
	$apissosecretkey = $options['apissosecretkey'];	
	$siteEditorPath = '/home/site/';

  //get token data
    $tokenArray = getSSOToken($account);
    
    //generate white label sso endpoint URL
    $ssoLocation = 'http://' .   $ssoEndPointURL . $siteEditorPath . $siteName . '?' . $tokenArray[url_parameter][name] . '=' . $tokenArray[url_parameter][value];
    
    //redirect user to endpoint URL
	
	
	
    return  $ssoLocation;
	
}


function createSubAccount($emailToCreate, $siteName) {
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];	

    

$data = '
	{	
	  "account_name": "' . $emailToCreate .'", 
	  "email": "'. $emailToCreate .'"
	}
';
//Initiate cURL 
$ch = curl_init();
//Set cURL parameters
curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json', 		
	'Content-Length: ' . strlen($data))                                                                       
);   
//Perform cURL call and set $output as returned data, if any is returned
$output = curl_exec($ch);
curl_close($ch);;

        return $output;


    

}





//define function to call Duda API and get a valid SSO token
function getSSOToken($account){
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];	
	$ssoEndPointURL = $options['apissoendpoint'];	
	$apissokey = $options['apissokey'];	
	$apissosecretkey = $options['apissosecretkey'];	
	$siteEditorPath = '/home/site/';
	define("API_USER",$duda_username);
	define("API_PASS",$duda_password);
	
	
	// Here you should provide the site name for which you need statistics

	$data = '';
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/sso/'.$account . '/token/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json', 		
		'Content-Length: ' . strlen($data))                                                                       
	);   
	$output = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($output, true);
	
	return $data;
}
    //set SSO values
	
	function dudapro_sendWelcome($account){
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];	
	$ssoEndPointURL = $options['apissoendpoint'];	
	$apissokey = $options['apissokey'];	
	$apissosecretkey = $options['apissosecretkey'];	
	$siteEditorPath = '/home/site/';
	define("API_USER",$duda_username);
	define("API_PASS",$duda_password);
	
	
	// Here you should provide the site name for which you need statistics

	$data = '';
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/sso/'.$account . '/token/');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json', 		
		'Content-Length: ' . strlen($data))                                                                       
	);   
	$output = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($output, true);
	
	return $data;
}


function grantAccountAccess($email,$siteName) {
	$options = get_option('dudapro_api_settings');

    $duda_username = $options['apiusername'];

	$duda_password = $options['apipassword'];	

   	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];	

    

   $data = '';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/accounts/grant-access/'. $email . '/sites/' .$siteName);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	'Content-Type: application/json', 		
	'Content-Length: ' . strlen($data))                                                                       
);   
$output = curl_exec($ch);
curl_close($ch);

     


    return true;


}




 function cnc_dudapro_get_mobile_preview($duda_username,$duda_password,$siteName,$user_email){

		$data = '';

	

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/' . $siteName);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password");

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

			'Content-Type: application/json', 		

			'Content-Length: ' . strlen($data))                                                                       

		);   

	//Perform cURL and set result as $output

	$output = curl_exec($ch);

	//Decode JSON results into array

	//$output = json_decode($output);

	$output = json_decode($output, true);

	curl_close($ch);

	//Echo exact preview URL from array

	//echo $output->site_extra_info->preview_url;

	 

	 $previewUrl = $output["site_extra_info"]["preview_url"];

	$beforeAfterUrl = $output["site_extra_info"]["before_after_preview_url"];

	

	

//	$beforeAfterUrl = str_replace("http","https",$beforeAfterUrl);

$createaccount = createSubAccount($user_email,$siteName);

$grantaccess = grantAccountAccess($user_email, $siteName);
$loginLink = sso_login($user_email, $siteName);

$sendemail = sendDudaEmail($user_email, $loginLink); 

echo json_encode(array("url" => dudapro_magicquotes($beforeAfterUrl), "preview" => dudapro_magicquotes($previewUrl), "ssolink" => $loginLink ));

 }

function dudapro_magicquotes($name)
{
    if (get_magic_quotes_gpc() )
    {
        return stripslashes($name);
    }
    return $name;
}

 
 function sendDudaEmail($user_email, $loginLink){
	$admin_email = get_option( 'admin_email' );
	$blogname = get_option( 'blogname' );
	$options = get_option('dudapro_api_display_settings');
    $mobileEmailMessage = $options['mobileEmailMessage'];

	$options5 = get_option('dudapro_general_settings');
    $dudaEmailFrom = $options5['dudaEmailFrom'];
	$dudaEmail = $options5['dudaEmail'];
	

	 
	 $to = $user_email;
	 
	 $headers ='From: '. $dudaEmailFrom . ' <' . $dudaEmail . '>' . "\r\n";
	 $headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$subject = "Your mobile website is now ready!"; 
	$message = '<html>
				<head>
				<title>HTML email</title>
				</head>
				<body>' .
	        $mobileEmailMessage .
			'<br/>You can login and edit your site by <a href="'. $loginLink . '">clicking here</a>' .
			'</body></html>';
	
	

   
 //  wp_mail($user_email, $subject, $message, $headers);
 }
 
 



 ?>