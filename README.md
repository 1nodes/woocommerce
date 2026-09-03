# 1nodes WooCommerce Gateway

Cryptocurrency payment gateway for WooCommerce powered by 1nodes.

## Requirements

* WordPress
* WooCommerce
* PHP 8.1+
* A <a href="https://1nodes.com/">1Nodes</a> merchant account

## Features

* Cryptocurrency payments for WooCommerce
* Automatic payment verification
* Webhook support
* Automatic WooCommerce order status updates
* Support for WooCommerce Checkout Blocks
* Classic Checkout support
* Secure webhook signature verification

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

Your merchant credentials are available from your 1nodes merchant dashboard.

## Webhooks

1nodes uses webhooks to notify WooCommerce when a payment has been successfully completed.

Webhook requests are authenticated using an **HMAC-SHA256** signature.

## License

Proprietary software. All rights reserved.
