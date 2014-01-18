=== Plugin Name ===

Contributors: cnckevin

Tags: dudamobile, reseller

Requires at least: 3.8

Tested up to: 3.8

Stable tag: 1.0

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html


Add dudamobile previews on your wordpress page easy by using a shortcode.



== Description ==

This plugin is for dudamobile resellers that have API access. It allows you to enter your api username and api password in the settings.  Then you can use a shortcode to display the mobile preview on any page.  This was only tested on WordPress version 3.8 so I cannot conifrm if it works on any past versions.  If it does please let me know.<br/><br/>
All you need to make this work is to add the short code: [cnc_duda] to your page.  Remeber to add your username and password under Settings in the admin.









== Installation ==


1. Click install or upload `cnc-duda-reseller` to the `/wp-content/plugins/` directory

1. Activate the plugin through the 'Plugins' menu in WordPress

1. Go to the admin and click on settings then choose 'CNC Duda Preview'. Enter your API Username & Password here

1. Place the shortcode [cnc_duda] on any page you would like to show the mobile preview.



== Frequently Asked Questions ==



= Does this work with any Dudamobile plan =
It only works if you have a plan that has the Duda API access.

= It won't work, what could be wrong? =
In the settings you can turn debugging on or off.  If you turn it on you will see the debugging error message returned from the API. Common
errors are not updating the API Username & Password in the settings.

= Do I have to edit code to make this work? =
Absolutely not! Just install the plugin, add your username & password in the settings adn then use the shortcode.  Simple!


= Does this use all the API functions =
Not yet, this only creates the mobile comparison preview.  I will add more features as time allows.

= When will other features be available? =
Truthfully I'm not sure, this is primarily a hobby at this point. If I can receive some donations that would motivate me more. :)





== Screenshots ==

1. Just add your username & password and save

2. Textbox to type the website to convert

3. Pretty comparison embeddded in your site






== Changelog ==



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

* Settings page to add your api username & password.  Also turn debuging on and off.

* Shortcode to add your mobile preview on any page [cnc_duda]



