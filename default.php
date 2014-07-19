<?php
/**
 * Plugin Name: Duda Mobile Reseller API Plugin
 * Plugin URI: http://wordpress.org/plugins/cnc-dudamobile-reseller-preview/
 * Description: Create duda mobile previews quickly without any programming
 * Version: 1.5.10
 * Author: Kevin Champlin
 * Author URI: http://kevinchamplin.com
 * Donate link: http://www.kevinchamplin.com/wordpress-plugins/
 * License: GPL2 
 */
 /*  Copyright 2014  Kevin Champlin(email :kevinchamplin.com)
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
   // wp_deregister_script( 'jquery' );
function cnc_scripts() {
 //  wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'cnc_scripts' ); // wp_enqueue_scripts action hook to link only on the front-end
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
	$duda_button_text = get_option('duda_button_text');
	$duda_debug = get_option('duda_api_debug');
	
	
	
	
	if (strlen($duda_button_text) == 0){
		$duda_button_text = "create my mobile preview";
	}
	
	
	
?>
<style>
#website_form {
	width: 100%;
	margin-right: auto;
	margin-left: auto;
}
#website_form legend {
}
#website_form label {
}
#website_form label span {
	float: left;
	width: 100px;
	color: #666666;
}
#website_form input {
}
#website_form textarea {
}
.submit_btn {
}
.submit_btn:hover {
}
.success {
	background: #CFFFF5;
	padding: 10px;
	margin-bottom: 10px;
	border: 1px solid #B9ECCE;
	border-radius: 5px;
	font-weight: normal;
}
.error {
	background: #FFDFDF;
	padding: 10px;
	margin-bottom: 10px;
	border: 1px solid #FFCACA;
	border-radius: 5px;
	font-weight: normal;
}
#content3 { 
	width: 960px;
	height: 950px;
	margin: 0 auto;
	background-color: #FFF;
	display: block;
	margin-top: 30px;
	border: none;
}
legend {
}
.centerCNC {
	text-align: center;
}
.centerCNC > div { /* N.B. child combinators don't work in IE7 or less */
	display: inline-block;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function( $ ) {
	$("#content3").hide();
    $("#submit_btn").click(function() { 
        //get input field values
		$('#loading').show('fast');
        var user_website = $('input[name=website]').val(); 
        
        //simple validation at client's end
       
        var proceed = true;
        if(user_website==""){ 
		$('#loading').hide('fast');
            $('input[name=website]').css('border-color','red'); 
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
 
			<?php  
			// remmoves the wp site domain to fix the CORS issue
			//$wpurl = get_site_url();
			$theurl = plugins_url('/cnc-dudamobile-reseller-preview/');
			//$newurl = str_replace($wpurl,'',$theurl);
			?>
			
			$.ajax({                        
            type: "post", 
       		url: "<?= $theurl?>mobileMagic.php",
	         data: "userWebsite=" + user_website, 
			dataType: "json",
			success:function(data) 
				{
			
			if ((navigator.userAgent.match(/(iPhone|iPod|BlackBerry|Android.*Mobile|BB10.*Mobile|webOS|Windows CE|IEMobile|Opera Mini|Opera Mobi|HTC|LG-|LGE|SAMSUNG|Samsung|SEC-SGH|Symbian|Nokia|PlayStation|PLAYSTATION|Nintendo DSi)/i)) ) {
				window.location.href=data.preview;
			}
			
			
			//    $("#content2").html(data.url);
				$("#content3").show();
			    $("#content3").attr("src",data.url);
				$('#loading').hide('fast');
					 
				},
        error: function(jqXHR, textStatus, errorThrown) 
        {
			$("#content3").hide('fast');
			$("#content2").show();
			$('#loading').hide('fast');
			$("#content2").html(errorThrown);
			
			 console.log("success" , arguments); // see all the parameters!
			 
        }
    });
        }
    });
    
    //reset previously set border colors and hide all message on .keyup()







    $("#website_form input").keyup(function() { 







        $("#website_form input").css('border-color','');  







        $("#result").slideUp();







    });







    







});







</script>



<fieldset id="website_form" class="centerCNC">

  <label for="website">

    <input name="website" type="text" id="website" placeholder=" Enter Your Website"  />

  </label>

  <label>

    <button class="submit_btn" id="submit_btn">

    <?=$duda_button_text; ?>

    </button>

  </label>

</fieldset>

<div id="loading" style="display: none; margin:0 auto;">

  <center>

    <img src="<?= plugins_url('/cnc-dudamobile-reseller-preview/')?>images/loader.gif" style="vertical-align: middle;margin:0 auto;" /> Loading...

  </center>

</div>

<div id="content2"></div> 

<div>

  <iframe id="content3" name="content3"></iframe>

</div>

<?php































































































}






























	































	































































 add_shortcode( 'cnc_duda', 'show_duda_stuff' ); // old shortcode (still works for backward compatability)















 add_shortcode( 'dudamobile_preview', 'show_duda_stuff' );  // new shortcode - will be used moving forward































 































  function set_duda_options()















 {















	add_option('duda_api_username','api username goes here','API Username');















	add_option('duda_api_password','api password','API Password');















	add_option('duda_button_text','button text ','Button Text');















	add_option('duda_api_debug','api debug','API Debug');





































 }































 































 function unset_duda_options() {































 	delete_option('duda_api_username');















	delete_option('duda_api_password');















	delete_option('duda_button_text');















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

  <h2>Duda Reseller API Options</h2>

  <hr/>

  <?php































	if ($_REQUEST['submit']){































		update_duda_options();































	}































	print_duda_form();































    ?>

</div>

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































if ($_REQUEST['duda_button_text']){































	update_option('duda_button_text',$_REQUEST['duda_button_text']);































	$ok=true;































}















































	update_option('duda_api_debug',$_REQUEST['duda_api_debug']);













































































































	if($ok){































		?>

<div id="message" class="updated fade">

  <p>Options saved.</p>

</div>

<?php































	}































	else































	{































		?>

<div id="message" class="updated fade">

  <p>Failed to save options.</p>

</div>

<?php































	}































		































}































































































function print_duda_form(){































	$duda_api_username =  get_option('duda_api_username');















	$duda_api_password = get_option('duda_api_password');















	$duda_button_text = get_option('duda_button_text');















	$duda_api_debug = get_option('duda_api_debug');





















































	?>

This will create a form allow users to submit their url and then generates a mobile preview. <br/>

This mobile preview is also saved in your Duda Reseller Dashboard.<br/>

<br/>

<form action="options-general.php?page=cnc-dudamobile-reseller-preview/default.php" method="post">

  <label for"api_username">API Username:

    <input type="text" name="duda_api_username" value="<?=$duda_api_username?>" />

  </label>

  <br/>

  <label for"api_password">&nbsp;&nbsp;API Password:

    <input type="text" name="duda_api_password" value="<?=$duda_api_password?>" />

  </label>

  <br/>

  <label for"duda_button_text">&nbsp;&nbsp;Button Text:

    <input   name="duda_button_text" type="text" value="<?=$duda_button_text?>" size="50" />

  </label>

  <br/>

  <br/>

  <label for"jquery_toggle">&nbsp;&nbsp;Debug:

    <input name="duda_api_debug" type="radio" value="0" <?php if ($duda_api_debug ==0 || $duda_api_debug !=1) echo "checked=checked" ?>  />

    no

    <input type="radio" name ="duda_api_debug" value="1" <?php if ($duda_api_debug ==1) echo "checked=checked" ?> />

    yes </label>

  <br/>

  <br/>

  

  <input type ="submit" name ="submit" value=" Save " />

</form>

<br/>

<br/>

<hr/>

If you found this plugin useful please consider donating.

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">

  <input type="hidden" name="cmd" value="_s-xclick">

  <input type="hidden" name="hosted_button_id" value="J5RLUBJ9CFP4E">

  <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">

  <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">

</form>

<br/>

<br/>

If you need hosting, domains, design or custom programming <a href="mailto:kevin@cncwebsolutions.com">contact us</a><br/>

<strong><a href="http://cncwebsolutions.com/1-web-hosting/" target="_blank">WordPress hosting starting at just $1.</a></strong>

<?php































}































	































add_action('admin_menu','modify_menu');





























































?>