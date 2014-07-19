<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include_once $path . '/wp-config.php';
include_once $path . '/wp-load.php';
include_once $path . '/wp-includes/wp-db.php';
include_once $path . '/wp-includes/pluggable.php';
if($_POST)
{
    $duda_username = get_option('duda_api_username');
	$duda_password = get_option('duda_api_password');
	$duda_debug = get_option('duda_api_debug');
	$admin_email = get_option('admin_email');
    $headers = 'From: '.$user_Email.'' . "\r\n" . //remove this line if line above this is un-commented
    'Reply-To: '.$user_Email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
//    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    
        //exit script outputting json data
        $output = json_encode(
        array(
            'type'=>'error', 
            'text' => 'Request must come from Ajax'
        ));
        
        die($output);
    } 
    
    //check $_POST vars are set, exit if any missing
    if(!isset($_POST["userWebsite"]))
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Input fields are empty!'));
        die($output);
    }
    //Sanitize input data using PHP filter_var().
    $user_Website        = filter_var($_POST["userWebsite"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($user_Website)<4) // If length is less than 4 it will throw an HTTP error.
    {
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    
    
 
//---------------------------------------------------------------------------------------
	
	// Create Site
//	echo "Creating Site...<br/>";
	
	// enter your site url here. We are using moeplumbing.com as an example here
	$data = '
		{	
		"site_data":
			{						
				"original_site_url":"'. $user_Website . '"
			}
		}
	';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/create');
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
		
	if ($info['http_code'] != 200) {
		if ($duda_debug ==1)
		{
		print(".<br/>Error creating site.<br/><br/>");
		$sentMail = @mail($admin_email, "WP Duda Error - Error creating site", $output . $info, $headers); 
		echo $output;
		echo $info;
	//	die();
		}
	}
	
	// Get result site name
	$output = json_decode($output, true);
	$siteName = $output['site_name'];
	
//	echo "Site Created..<br/><br/>";		
	
	// Get Site
//	echo "Getting site information....<br/>"; 
	
	$data = '';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/'.$siteName);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$duda_username:$duda_password"); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		'Content-Type: application/json', 		
		'Content-Length: ' . strlen($data))                 
	);
	$output = curl_exec($ch);
	$info = curl_getinfo($ch);
	
	if ($info['http_code'] != 200) {
		if ($duda_debug ==1)
		{
		print("<br/>Error getting site data.<br/><br/>");
		$sentMail = @mail($admin_email, "WP Duda Error - Error getting site data.", $output . $info, $headers); 
		echo $output;
		echo $info;
	//	die();
		}
	}
	
//	echo "Information Retrieved.<br/><br/>";
	$beforeAfterUrl = '';
	
	$output = json_decode($output, true);
	$previewUrl = $output["site_extra_info"]["preview_url"];
	$beforeAfterUrl = $output["site_extra_info"]["before_after_preview_url"];
	
//	echo "Preview URL:".$previewUrl."<br/>";
//	echo "Before/After URL:".$beforeAfterUrl."<br/>";
	
	curl_close($ch);

 echo json_encode(array("url" => $beforeAfterUrl, "preview" => $previewUrl ));
 } ?>