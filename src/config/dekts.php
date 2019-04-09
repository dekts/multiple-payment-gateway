<?php

return [

   /**
    *--------------------------------------------------------------------------
    * Indipay Service Config
    *--------------------------------------------------------------------------
    * gateway = CCAvenue / PayUMoney / EBS / Citrus / InstaMojo / Mocker
    * view    = File
    */

    // Replace with the name of default gateway you want to use
    'gateway' => env('DEFAULT_GATEWAY'),

    // True for Testing the Gateway [For production: false]
    'testMode'  => env('PAYMENT_MODE'),

    // CCAvenue Parameters
    'ccavenue' => [
        'merchantId'  => env('INDIPAY_MERCHANT_ID', ''),
        'accessCode'  => env('INDIPAY_ACCESS_CODE', ''),
        'workingKey' => env('INDIPAY_WORKING_KEY', ''),

        // Should be route address for url() function
        'redirectUrl' => env('INDIPAY_REDIRECT_URL', 'indipay/response'),
        'cancelUrl' => env('INDIPAY_CANCEL_URL', 'indipay/response'),

        'currency' => env('INDIPAY_CURRENCY', 'INR'),
        'language' => env('INDIPAY_LANGUAGE', 'EN'),
    ],

    // PayUMoney Parameters
    'payumoney' => [
        'merchantKey'  => env('INDIPAY_MERCHANT_KEY', ''),
        'salt'  => env('INDIPAY_SALT', ''),
        'workingKey' => env('INDIPAY_WORKING_KEY', ''),

        // Should be route address for url() function
        'successUrl' => env('INDIPAY_SUCCESS_URL', 'indipay/response'),
        'failureUrl' => env('INDIPAY_FAILURE_URL', 'indipay/response'),
    ],

    // EBS Parameters
    'ebs' => [
        'account_id'  => env('INDIPAY_MERCHANT_ID', ''),
        'secretKey' => env('INDIPAY_WORKING_KEY', ''),

        // Should be route address for url() function
        'return_url' => env('INDIPAY_SUCCESS_URL', 'indipay/response'),
    ],

    // Citrus Parameters
    'citrus' => [
        'vanityUrl'  => env('INDIPAY_CITRUS_VANITY_URL', ''),
        'secretKey' => env('INDIPAY_WORKING_KEY', ''),

        // Should be route address for url() function
        'returnUrl' => env('INDIPAY_SUCCESS_URL', 'indipay/response'),
        'notifyUrl' => env('INDIPAY_SUCCESS_URL', 'indipay/response'),
    ],

    'instamojo' =>  [
        'api_key' => env('INSTAMOJO_API_KEY',''),
        'auth_token' => env('INSTAMOJO_AUTH_TOKEN',''),
        'redirectUrl' => env('INDIPAY_REDIRECT_URL', 'indipay/response'),
    ],

    'mocker' =>  [
        'service' => env('MOCKER_SERVICE','default'),
        'redirect_url' => env('MOCKER_REDIRECT_URL', 'indipay/response'),
    ],

    // Add your response link here. In Laravel 5.2 you may use the api middleware instead of this.
    'remove_csrf_check' => [
        'indipay/response'
    ],
];
