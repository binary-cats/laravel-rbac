<?php

namespace BinaryCats\LaravelRbac\Facades;

use Illuminate\Support\Facades\Facade;

class Rbac extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BinaryCats\LaravelRbac\Rbac::class;
    }
}
