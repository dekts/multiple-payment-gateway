<?php

namespace Dekts\Payments\Gateways;

/**
 * Interface PaymentGatewayInterface
 * @package Dekts\Payments\Gateways
 */
interface PaymentGatewayInterface
{
    public function request($parameters);
    public function send();
    public function response($request);
}
