<?php

namespace App\Abilities;

enum FooAbility: string
{
    case ViewFoo = 'view foo';
    case CreateFoo = 'create foo';
    case UpdateFoo = 'update foo';
    case DeleteFoo = 'delete foo';
}
