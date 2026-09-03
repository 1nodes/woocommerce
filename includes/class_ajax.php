<?php

namespace OneNodes\Gateway;

defined('ABSPATH') || exit;

final class OneNodes_Ajax
{
    public static function check_payment_status(): void
    {
        $order_id = isset($_POST['order_id'])
            ? absint($_POST['order_id'])
            : 0;

        $order_key = isset($_POST['order_key'])
            ? wc_clean(wp_unslash($_POST['order_key']))
            : '';

        if (!$order_id || empty($order_key)) {
            wp_send_json_error([
                'message' => 'Invalid request.',
            ], 400);
        }

        $order = wc_get_order($order_id);

        if ( ! $order ) {
            wp_send_json_error([
                'message' => 'Order not found.',
            ], 404);
        }

        /*
         * Make sure the order key belongs to this order.
         */
        if (!hash_equals($order->get_order_key(), $order_key)) {
            wp_send_json_error([
                'message' => 'Unauthorized.',
            ], 403);
        }

        wp_send_json_success([
            'status' => $order->get_status(),
            'paid'   => $order->is_paid(),
        ]);
    }
}