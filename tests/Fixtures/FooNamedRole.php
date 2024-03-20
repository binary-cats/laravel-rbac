<?php

namespace BinaryCats\LaravelRbac\Tests\Fixtures;

use BinaryCats\LaravelRbac\DefinedRole;

class FooNamedRole extends DefinedRole
{
    /** @var string */
    protected string $name = 'Bar Bar Role';
}
