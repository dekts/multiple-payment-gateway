<?php

namespace Dekts\Payments\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Dekts\Payments\Exceptions\IndipayParametersMissingException;

/**
 * Class PayUMoneyGateway
 * @package Dekts\Payments\Gateways
 */
class PayUMoneyGateway implements PaymentGatewayInterface {

    protected $parameters = array();
    protected $testMode = false;
    protected $merchantKey = '';
    protected $salt = '';
    protected $hash = '';
    protected $liveEndPoint = 'https://secure.payu.in/_payment';
    protected $testEndPoint = 'https://sandboxsecure.payu.in/_payment';
    public $response = '';

    /**
     * PayUMoneyGateway constructor.
     */
    function __construct()
    {
        $this->merchantKey = Config::get('dekts.payumoney.merchantKey');
        $this->salt = Config::get('dekts.payumoney.salt');
        $this->testMode = Config::get('dekts.testMode');

        $this->parameters['key'] = $this->merchantKey;
        $this->parameters['txnid'] = $this->generateTransactionID();
        $this->parameters['surl'] = url(Config::get('dekts.payumoney.successUrl'));
        $this->parameters['furl'] = url(Config::get('dekts.payumoney.failureUrl'));
        $this->parameters['service_provider'] = 'payu_paisa';
        $this->parameters['firstname'] = '';
        $this->parameters['email'] = '';
        $this->parameters['phone'] = '';
        $this->parameters['productinfo'] = 'DEKTS';
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->testMode?$this->testEndPoint:$this->liveEndPoint;
    }

    /**
     * @param $parameters
     * @return $this
     * @throws IndipayParametersMissingException
     */
    public function request($parameters)
    {
        $this->parameters = array_merge($this->parameters,$parameters);

        $this->checkParameters($this->parameters);

        $this->encrypt();

        return $this;
    }

    /**
     * @return mixed
     */
    public function send()
    {
        Log::info('Indipay Payment Request Initiated: ');

        return View::make('dekts::payumoney')->with('hash',$this->hash)
                             ->with('parameters',$this->parameters)
                             ->with('endPoint',$this->getEndPoint());
    }

    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function response($request)
    {
        $response = $request->all();

        $response_hash = $this->decrypt($response);

        if($response_hash!=$response['hash']){
            return 'Hash Mismatch Error';
        }

        return $response;
    }

    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        $validator = Validator::make($parameters, [
            'key' => 'required',
            'txnid' => 'required',
            'surl' => 'required|url',
            'furl' => 'required|url',
            'firstname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'productinfo' => 'required',
            'service_provider' => 'required',
            'amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            throw new IndipayParametersMissingException;
        }
    }

    /**
     * PayUMoney Encrypt Function
     */
    protected function encrypt()
    {
        $this->hash = '';
        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';

        foreach($hashVarsSeq as $hash_var) {
            $hash_string .= isset($this->parameters[$hash_var]) ? $this->parameters[$hash_var] : '';
            $hash_string .= '|';
        }

        $hash_string .= $this->salt;
        $this->hash = strtolower(hash('sha512', $hash_string));
    }

    /**
     * PayUMoney Decrypt Function
     *
     * @param $response
     * @return string
     */
    protected function decrypt($response)
    {

        $hashSequence = "status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key";
        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = $this->salt."|";

        foreach($hashVarsSeq as $hash_var) {
            $hash_string .= isset($response[$hash_var]) ? $response[$hash_var] : '';
            $hash_string .= '|';
        }

        $hash_string = trim($hash_string,'|');

        return strtolower(hash('sha512', $hash_string));
    }

    /**
     * @return bool|string
     */
    public function generateTransactionID()
    {
        return substr(hash('sha256', mt_rand() . microtime()), 0, 20);
    }
}