<?php

namespace OneNodes\Gateway;

use WC_Payment_Gateway;
use WC_Order;
use function WooCommerce\PayPalCommerce\OrderTracking\tr;


defined('ABSPATH') || exit;

class OneNodes_WC_Gateway extends WC_Payment_Gateway
{
    private string $api_url      = 'https://1nodes.com/wp-json/v1/api/create-payment';
    private string $merchant_key = '';
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 'onenodes_crypto';

        $this->icon = apply_filters(
            'onenodes_woocommerce_icon',
            ONENODES_URL . 'assets/images/1nodes.png'
        );

        $this->has_fields         = false;
        $this->method_title       = __('1nodes Payments', '1nodes-for-woocommerce');
        $this->method_description = __('Accept cryptocurrency payments securely via 1nodes gateway.', '1nodes-for-woocommerce');
        $this->supports           = ['products'];

        /*
         * Settings.
         */
        $this->init_form_fields();
        $this->init_settings();

        /*
         * Load settings.
         */
        $this->enabled = $this->get_option('enabled', 'no');

        $this->title = $this->get_option(
            'title',
            __('Cryptocurrency (1nodes)', '1nodes-for-woocommerce')
        );

        $this->description = $this->get_option(
            'description',
        );

        $this->merchant_key = trim(
            (string) $this->get_option('merchant_key', '')
        );


        /*
         * Admin settings save.
         */
        add_action(
            'woocommerce_update_options_payment_gateways_' . $this->id,
            [
                $this,
                'process_admin_options',
            ]
        );
    }

    /**
     * Gateway settings.
     */
    public function init_form_fields(): void
    {
        $this->form_fields = [

            'enabled' => [
                'title'   => __('Enable/Disable', '1nodes-for-woocommerce'),
                'type'    => 'checkbox',
                'label'   => __('Enable 1nodes Cryptocurrency Payment', '1nodes-for-woocommerce'),
                'default' => 'no',
            ],

            'title' => [
                'title'       => __('Title', '1nodes-for-woocommerce'),
                'type'        => 'text',
                'description' => __('This controls the title which the user sees during checkout.', '1nodes-for-woocommerce'),
                'default'     => __('Cryptocurrency (1nodes)', '1nodes-for-woocommerce'),
                'desc_tip'    => true,
            ],

            'description' => [
                'title'       => __('Description', '1nodes-for-woocommerce'),
                'type'        => 'textarea',
                'description' => __('This controls the description which the user sees during checkout.', '1nodes-for-woocommerce'),
                'default'     => __('Pay securely using Bitcoin, DogeCoin, LiteCoin and other cryptocurrencies via 1nodes.', '1nodes-for-woocommerce'),
            ],

            'merchant_key' => [
                'title'       => __('Merchant Key', '1nodes-for-woocommerce'),
                'type'        => 'text',
                'description' => __('Enter your 1nodes Merchant Key.', '1nodes-for-woocommerce'),
                'default'     => '',
                'desc_tip'    => true,
            ],

            'secret_key' => [
                'title'       => __('Secret Key', '1nodes-for-woocommerce'),
                'type'        => 'password',
                'description' => __('Enter your 1nodes Secret Key.', '1nodes-for-woocommerce'),
                'default'     => '',
                'desc_tip'    => true,
            ],

        ];
    }



    /**
     * Check availability.
     *
     * This is used by Classic Checkout.
     */
    public function is_available(): bool
    {
        if ('yes' !== $this->enabled) {
            return false;
        }

        if (empty($this->merchant_key)) {
            return false;
        }

        return true;
    }


    /**
     * Process payment.
     *
     * This method is used by both Classic Checkout
     * and legacy payment processing from Checkout Block.
     */
    public function process_payment($order_id): array
    {
        $order = wc_get_order($order_id);

        if ( ! $order instanceof WC_Order ) {
            wc_add_notice( __('Invalid order.', '1nodes-for-woocommerce'), 'error');

            return [
                'result' => 'fail',
            ];
        }

        /*
         * Validate credentials.
         */
        if ( empty($this->merchant_key) ) {
            wc_add_notice( __('Payment gateway configuration error. Please contact the administrator.', '1nodes-for-woocommerce'), 'error' );

            return [
                'result' => 'fail',
            ];
        }


        /**
         * Amount
         *
         * WooCommerce total is already in the
         * order currency.
         */
        $amount = (string) $order->get_total();

        /**
         * Callback URL
         *
         * This endpoint is called by 1nodes after
         * payment status changes.
         */
        $callback_url = WC()->api_request_url(
            'onenodes_webhook'
        );


        /**
         * Return URL
         *
         * Customer returns here after checkout.
         */
        $return_url = add_query_arg(
            'check_pay_status',
            '1',
            $this->get_return_url($order)
        );

        /**
         * Meta
         *
         * Put whatever information you need here.
         *
         * Keep it JSON serializable.
         */
        $meta = [
            'woocommerce_order_id' => (string) $order->get_id(),
            'currency' => $order->get_currency(),
            'customer_email' => $order->get_billing_email(),
        ];


        /**
         * Request payload.
         */
        $payload = [
            'amount'     => $amount,
            'order_id'   => (string) $order->get_id(),
            'callback'   => $callback_url,
            'return_url' => $return_url,
            'meta'       => $meta,
        ];


        /**
         * JSON encode.
         */
        $json = wp_json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES
        );


        if (false === $json) {

            wc_add_notice(
                __(
                    'Unable to prepare payment request.',
                    '1nodes-for-woocommerce'
                ),
                'error'
            );

            return [
                'result' => 'fail',
            ];
        }



        /**
         * Send request.
         */
        $response = wp_remote_post(
            $this->api_url,
            [
                'sslverify'       => true,
                'timeout'         => 30,
                'connect_timeout' => 10,
                'body'            => $json,
                'headers'         => [
                    'Accept'        => 'application/json',
                    'Content-Type'  => 'application/json',
                    'Authorization' =>
                        'Bearer ' . $this->merchant_key,
                ],
            ]
        );

        
        /**
         * Connection error.
         */
        if (is_wp_error($response)) {

            $order->add_order_note(
                '1nodes connection error: '
                . $response->get_error_message()
            );

            wc_add_notice(
                __(
                    'Connection to payment gateway failed. Please try again.',
                    '1nodes-for-woocommerce'
                ),
                'error'
            );

            return [
                'result' => 'fail',
            ];
        }


        /**
         * HTTP status.
         */
        $http_code = wp_remote_retrieve_response_code($response);


        /**
         * Response body.
         */
        $response_body = wp_remote_retrieve_body($response);


        /**
         * Decode response.
         */
        $decoded = json_decode(
            $response_body,
            true
        );


        /**
         * Invalid response.
         */
        if (!is_array($decoded)) {

            $order->add_order_note(
                sprintf(
                    '1nodes invalid response. HTTP %d. Body: %s',
                    $http_code,
                    $response_body
                )
            );

            wc_add_notice(
                __(
                    'Invalid response from payment gateway.',
                    '1nodes-for-woocommerce'
                ),
                'error'
            );

            return [
                'result' => 'fail',
            ];
        }

        /**
         * API returned failure.
         */
        if (empty($decoded['success'])) {
            $message = !empty($decoded['message'])
                ? sanitize_text_field(
                    (string) $decoded['message']
                )
                : __(
                    'An error occurred during payment processing.',
                    '1nodes-for-woocommerce'
                );


            $order->add_order_note(
                '1nodes payment error: ' . $message
            );

            wc_add_notice(
                $message,
                'error'
            );

            return [
                'result' => 'fail',
            ];
        }

        /**
         * Get checkout URL.
         */
        $checkout_url = $decoded['data']['checkout_url'] ?? '';


        if (empty($checkout_url)) {

            $order->add_order_note(
                '1nodes response did not contain checkout_url.'
            );

            wc_add_notice(
                __(
                    'Payment gateway did not return a checkout URL.',
                    '1nodes-for-woocommerce'
                ),
                'error'
            );

            return [
                'result' => 'fail',
            ];
        }


        /**
         * Save 1nodes checkout URL.
         */
        $order->update_meta_data(
            '_onenodes_checkout_url',
            esc_url_raw($checkout_url)
        );


        /**
         * Save optional payment ID if API sends one.
         */
        if (!empty($decoded['data']['payment_id'])) {

            $order->update_meta_data(
                '_onenodes_payment_id',
                sanitize_text_field(
                    (string) $decoded['data']['payment_id']
                )
            );
        }


        /**
         * Pending.
         */
        $order->update_status(
            'pending',
            __(
                'Awaiting 1nodes cryptocurrency payment confirmation.',
                '1nodes-for-woocommerce'
            )
        );


        /**
         * Save order.
         */
        $order->save();


        /**
         * Reduce stock only once.
         */
        if (!$order->get_meta('_onenodes_stock_reduced')) {

            wc_reduce_stock_levels($order_id);

            $order->update_meta_data(
                '_onenodes_stock_reduced',
                'yes'
            );

            $order->save();
        }

        /**
         * Empty cart.
         */
        if (WC()->cart) {
            WC()->cart->empty_cart();
        }

        /**
         * Redirect customer to 1nodes.
         */
        return [
            'result' => 'success',
            'redirect' => esc_url_raw($checkout_url),
        ];

    }
}