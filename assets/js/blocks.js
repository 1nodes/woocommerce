(function () {
    'use strict';

    if (
        !window.wc ||
        !window.wc.wcBlocksRegistry ||
        !window.wc.wcSettings ||
        !window.wp ||
        !window.wp.element
    ) {
        return;
    }

    const {
        registerPaymentMethod
    } = window.wc.wcBlocksRegistry;


    const {
        getSetting
    } = window.wc.wcSettings;


    const {
        createElement,
        Fragment
    } = window.wp.element;


    const settings = getSetting(
        'onenodes_crypto_data',
        {}
    );


    /**
     * Payment method label.
     */
    const Label = function (props) {

        const PaymentMethodLabel =
            props.components &&
            props.components.PaymentMethodLabel;

        const title =
            settings.title ||
            'Cryptocurrency (1nodes)';


        if (PaymentMethodLabel) {

            return createElement(
                PaymentMethodLabel,
                {
                    text: title
                }
            );
        }


        return createElement(
            'span',
            null,
            title
        );
    };


    /**
     * Payment method content.
     */
    const Content = function () {

        const description =
            settings.description ||
            '';


        return createElement(
            'div',
            {
                className: 'onenodes-payment-description'
            },
            description
        );
    };


    /**
     * Editor content.
     */
    const Edit = function () {

        return createElement(
            Content
        );
    };


    /**
     * Register payment method.
     *
     * WooCommerce will send:
     *
     * payment_method = onenodes_crypto
     *
     * to the Checkout Store API.
     *
     * WooCommerce then invokes the existing
     * WC_Payment_Gateway::process_payment().
     */
    registerPaymentMethod({

        name: 'onenodes_crypto',

        label: createElement(
            Label
        ),

        content: createElement(
            Content
        ),

        edit: createElement(
            Edit
        ),

        ariaLabel:
            settings.title ||
            'Cryptocurrency (1nodes)',

        canMakePayment: function () {
            return true;
        },

        supports: {

            features:
                settings.supports ||
                ['products'],
        }
    });

})();