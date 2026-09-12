(function ($) {
    'use strict';

    let attempts        = 0;
    const maxAttempts   = 60;
    const checkInterval = 4000;

    function checkPaymentStatus() {

        if (attempts >= maxAttempts) {
            $('#onenodes-payment-checking').removeClass('is-active');

            $('#onenodes-payment-message').text(
                OneNodesPayment.messages.timeout
            );

            return;
        }

        attempts++;

        $.ajax({
            url: OneNodesPayment.ajax_url,

            type: 'POST',

            dataType: 'json',

            data: {
                nonce: OneNodesPayment.nonce,
                action: 'onenodes_check_payment_status',
                order_id: OneNodesPayment.order_id,
                order_key: OneNodesPayment.order_key
            },

            success: function (response) {

                if (
                    response.success &&
                    response.data &&
                    response.data.paid
                ) {
                    /*
                     * Payment confirmed.
                     *
                     * Remove check_pay_status from URL
                     * and reload the order received page.
                     */
                    const url = new URL(
                        window.location.href
                    );

                    url.searchParams.delete(
                        'check_pay_status'
                    );

                    window.location.replace(
                        url.toString()
                    );

                    return;
                }

                setTimeout(
                    checkPaymentStatus,
                    checkInterval
                );
            },

            error: function () {

                /*
                 * Don't stop polling because of a
                 * temporary AJAX/network error.
                 */
                setTimeout(
                    checkPaymentStatus,
                    checkInterval
                );
            }
        });
    }


    /*
     * Start checking only when this script
     * has been loaded.
     */
    $(function () {

        if (
            typeof OneNodesPayment === 'undefined' ||
            !OneNodesPayment.order_id ||
            !OneNodesPayment.order_key
        ) {
            return;
        }

        /*
         * Show payment checking overlay.
         */
        $('#onenodes-payment-checking')
            .addClass('is-active');

        $('#onenodes-payment-message').text(
            OneNodesPayment.messages.checking
        );

        checkPaymentStatus();
    });

})(jQuery);