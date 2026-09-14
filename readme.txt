=== 1nodes crypto payments for woocommerce ===
Contributors: 1nodes
Tags: woocommerce, cryptocurrency, bitcoin, payments, payment gateway
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Cryptocurrency payment gateway for WooCommerce.

== Description ==

1nodes is a cryptocurrency payment gateway for WooCommerce.

This plugin connects to the 1nodes API to create payment requests and retrieve a hosted cryptocurrency checkout URL.

It allows WooCommerce stores to accept cryptocurrency payments through the 1nodes payment service.

The plugin supports both the classic WooCommerce checkout and WooCommerce Checkout Blocks.

== External Services ==

This plugin connects to the 1nodes payment API to create cryptocurrency payment sessions.

Service endpoint:
https://1nodes.com/wp-json/v1/api/create-payment

When a customer places an order using the 1nodes payment method, the plugin sends data to the 1nodes API in order to create a payment request and receive a hosted checkout URL.

Data sent to the service:
- Order total amount
- WooCommerce order ID
- Order currency
- Customer billing email address
- Callback URL for payment status updates
- Return URL for redirecting the customer after payment
- Merchant authentication token (Merchant Key) in the Authorization header

When data is sent:
- When the customer submits the checkout form and chooses the 1nodes payment gateway

Why data is sent:
- To create a payment session with 1nodes
- To generate a checkout/payment URL for the customer
- To allow 1nodes to send payment status updates back to the store
- To complete and manage the cryptocurrency payment flow

Terms of Service:
https://1nodes.com/terms/

Privacy Policy:
https://1nodes.com/terms/

== Installation ==

1. Upload the `1nodes-crypto-payments-for-woocommerce` folder to the `/wp-content/plugins/` directory, or install the plugin ZIP file through `Plugins → Add New Plugin → Upload Plugin`.
2. Activate the plugin through the `Plugins` menu in WordPress.
3. Go to `WooCommerce → Settings → Payments`.
4. Select 1nodes.
5. Enter your Merchant Key and Secret Key.
6. Save the settings.

== Configuration ==

After activating the plugin:

1. Go to `WooCommerce → Settings → Payments`.
2. Select 1nodes.
3. Enable the 1nodes payment gateway.
4. Enter your Merchant Key.
5. Enter your Secret Key.
6. Save the settings.

You can obtain your Merchant Key and Secret Key from your 1nodes account dashboard.

== Frequently Asked Questions ==

= Does 1nodes support WooCommerce Checkout Blocks? =

Yes. 1nodes supports both the classic WooCommerce checkout and WooCommerce Checkout Blocks.

= Is WooCommerce required? =

Yes. WooCommerce is required to use this plugin.

= Which PHP version is required? =

PHP 8.1 or later is required.

= How do I get my Merchant Key and Secret Key? =

Create a 1nodes account and obtain your Merchant Key and Secret Key from your account dashboard.

== Changelog ==

= 1.0.0 =
* Initial release.
