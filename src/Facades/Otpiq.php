<?php

namespace Rstacode\Otpiq\Facades;

use Illuminate\Support\Facades\Facade;

class Otpiq extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'otpiq';
    }
}