<?php

namespace BinaryCats\Rbac\Tests\Fixtures;

use BinaryCats\Rbac\DefinedRole;

class FooNamedRole extends DefinedRole
{
    /** @var string */
    protected string $name = 'Bar Bar Role';
}
