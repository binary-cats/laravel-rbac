<?php

namespace BinaryCats\Rbac\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \BinaryCats\Rbac\Rbac
 */
class Rbac extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \BinaryCats\Rbac\Rbac::class;
    }
}
