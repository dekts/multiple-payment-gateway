<?php

use Dekts\Payments\Facades\Dekts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Dekts\Payments\Exceptions\IndipayParametersMissingException;

Route::post('paynow/{payment_mode}', function(Request $request,$payment_mode){

    $parameters = [
        'tid' => $request->tid,
        'order_id' => $request->order_id,
        'payment_mode' => $payment_mode,
        'amount' => $request->amount,
        'firstname' => $request->firstname,
        'email' => $request->email,
        'phone' => $request->phone,
        'productinfo' => $request->order_id, // For the Payumoney Gateway Optional Paramater
    ];

    // gateway = CCAvenue / PayUMoney / EBS / Citrus / InstaMojo / ZapakPay / Mocker / CitrusPopup
    if(empty($payment_mode) || env('IS_DEFAULT_GATEWAY')==true)
        $order = Dekts::prepare($parameters);
    else
        $order = Dekts::gateway($payment_mode)->prepare($parameters);

    return Dekts::process($order);
});

Route::post('/indipay/response',function(Request $request) {
    if(isset($request->Order)) {
        $response = Dekts::gateway($request->Domain)->response($request);
        if(is_array($response))
            $response['payment_method']='Citrus';

    } elseif (isset($request->productinfo)) {
        $response = Dekts::gateway('PayUMoney')->response($request);
        if(is_array($response))
            $response['payment_method']='PayUMoney';
    } else {
        $response = Dekts::response($request);
        if(is_array($response))
            $response['payment_method']='Other';
    }
});