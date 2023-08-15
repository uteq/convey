<?php

namespace Uteq\Convey\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Uteq\Convey\Convey
 */
class Convey extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Uteq\Convey\Convey::class;
    }
}
