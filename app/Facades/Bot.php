<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Bot extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bot';
    }
}
