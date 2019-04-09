<?php

namespace Dekts\Payments\Gateways;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Dekts\Payments\Exceptions\IndipayParametersMissingException;

/**
 * Class MockerGateway
 * @package Dekts\Payments\Gateways
 */
class MockerGateway implements PaymentGatewayInterface {

    protected $parameters = array();
    protected $service = 'default';
    
    protected $liveEndPoint = 'http://mocker.in/payment/';
    protected $testEndPoint = 'http://mocker.in/payment/';
    public $response = '';

    /**
     * MockerGateway constructor.
     */
    function __construct()
    {
        $this->service = Config::get('dekts.mocker.service');
        $this->testMode = Config::get('dekts.testMode');
        $this->parameters['redirect_url'] = url(Config::get('dekts.mocker.redirect_url'));
    }

    /**
     * @return string
     */
    public function getEndPoint()
    {
        return $this->testMode?$this->testEndPoint.$this->service:$this->liveEndPoint.$this->service;
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

        return $this;
    }

    /**
     * @return mixed
     */
    public function send()
    {
        Log::info('Indipay Payment Request Initiated: ');

        return View::make('dekts::mocker')->with('data',$this->parameters)
                                            ->with('end_point',$this->getEndPoint());
    }

    /**
     * Check Response
     * @param $request
     * @return array
     */
    public function response($request)
    {
        return $request->all();
    }

    /**
     * @param $parameters
     * @throws IndipayParametersMissingException
     */
    public function checkParameters($parameters)
    {
        if($this->service == 'default') {
            $validator = Validator::make($parameters, [
                'redirect_url' => 'required|url',
                'amount' => 'required|numeric',
            ]);
            
        }elseif($this->service == 'instamojo'){
            $validator = Validator::make($parameters, [
                'amount' => 'required|numeric|between:9,200000',
                'redirect_url' => 'required|url',
            ]);
        }elseif($this->service == 'ccavenue'){
            $validator = Validator::make($parameters, [
                'amount' => 'required|numeric|between:9,200000',
                'redirect_url' => 'required|url',
            ]);
        }

        if ($validator->fails()) {
            throw new IndipayParametersMissingException;
        }
    }
}
