# Dekts Pay

The Laravel 5.8 Package for Indian Payment Gateways. Currently supported gateway: CCAvenue, PayUMoney, EBS, CitrusPay, ZapakPay (Mobikwik), Mocker

#### Installation

Step 1: Install package using composer.json add some code
	
	"repository":[
	    {
		type:"git",
		url:"https://github.com/dekts/multiple-payment-gateway.git"
	    }
	]
    "require": {
        "dekts/payments": "dev/master#1.*",
    },
	"autoload": {
            "psr-4": {
            "Dekts\\Payments\\": "src/"
        }
    }

and then

    Composer Update
    
Step 2: Add the service provider to the config/app.php file in Laravel (Optional for Laravel 5.8)

    Dekts\Payments\PaymentServiceProvider::class,

Step 3: Add an alias for the Facade to the config/app.php file in Laravel (Optional for Laravel 5.8)

    'Payments' => Dekts\Payments\Facades\Dekts::class,

Step 4: Publish the config & Middleware by running in your terminal

    php artisan vendor:publish

Step 5: Modify the app\Http\Kernel.php to use the new Middleware. This is required so as to avoid CSRF verification on the Response Url from the payment gateways. You may adjust the routes in the config file config/dekts.php to disable CSRF on your gateways response routes.

    App\Http\Middleware\VerifyCsrfToken::class,

to

    App\Http\Middleware\VerifyCsrfMiddleware::class,

#### Usage

Edit the config/dekts.php. Set the appropriate Gateway and its parameters. Then in your code... 

    use Dekts\Payments\Facades\Dekts;
   
Initiate Purchase Request and Redirect using the default gateway:-

    /* All Required Parameters by your Gateway */
      
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
        $order = Ggpay::prepare($parameters);
    else
        $order = Ggpay::gateway($payment_mode)->prepare($parameters);
    
    return Ggpay::process($order);

Get the Response from the Gateway (Add the Code to the Redirect Url Set in the config file. Also add the response route to the remove_csrf_check config item to remove CSRF check on these routes.):
 
    public function response(Request $request)    
    {
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
    }
