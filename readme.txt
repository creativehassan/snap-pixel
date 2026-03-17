=== Pixel Integration for Snapchat ===
Contributors: creativehassan, mustaneerabdullah93, muzammilahsan
Author URI: https://creativehassan.com
Plugin URI: https://wordpress.org/plugins/snap-pixel
Donate link: https://paypal.me/coresol
Tags: snapchat, pixel, tracking, conversion tracking, woocommerce
Requires at least: 5.0
Tested up to: 6.8.2
Requires PHP: 5.6
Stable tag: 1.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Pixel Integration for Snapchat helps advertisers measure the cross-device impact of campaigns with easy integration for WordPress and WooCommerce. This plugin is not affiliated with or endorsed by Snap Inc.

== Description ==

Pixel Integration for Snapchat provides a seamless way to integrate the Snapchat tracking pixel into your WordPress website and WooCommerce store. It allows you to track user interactions and conversions, helping you measure the effectiveness of your Snapchat advertising campaigns. This plugin is not affiliated with or endorsed by Snap Inc.

= Key Features =

* **Easy Setup** - Simply enter your Pixel ID and you're ready to go
* **WordPress Integration** - Track user activity across your entire WordPress site
* **WooCommerce Support** - Track standard e-commerce events automatically
* **Easy Digital Downloads Support** - Track digital product sales and downloads
* **Flexible Configuration** - Choose exactly which pages and events to track
* **AJAX Support** - Compatible with AJAX-powered add-to-cart functionality
* **User Email Tracking** - Improve conversion tracking with user email data
* **Modern Admin Interface** - Intuitive settings panel

= Available Add-ons =

Enhance your pixel integration with these powerful add-ons available at [coresolstudio.com/plugins/snap-pixel](https://coresolstudio.com/plugins/snap-pixel):

**1. Pixel Analytics for Snapchat**

Track and analyze your pixel events directly in your WordPress dashboard. Get insights into user behavior, conversions, and campaign performance without leaving your site.

Features:
* Real-time event tracking dashboard
* Detailed event data visualization
* Export capabilities for further analysis
* Historical data storage and reporting
* Filter events by date range and type

**2. Pixel Integration for Membership Sites (Future)**

Specialized tracking for membership sites and subscription-based businesses using popular plugins like MemberPress, WooCommerce Subscriptions, and more.

Features:
* Subscription event tracking
* Member registration and login tracking
* Membership level changes tracking
* Renewal and cancellation tracking
* Member engagement metrics

= Supported Events =

The plugin supports all standard Snapchat pixel events:

* **PAGE_VIEW** - Tracks when users view any page
* **VIEW_CONTENT** - Tracks when users view product pages
* **ADD_CART** - Tracks when users add products to cart
* **START_CHECKOUT** - Tracks when users begin the checkout process
* **ADD_BILLING** - Tracks when users add billing information
* **PURCHASE** - Tracks when users complete a purchase
* **SEARCH** - Tracks search queries on your site

= WooCommerce Integration =

When used with WooCommerce, the plugin automatically tracks:

* Product views with detailed product data
* Add to cart actions (including AJAX add to cart)
* Checkout initiation
* Purchase completion with transaction data

= Easy Digital Downloads Integration =

When used with Easy Digital Downloads, the plugin automatically tracks:

* Digital product views with detailed product data
* Add to cart actions for digital products
* Checkout initiation for digital purchases
* Purchase completion with transaction data

= Privacy and GDPR =

This plugin collects data for conversion tracking purposes. To ensure GDPR compliance, we recommend:

1. Updating your privacy policy to disclose the use of the Snapchat tracking pixel
2. Implementing a cookie consent solution
3. Only enabling user email tracking with proper consent

= Upcoming Features =

* SAVE event for Wishlist for products or items
* SIGNUP event for all signups on website

== Installation ==

= Automatic Installation =

1. Log in to your WordPress dashboard
2. Navigate to "Plugins > Add New"
3. Search for "Pixel Integration for Snapchat"
4. Click "Install Now" and then "Activate"
5. Go to "Pixel Integration for Snapchat" in your admin menu to configure

= Manual Installation =

1. Download the plugin ZIP file
2. Log in to your WordPress dashboard
3. Navigate to "Plugins > Add New > Upload Plugin"
4. Upload the ZIP file and click "Install Now"
5. Activate the plugin and configure settings

= Configuration =

1. Obtain your Pixel ID from your Snapchat Ads Manager
2. Go to "Pixel Integration for Snapchat" in your WordPress admin menu
3. Enter your Pixel ID in the settings
4. Select which pages and events you want to track
5. If using WooCommerce, enable WooCommerce integration
6. Save your settings
7. For enhanced functionality, install the desired add-ons from [coresolstudio.com/plugins/snap-pixel](https://coresolstudio.com/plugins/snap-pixel)

= Documentation and Support =

For detailed documentation, visit [coresolstudio.com/docs/snap-pixel](https://coresolstudio.com/docs/snap-pixel)

For support, please visit [coresolstudio.com/support](https://coresolstudio.com/support)

== Frequently Asked Questions ==

= How do I find my Pixel ID? =

You can find your Pixel ID in your Snapchat Ads Manager. Go to the "Events Manager" section and select your pixel. The Pixel ID will be displayed at the top of the page.

= How can I verify that my pixel is working correctly? =

You can download the Snap Pixel Helper Chrome Extension (an official Snapchat tool) from the [Chrome Web Store](https://chrome.google.com/webstore/detail/snap-pixel-helper/hnlbfcoodjpconffdbddfglilhkhpdnf). This tool will show you when the pixel fires on your website.

= Does this plugin work with WooCommerce? =

Yes! The plugin has built-in support for WooCommerce and can track all standard e-commerce events automatically.

= Does this plugin work with Easy Digital Downloads? =

Yes! The plugin has built-in support for Easy Digital Downloads and can track all standard digital product events automatically.

= Will this plugin slow down my website? =

No, the plugin is designed to be lightweight and efficient. The pixel code is loaded asynchronously, which means it won't block your page from loading.

= Is this plugin compatible with caching plugins? =

Yes, the plugin is compatible with most caching plugins. However, for dynamic events like add-to-cart, you may need to exclude certain pages from caching.

= Where can I find more information on the Snapchat tracking pixel? =

You can find more information on the [Snapchat Pixel documentation page](https://businesshelp.snapchat.com/en-US/article/snap-pixel-about).

= Are there any add-ons available? =

Yes! We offer several powerful add-ons to enhance your pixel integration:

1. **Pixel Analytics for Snapchat** - Track and analyze your pixel events directly in your WordPress dashboard
2. **Advanced E-commerce Pixel Tracking** - Advanced WooCommerce tracking features
3. **Conversion API for Snapchat** - Server-side tracking for more reliable conversion data
4. **Pixel Integration for Membership Sites** - Specialized tracking for membership and subscription sites

You can find all these add-ons at [coresolstudio.com/plugins/snap-pixel](https://coresolstudio.com/plugins/snap-pixel).

= I am a developer. Can I help improve the plugin? =

Of course! This plugin is open-sourced on GitHub. You can find the code and contribution instructions in the [plugin repository](https://github.com/creativehassan/snap-pixel).

== Screenshots ==

1. General Settings
2. WordPress Integration Options
3. WooCommerce Integration Settings
4. Help and Support Section

== Changelog ==

= 1.9.2 =
* Added Pixel ID validation with regex pattern to ensure proper format
* Pixel ID now only accepts lowercase letters, numbers, and hyphens
* Added error message for invalid Pixel ID format during settings save
* Improved data integrity and security for Pixel ID input

= 1.9.1 =
* Critical security fix: Fixed nonce bypass vulnerability in AJAX handlers that could allow unauthorized access to product data
* Replaced manual nonce verification with WordPress standard check_ajax_referer() function for improved security
* Updated JavaScript AJAX calls to use proper nonce parameter naming convention
* Removed deprecated load_plugin_textdomain() function call (WordPress handles translations automatically since 4.6)
* Tested and confirmed compatibility with WordPress 6.8.2

= 1.9.0 =
* Security fix: Improved nonce verification in AJAX handlers to prevent potential security vulnerabilities
* Enhanced data validation and sanitization throughout the plugin

= 1.8.0 =
* Major code refactoring for improved security and performance
* Enhanced admin interface with Snapchat-inspired design
* Improved WooCommerce integration
* Better sanitization and validation of user inputs
* Added comprehensive help section
* Fixed AJAX add-to-cart tracking issues
* Updated compatibility with WordPress 6.6.2

= 1.7.0 =
* Security fix applied

= 1.6.0 =
* Security fix applied

= 1.5.8 =
* Security fix applied

= 1.5.7 =
* Updated the backend UI and fixed some backend issues

= 1.5.4 =
* Fatal Error removed for undefined function

= 1.5.3 =
* Product ID is added in PAGE_VIEW and improved the product details for pixel

= 1.5.2 =
* WordPress 5.5 updates

= 1.5.1 =
* User Email is added for tracking if user is logged in

= 1.5.0 =
* Support for Ajax Add to Cart added

= 1.4.0 =
* Add to Cart Issue Solved

= 1.3.0 =
* Add to Cart Ajax Bug Solved

= 1.2.9 =
* Woocommerce product bug solved

= 1.2.0 =
* Settings will remain save after deactivation of the plugin

= 1.1.0 =
* Woocommerce conditions

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.9.2 =
Enhancement update that adds Pixel ID validation to ensure proper format and improve data integrity.

= 1.9.1 =
Critical security update that fixes a nonce bypass vulnerability in AJAX handlers. Please upgrade immediately.

= 1.9.0 =
Important security update that fixes a nonce verification vulnerability. Please upgrade immediately.

= 1.8.0 =
Major update with improved security, better UI, and enhanced WooCommerce integration. Please backup before upgrading.

= 1.7.0 =
Important security update. Please upgrade immediately.