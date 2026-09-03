<?php

namespace OneNodes\Gateway;

use WC_Order;

defined('ABSPATH') || exit;

final class OneNodes_Webhook
{
    /**
     * Handle webhook.
     */
    public static function handle(): void
    {

        /*
         * Only POST requests.
         */
        if (
            isset($_SERVER['REQUEST_METHOD']) &&
            strtoupper(
                sanitize_text_field(
                    wp_unslash($_SERVER['REQUEST_METHOD'])
                )
            ) !== 'POST'
        ) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Method not allowed',
            ], 405);
        }

        /*
         * Get raw request body.
         *
         * Signature is generated from the exact raw body.
         */
        $raw_body = file_get_contents('php://input');

        if (empty($raw_body)) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Empty request body',
            ], 400);
        }

        /*
         * Get webhook signature.
         */
        $signature = '';

        if (isset($_SERVER['HTTP_X_WEBHOOK_SIGNATURE'])) {
            $signature = trim(
                sanitize_text_field(
                    wp_unslash(
                        $_SERVER['HTTP_X_WEBHOOK_SIGNATURE']
                    )
                )
            );
        }

        if (empty($signature) && function_exists('getallheaders')) {
            $headers = getallheaders();

            foreach ($headers as $key => $value) {
                if (strtolower($key) === 'x-webhook-signature') {
                    $signature = trim((string) $value);
                    break;
                }
            }
        }

        if (empty($signature)) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Missing signature',
            ], 400);
        }

        /*
         * Get secret key from WooCommerce gateway settings.
         */
        $settings = get_option(
            'woocommerce_onenodes_crypto_settings',
            []
        );

        $secret_key = trim((string) ($settings['secret_key'] ?? ''));

        if (empty($secret_key)) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Webhook secret is not configured',
            ], 500);
        }

        /*
         * Verify signature.
         */
        $expected_signature = hash_hmac(
            'sha256',
            $raw_body,
            $secret_key
        );

        if ( ! hash_equals($expected_signature, $signature ) ) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Invalid signature',
            ], 401);
        }

        /*
         * Decode JSON.
         */
        $inputs = json_decode(
            $raw_body,
            true
        );

        if (!is_array($inputs)) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Invalid JSON',
            ], 400);
        }

        /*
         * Validate gateway.
         */
        if (
            empty($inputs['gateway']) ||
            $inputs['gateway'] !== '1nodes'
        ) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Invalid gateway',
            ], 400);
        }

        /*
         * Validate order ID.
         */
        if (empty($inputs['order_id'])) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Missing order_id',
            ], 400);
        }

        $order_id = absint($inputs['order_id']);

        if ( ! $order_id ) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Invalid order_id',
            ], 400);
        }

        /*
         * Get WooCommerce order.
         */
        $order = wc_get_order($order_id);

        if (!$order instanceof WC_Order) {
            wp_send_json([
                'status' => 'error',
                'message' => 'Order not found',
            ], 404);
        }

        /*
         * Only process paid payments.
         */
        if (
            empty($inputs['status']) ||
            strtolower((string) $inputs['status']) !== 'paid'
        ) {
            wp_send_json([
                'status' => 'success',
                'message' => 'Ignored',
            ], 200);
        }

        /*
         * Save 1nodes payment information.
         */
        if (!empty($inputs['payment_id'])) {
            $order->update_meta_data(
                '_onenodes_payment_id',
                sanitize_text_field(
                    (string) $inputs['payment_id']
                )
            );
        }

        if (!empty($inputs['asset'])) {
            $order->update_meta_data(
                '_onenodes_asset',
                sanitize_text_field(
                    (string) $inputs['asset']
                )
            );
        }

        if (isset($inputs['total_paid'])) {
            $order->update_meta_data(
                '_onenodes_total_paid',
                sanitize_text_field(
                    (string) $inputs['total_paid']
                )
            );
        }

        if (!empty($inputs['payment_state'])) {
            $order->update_meta_data(
                '_onenodes_payment_state',
                sanitize_text_field(
                    (string) $inputs['payment_state']
                )
            );
        }

        if (
            !empty($inputs['tx_ids']) &&
            is_array($inputs['tx_ids'])
        ) {
            $tx_ids = array_map(
                static function ($tx_id) {
                    return sanitize_text_field(
                        (string) $tx_id
                    );
                },
                $inputs['tx_ids']
            );

            $order->update_meta_data(
                '_onenodes_tx_ids',
                wp_json_encode($tx_ids)
            );
        }

        $order->save();

        /*
         * Prevent duplicate webhook processing.
         */
        if ($order->is_paid()) {
            wp_send_json([
                'status' => 'success',
                'message' => 'Already processed',
            ], 200);
        }

        /*
         * Complete WooCommerce payment.
         */
        $payment_id = !empty($inputs['payment_id'])
            ? sanitize_text_field(
                (string) $inputs['payment_id']
            )
            : '';

        $order->payment_complete($payment_id);

        $order->add_order_note(
            __(
                'Payment confirmed by 1nodes webhook.',
                'onenodes'
            )
        );

        wp_send_json([
            'status' => 'success',
            'message' => 'OK',
        ], 200);
    }
}