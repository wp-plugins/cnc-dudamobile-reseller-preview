<?php

/**

 * Plugin Name: Duda Reseller API Plugin

 * Plugin URI: http://cncwebsolutions.com/dudamobile-reseller-wordpress-plugin/

 * Description: Create Dudamobile previews instantly

 * Version: 0.1

 * Author: Kevin Champlin

 * Author URI: http://cncwebsolutions.com

 * License: GPL2

 */

 

 /*  Copyright 2014  CNC Web Solutions (email :kevin@cncwebsolutions.com)



    This program is free software; you can redistribute it and/or modify

    it under the terms of the GNU General Public License, version 2, as 

    published by the Free Software Foundation.



    This program is distributed in the hope that it will be useful,

    but WITHOUT ANY WARRANTY; without even the implied warranty of

    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the

    GNU General Public License for more details.



    You should have received a copy of the GNU General Public License

    along with this program; if not, write to the Free Software

    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

$pluginsURI = plugins_url('/cnc-dudamobile-reseller-preview/');

	wp_register_style('cnc_css', $pluginsURI . 'css/cnc-style.css', array(), '1.0' ); 
	wp_enqueue_style( 'cnc_css' );



function cnc_plugin_settings_duda($links) {
	$settings_link = '<a href="options-general.php?page=cnc-dudamobile-reseller-preview/default.php">Settings</a>';
	array_unshift($links,$settings_link);
	return $links;
	
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'cnc_plugin_settings_duda' );
 

 function show_duda_stuff()

 {

	$duda_username = get_option('duda_api_username');

	$duda_password = get_option('duda_api_password');

	$duda_debug = get_option('duda_api_debug');

?>	



<script>

function validateForm()

{

var x=document.forms["preview-form"]["ifrmSite"].value;

if (x==null || x=="")

  {

  alert("url name must be filled out");

  return false;

  }

  

  

   var url = x;

        var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

        if (pattern.test(url)) {

			  document.forms["preview-form"]["ifrmSite"].value =  x;



            //alert("Url is valid");

            return true;

        } 

           // alert("Url is not valid!");

            document.forms["preview-form"]["ifrmSite"].value = "http://" + x;

			return true;



}


function ButtonClicked()
{
   document.getElementById("formsubmitbutton").style.display = "none"; // to undisplay
   document.getElementById("buttonreplacement").style.display = ""; // to display
   return true;
}
var FirstLoading = true;
function RestoreSubmitButton()
{
   if( FirstLoading )
   {
      FirstLoading = false;
      return;
   }
   document.getElementById("formsubmitbutton").style.display = ""; // to display
   document.getElementById("buttonreplacement").style.display = "none"; // to undisplay
}
// To disable restoring submit button, disable or delete next line.


</script>


<div id="cncForm"> 
<form method="post" name="preview-form"  onSubmit="validateForm()" >

  
   <strong>Enter your Website URL:</strong>
        <input type="text" value="" name="ifrmSite" id="ifrmsite"/ > 
  

        <div id="formsubmitbutton"  class="cncSubmit" >
            <input type="submit" name="submit" id="submit" value=" create my mobile site "  onclick="ButtonClicked()">
        </div>
        <div id="buttonreplacement"  style="position:relative; margin-left:370px; top:-35px; display:none;">
        <img src="<?= plugins_url('/cnc-dudamobile-reseller-preview/') .'images/ajax-loader.gif'?>" alt="loading..." />
        </div>
 


</form>
</div>


	<script>

var newurl = $('#ifrmSite').val();

$('#mobilepreview').attr('src', newurl);

window.frames["mobilepreview"].location.reload();



</script>



<?php

	if(isset($_POST['submit']))  

{

	// Create Site

//	echo "Creating Site...<br/>";

	

	// enter your site url here. We are using moeplumbing.com as an example here

	$data = '

		{	

		"site_data":

			{						

				"original_site_url":"' . $_POST['ifrmSite'] . '"

			}

		}

	';



	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://api.dudamobile.com/api/sites/create');

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_USERPWD,  "$duda_username:$duda_password");

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

		print(".<br/>Error creating site.<br/><br/>");

		if ($duda_debug ==1)

		{

		print_r($output);

		print_r($info);

		}

		die();

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

	curl_setopt($ch, CURLOPT_USERPWD,  "$duda_username:$duda_password");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          

		'Content-Type: application/json', 		

		'Content-Length: ' . strlen($data))                 

	);

	$output = curl_exec($ch);

	$info = curl_getinfo($ch);

	

	if ($info['http_code'] != 200) {

	//	print("<br/>Error getting site data.<br/><br/>");

	//	print_r($output);

	//	print_r($info);

		die();

	}

	

//	echo "Information Retrieved.<br/><br/>";

	

	

	$output = json_decode($output, true);

	$previewUrl = $output["site_extra_info"]["preview_url"];

	$beforeAfterUrl = $output["site_extra_info"]["before_after_preview_url"];

	

//	echo "Preview URL:".$previewUrl."<br/>";

//	echo "Before/After URL:".$beforeAfterUrl."<br/>";

	

	curl_close($ch);

	echo '<iframe  id="mobilepreview" width="1100" height="930" src="'. $beforeAfterUrl . '"></iframe>';



}

?>	

	



<?php	

	

	return "$preview_code"; 

 }

 add_shortcode( 'cnc_duda', 'show_duda_stuff' );

 

  function set_duda_options()

 {

	add_option('duda_api_username','api username goes here','API Username');

	add_option('duda_api_password','api password','API Password');

	add_option('duda_api_debug','api debug','API Debug');

 }

 

 function unset_duda_options() {

 	delete_option('duda_api_username');

	delete_option('duda_api_password');

	delete_option('duda_api_debug');



 }



register_activation_hook(__FILE__,'set_duda_options');

register_deactivation_hook(__FILE__,'unset_duda_options');







function modify_menu(){

		add_options_page(

							'CNC Duda Preview',

							'CNC Duda Preview',

							'manage_options',

							__FILE__,

							'admin_duda_options'

						);

	}



function admin_duda_options(){

	?>

    <div class="wrap">

    <h2>Duda Reseller Preview Options</h2>

    <?php

	if ($_REQUEST['submit']){

		update_duda_options();

	}

	print_duda_form();

    ?></div>

    <?php	

}



function update_duda_options(){

$ok = false;



if ($_REQUEST['duda_api_username']){

	update_option('duda_api_username',$_REQUEST['duda_api_username']); 

	$ok=true;

}



if ($_REQUEST['duda_api_password']){

	update_option('duda_api_password',$_REQUEST['duda_api_password']);

	$ok=true;

}



	update_option('duda_api_debug',$_REQUEST['duda_api_debug']);





	if($ok){

		?><div id="message" class="updated fade">

		<p>Options saved.</p>

		</div><?php

	}

	else

	{

		?><div id="message" class="updated fade">

		<p>Failed to save options.</p>

		</div><?php

	}

		

}





function print_duda_form(){

	$duda_api_username =  get_option('duda_api_username');

	$duda_api_password = get_option('duda_api_password');

	$duda_api_debug = get_option('duda_api_debug');

	?>

    Currently this only shows a textbox to the user and then generates a mobile preview. This mobile preview is also saved in your Duda Reseller Dashboard.<br/><br/>

 

    <form action="options-general.php?page=cnc-dudamobile-reseller-preview/default.php" method="post"> 

    	<label for"api_username">API Username:

        	<input type="text" name="duda_api_username" value="<?=$duda_api_username?>" />

        </label>

        <br/>

        <label for"api_password">&nbsp;&nbsp;API Password:

        	<input type="text" name="duda_api_password" value="<?=$duda_api_password?>" />

        </label>

        <br/>

        

        <label for"ap_debug">&nbsp;&nbsp;Debug:

        

        <input type="radio" name="duda_api_debug" value="0" <?php if ($duda_api_debug ==0) echo "checked=checked" ?>  />no

        <input type="radio" name ="duda_api_debug" value="1" <?php if ($duda_api_debug ==1) echo "checked=checked" ?> />yes

        </label>

        <br/>

        <input type ="submit" name ="submit" value="Submit" />

    </form>

    

    <br/><br/>

    <hr/>

    This is in the very early stages, much more to come!  If this is helpful to you, please consider a donation.  This takes time to do and I'm not going to sell this. However your donations will motivate me to add more  Duda API calls to this.  

    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">

<input type="hidden" name="cmd" value="_s-xclick">

<input type="hidden" name="hosted_button_id" value="J5RLUBJ9CFP4E">

<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">

<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">

</form>

<br/><br/>

    I am also available if you are looking for hosting, web design or custom programming. Contact me if you have a project in mind at <a href="mailto:kevin@cncwebsolutions.com">kevin@cncwebsolutions.com</a>. <strong><br/>WordPress hosting starting at just $1.</strong>

    <?php

}

	

add_action('admin_menu','modify_menu');



?>