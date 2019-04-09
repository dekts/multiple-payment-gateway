<?php

namespace Dekts\Payments;

use Dekts\Payments\Gateways\CCAvenueGateway;
use Dekts\Payments\Gateways\CitrusGateway;
use Dekts\Payments\Gateways\CitrusPopupGateway;
use Dekts\Payments\Gateways\EBSGateway;
use Dekts\Payments\Gateways\InstaMojoGateway;
use Dekts\Payments\Gateways\PaymentGatewayInterface;
use Dekts\Payments\Gateways\PayUMoneyGateway;

/**
 * Class Dekts
 * @package Dekts\Payments
 */
class Dekts
{
    /**
     * @var PaymentGatewayInterface
     */
    protected $gateway;

    /**
     * Dekts constructor.
     * @param PaymentGatewayInterface $gateway
     */
    function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function purchase($parameters = array())
    {
        return $this->gateway->request($parameters)->send();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function response($request)
    {
        return $this->gateway->response($request);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function prepare($parameters = array())
    {
        return $this->gateway->request($parameters);
    }

    /**
     * @param $order
     * @return mixed
     */
    public function process($order)
    {
        return $order->send();
    }

    /**
     * @param $name
     * @return $this
     */
    public function gateway($name)
    {
        switch($name)
        {
            case 'CCAvenue':
                $this->gateway = new CCAvenueGateway();
                break;

            case 'PayUMoney':
                $this->gateway = new PayUMoneyGateway();
                break;

            case 'EBS':
                $this->gateway = new EBSGateway();
                break;

            case 'Citrus':
                $this->gateway = new CitrusGateway();
                break;

            case 'CitrusPopup':
                $this->gateway = new CitrusPopupGateway();
                break;

            case 'InstaMojo':
                $this->gateway = new InstaMojoGateway();
                break;
        }

        return $this;
    }
}
