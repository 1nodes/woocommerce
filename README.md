# 1nodes WooCommerce Gateway

Cryptocurrency payment gateway for WooCommerce powered by [1nodes](https://1nodes.com/).

Accept crypto payments through the standard 1nodes gateway or use the non-custodial option to receive payments directly to your own wallet.

## Requirements

* WordPress
* WooCommerce
* PHP 8.1+
* A [1nodes](https://1nodes.com/) merchant account

## Features

* Cryptocurrency payments for WooCommerce
* Automatic payment verification
* Webhook support
* Automatic WooCommerce order status updates
* Standard percentage-based payment option
* Non-custodial payment option
* Direct-to-wallet payments using XPUB
* Monthly subscription option for non-custodial payments
* Support for WooCommerce Checkout Blocks
* Classic Checkout support
* Secure webhook signature verification

## Payment Options

1nodes gives merchants two ways to accept cryptocurrency payments.

### Standard Gateway

Use the standard 1nodes payment gateway with percentage-based transaction fees. Payments are processed through 1nodes and automatically verified, while WooCommerce order statuses are updated when payments are confirmed.

### Non-Custodial Payments

The non-custodial option is designed for merchants who prefer to receive crypto directly in their own wallet.

Using XPUB, payment addresses can be generated for customer transactions without giving 1nodes access to your private keys. Payments go directly to the merchant's wallet rather than being held by 1nodes.

This option uses a monthly subscription instead of percentage-based transaction fees.

> **Important:** Never share your wallet seed phrase or private keys. 1nodes does not need them to process non-custodial payments.

## Installation

1. Download or clone the plugin.

2. Upload the `1nodes` plugin directory to:

   ```text
   wp-content/plugins/
   ```

3. Activate **1nodes** from the WordPress Plugins page.

4. Open:

   **WooCommerce → 1nodes Guide**

5. Follow the setup instructions and configure your merchant credentials.

## Configuration

After activation, open:

**WooCommerce → 1nodes**

Configure the required credentials:

* **Merchant Key**
* **Secret Key**

Your merchant credentials are available from your [1nodes merchant dashboard](https://1nodes.com/).

Once configured, 1nodes will be available as a cryptocurrency payment option during WooCommerce checkout.

## How Payments Work

When a customer selects 1nodes during checkout:

1. WooCommerce creates the order.
2. The customer receives the cryptocurrency payment details.
3. The payment is monitored and verified.
4. WooCommerce is notified when the payment is confirmed.
5. The order status is updated automatically.

For non-custodial payments, funds are sent directly to the merchant's wallet using addresses derived through the XPUB-based payment setup.

## Webhooks

1nodes uses webhooks to notify WooCommerce when a payment has been successfully completed.

Webhook requests are authenticated using an **HMAC-SHA256** signature before the WooCommerce order is updated.

## Security

1nodes does not require your wallet private keys or recovery phrase for non-custodial payments.

Keep your **Merchant Key** and **Secret Key** private and never share your wallet seed phrase or private keys.

## Support

For help with installation, configuration, or payments, visit [1nodes.com](https://1nodes.com/).

## License

Proprietary software. All rights reserved.
