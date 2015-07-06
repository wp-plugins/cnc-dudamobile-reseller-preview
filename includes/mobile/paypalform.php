<?php

show_paypal($_POST["website"]);
	 function show_paypal($website)
	{
		$options = get_option('billing');
		$dudaproductname1 = $options['dudaproductname1'];
		$mobibilemonthly = $options['mobibilemonthly'];
		
		
		$options = get_option('paypal');
		$paypalusername = $options['paypalusername'];
		$paypalpassword = $options['paypalpassword'];
		$paypalsignature = $options['paypalsignature'];
		$paypalprocesspage = $options['processpage'];
		$paypalcancelpage = $options['cancelpage'];
		$paypalpaypalbusiness = $options['paypalbusiness'];
		
		$options2 = get_option('dudapro_api_display_settings');
		$mobileType = $options2['mobileType'];
		$mobileButtonText = $options2['mobilebutton'];
		$mobilecustomtext = $options2['mobilecustomtext'];
		$showpapaypal = $options2['showpapaypal'];

	
		$PayPalMode         = 'sandbox'; // sandbox or live
		$PayPalApiUsername  = $paypalusername; //PayPal API Username
		$PayPalApiPassword  = $paypalpassword ; //Paypal API password
		$PayPalApiSignature     = $paypalsignature; //Paypal API Signature
		$PayPalCurrencyCode     = 'USD'; //Paypal Currency Code
		$PayPalReturnURL    = $paypalprocesspage; //Point to process.php page
		$PayPalCancelURL    = $paypalcancelpage; //yoursite.com/paypal/cancel_url.php'; //Cancel URL if user clicks cancel



?>


<div id="paypalexpress"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">

    <!-- Identify your business so that you can collect the payments. -->
    <input type="hidden" name="business" value="<?=$paypalpaypalbusiness?>">

    <!-- Specify a Subscribe button. -->
    <input type="hidden" name="cmd" value="_xclick-subscriptions">
    <!-- Identify the subscription. -->
    <input type="hidden" name="item_name" value="<?=$dudaproductname1 . ' - ' . $website?>">

    <!-- Set the terms of the regular subscription. -->
    <input type="hidden" name="currency_code" value="USD">
    <input type="hidden" name="a3" value="<?=$mobibilemonthly?>">
    <input type="hidden" name="p3" value="1">
    <input type="hidden" name="t3" value="M">

    <!-- Set recurring payments until canceled. -->
    <input type="hidden" name="src" value="1">

    <!-- Display the payment button. -->
    <div id="paypalexpress"><input type="image" name="submit" border="0"
    src="https://www.paypalobjects.com/en_US/i/btn/btn_subscribe_LG.gif"
    alt="PayPal - The safer, easier way to pay online">
  <img alt="" border="0" width="1" height="1"
    src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" ></div>
</form></div>
<?php

		
		
	}
	?>