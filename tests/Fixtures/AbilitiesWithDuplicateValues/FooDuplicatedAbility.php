<?php

namespace BinaryCats\LaravelRbac\Tests\Fixtures\AbilitiesWithDuplicateValues;

enum FooDuplicatedAbility: string
{
    case One = 'una';
    case Two = 'otra';
}
