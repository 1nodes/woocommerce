<?php

defined('ABSPATH') || exit;

$settings_url = admin_url(
    'admin.php?page=wc-settings&tab=checkout&section=onenodes_crypto'
);

$register_url = 'https://1nodes.com/';
?>

<div class="wrap onenodes-guide">

    <div class="onenodes-hero">

        <div class="onenodes-hero-content">

            <div class="onenodes-badge">
                1NODES
            </div>

            <h1>
                <?php
                esc_html_e(
                    '1nodes Payment Gateway',
                    'onenodes'
                );
                ?>
            </h1>

            <p>
                <?php
                esc_html_e(
                    'Accept cryptocurrency payments on your WooCommerce store with 1nodes.',
                    'onenodes'
                );
                ?>
            </p>

        </div>

        <div class="onenodes-hero-action">

            <a
                    href="<?php echo esc_url($register_url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="onenodes-button onenodes-button-light"
            >
                <?php
                esc_html_e(
                    'Open 1nodes',
                    'onenodes'
                );
                ?>

                <span>↗</span>
            </a>

        </div>

    </div>


    <div class="onenodes-content">

        <div class="onenodes-intro">

            <h2>
                <?php
                esc_html_e(
                    'Get started in a few simple steps',
                    'onenodes'
                );
                ?>
            </h2>

            <p>
                <?php
                esc_html_e(
                    'Follow the steps below to connect your WooCommerce store to 1nodes.',
                    'onenodes'
                );
                ?>
            </p>

        </div>


        <!-- STEP 1 -->

        <div class="onenodes-step">

            <div class="onenodes-step-number">
                01
            </div>

            <div class="onenodes-step-content">

                <div class="onenodes-step-header">

                    <div>

                        <h3>
                            <?php
                            esc_html_e(
                                'Create your 1nodes account',
                                'onenodes'
                            );
                            ?>
                        </h3>

                        <p>
                            <?php
                            esc_html_e(
                                'Create an account and access your 1nodes merchant dashboard.',
                                'onenodes'
                            );
                            ?>
                        </p>

                    </div>

                    <span class="onenodes-step-icon">
                        ↗
                    </span>

                </div>

                <a
                        href="<?php echo esc_url($register_url); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="onenodes-button onenodes-button-primary"
                >
                    <?php
                    esc_html_e(
                        'Create 1nodes Account',
                        'onenodes'
                    );
                    ?>

                    <span>↗</span>
                </a>

            </div>

        </div>


        <!-- STEP 2 -->

        <div class="onenodes-step">

            <div class="onenodes-step-number">
                02
            </div>

            <div class="onenodes-step-content">

                <h3>
                    <?php
                    esc_html_e(
                        'Get your API credentials',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'From your 1nodes merchant dashboard, create or select a merchant and copy your API credentials.',
                        'onenodes'
                    );
                    ?>
                </p>


                <div class="onenodes-credentials">

                    <div class="onenodes-credential">

                        <div class="onenodes-credential-icon">
                            M
                        </div>

                        <div>

                            <strong>
                                <?php
                                esc_html_e(
                                    'Merchant Key',
                                    'onenodes'
                                );
                                ?>
                            </strong>

                            <span>
                                <?php
                                esc_html_e(
                                    'Your merchant identifier used to connect your store to 1nodes.',
                                    'onenodes'
                                );
                                ?>
                            </span>

                        </div>

                    </div>


                    <div class="onenodes-credential">

                        <div class="onenodes-credential-icon">
                            S
                        </div>

                        <div>

                            <strong>
                                <?php
                                esc_html_e(
                                    'Secret Key',
                                    'onenodes'
                                );
                                ?>
                            </strong>

                            <span>
                                <?php
                                esc_html_e(
                                    'Your private key used to authenticate payment requests and webhooks.',
                                    'onenodes'
                                );
                                ?>
                            </span>

                        </div>

                    </div>

                </div>

                <div class="onenodes-warning">

                    <span class="onenodes-warning-icon">
                        !
                    </span>

                    <p>
                        <?php
                        esc_html_e(
                            'Keep your Secret Key private. Never share it publicly or expose it in frontend code.',
                            'onenodes'
                        );
                        ?>
                    </p>

                </div>

            </div>

        </div>


        <!-- STEP 3 -->

        <div class="onenodes-step">

            <div class="onenodes-step-number">
                03
            </div>

            <div class="onenodes-step-content">

                <h3>
                    <?php
                    esc_html_e(
                        'Open WooCommerce payment settings',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'Go to WooCommerce → Settings → Payments and open the 1nodes payment gateway.',
                        'onenodes'
                    );
                    ?>
                </p>

                <div class="onenodes-path">

                    <span>WooCommerce</span>

                    <b>›</b>

                    <span>Settings</span>

                    <b>›</b>

                    <span>Payments</span>

                    <b>›</b>

                    <strong>1nodes</strong>

                </div>

                <a
                        href="<?php echo esc_url($settings_url); ?>"
                        class="onenodes-button onenodes-button-primary"
                >
                    <?php
                    esc_html_e(
                        'Open 1nodes Settings',
                        'onenodes'
                    );
                    ?>

                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- STEP 4 -->

        <div class="onenodes-step">

            <div class="onenodes-step-number">
                04
            </div>

            <div class="onenodes-step-content">

                <h3>
                    <?php
                    esc_html_e(
                        'Enter your credentials',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'Enter the Merchant Key and Secret Key from your 1nodes dashboard and save the settings.',
                        'onenodes'
                    );
                    ?>
                </p>

                <div class="onenodes-fields">

                    <div class="onenodes-field">

                        <span class="onenodes-check">
                            ✓
                        </span>

                        <div>

                            <strong>
                                <?php
                                esc_html_e(
                                    'Merchant Key',
                                    'onenodes'
                                );
                                ?>
                            </strong>

                            <small>
                                <?php
                                esc_html_e(
                                    'Paste your Merchant Key',
                                    'onenodes'
                                );
                                ?>
                            </small>

                        </div>

                    </div>


                    <div class="onenodes-field">

                        <span class="onenodes-check">
                            ✓
                        </span>

                        <div>

                            <strong>
                                <?php
                                esc_html_e(
                                    'Secret Key',
                                    'onenodes'
                                );
                                ?>
                            </strong>

                            <small>
                                <?php
                                esc_html_e(
                                    'Paste your Secret Key',
                                    'onenodes'
                                );
                                ?>
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- STEP 5 -->

        <div class="onenodes-step onenodes-step-last">

            <div class="onenodes-step-number">
                05
            </div>

            <div class="onenodes-step-content">

                <h3>
                    <?php
                    esc_html_e(
                        'Enable 1nodes payments',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'Enable the gateway, save your settings, and 1nodes will be available as a payment method during checkout.',
                        'onenodes'
                    );
                    ?>
                </p>

                <a
                        href="<?php echo esc_url($settings_url); ?>"
                        class="onenodes-button onenodes-button-primary"
                >
                    <?php
                    esc_html_e(
                        'Configure 1nodes',
                        'onenodes'
                    );
                    ?>

                    <span>→</span>
                </a>

            </div>

        </div>


        <!-- SUCCESS -->

        <div class="onenodes-success">

            <div class="onenodes-success-icon">
                ✓
            </div>

            <div>

                <h3>
                    <?php
                    esc_html_e(
                        'You are ready to accept crypto payments',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'Once your credentials are saved and the gateway is enabled, customers can pay using supported cryptocurrencies through 1nodes.',
                        'onenodes'
                    );
                    ?>
                </p>

            </div>

        </div>


        <!-- HELP -->

        <div class="onenodes-help">

            <div>

                <span class="onenodes-help-label">
                    1NODES SUPPORT
                </span>

                <h3>
                    <?php
                    esc_html_e(
                        'Need help?',
                        'onenodes'
                    );
                    ?>
                </h3>

                <p>
                    <?php
                    esc_html_e(
                        'If you have any questions about your Merchant Key, Secret Key, or payment integration, contact the 1nodes support team.',
                        'onenodes'
                    );
                    ?>
                </p>

            </div>

            <a
                    href="<?php echo esc_url($register_url); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="onenodes-button onenodes-button-dark"
            >
                <?php
                esc_html_e(
                    'Visit 1nodes',
                    'onenodes'
                );
                ?>

                <span>↗</span>
            </a>

        </div>

    </div>

</div>