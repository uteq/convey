<?php

namespace Uteq\LaravelVoltWebPush\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Uteq\LaravelVoltWebPush\WebPush
 */
class WebPush extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Uteq\LaravelVoltWebPush\WebPush::class;
    }
}
