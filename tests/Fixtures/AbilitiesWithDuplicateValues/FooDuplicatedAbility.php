<?php

namespace BinaryCats\Rbac\Tests\Fixtures\AbilitiesWithDuplicateValues;

enum FooDuplicatedAbility: string
{
    case One = 'una';
    case Two = 'otra';
}
