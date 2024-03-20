<?php

namespace BinaryCats\Rbac\Facades;

use Illuminate\Support\Facades\Facade;

class Rbac extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BinaryCats\Rbac\Rbac::class;
    }
}
