<?php
$strScriptFilename          = $_SERVER["SCRIPT_FILENAME"];
$strScriptName              = $_SERVER["SCRIPT_NAME"];
$intPositionOfName          = stripos($strScriptFilename, $strScriptName);
$docRoot  = substr($strScriptFilename, 0,($intPositionOfName + 1));
require $docRoot.'wp-load.php';

global $wpdb;




$site = $_GET['id'];
	$options = get_option('dudapro_api_settings');
    $apissoendpoint = $options['apissoendpoint'];
	$options = get_option('dudapro_api_settings');
    $duda_username = $options['apiusername'];
	$duda_password = $options['apipassword'];


$args = array(
		'method' => 'delete',
		'headers' => array(

			'Authorization' => 'Basic ' . base64_encode( $duda_username . ':' . $duda_password )

			)

		);
	

	$json = wp_remote_retrieve_body(wp_remote_get('https://api.dudamobile.com/api/sites/multiscreen/'. $site , $args ));

	

	$data = json_decode($json, true);

$i=0;


//echo json_encode(array("message" => "site was deleted"));

?>