=== Plugin Name ===

Contributors: cnckevin

Tags: dudamobile, reseller

Requires at least: 3.8

Tested up to: 3.9

Stable tag: 1.5.8
License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html


Add duda mobile previews on your wordpress page easily by using a shortcode.



== Description ==

This plugin is for dudamobile resellers that have API access for the mobile sites (not duda one). It allows you to enter your api username and api password in the settings.  Then you can use a shortcode to display the mobile preview on any page.


All you need to make this work is to add the short code: [dudamobile_preview] to your page.  Remember to add your username and password under Settings in the admin.

The duda one api plugin will be released sometime July 2014 as a seperate plugin.

Features:

* Easy to use  (no coding required!)
* AJAX - preview loads without the page reloading
* Mobile - view the form on your moible device and see the mobile site (not the comparison)
* Design - design was minimal so it uses your templates css



** Be sure to rate the plugin, if I know people like it i'll be sure to keep improving it: http://wordpress.org/plugins/cnc-dudamobile-reseller-preview/ ** 




== Installation ==


1. Click install or upload `cnc-duda-reseller` to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress

3. Go to the admin and click on settings then choose 'CNC Duda Preview'. Enter your API Username & Password here

4. Place the shortcode [dudamobile_preview] on any page you would like to show the mobile preview.

5. If it doesn't work go back into your settings and make sure toggle Use jQuery link to yes or no. 

== Frequently Asked Questions ==

= It's not working? =
Under the Settings there is a section to show jQuery or not (yes/no) if its not working change this and try it again. Now days most templates include jquery but some done so here you can toggle it on and off depending on your websites needs.


= Does this work with any Dudamobile plan =
It only works if you have a plan that has the Duda API access.

= It won't work, what could be wrong? =
In the settings you can turn debugging on or off.  If you turn it on you will see the debugging error message returned from the API. Common
errors are not updating the API Username & Password in the settings.

= Do I have to edit code to make this work? =
Absolutely not! Just install the plugin, add your api username & api password in the settings and then use the shortcode [dudamobile_preview]  Simple!


= Does this use all the API functions =
Not yet, this only creates the mobile comparison preview.  I will add more features as time allows.

= It just isn't working, what can I do? =
The best thing is to install an error log plugin and look at the error log.  If you have an error log you can send it to kevin@cncwebsolutions.com

= This is awesome, can I send you a donation? =
Yes! I truly spent a lot of time on this and would love a donation.  My paypal id is sales@cncwebsolutions.com  If you found this useful or better if it makes you money please don't forget about me :)
Be sure to rate the plugin, if I know people like it i'll be sure to keep improving it: http://wordpress.org/plugins/cnc-dudamobile-reseller-preview




== Screenshots ==

1. Just add your username & password and save

2. Textbox to type the website to convert

3. Pretty comparison embedded in your site 






== Changelog ==

= 1.5.8 = 

* Fixed a CSS bug if you had existing iframes on the site 



= 1.5.7 = 

* Fixed a CSS bug with the content area - some themes would show the form and then it would quickly disappear.  This has been resolved 
* Also removed jquery from admin as its no longer needed as of version 1.5.5



= 1.5.6 =

* Added debug options back in, these were removed in a previous update



= 1.5.5  =

* Removed jquery toggle - no longer needed as WP handles this
* Cleanup: Removed deprecated functions
* Cleanup: add jquery the WP way


= 1.5.4 =

* Bug fix: Changed file path to relative to avoid the CORS (cross-origin resource sharing) issue
* Centered textbox and submt button; removed width on form

= 1.5.3 =

* The iframe now loads when the mobile comparison is ready - saves on screen real estate this way.
* Removed a lot of the design, this way it blends in with other templates better
 

= 1.5.2 =


* Fixed jquery toggle 
* Updated description
 

= 1.5.1 =

* A big rewrite to make it a bit more user friendly.
* Added ajax to the preview so it shows up without having to refresh
* Added a new prettier form
* Added mobile functionality - use the form on a phone and the mobile preview shows up instead of the comparison
* Added admin setting to turn the jquery link on or off. Some sites already include this link.
 

= 1.4.1 =

* removed a duplicate value in the embed code
* if the latest & greatest doesn't work for you this is an oldie but goodie and will work


= 1.4 =


* Some plugins would sanitize the url breaking the embed code, this fixes that.


= 1.3 =


* Allowed the iframe source to scroll if the site is to small to containthe preview of 1100px.



= 1.2 =


* Fixed a bug with the embed tag



= 1.1 =



* Added custom button text

* Added new styles form

* Added new shortcode [dudamobile_preview]  (old shortcode still works)

= 1.0 =

Version 1 was released. 

* Added a loading image after the form is submitted

* Some css fixes

* Added Settings page on the plugins page

* Added better installation and FAQ pages




= 0.2 =

Added debug option in settings to see the error returned by dudamobile api



= 0.1 =

Initial release



== Arbitrary section ==

* Settings page to add your api username & password.  Also turn debugging on and off.

* Shortcode to add your mobile preview on any page [dudamobile_preview]

* Change the button text to anything  you like



