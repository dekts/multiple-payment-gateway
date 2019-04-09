<?php

namespace Dekts\Payments\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Dekts
 * @package Dekts\Payments\Facades
 */
class Dekts extends Facade
{
    protected static function getFacadeAccessor() {
        return 'indipay';
    }
}
