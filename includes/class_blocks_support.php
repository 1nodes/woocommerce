<?php

namespace OneNodes\Gateway;

use Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType;

defined('ABSPATH') || exit;

final class OneNodes_Blocks_Support extends AbstractPaymentMethodType
{
    /**
     * Payment method ID.
     */
    protected $name = 'onenodes_crypto';


    /**
     * Gateway settings.
     */
    protected $settings = [];


    /**
     * Initialize.
     */
    public function initialize()
    {
        $this->settings = get_option('woocommerce_onenodes_crypto_settings', []);
    }


    /**
     * Is payment method active?
     */
    public function is_active()
    {
        return (
            !empty($this->settings['enabled']) &&
            $this->settings['enabled'] === 'yes' &&
            !empty($this->settings['merchant_key']) &&
            !empty($this->settings['secret_key'])
        );
    }


    /**
     * Register frontend script.
     */
    public function get_payment_method_script_handles(): array
    {
        $handle = 'onenodes-blocks';

        wp_register_script(
            $handle,

            ONENODES_URL . 'assets/js/blocks.js',

            [
                'wc-blocks-registry',
                'wc-settings',
                'wp-element',
            ],

            ONENODES_VERSION,

            true
        );

        return [$handle];
    }


    /**
     * Admin/editor scripts.
     */
    public function get_payment_method_script_handles_for_admin(): array
    {
        return $this->get_payment_method_script_handles();
    }


    /**
     * Data exposed to JavaScript.
     *
     * IMPORTANT:
     * Never expose merchant key or secret key.
     */
    public function get_payment_method_data(): array
    {
        return [

            'title'       => $this->settings['title'] ?? __('Cryptocurrency (1nodes)', '1nodes-crypto-payments-for-woocommerce'),

            'description' => $this->settings['description']
                ?? '',

            'supports'    => [
                'products',
            ],
        ];
    }
}