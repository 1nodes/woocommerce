=== 1nodes crypto payments for woocommerce ===
Contributors: 1nodes
Tags: woocommerce, cryptocurrency, bitcoin, payments, payment gateway
Requires at least: 6.4
Tested up to: 7.1
Requires PHP: 8.1
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Cryptocurrency payment gateway for WooCommerce with standard and non-custodial payment options.

== Description ==

1nodes is a cryptocurrency payment gateway for WooCommerce.

The plugin connects your WooCommerce store to the 1nodes payment service, allowing customers to pay for their orders using cryptocurrency.

1nodes supports two payment models:

**Standard Gateway**

The standard payment option uses percentage-based transaction fees. Payment requests are created through 1nodes, and payment status updates are sent back to WooCommerce automatically.

**Non-Custodial Payments**

The non-custodial option allows merchants to receive cryptocurrency payments directly to their own wallet using an XPUB-based setup.

Payment addresses can be derived without providing wallet private keys or recovery phrases to 1nodes. Customer payments are sent directly to the merchant's wallet rather than being held by 1nodes.

The non-custodial option uses a monthly subscription instead of percentage-based transaction fees.

Never provide your wallet private key or recovery phrase to 1nodes or enter them into this plugin.

The plugin supports:

* Cryptocurrency payments for WooCommerce
* Automatic payment verification
* Automatic WooCommerce order status updates
* Standard percentage-based payment processing
* Non-custodial payments
* XPUB-based direct-to-wallet payments
* Monthly subscription option for non-custodial payments
* Webhook payment notifications
* Secure webhook signature verification
* Classic WooCommerce Checkout
* WooCommerce Checkout Blocks

The plugin connects to the 1nodes API to create payment requests and retrieve a hosted cryptocurrency checkout URL.

== External Services ==

This plugin connects to the 1nodes payment API to create cryptocurrency payment sessions and manage the cryptocurrency payment flow.

Service endpoint:
https://1nodes.com/wp-json/v1/api/create-payment

When a customer places an order using the 1nodes payment method, the plugin sends data to the 1nodes API in order to create a payment request and receive a hosted checkout URL.

Data sent to the service:

* Order total amount
* WooCommerce order ID
* Order currency
* Customer billing email address
* Callback URL for payment status updates
* Return URL for redirecting the customer after payment
* Merchant authentication token (Merchant Key) in the Authorization header

When data is sent:

* When the customer submits the checkout form and chooses the 1nodes payment gateway

Why data is sent:

* To create a payment session with 1nodes
* To generate a checkout/payment URL for the customer
* To verify and manage the cryptocurrency payment
* To allow 1nodes to send payment status updates back to the store
* To complete the cryptocurrency payment flow

For merchants using the non-custodial payment option, 1nodes may use the merchant's configured XPUB information as part of the direct-to-wallet payment process. Private keys and wallet recovery phrases are not required by this plugin and should never be provided.

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

The payment model and non-custodial settings, when available for your account, are managed through your 1nodes account.

== Frequently Asked Questions ==

= Does 1nodes support WooCommerce Checkout Blocks? =

Yes. 1nodes supports both the classic WooCommerce checkout and WooCommerce Checkout Blocks.

= Is WooCommerce required? =

Yes. WooCommerce is required to use this plugin.

= Which PHP version is required? =

PHP 8.1 or later is required.

= How do I get my Merchant Key and Secret Key? =

Create a 1nodes account and obtain your Merchant Key and Secret Key from your account dashboard.

= What is the non-custodial payment option? =

The non-custodial option allows merchants to receive cryptocurrency payments directly to their own wallet using an XPUB-based setup.

1nodes does not need access to your wallet private keys or recovery phrase to provide this payment option.

= Does 1nodes hold my funds when I use non-custodial payments? =

With the non-custodial payment option, customer payments are sent directly to addresses derived for the merchant's wallet rather than being held by 1nodes.

= Do I need to provide my private key? =

No. Never provide your private key or wallet recovery phrase. They are not required by the 1nodes WooCommerce plugin.

= What is the difference between the standard and non-custodial payment options? =

The standard gateway uses percentage-based transaction fees.

The non-custodial option uses a monthly subscription and allows supported cryptocurrency payments to be sent directly to the merchant's wallet using an XPUB-based setup.

== Changelog ==

= 1.0.0 =

* Initial release.
