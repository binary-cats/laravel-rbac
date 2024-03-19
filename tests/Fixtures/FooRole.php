<?php

namespace BinaryCats\Rbac\Tests\Fixtures;

use BinaryCats\Rbac\DefinedRole;
use BinaryCats\Rbac\Tests\Fixtures\Abilities\FooAbility;

class FooRole extends DefinedRole
{
    /** @var array|string[]  */
    protected array $guards = [
        'web'
    ];

    /**
     * List of enumerated permissions for the `web` guard
     *
     * @return array
     */
    public function web(): array
    {
        return [
            FooAbility::One
        ];
    }
}
