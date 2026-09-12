<?php

/**
 * Plugin Name: 1nodes for woocommerce
 * Description: Cryptocurrency payment gateway for WooCommerce.
 * Version: 1.0.0
 * Author: 1nodes
 * Author URI: https://plugins.1nodes.com/
 * Plugin URI: https://1nodes.com/
 *  License: GPL-2.0-or-later
 *  License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 1nodes-for-woocommerce
 * Domain Path: /languages
 * Requires Plugins: woocommerce
 * Requires PHP: 8.1
 */

defined('ABSPATH') || exit;

define('ONENODES_VERSION', '1.0.0');
define('ONENODES_FILE', __FILE__);
define('ONENODES_PATH', plugin_dir_path(__FILE__));
define('ONENODES_URL', plugin_dir_url(__FILE__));


/**
 * Initialize plugin.
 */
add_action('plugins_loaded', 'onenodes_init_gateway_plugin', 20);

function onenodes_init_gateway_plugin(): void
{
    if ( ! class_exists('WooCommerce') ) {
        return;
    }

    require_once ONENODES_PATH . 'includes/class_gateway.php';
    require_once ONENODES_PATH . 'includes/class_blocks_support.php';
    require_once ONENODES_PATH . 'includes/class_webhook.php';
    require_once ONENODES_PATH . 'includes/class_ajax.php';
    require_once ONENODES_PATH . 'lib/View.php';


    /**
     * Register classic WooCommerce gateway.
     */
    add_filter(
        'woocommerce_payment_gateways',
        function (array $methods): array {

            $methods[] = \OneNodes\Gateway\OneNodes_WC_Gateway::class;

            return $methods;
        }
    );

    /**
     * Register Checkout Block payment method.
     */
    add_action(
        'woocommerce_blocks_payment_method_type_registration',
        function ($payment_method_registry): void {

            if (
                class_exists(
                    '\Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry'
                )
            ) {
                $payment_method_registry->register(
                    new \OneNodes\Gateway\OneNodes_Blocks_Support()
                );
            }
        }
    );


    /**
     * Register 1nodes admin guide.
     */
    add_action(
        'admin_menu',
        'onenodes_register_admin_guide'
    );
}


function onenodes_register_admin_guide(): void
{
    add_submenu_page(
        'woocommerce',
        __('1nodes Guide', '1nodes-for-woocommerce'),
        __('1nodes Guide', '1nodes-for-woocommerce'),
        'manage_woocommerce',
        'onenodes-guide',
        function () {
             \OneNodes\Gateway\lib\View::render('help.php', [], false);
        }
    );
}

add_action(
    'admin_init',
    'onenodes_move_admin_guide_to_bottom',
    PHP_INT_MAX
);

function onenodes_move_admin_guide_to_bottom(): void
{
    global $submenu;

    if (empty($submenu['woocommerce'])) {
        return;
    }

    $guide = null;
    $items = [];

    foreach ($submenu['woocommerce'] as $item) {

        if (
            isset($item[2]) &&
            $item[2] === 'onenodes-guide'
        ) {
            $guide = $item;
            continue;
        }

        $items[] = $item;
    }

    if ($guide === null) {
        return;
    }

    $items[] = $guide;

    $submenu['woocommerce'] = $items;
}

/**
 * Register webhook endpoint.
 */
add_action(
    'woocommerce_api_onenodes_webhook',
    function(): void
    {
        \OneNodes\Gateway\OneNodes_Webhook::handle();
    }
);


add_action(
    'wp_ajax_onenodes_check_payment_status',
    '\OneNodes\Gateway\OneNodes_Ajax::check_payment_status'
);

add_action(
    'wp_ajax_nopriv_onenodes_check_payment_status',
    '\OneNodes\Gateway\OneNodes_Ajax::check_payment_status'
);



add_action(
    'wp_enqueue_scripts',
    'onenodes_enqueue_payment_status_assets'
);

function onenodes_enqueue_payment_status_assets(): void
{
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a read-only GET flag used only to conditionally enqueue assets.
    if ( ! isset($_GET['check_pay_status']) || sanitize_text_field(wp_unslash($_GET['check_pay_status'])) !== '1') {
        return;
    }

    $order_id = absint(
        get_query_var('order-received')
    );

    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    wp_enqueue_script(
        'onenodes-payment-status',
        ONENODES_URL . 'assets/js/payment-status.js',
        ['jquery'],
        ONENODES_VERSION,
        true
    );

    wp_enqueue_style(
        'onenodes-payment-status',
        ONENODES_URL . 'assets/css/payment-status.css',
        [],
        ONENODES_VERSION
    );

    wp_localize_script(
        'onenodes-payment-status',
        'OneNodesPayment',
        [
            'ajax_url'  => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('onenodes_check_payment_status'),
            'order_id'  => $order->get_id(),
            'order_key' => $order->get_order_key(),
            'messages'  => [
                'checking' => __(
                    'Please wait, we are checking your payment status...',
                    '1nodes-for-woocommerce'
                ),

                'timeout' => __(
                    'We are still waiting for payment confirmation.',
                    '1nodes-for-woocommerce'
                ),
            ],
        ]
    );
}


add_action(
    'wp_footer',
    'onenodes_render_payment_status_overlay'
);

function onenodes_render_payment_status_overlay(): void
{
    if (!is_wc_endpoint_url('order-received')) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- This is a read-only GET flag used only to conditionally enqueue assets.
    if (!isset($_GET['check_pay_status']) || sanitize_text_field(wp_unslash($_GET['check_pay_status'])) !== '1') {
        return;
    }

    ?>
    <div id="onenodes-payment-checking" aria-live="polite">
        <div class="onenodes-payment-checking-box">

            <div class="onenodes-payment-spinner" aria-hidden="true"></div>

            <h3 class="onenodes-payment-title">
                <?php
                esc_html_e('Checking payment', '1nodes-for-woocommerce');
                ?>
            </h3>

            <p id="onenodes-payment-message" class="onenodes-payment-message"></p>
        </div>
    </div>
    <?php
}

add_action(
    'admin_enqueue_scripts',
    'onenodes_enqueue_admin_guide_assets'
);

function onenodes_enqueue_admin_guide_assets(string $hook): void
{
    if ($hook !== 'woocommerce_page_onenodes-guide') {
        return;
    }

    wp_enqueue_style(
        'onenodes-admin-guide',
        ONENODES_URL . 'assets/css/admin-guide.css',
        [],
        ONENODES_VERSION
    );
}

register_activation_hook(ONENODES_FILE, function () {
    if (is_multisite() && function_exists('is_network_admin') && is_network_admin()) {
        return;
    }

    update_option('onenodes_do_activation_redirect', 1, false);
});

add_action('admin_init', function () {
    if (!get_option('onenodes_do_activation_redirect')) {
        return;
    }

    delete_option('onenodes_do_activation_redirect');

    if (!is_admin()) return;

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading core flag, no state change.
    if (!empty($_GET['activate-multi'])) return;

    if (!current_user_can('manage_options')) return;

    wp_safe_redirect(admin_url('admin.php?page=onenodes-guide'));
    exit;
});