<?php

/**
 * Plugin Name:       Paystrax
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Paystrax payment plugin for accepting multiple cards.
 * Version:           1.0.5
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Paystrax
 * Author URI:        https://paystrax.com/
 * php version 7.4
 */

defined('ABSPATH') || exit;

/**
 * Paystrax Payment Gateway.
 *
 * Provides a Credit Card Payment.
 *
 * @class   WC_Paystrax_Gateway
 * @extends WC_Payment_Gateway
 * @version 1.0.5
 * @package WooCommerce\Classes\Payment
 * Requires PHP: 7.4
 */

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    return;
}

add_filter('woocommerce_payment_gateways', 'add_paystrax_gateway_class');
/**
 * Add Paystrax gate class in the woocommerce class.
 * 
 * @return null
 */
function add_paystrax_gateway_class($gateways)
{
    $gateways[] = 'WC_Paystrax_Gateway';
    return $gateways;
}

add_action('plugins_loaded', 'initialize_gateway_class', 11);
/**
 * Load the plugin.
 * 
 * @return null
 */
function initialize_gateway_class()
{
    if (class_exists('WC_Payment_Gateway')) {

        class WC_Paystrax_Gateway extends WC_Payment_Gateway
        {
            public $TOKEN, $ENTITYID, $API_Endpoint, $test_mode;

            public $MASTER, $VISA, $AMEX, $DINERS, $GOOGLEPAY, $APPLEPAY, $Language;

            /**
             * Constructor for the gateway.
             */
            public function __construct()
            {

                $this->id = 'paystrax';
                $this->icon = '';
                $this->has_fields = true; // for custom credit card form
                $this->title = __('Paystrax Gateway', 'text-domain');
                $this->method_title = __('Paystrax Gateway', 'text-domain');
                $this->method_description = __('Custom Paystrax payment gateway', 'text-domain'); // payment method description

                $this->supports = array('products', 'refunds');

                // load backend options fields
                $this->init_form_fields();


                // load the settings.
                $this->init_settings();
                $this->title = $this->get_option('title');
                $this->description = $this->get_option('description');
                $this->enabled = $this->get_option('enabled');
                $this->test_mode = 'yes' === $this->get_option('test_mode');
                $this->TOKEN = $this->test_mode ? $this->get_option('test_TOKEN') : $this->get_option('Live_TOKEN');
                $this->ENTITYID = $this->test_mode ? $this->get_option('test_ENTITYID') : $this->get_option('Live_ENTITYID');
                $this->API_Endpoint = $this->test_mode ? $this->get_option('Test_API_URL') : $this->get_option('Live_API_URL');
				$this->GPAY_MID = $this->test_mode ? '' : $this->get_option('Google_merchantId');


                //Payment Brands
                $this->MASTER = 'yes' === $this->get_option('MASTER') ? 'MASTER' : '';
                $this->VISA = 'yes' === $this->get_option('VISA') ? 'VISA' : '';
                $this->AMEX = 'no' === $this->get_option('AMEX') ? '' : 'AMEX';
                $this->DINERS = 'no' === $this->get_option('DINERS') ? '' : 'DINERS';
                $this->GOOGLEPAY = 'no' === $this->get_option('GOOGLEPAY') ? '' : 'GOOGLEPAY';
                $this->APPLEPAY = 'no' === $this->get_option('APPLEPAY') ? '' : 'APPLEPAY';
                $this->Language = $this->get_option('selectLanguage');
                $this->TD_Frictionless = 'no' === $this->get_option('TD_Frictionless') ? '' : 'frictionless';
                $this->TEST_EXTERNAL = 'no' === $this->get_option('TEST_EXTERNAL') ? '' : 'EXTERNAL';
                // Action hook to saves the settings
                if (is_admin()) {
                    add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
                }

                // Action hook to load custom JavaScript
                add_action('wp_enqueue_scripts', array($this, 'payment_gateway_scripts'));
                add_action('admin_enqueue_scripts', array($this, 'add_admin_style'));
                add_action('wp_footer', array($this, 'add_this_script_footer'));
                add_action('wp_head', array($this, 'my_paystraxForm_js'));
            }
            /**
             * Save admin options and upload css file.
             * 
             * @return null
             */
            public function process_admin_options()
            {
                $saved = parent::process_admin_options();
                $file = $_FILES['woocommerce_paystrax_uploadFile'];
                if (isset($file['name']) && !empty($file['name'])) {
                    try {
                        if ($file['type'] == 'text/css' && $file['name'] == 'index.css') {
                            $fileExist = file_exists(__DIR__ . '/style/' . $file['name']);
                            $fileupload = __DIR__ . '/style/' . $file['name'];
                            if ($fileExist) {
                                unlink($fileupload);
                                move_uploaded_file($file['tmp_name'], $fileupload);
                                wp_redirect(admin_url('admin.php?page=wc-settings&tab=checkout&section=paystrax'));
                                exit();
                            } else {
                                move_uploaded_file($file['tmp_name'], $fileupload);
                                wp_redirect(admin_url('admin.php?page=wc-settings&tab=checkout&section=paystrax'));
                                exit();
                            }
                        } else {
                            throw new Exception(__("File " . $file['type'] . " incorrect file type. File name should be 'index.css'  "));
                        }
                    } catch (Exception $e) {
                        die($e->getMessage());
?>
                        <div class="notice notice-error is-dismissible ">
                            <p><?php _e($e->getMessage(), 'sample-text-domain'); ?></p>
                        </div>

                <?php
                    }
                }
                return $saved;
            }
            /**
             * Javascript for paystrax payment form.
             * 
             * @return null
             */
            public function my_paystraxForm_js()
            {
                wp_enqueue_script(
                    'paystraxForm',
                    plugin_dir_url(__FILE__) . 'scripts/paymentFormStyle.js'
                );
            }
            /**
             * Add custom js file in footer.
             * 
             * @return null
             */
            public function add_this_script_footer()
            {
                wp_enqueue_script(
                    'paystrax',
                    plugin_dir_url(__FILE__) . 'scripts/paystrax_style.js'
                );
            }
            /**
             * Add js in admin side.
             * 
             * @return null
             */
            public function add_admin_style()
            {
                wp_register_style(
                    'prefix_bootstrap',
                    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'
                );
                wp_enqueue_style('prefix_bootstrap');
                wp_register_style(
                    'copyandpay_adminstyle',
                    plugin_dir_url(__FILE__)  . 'style/adminStyle.css',
                    false,
                    '1.0.0'
                );
                wp_enqueue_style('copyandpay_adminstyle');
            }
            /**
             * Add js for checkout page.
             * 
             * @return null
             */
            public function payment_gateway_scripts()
            {
                wp_enqueue_style(
                    'copyandpay_style',
                    plugins_url('/style/style.css', __FILE__)
                );
                wp_enqueue_style('paystraxCard_style', plugins_url('/style/index.css', __FILE__));
                wp_register_script(
                    'sweetalert',
                    'https://unpkg.com/sweetalert/dist/sweetalert.min.js',
                    null,
                    null,
                    true
                );
                wp_enqueue_script('sweetalert');
                wp_enqueue_script(
                    'add_jquery',
                    plugins_url('scripts/jquery.min.js', __FILE__)
                );
            }
            /**
             * Initialise Gateway Settings Form Fields.
             * 
             * @return null
             */
            public function init_form_fields()
            {
                include "paymentSettingForm.php";
                $this->form_fields = $form;
            }
            /**
             * Initialize the Form Fields that show on checkout page.
             *
             * @return null
             */
            public function payment_fields()
            {
                ?>
                <button type="button" class="open-modal" data-open="modal1">Click to Pay</button>
            <?php
            }
            /**
             * Show payment form in modal.
             * 
             * @return null
             */
            public function card_after_checkout_form()
            {
                $id = WC()->session->get('Checkout-ID');
				$return_shop_url = wc_get_checkout_url();
            ?>
                <!-- modal body -->
                <div class="modal" id="modal1">
                    <div class="modal-dialog">
                        <header class="modal-header">
                            <button class="close-modal" aria-label="close modal" data-close>✕</button>
                        </header>
                        <section class="modal-content">
						<script src="<?php echo $this->API_Endpoint ?>paymentWidgets.js?checkoutId=<?php echo $id ?>"></script>					                    		 	
                          <form action=<?php echo $return_shop_url ?> class="paymentWidgets" createCheckout ="<?php echo $this->VISA . ' ' . $this->MASTER . ' ' . $this->AMEX . ' ' . $this->GOOGLEPAY . ' ' . $this->APPLEPAY . ' ' . $this->DINERS ?>"></form>
                        </section>
                        <p id="debug-message"></p>
                    </div>
                </div>
            <?php
            }
            /**
             * Call prepare the checkout API.
             *
             * @return null
             */
            public function on_checkout_prepare_the_checkout_ID($order_id)
            {
            ?>
                <div id='payment_success_msg'></div>
                <script>
                    let language = "<?php echo $this->Language ?>";
                    let entityId = "<?php echo $this->ENTITYID ?>";
					let merchantId = "<?php echo $this->GPAY_MID ?>";
                    localStorage.setItem('entityId', entityId);
                    localStorage.setItem('language', language);
                    localStorage.setItem('merchantId', merchantId);
                </script>
                <?php

                global $woocommerce , $post;
                
                $items            = $woocommerce->cart->get_cart();
                $cart_subtotal    = $woocommerce->cart->subtotal;
                $shipping_total = WC()->cart->get_shipping_total() + WC()->cart->get_shipping_tax();
                $total = round($cart_subtotal + $shipping_total,2);
                $currency_code    = get_woocommerce_currency();
                $billing_phone    = WC()->customer->get_billing_phone();
                
                $latest_order_id = $this->get_last_order_id(); // Last order ID
                $new_order_id = $latest_order_id + 1;

				if ($this->test_mode) {
                $args = array(
                    'method' => 'post',
                    'headers'     => array(
                        'Authorization' => 'Bearer ' . $this->TOKEN,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ),
                    'body' => array(
                        'entityId' => $this->ENTITYID,
                        'amount'   => $total,
                        'customer.phone' =>  $billing_phone,
                        'currency' => $currency_code,
                        'paymentType' => 'DB',
                        'merchantTransactionId' => $new_order_id,
                        'testMode' => $this->TEST_EXTERNAL,
                        'customParameters' => array(
                            'SHOPPER_PaymentId' => $new_order_id,
                            '3DS2_enrolled' => 'true',
                            '3DS2_flow' => $this->TD_Frictionless
                        )

                    )
                );
				}
				else {
				    $args = array(
                    'method' => 'post',
                    'headers'     => array(
                        'Authorization' => 'Bearer ' . $this->TOKEN,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ),
                    'body' => array(
                        'entityId' => $this->ENTITYID,
                        'amount'   =>  $total,
                        'customer.phone' =>  $billing_phone,
						// PT : uncommented above line and commented next 2 
                        //'requiredBillingContactFields' => array('email','name','phone'),
                        //'submitOnPaymentAuthorized' => array('customer'),
                        'currency' => $currency_code,
                        'paymentType' => 'DB',
                        'merchantTransactionId' => $new_order_id,
                        'customParameters' => array(
                            'SHOPPER_PaymentId' => $new_order_id
                        )
					)
				);
				}
				
 				//print_r ($args);
				$this->custom_logs('inside on_checkout_prepare_the_checkout_ID: ' . $args);
                $response = wp_remote_post($this->API_Endpoint . 'checkouts', $args);
                $this->custom_logs($args);
                if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                    error_log(print_r($response, true));
                    return new WP_Error(
                        'error',
                        __('Create Checkout Id failed.', 'woocommerce')
                    );
                }
                $responseBody = wp_remote_retrieve_body($response);
                $data = json_decode($responseBody, true);
                $this->custom_logs($data);

                //checkout id
                $checkoutID = $data['id'];

                $this->custom_logs('==== checkout id =' . $checkoutID);
                WC()->session->set('Checkout-ID', $checkoutID);
				$this->custom_logs('==== endpoint url =' . $this->API_Endpoint);
                $this->custom_logs('==== end prepare the checkout=========');
            }
            /**
             * Get the payment Response after payment.
             * 
             * @return null
             */
            public function get_payment_response_after_Payment()
            {
                $this->custom_logs('start payment_response_afterPay');
                if (isset($_GET['id'])) {
                    $checkoutID = $_GET['id'];
                    $url = $this->API_Endpoint . "checkouts/" . $checkoutID . "/payment";
                    $args = array(
                        'headers' => array(
                            'Authorization' => 'Bearer ' . $this->TOKEN,
                            'Content-Type'  => 'application/x-www-form-urlencoded'
                        ),
                        'body' => array(
                            'entityId' => $this->ENTITYID
                        )
                    );

                    $response = wp_remote_get($url, $args);

                    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                        error_log(print_r($response, true));
                        return new WP_Error('error', __('Payment failed.', 'woocommerce'));
                    }
                    $responseBody = wp_remote_retrieve_body($response);
                    $data = json_decode($responseBody, true);

                    $this->custom_logs(array('Response from Payment API', $data));

                    if (isset($data['id']) && preg_match('/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0)/', $data['result']['code']) === 1) {
                        $referencedPaymentId = $data['id'];
                        WC()->session->set('referencedPaymentId ',  $referencedPaymentId);
                        WC()->session->set('statusCode', $data['result']['code']);
                        $msg = [
                            'msg' => 'Got successful Payment Response ',
                            'referencedPaymentId' => $referencedPaymentId,
                            'statusCode' => $data['result']['code']
                        ];

                ?>
                        <script>
                            jQuery('#place_order').removeClass('hide');
                            jQuery('#payment_method_paystrax').prop('checked', true);
                            jQuery('#payment_success_msg').text('Payment is Successful.');
                            jQuery('#payment_success_msg').addClass('woocommerce-message');

                            if (localStorage.getItem("shipping_address") == 'true') {
                                $('#ship-to-different-address').find('input').prop('checked', true);
                            }

                            //set checkout details when payment is successful.
                            var details = JSON.parse(localStorage.getItem("customerDetatails"));
                            var customer_details = document.forms.checkout.children.customer_details;                           
                            if (details) {
                                for (var key in details) {
                                    if (key !== 'requiredShippingDetails') {
                                        customer_details.querySelector('#' + key).value = details[key];
                                    }
                                    if (key == 'requiredShippingDetails') {
                                        for (var key1 in details[key]) {
                                            customer_details.querySelector('#' + key1).value = details[key][key1];
                                        }
                                    }
                                }
                            }
                            setInterval(() => {
                                jQuery("#place_order").trigger("click");
                                localStorage.setItem("shipping_address", '');
                            }, 3000)
                        </script>

                    <?php
                    } else {
                        $msg = [
                            'msg' => 'Got Un-successful Payment Response ',
                            'referencedPaymentId' => $data['id'],
                            'statusCode' => $data['result']['code']
                        ];
                    ?>
                        <script>
                            jQuery('#place_order').removeClass('hide');
                            jQuery('#payment_method_paystrax').prop('checked', true);
                            jQuery('#payment_success_msg').text('Payment is Not Successful ! , \" <?php echo $data['result']['description'] ?>"\.');
                            jQuery('#payment_success_msg').addClass('woocommerce-message');
                        </script>
<?php
                    }
                    $this->custom_logs($msg);			
                    $this->custom_logs('end payment_response_afterPay');
                }
            }
            /**
             * Process the payments here
             *
             * @param int $order_id Order ID.
             * 
             * @return NULL|array
             */
            public function process_payment($order_id)
            {
                global $woocommerce, $order_number;
                $order = new WC_Order($order_id);
                $this->custom_logs('start payment process line - 493');
                $transactionID = WC()->session->get('referencedPaymentId');
                $statusCode = WC()->session->get('statusCode');

                $order->set_transaction_id($transactionID);
                $order->save();
                $order_number = $order->get_id();
                $this->custom_logs('ORDER NUMBER: ' . $order_number);
                $this->custom_logs($order);
                $this->custom_logs('payment status ' .  $statusCode);
                $order->update_status('pending', __('paystrax', 'woocommerce'));

                //based on the response from the payment gateway, set the order status to processing or completed if successful.
                if (preg_match('/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0)/', $statusCode) === 1 && WC()->session->get('referencedPaymentId')) {
                    $order->update_status('processing', __('paystrax', 'woocommerce'));
                    $order->add_order_note(
                        __('credit card payment completed', 'woocommerce')
                    );
                    $woocommerce->cart->empty_cart();
                    $order->reduce_order_stock();
                    $order->add_order_note(
                        __('You have approved this order on', 'woocommerce') .
                            ' ' . date_i18n('F j, Y'),
                        true
                    );

                    $this->custom_logs('end payment process and order placed..');
                    WC()->session->set('statusCode', null); //remove success payment status after the order placed.

                    return array(
                        'result' => 'success',
                        'redirect' => $this->get_return_url($order) // Return thankyou redirect
                    );
                } else {

                    $order->update_status('failed', __('paystrax', 'woocommerce'));
                    wc_add_notice(__('Cart Process Declined, Try again.'), 'error');
                    return;
                }
            }
            /**
             * Can the order be refunded via Paystrax?
             *
             * @param WC_Order $order Order object.
             * 
             * @return bool
             */
            public function can_refund_order($order)
            {
                if ($order instanceof WC_Order && method_exists($order, 'get_transaction_id')) {
                    $this->custom_logs('can refund yes');
                    return $order && $order->get_transaction_id();
                }
                $this->custom_logs('can refund no');
                return false;
            }
            /**
             * Process a refund manually if supported.
             *
             * @param int    $order_id Order ID.
             * @param float  $amount   Refund amount.
             * @param string $reason   Refund reason.
             * 
             * @return bool|WP_Error
             */
            public function process_refund($order_id, $amount = null, $reason = '')
            {
                $this->custom_logs('----------------inside process refund-----------------------');
                if (function_exists('wc_get_order')) {
                    $order = wc_get_order($order_id);
                } else {
                    $order = new WC_Order($order_id);
                }

                if (!$this->can_refund_order($order)) {
                    $this->custom_logs('refund failed');
                    return new WP_Error(
                        'error',
                        __('Refund failed.', 'woocommerce')
                    );
                }
                if ('refunded' == $order->get_status()) {
                    return new WP_Error(
                        'wc-order',
                        __('Order has been already refunded', 'woocommerce')
                    );
                }

                if ('failed' == $order->get_status()) {
                    $order->add_order_note(
                        _(
                            "Refund of amount " . $amount
                                . " can not be done, As payment is still pending. 
                         wc_gateway_paystrax"
                        )
                    );
                    return new WP_Error(
                        'wc-order',
                        __(
                            'Order status is failed, Refund can not be possible',
                            'woocommerce'
                        )
                    );
                }
                if ('completed' == $order->get_status()) {
                    $order->add_order_note(
                        _(
                            "Refund of amount " . $amount
                                . " can not be done, As order Status is  'completed'. 
                             wc_gateway_paystrax"
                        )
                    );
                    return new WP_Error(
                        'wc-order',
                        __(
                            'Order status is "completed", So, Refund can not be possible',
                            'woocommerce'
                        )
                    );
                }

                $success = $this->init_refunds($order, $amount, $order_id);
                if ($success) {
                    //$order->update_status('refunded', __('paystrax', 'woocommerce'));
                    $this->custom_logs('Refund successful from process_refund');
                    $order->add_order_note(
                        _(
                            "Refund of amount " . $amount
                                . " sent to gateway. Reason: " . $reason
                                . " wc_gateway_paystrax"
                        )
                    );
                    return true;
                }
                $this->custom_logs('refund not successful');
                $order->add_order_note(
                    _(
                        'Failed to send refund of amount -'
                            . $amount . ' to gateway.' . ' wc_gateway_paystrax'
                    )
                );
                return false;
            }
            /**
             * Call refund API.
             * 
             * @param object $order    Customer order.
             * @param float  $amount   Refund amount.
             * @param int    $order_id Order ID.
             * 
             * @return bool|WP_Error
             */
            public function init_refunds($order, $amount, $order_id)
            {
                $this->custom_logs('====start create refund, amount====' . $amount);
                $orderTransactionID = $order->get_transaction_id();
                if (method_exists($order, 'get_currency')) {
                    $currency = $order->get_currency();
                } else {
                    $currency = $order->get_order_currency();
                }
                $this->custom_logs(
                    '==inside create refund, referencedPaymentId== '
                        . $orderTransactionID
                );

                //Refund API call.
                $url = $this->API_Endpoint . "payments/" . $orderTransactionID;

				if ($this->test_mode) {
                $args = array(
                    // 'method' => 'post',
                    'headers'     => array(
                        'Authorization' => 'Bearer ' . $this->TOKEN,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ),
                    'body' => array(
                        'entityId' => $this->ENTITYID,
                        'amount'   => $amount,
                        'currency' => $currency,
                        'paymentType' => 'RF',
                        'testMode' => $this->TEST_EXTERNAL,
                        'customParameters' => array(
                            '3DS2_enrolled' => 'true',
                            '3DS2_flow' => $this->TD_Frictionless
                        )
                    )
                );
				}
				else {
				    $args = array(
                    // 'method' => 'post',
                    'headers'     => array(
                        'Authorization' => 'Bearer ' . $this->TOKEN,
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ),
                    'body' => array(
                        'entityId' => $this->ENTITYID,
                        'amount'   => $amount,
                        'currency' => $currency,
                        'paymentType' => 'RF'
					)
				);
				}
                $response = wp_remote_post($url, $args);
                $this->custom_logs($response);
                if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                    error_log(print_r($response, true));
                    $this->custom_logs('Refund Failed');
                    return new WP_Error('error', $response->get_error_message());
                }
                $responseBody = wp_remote_retrieve_body($response);
                $data = json_decode($responseBody, true);
                $logdata = [
                    'msg' => 'Refund api response',
                    'data' => $data
                ];

                $this->custom_logs($logdata);

                if (preg_match('/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0)/', $data['result']['code']) === 1) {
                    $this->custom_logs('Refunded successfully');
                    return true;
                } else {
                    $this->custom_logs(
                        'You do not have enough amount to refund, status code is :-'
                            . $response['result']['code']
                    );
                    $this->custom_logs(
                        ' Refund Failed: ' .
                            $data['result']['description']
                    );
                    return false;
                }
                $this->custom_logs('====end create refund====');
                return false;
            }
            /**
             * Schedule cron job.
             *
             * @param float $schedules time interval.
             * 
             * @return array
             */
            function my_schedules($schedules)
            {
                $schedules['5min'] = array(
                    'interval' => 300,
                    'display' => __('Once every 5 minutes')
                );
                return $schedules;
            }
            /**
             * Refund_payment_to_customer.
             * 
             * @return void
             */
            public function refund_payment_to_customer()
            {
                $this->custom_logs('cron testing from refund_order_from_customer');
                $custom_query_args = array(
                    "fields" => "ids",
                    "post_type" => "shop_order",
                    "post_status" => array('wc-cancelled'),
                    "posts_per_page" => "-1",
                    "offset" => "0",
                    "order" => "DESC"
                );

                $debugQuery = new WP_Query($custom_query_args);
                $orderIDs  = $debugQuery->posts;               

                foreach ($orderIDs as $key => $orderID) {
                    $order = wc_get_order($orderID);
                    $order_items = $order->get_items();
                    $orderTotal = $order->get_total();
                    $shipping_total = $order->get_shipping_total();
                    $shipping_tax   = $order->get_shipping_tax();
                    $order_refunded_amount = $order->get_total_refunded();
                    $payment_method_title = $order->get_payment_method_title();
                    $installed_payment_methods =  WC()->payment_gateways()->get_available_payment_gateways();

                    $amount_to_be_refunded = $orderTotal - $order_refunded_amount - $shipping_total - $shipping_tax;
                    if ($payment_method_title == "Credit Card") {
                        $this->refund_cornjob(
                            $orderID,
                            $amount_to_be_refunded,
                            $reason = ''
                        );
                    }
                }
            }
            /**
             * Call refund api in refund_cornjob function.
             *
             * @param int    $order_id Order  ID.
             * @param float  $amount   Refund amount.
             * @param string $reason   Refund reason.
             * 
             * @return bool|WP_Error
             */
            public function refund_cornjob($order_id, $amount, $reason = '')
            {
                $this->custom_logs('====start refund cron job, amount====' . $amount);

                if (function_exists('wc_get_order')) {
                    $order = wc_get_order($order_id);
                } else {
                    $order = new WC_Order($order_id);
                }

                if (!$this->can_refund_order($order)) {
                    $this->custom_logs('refund failed');
                    return new WP_Error(
                        'error',
                        __('Refund failed.', 'woocommerce')
                    );
                }
                if ('refunded' == $order->get_status()) {
                    $this->custom_logs('Order has been already refunded');
                    return new WP_Error(
                        'wc-order',
                        __('Order has been already refunded', 'woocommerce')
                    );
                }

                if ('failed' == $order->get_status()) {
                    $this->custom_logs('Order status is failed, Refund can not be possible');
                    $order->add_order_note(
                        _(
                            "Refund of amount " . $amount
                                . " can not be done, As payment is still pending.  
                                wc_gateway_paystrax"
                        )
                    );
                    return new WP_Error(
                        'wc-order',
                        __(
                            'Order status is failed, Refund can not be possible',
                            'woocommerce'
                        )
                    );
                }
                $orderTransactionID = $order->get_transaction_id();

                if (method_exists($order, 'get_currency')) {
                    $currency = $order->get_currency();
                } else {
                    $currency = $order->get_order_currency();
                }
                $this->custom_logs('====inside CRON REFUND, referencedPaymentId==== ' . $orderTransactionID);

                //Refund API call.
                $url = $this->API_Endpoint . "payments/" . $orderTransactionID;
				if ($this->test_mode) 
				{
					$args = array(
						'headers'     => array(
							'Authorization' => 'Bearer ' . $this->TOKEN,
							'Content-Type' => 'application/x-www-form-urlencoded'
						),
						'body' => array(
							'entityId' => $this->ENTITYID,
							'amount'   => $amount,
							'currency' =>  $currency,
							'paymentType' => 'RF',
							'testMode' => $this->TEST_EXTERNAL,
							'customParameters' => array(
//                            'SHOPPER_PaymentId' => $this->Order_id,
								'3DS2_enrolled' => 'true',
                            '3DS2_flow' => $this->TD_Frictionless
							)
						)
					);
				}
				else {
					$args = array(
						'headers'     => array(
							'Authorization' => 'Bearer ' . $this->TOKEN,
							'Content-Type' => 'application/x-www-form-urlencoded'
						),
						'body' => array(
							'entityId' => $this->ENTITYID,
							'amount'   => $amount,
							'currency' =>  $currency,
							'paymentType' => 'RF'
						)
					);
				}

                $response = wp_remote_post($url, $args);
                $this->custom_logs(wp_remote_retrieve_response_code($response));

                if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
                    error_log(print_r($response, true));
                    $this->custom_logs('Refund Failed..' . $url);
                    return new WP_Error('error', $response->get_error_message());
                }
                $responseBody = wp_remote_retrieve_body($response);
                $data = json_decode($responseBody, true);
                $this->custom_logs('CRON refund api response');
                $data = json_decode($responseBody, true);
                $logdata = [
                    'msg' => 'CRON Refund api response',
                    'data' => $url . ' : ' . $data
                ];

                $this->custom_logs($logdata);

                if (preg_match('/^(000.000.|000.100.1|000.[36]|000.400.[1][12]0)/', $data['result']['code']) === 1) {

                    $order->update_status('refunded', __('paystrax', 'woocommerce'));
                    $order->add_order_note(
                        __($data['result']['description'] . '. Refunded amount is ' . $amount, 'woocommerce') .
                            ' ' . date_i18n('F j, Y'),
                        true
                    );
                    $this->custom_logs('Refunded successfully,cron');
                    return true;
                } else {
                    $order->add_order_note(
                        __($data['result']['description'], 'woocommerce') .
                            ' ' . date_i18n('F j, Y'),
                        true
                    );
                    return false;
                }
                $this->custom_logs('====end create refund====');
                return false;
            }
            /**
             * Create custom log file.
             * 
             * @param string|array $message Log message.
             * 
             * @return void
             */
            public function custom_logs($message)
            {
                $upload = wp_upload_dir();
                $upload_dir = $upload['basedir'];
                if (is_array($message)) {
                    $message = json_encode($message);
                }
                $pluginlog = $upload_dir . '/wc-logs/debug-' . date('Y-m-d') . '.log';
                $file = fopen($pluginlog, "a");
                fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $message);
                fclose($file);
            }
            /**
             * Init function called from init hook .
             * 
             * @return void
             */
            public static function init()
            {
                $self = new self();
                add_action('wp_loaded', array($self, 'on_loaded'));
            }
            /**
             * Function called on wp_loaded hook.
             * 
             * @return void
             */
            public function on_loaded()
            {
                add_filter('cron_schedules', array($this, 'my_schedules'));
                if (!wp_next_scheduled('refund_payment_cron')) {
                    wp_schedule_event(time(), '5min', 'refund_payment_cron');
                    $this->custom_logs('cron schedule , 5min');
                }
                add_action(
                    'refund_payment_cron',
                    array($this, 'refund_payment_to_customer')
                );
                add_action(
                    'woocommerce_after_checkout_form',
                    array($this, 'card_after_checkout_form')
                );
                add_action(
                    'woocommerce_before_checkout_form',
                    array($this, 'on_checkout_prepare_the_checkout_ID')
                );
                add_action(
                    'woocommerce_checkout_after_order_review',
                    array($this, 'get_payment_response_after_Payment')
                );
            }
            /**
             * Here is a custom function that will return the last order ID:
             *
             *
            **/
            public function get_last_order_id(){
                global $wpdb;
                $statuses = array_keys(wc_get_order_statuses());
                $statuses = implode( "','", $statuses );

                // Getting last Order ID (max value)
                $results = $wpdb->get_col( "
                    SELECT MAX(ID) FROM {$wpdb->prefix}posts
                    WHERE post_type LIKE 'shop_order'
                    AND post_status IN ('$statuses')
                " );
                return reset($results);
            }

        }
        add_action('init', array('WC_Paystrax_Gateway', 'init'));
        include "webhookdatastore.php";
    }

}
