<?php

namespace App\Roles;

use BinaryCats\Rbac\DefinedRole;

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
        return [];
    }
}
