=== WebINK Connector ===

Contributors: WebINK
Tags: webfonts, web fonts, @font-face, webink
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.0.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

The WebINK Connector WordPress Plug-in makes it easy to use WebINK fonts on your WordPress sites.

== How it works ==

The WebINK WordPress Plug-in connects to your WebINK account and makes all of your WebINK fonts available for use in WordPress.

Fonts can be selected using the Visual editor in WordPress or applied to CSS selectors. The plug-in takes care the coding and WebINK service connection for you.

== Requirements ==
*	WordPress 3.0 or higher 
*	PHP Soap Support (including the SoapClient class) <http://php.net/manual/en/book.soap.php>
*	PHP cURL module <http://us1.php.net/manual/en/book.curl.php>
*   An active WebINK account. Sign up for free at <http://www.webink.com>

== Connecting to WebINK ==

= At WebINK =
1. Log in to your WebINK account at <http://webink.com>
2. Create a project and add the fonts you want to use on your WordPress site
3. Add your development and/or production domains to the list of approved domains from your WebINK account

= In WordPress =
1. Install and activate the webink-connector plugin
2. Click "WebINK" in the admin menu
3. Enter your WebINK account credentials (registered email and password)
4. Click "Refresh Project List"
5. Configure the desired project settings from the list that appears after sending your credentials<br /><em>Note: Make sure your site's domain name (e.g. myblog.com) is listed as one of the domains approved for use with the project you are configuring</em>
6. Be sure to save your settings at the bottom of the config screen!
7. Fonts you selected for inclusion in the post editor will appear in the "Font Family" menu in the Posts editor

== Frequently Asked Questions ==

= Can the plug-in be installed and used with Wordpress.com sites? =
At this time, no. The plug-in is only for use with sites that allow you to install and remove plug-ins. Self-hosted or ISP hosted WordPress installations only. We may add support for WordPress.com sites in the future. 

= What markup does the Plug-in insert into the post when fonts are applied using the Visual editor? =
In the WordPress Visual editor, when you apply a WebINK font, a <span> tag is inserted that calls the selected font.

= Does the WebINK plug-in work with free Development level projects? =
Yes, the plug-in does work with projects that are set to Development mode. Fonts in these projects are still limited to the ten IP address limit per day. So, you can develop your new WordPress site, but will need to turn on Production when you are ready to go live.


== Changelog ==
= 1.0.3 =
* Fixed issues related to dependency checking at activation time
= 1.0 =
* Release of Version 1


