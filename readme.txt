=== Woocommerce Custom Coupon Message ===
Contributors: come-back-home
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7H7FTM9VL9Y9L&lc=NO&item_name=Woocommerce%20Custom%20Coupon%20Message%20plugin%20development&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: woocommerce, coupons
Requires at least: 3.5.1
Tested up to: 3.5.1
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin adds a meta box to the coupon edit screen where you can make your own customized coupon message.

== Description ==

You may choose a colourful style for the custom coupon message, or wrap the message in your own CSS class container.

It's also possible to hide the default message from Woocommerce so that only your custom coupon message is displayed.
(the plugin uses inline CSS to hide the default Woocommerce message).

Take a look at the screenshots for the coupon edit screen with the plugin enabled and some examples.

The meta box was implemented using the technique described on this blog post:
http://wp.smashingmagazine.com/2011/10/04/create-custom-post-meta-boxes-wordpress/

The message styles were generated using this tool:
http://www.bestcssbuttongenerator.com/

== Installation ==

1. Upload the entire `woocommerce-custom-coupon-message` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Can I use HTML in the custom coupon message box? =

Yes you can. Just make sure that your HTML is correct, otherwise things may look strange.

= What do I enter in the CSS class textfield? =

If you'd like to use your own CSS style for the custom coupon message container, just enter your name of the CSS class in this textfield.
You do not need to enter the dot(.) indicating that this is a CSS class. Only one CSS class can be entered.

= Can I choose a style for the message AND enter a CSS class in the textfield? =

No, this will not work, sorry. If you do this the default Woocommerce style will be applied.

= Does the colourful message styles look good in all browsers? =

Yes, they should, but in Internet Explorer 8 and below they look just ok (No rounded corners, shadows etc).

= This plugin does not work. What can I do? =

The plugin should work fine using all newer versions of Woocommerce (tested with version 1.6.6 and 2.0.5)
If it's not working just ask for help in the support forum, and I'll try to sort out any bugs.

= Hey, I have and idea/feature request for this plugin. Will you consider adding it?  =

I might if I have the time. Let me know and I'll see if it's doable :)

== Screenshots ==

1. Coupon edit screen with meta box for the custom message on the right
2. Custom message displayed when coupon is applied. Default message is hidden.
3. Custom message and default message displayed when coupon is applied.

== Changelog ==

= 0.4 =
* Added option to choose a colourful style to wrap the custom coupon message in
* Added option to enter a CSS class for the custom coupon message container 
* Cleaned up code and fixed some typos

= 0.3 =
* Woocommerce 2.0 ready (works with 1.6.6 as well)

= 0.2 =
* Plugin can be now localized
* Added Norwegian translation

= 0.1 =
* First release

 == Upgrade Notice ==

= 0.1 =
First release, nothing to worry about so far.