<?php

/**
 * The list of Paystrax Payment Gateway setting fields.
 *
 */
$form = array(
    'enabled' => array(
        'title'       => __('Enable/Disable', 'text-domain'),
        'label'       => __('Enable Paystrax Gateway', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('This enable the Paystrax Gateway which allow to accept payment through creadit card.', 'text-domain'),
        'default'     => 'no',
        'desc_tip'    => true,
		'class'       => 'wppd-ui-toggle'
    ),
    'title' => array(
        'title'       => __('Title', 'text-domain'),
        'type'        => 'text',
        'description' => __('This controls the title which the user sees during checkout.', 'text-domain'),
        'default'     => __('Credit Card', 'text-domain'),
        'desc_tip'    => true,
    ),
    'description' => array(
        'title'       => __('Description', 'text-domain'),
        'type'        => 'textarea',
        'description' => __('This controls the description which the user sees during checkout.', 'text-domain'),
        'default'     => __('Pay with your credit card via our super-cool Paystrax Gateway gateway.', 'text-domain'),
    ),
    'test_mode' => array(
        'title'       => __('Test mode', 'text-domain'),
        'label'       => __('Enable Test Mode', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Place the Paystrax Gateway in test mode using test API keys.', 'text-domain'),
        'default'     => 'yes',
        'desc_tip'    => true,
		'class'       => 'wppd-ui-toggle'
    ),

    'test_TOKEN' => array(
        'title'       => __('Test TOKEN', 'text-domain'),
        'type'        => 'text'
    ),
    'test_ENTITYID' => array(
        'title'       => __('Test ENTITY ID', 'text-domain'),
        'type'        => 'text',
    ),
    'Live_TOKEN' => array(
        'title'       => __('Live TOKEN', 'text-domain'),
        'type'        => 'text'
    ),
    'Live_ENTITYID' => array(
        'title'       => __('Live ENTITY ID', 'text-domain'),
        'type'        => 'text'
    ),

    'Test_API_URL' => array(
        'title'       => __(' Test API URL', 'text-domain'),
        'type'        => 'text'
    ),
    'Live_API_URL' => array(
        'title'       => __(' Live API URL', 'text-domain'),
        'type'        => 'text'
    ),
    'Webhook_Secret_Key' => array(
        'title'       => __(' Webhook Secret Key', 'text-domain'),
        'type'        => 'text'
    ),
    'Webhook_URL' => array(
        'title'       => __(' Webhook URL ', 'text-domain'),
        'type'        => 'text'
    ),
    //enable payment brand
    'MASTER' => array(
        // 'title'       => __('MasterCard', 'text-domain'),
        'label'       => __('MasterCard', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add MasterCard as PAYMENT BRAND.', 'text-domain'),
        'default'     => 'yes',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'

    ),
    'VISA' => array(
        //'title'       => __('Visa', 'text-domain'),
        'label'       => __('Visa', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add Visa as PAYMENT BRAND.', 'text-domain'),
        'default'     => 'yes',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'
    ),
    'AMEX' => array(
        //'title'       => __('American Express', 'text-domain'),
        'label'       => __('American Express', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add American Express as PAYMENT BRAND.', 'text-domain'),
        'default'     => 'no',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'
    ),
    'DINERS' => array(
        //'title'       => __('Diners', 'text-domain'),
        'label'       => __('Diners', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add Diners as PAYMENT BRAND..', 'text-domain'),
        'default'     => 'no',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'
    ),
    'GOOGLEPAY' => array(
        //'title'       => __('GooglePay', 'text-domain'),
        'label'       => __('GooglePay', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add GooglePay as PAYMENT BRAND.', 'text-domain'),
        'default'     => 'no',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'
    ),
    'APPLEPAY' => array(
        //'title'       => __('ApplePay', 'text-domain'),
        'label'       => __('ApplePay', 'text-domain'),
        'type'        => 'checkbox',
        'description' => __('Add ApplePay as PAYMENT BRAND.', 'text-domain'),
        'default'     => 'no',
        'desc_tip'    => true,
        'class'       => 'wppd-ui-toggle'
    ),
    'uploadFile' => array(
        'title'       => __('Add your own style', 'text-domain'),
        'label'       => __('File', 'text-domain'),
        'type'        => 'file',
        'description' => __('Add your own stylesheet ( index.css).', 'text-domain'),
        'desc_tip'    => true,
        'default'     => '',

    ),

    'selectLanguage' => array(
        'title' => __('Select Language', 'text-domain'),
        'description' => __('Select Language for Your Plugin', 'text-domain'),
        'type' => 'select',
        'default' => 'en',
        'options' => array(
            'ar' => 'Arabic',
            'be' => 'French',
            'bg' => 'Bulgarian',
            'ca' => 'Catalan',
            'cn' => 'Simplified Chinese',
            'cz' => 'Czech',
            'da' => 'Danish',
            'de' => 'German',
            'el' => 'Greek',
            'en' => 'English',
            'es' => 'Spanish',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fi' => 'Finnish',
            'fr' => 'French',
            'gr' => 'Greek',
            'hr' => 'Croatian',
            'hu' => 'Hungarian',
            'id' => 'Indonesian',
            'it' => 'Italian',
            'ja' => 'Japanese',
            'ko' => 'Korean',
            'lt' => 'Lithuanian',
            'lv' => 'Latvian',
            'nl' => 'Dutch',
            'no' => 'Norwegian',
            'pl' => 'Polish',
            'pt' => 'Portugese',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'sk' => 'Slovak',
            'sl' => 'Slovene',
            'sv' => 'Swedish',
            'tr' => 'Turkish',
            'zh' => 'Traditional Chinese',
        )
    )

);
